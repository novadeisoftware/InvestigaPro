<?php

namespace App\Services\Ai;

use Gemini;
use Gemini\Client;
use Illuminate\Support\Facades\Log;
// Importamos las clases necesarias para los tipos de datos
use Gemini\Data\SafetySetting;
use Gemini\Enums\HarmCategory;
use Gemini\Enums\HarmBlockThreshold;
use Gemini\Enums\MimeType;
use Gemini\Data\Blob;

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
            case 'II. Marco Teórico':
            $prompt = "
                $context
                TAREA: Genera un Marco Teórico extenso y riguroso.
                
                REGLAS DE FORMATO (OBLIGATORIO PARA RENDERIZAR):
                1. Usa EXCLUSIVAMENTE etiquetas HTML (<p>, <h3>, <strong>).
                2. CADA unidad de idea debe ser un párrafo encerrado en <p>...</p>.
                3. NO uses Markdown (nada de ### o **).
                4. Empieza directamente con el contenido, sin introducciones.
        
                ESTRUCTURA DE REDACCIÓN:

                - Redacta 3 párrafos de 8 a 10 renglones cada uno para las 'Bases Teóricas de la Variable Independiente'.
                - Redacta 3 párrafos de 8 a 10 renglones cada uno para las 'Bases Teóricas de la Variable Dependiente'.
                - Usa etiquetas <strong> para conceptos clave dentro de los párrafos.
                - Incluye citas en formato APA 7 en cada párrafo.
                
                REGLA DE ORO: Si no usas la etiqueta <p>, el editor no reconocerá los saltos de línea. Asegúrate de que cada párrafo sea un bloque HTML independiente.";
            break;

            case 'problema':
            case 'III. Problema':
            $prompt = "
                $context
                TAREA: Formula el Planteamiento del Problema, Objetivos e Hipótesis con rigor científico.
        
                REGLAS DE FORMATO (CRÍTICO PARA TINYMCE):
                1. Usa EXCLUSIVAMENTE etiquetas HTML (<p>, <h3>, <strong>).
                2. CADA sección debe ser un bloque independiente encerrado en <p>...</p>.
                3. NO uses Markdown (nada de ###, ** o listas con guiones -).
                4. Empieza directamente con el contenido, sin saludos ni introducciones.
        
                ESTRUCTURA DE REDACCIÓN (OBLIGATORIA):
                
                1. <h3>1.1. Realidad Problemática</h3>
                   - Redacta 2 párrafos de 8 a 10 renglones que describan la situación actual en Trujillo.
                
                2. <h3>1.2. Formulación del Problema</h3>
                   - <p><strong>Problema General:</strong> Redacta la pregunta de investigación relacionando las variables, el lugar y el tiempo.</p>
                
                3. <h3>1.3. Objetivos de la Investigación</h3>
                   - <p><strong>Objetivo General:</strong> Redacta el propósito principal en un solo párrafo.</p>
                   - <p><strong>Objetivos Específicos:</strong> Redacta dos párrafos independientes, cada uno con un objetivo evaluativo específico.</p>
                
                4. <h3>1.4. Hipótesis</h3>
                   - <p><strong>Hipótesis General:</strong> Redacta una respuesta lógica y tentativa al problema planteado.</p>
        
                REGLA DE ORO: Si no usas <p> para separar los puntos, el editor mostrará todo pegado. Asegura la consistencia con el método científico.";
            break;

            case 'metodologia':
            case 'VI. Metodología':

              $isExperimental = str_contains(strtolower($gd['area'] ?? ''), 'experimental');
              $struct = $isExperimental 
                  ? "Lugar de ejecución, Instalaciones, Población y Muestra, Manejo del experimento, Toma de muestras, Tratamientos experimentales y Análisis estadístico." 
                  : "Lugar de ejecución, Población, Muestra (Probabilística/No probabilística), Sistema de Variables y Procesamiento de datos/Análisis estadístico.";

              $prompt = "
                  $context
                  TAREA: Redacta la sección de MATERIALES Y MÉTODOS (METODOLOGÍA) con rigor científico.
                  ÁREA: " . ($gd['area'] ?? 'Investigación') . "
                  ESTRUCTURA REQUERIDA: $struct

                  REGLAS DE FORMATO (CRÍTICO PARA TINYMCE):
                  1. Usa EXCLUSIVAMENTE etiquetas HTML (<h3>, <p>, <strong>).
                  2. CADA sección de la estructura debe ser un subtítulo <h3>.
                  3. Desarrolla CADA punto con al menos 2 párrafos de 8 a 10 renglones.
                  4. Prohibido usar Markdown (#, **, listas con guiones).
                  5. Redacción técnica en tercera persona (pasiva refleja).

                  CONTENIDO ESPECÍFICO:
                  - Describe el diseño de investigación (Experimental o No Experimental).
                  - Detalla los criterios de inclusión y exclusión para la Población.
                  - En 'Análisis estadístico', menciona software específico (SPSS, R-Studio, Excel) y las pruebas (T-Student, ANOVA, correlación de Pearson) según el área.
                  - NO incluyas introducciones. Empieza directo con el primer punto de la estructura.

                  REGLA DE ORO: Si no usas <p> para separar los párrafos, el contenido se verá pegado. Asegura la coherencia con el título del proyecto.";
            break;
          default:
           $prompt = "
               $context
               ACTÚA COMO ASESOR SENIOR DE TESIS.
               SECCIÓN A REDACTAR: \"$stepTitle\"
               ÁREA DE ESTUDIO: " . ($gd['area'] ?? 'Sistemas') . "
               
               TAREA:
               Redacta el contenido académico formal para esta sección específica.
               
               REGLAS DE FORMATO (CRÍTICO PARA RENDERIZADO):
               1. Usa EXCLUSIVAMENTE etiquetas HTML (<p>, <h3>, <strong>).
               2. CADA párrafo o idea nueva DEBE estar encerrado en <p>...</p>.
               3. Prohibido usar Markdown (nada de ###, ** o guiones).
               4. NO incluyas introducciones ni comentarios del asistente (ej: 'Claro', 'Aquí tienes').
               5. Empieza directamente con el contenido técnico.

               ESTRUCTURA DE REDACCIÓN:
               - Redacción técnica en tercera persona.
               - Mínimo 3 párrafos de 8 a 10 renglones cada uno para asegurar profundidad.
               - Usa terminología avanzada del área de " . ($gd['area'] ?? 'Sistemas') . ".
               - Incluye citas en formato APA 7 si la sección lo requiere.
               - Instrucciones adicionales del usuario: " . ($data['instructions'] ?? 'Ninguna') . "

               REGLA DE ORO: Todo el output debe ser código HTML válido para que el editor TinyMCE lo procese correctamente.";
           break;
        }

        return $this->callGemini($prompt);
    }

    /**
     * Analiza un archivo de tesis para extraer el contexto y datos generales.
     * Soporta PDF y Word (el contenido se envía como bytes a Gemini).
     */
    public function analyzeThesisFile(string $filePath): array
    {
        // 1. Validar que el archivo existe
        if (!file_exists($filePath)) {
            throw new \Exception("El archivo no se encuentra en la ruta especificada.");
        }

        // 2. Leer el contenido binario y codificar en base64
        $fileContent = base64_encode(file_get_contents($filePath));
        $mimeType = mime_content_type($filePath);

        // 3. Crear el Prompt Maestro de Análisis
   $prompt = "
    ACTÚA COMO UN ASESOR SENIOR DE TESIS Y EXPERTO EN METODOLOGÍA DE LA INVESTIGACIÓN.
    TAREA: Analiza el documento adjunto y extrae la información para completar los 10 pasos clave de la estructura de investigación.

    DEBES DEVOLVER UN JSON PURO CON ESTA ESTRUCTURA EXACTA:
    {
        \"datos_generales\": {
            \"area\": \"Área de estudio identificada\",
            \"objeto\": \"Objeto de estudio específico\",
            \"problema\": \"Resumen técnico de la problemática\",
            \"solucion\": \"Alternativa de solución propuesta\",
            \"lugar\": \"Ciudad y Región\",
            \"tiempo\": \"Año o periodo de ejecución\"
        },
        \"resumen_pasos\": [
            {\"paso\": 1, \"titulo\": \"I. Antecedentes\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 2, \"titulo\": \"II. Marco Teórico\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 3, \"titulo\": \"III. Problema\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 4, \"titulo\": \"IV. Hipótesis\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 5, \"titulo\": \"V. Objetivos\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 6, \"titulo\": \"VI. Metodología\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 7, \"titulo\": \"VII. Cronograma\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 8, \"titulo\": \"VIII. Presupuesto\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 9, \"titulo\": \"IX. Bibliografía\", \"estado\": \"\", \"resumen\": \"\"},
            {\"paso\": 10, \"titulo\": \"X. Anexos\", \"estado\": \"\", \"resumen\": \"\"}
        ],
        \"analisis_detallado\": {
            \"01_antecedentes\": \"...\",
            \"02_marco_teorico\": \"...\",
            \"03_problema\": \"...\",
            \"04_objetivos\": \"...\",
            \"05_hipotesis\": \"...\",
            \"06_metodologia\": \"...\",
            \"07_resultados\": \"...\",
            \"08_discusion\": \"...\",
            \"09_conclusiones\": \"...\",
            \"10_bibliografia\": \"...\"
        },
        \"resumen_ejecutivo\": \"Genera aquí un texto extenso compuesto por 10 párrafos claramente diferenciados. Cada párrafo debe iniciar con el nombre del paso (ej: '01. ANTECEDENTES: ...'). El contenido de cada párrafo debe ser un resumen detallado y técnico (mínimo 6 líneas por paso) basado exclusivamente en lo hallado en el documento. Si un paso no existe, el párrafo debe decir 'SECCIÓN NO DETECTADA: El documento no presenta información sobre este punto'.\"
    }

    REGLAS CRÍTICAS:
    1. Para cada objeto en 'resumen_pasos', el 'estado' debe ser 'completado' si encontraste información suficiente o 'faltante' si no existe en el documento.
    2. En 'resumen', escribe una frase de máximo 15 palabras resumiendo el hallazgo de ese paso.
    3. Si una sección es 'faltante', en 'analisis_detallado' escribe 'Sección no detectada en el documento original'.
    4. El formato de salida debe ser JSON PURO, sin textos adicionales, para poder ser procesado por json_decode.
";

        try {
    $model = $this->client->generativeModel(model: self::MODEL);

    // 1. Detectamos el MIME type del archivo de forma segura
    $detectedMime = mime_content_type($filePath);

    // 2. Convertimos el string al Enum de Gemini usando el método from()
    // Esto evita el error de "Undefined Constant"
    try {
        $geminiMime = \Gemini\Enums\MimeType::from($detectedMime);
    } catch (\ValueError $e) {
        // Si no lo reconoce (ej. un docx raro), forzamos PDF o lanzamos error
        $geminiMime = \Gemini\Enums\MimeType::PDF; 
    }

    // 3. Enviamos el contenido a Gemini
    $result = $model->generateContent([
        $prompt,
        new \Gemini\Data\Blob(
            mimeType: $geminiMime,
            data: $fileContent
        )
    ]);

    $rawText = $result->text();
    
    // 4. Limpiamos y decodificamos el JSON
    $cleanJson = preg_replace('/^```json|```$/m', '', $rawText);
    $decoded = json_decode(trim($cleanJson), true);

    // Si el decode falla, intentamos limpiar caracteres extraños
    if (!$decoded) {
        Log::warning("Gemini devolvió un JSON mal formado, intentando limpiar...");
        return [
            'error' => 'No se pudo parsear el JSON de la IA',
            'raw' => $rawText
        ];
    }

    return $decoded;

} catch (\Exception $e) {
    Log::error("DETALLE ERROR GEMINI EN SETUP: " . $e->getMessage());
    throw new \Exception("Error al procesar el documento: " . $e->getMessage());
}
    }


    /**
     * MÉTODO EXCLUSIVO PARA PARAFRASEO ACADÉMICO
     */
    public function paraphraseText(string $text, array $projectContext): string
    {
        if (empty(trim($text))) return "Error: No se proporcionó texto.";
    
        $universidad = $projectContext['university_siglas'] ?? 'la Universidad';
        $area = $projectContext['general_data']['area'] ?? 'Investigación Científica';
            
                $prompt = "
            ACTÚA COMO UN MOTOR DE PARAFRASEO ACADÉMICO DE ALTO NIVEL.
            PROYECTO: \"{$projectContext['project_title']}\"
            ÁREA: {$area}
            CONTEXTO: Tesis de grado para {$universidad}.
        
            TAREA:
            Reescribe el texto proporcionado usando un lenguaje científico, formal y técnico bajo normas APA 7.
        
            REGLAS DE SALIDA (ESTRICTAS):
            1. PROHIBIDO cualquier texto de introducción o saludo (Nada de 'Entendido' o 'Aquí tienes').
            2. Usa EXCLUSIVAMENTE etiquetas HTML para separar los bloques.
            3. EL FORMATO DE RESPUESTA DEBE SER EXACTAMENTE ESTE:
        
            <p><strong>TEXTO ORIGINAL:</strong></p>
            <p><em>(Aquí pones el texto original que te envié exactamente igual)</em></p>
            
            <p><strong>PARÁFRASIS ACADÉMICA:</strong></p>
            <p>(Aquí pones el resultado de tu parafraseo técnico en un párrafo nuevo)</p>
        
            TEXTO A PROCESAR:
            \"$text\"
        ";
    
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