<?php

namespace App\Services\Ai;

use Gemini;
use Gemini\Client;
use Illuminate\Support\Facades\Log;
// Importamos las clases necesarias para los tipos de datos
use Gemini\Data\SafetySetting;
use Gemini\Enums\HarmCategory;
use Gemini\Enums\HarmBlockThreshold;

class AiWriterService
{
    protected Client $client;
    private const MODEL = 'models/gemini-2.5-pro';

    public function __construct()
    {
        $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        $this->client = Gemini::client($apiKey);
    }

    /**
     * Genera el borrador de una sección académica.
     */
    public function generateDraft(array $data): string
    {
        $gd = $data['general_data'] ?? [];
        $stepTitle = $data['step_title'] ?? '';
        $sectionId = $this->detectSectionType($stepTitle, $data['step_key'] ?? 'generico');
        $history = $data['history_context'] ?? 'No hay contenido previo todavía.';

        $context = "
            CONTEXTO DEL PROYECTO:
            Título: \"{$data['project_title']}\"
            Área: " . ($gd['area'] ?? 'Investigación General') . "
            Objeto de estudio: \"" . ($gd['objeto'] ?? '') . "\"
            Lugar: \"" . ($gd['lugar'] ?? 'Trujillo') . "\"
            Tiempo: \"" . ($gd['tiempo'] ?? '2026') . "\"
            Problema: \"" . ($gd['problema'] ?? '') . "\"
            Solución: \"" . ($gd['solucion'] ?? '') . "\"
            Universidad: " . ($data['university_siglas'] ?? 'UCT') . "

            HISTORIAL DE REDACCIÓN:
            $history
        ";

        switch ($sectionId) {
            case 'antecedentes':
                $prompt = "$context\nActúa como experto en metodología. Redacta 7 párrafos de antecedentes y justificación siguiendo el estándar académico: 1. Área de estudio (6-9 renglones). 2. Objeto de estudio (6-9 renglones). 3, 4, 5. Realidad problemática (6 renglones c/u). 6. Alternativa de solución (6-9 renglones). 7. Justificación: beneficios y aportes estratégicos. Extrae datos estadísticos coherentes y usa citas formato APA 7.";
                break;
            case 'marco_teorico':
                $prompt = "$context\nGenera un Marco Teórico extenso. Estructura temas y subtemas jerarquizados sobre la variable independiente y dependiente. Incluye definiciones conceptuales rigurosas y marcadores para tablas: <p><strong>[Tabla sugerida: ...]</strong></p>.";
                break;
            case 'metodologia':
                $isExperimental = str_contains(strtolower($gd['area'] ?? ''), 'experimental');
                $struct = $isExperimental ? "Lugar, Instalaciones, Población, Manejo, Toma de muestras, Tratamientos, Análisis estadístico" : "Lugar, Población, Muestra, Sistema de Variables, Análisis estadístico";
                $prompt = "$context\nRedacta MATERIALES Y MÉTODOS siguiendo esta estructura: $struct. Desarrolla cada punto de forma técnica.";
                break;
            default:
                $prompt = "$context\nActúa como asesor senior. Título de la sección: \"$stepTitle\". TAREA: Redacta contenido académico formal en tercera persona. Profundiza en la terminología técnica de " . ($gd['area'] ?? 'Sistemas') . ". INSTRUCCIONES ADICIONALES: " . ($data['instructions'] ?? '') . " Usa citas APA 7 y mantén la coherencia con el historial.";
                break;
        }

        return $this->callGemini($prompt);
    }

    public function suggestTitles(array $data): array
    {
        $prompt = "Actúa como experto en investigación. Basado en: " . json_encode($data) . 
                  ". Genera TRES (3) títulos de tesis técnicos, precisos y académicos. " .
                  "Devuelve SOLAMENTE los títulos separados por el símbolo \"|\". No uses numeración.";

        try {
            $rawText = $this->callGemini($prompt);
            return array_map('trim', explode('|', $rawText));
        } catch (\Exception $e) {
            Log::error("Error en suggestTitles: " . $e->getMessage());
            return ["Error al generar títulos: " . $e->getMessage()];
        }
    }

private function callGemini(string $prompt): string 
    {
        try {
            // Usamos HarmCategory::from() con el string técnico de Google.
            // Esto es mucho más seguro que usar la constante directamente.
            $safetySettings = [
                new SafetySetting(HarmCategory::from('HARM_CATEGORY_HATE_SPEECH'), HarmBlockThreshold::BLOCK_NONE),
                new SafetySetting(HarmCategory::from('HARM_CATEGORY_HARASSMENT'), HarmBlockThreshold::BLOCK_NONE),
                new SafetySetting(HarmCategory::from('HARM_CATEGORY_SEXUALLY_EXPLICIT'), HarmBlockThreshold::BLOCK_NONE),
                new SafetySetting(HarmCategory::from('HARM_CATEGORY_DANGEROUS_CONTENT'), HarmBlockThreshold::BLOCK_NONE),
            ];

            $model = $this->client->generativeModel(model: self::MODEL);
            
            foreach ($safetySettings as $setting) {
                $model = $model->withSafetySetting($setting);
            }

            $response = $model->generateContent($prompt);
            return $response->text();

        } catch (\Exception $e) {
            Log::error("DETALLE ERROR GEMINI: " . $e->getMessage());
            return "Error al conectar con la IA: " . $e->getMessage();
        }
    }

    private function detectSectionType($title, $key) {
        $t = strtolower($title);
        if (!in_array($key, ['generico', 'default', ''])) return $key;
        if (preg_match('/(antecedent|introduc|realidad|problem)/', $t)) return 'antecedentes';
        if (preg_match('/(teoric|base|fundament|literatur|conceptu)/', $t)) return 'marco_teorico';
        if (preg_match('/(metod|material|procedim|diseñ)/', $t)) return 'metodologia';
        return 'generico';
    }
}