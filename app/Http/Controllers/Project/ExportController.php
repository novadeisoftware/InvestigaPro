<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Language;

class ExportController extends Controller
{
    public function exportToWord($projectId)
    {
        if (ob_get_level()) ob_end_clean();

        $project = Project::with(['steps', 'university', 'user'])->findOrFail($projectId);
        $phpWord = new PhpWord();
        
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginLeft'   => 1701, // 3cm
            'marginRight'  => 1417, // 2.5cm
            'marginTop'    => 1417, 
            'marginBottom' => 1417,
        ]);

        $docType = strtoupper($project->document_type);

        // LÓGICA DE CONTROL DE HOJAS PRELIMINARES
        switch ($docType) {
            case 'PROYECTO DE TESIS':
                $this->addPortadaProyecto($section, $project);
                $this->addGeneralidadesProyecto($section, $project);
                $this->addIndice($section, $project);
                break;

            case 'TESIS':
            case 'INFORME FINAL':
                $this->addPortadaTesis($section, $project);
                $this->addAcreditacion($section, $project);
                $this->addPresentacion($section, $project);
                $this->addDedicatoria($section, $project);
                $this->addIndice($section, $project);
                break;
        }

        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER, 'spaceAfter' => 480]);


        // Define esto antes de tu bucle foreach de pasos

// En tu método exportToWord, antes del bucle de pasos
$phpWord->addTableStyle('AcademicTable', [
    'borderSize'  => 6,
    'borderColor' => '000000',
    'cellMarginLeft'   => 150, // Aumentamos el margen izquierdo
    'cellMarginRight'  => 150, // Aumentamos el margen derecho
    'cellMarginTop'    => 100, // Espacio arriba del texto
    'cellMarginBottom' => 100, // Espacio abajo del texto
    'alignment'   => Jc::CENTER,
    'unit'        => 'pct',
    'width'       => 100 * 50,
], [
    'alignment' => Jc::CENTER,
    'spaceBefore' => 120, // Espacio extra antes del texto en la celda
    'spaceAfter'  => 120  // Espacio extra después del texto en la celda
]);
        foreach ($project->steps->sortBy('order') as $step) {
            $section->addPageBreak();
            $section->addTitle(mb_strtoupper($step->title), 1);

            // NUEVA LÓGICA PARA TABLAS
            if ($this->isPlanningStep($step->title)) {
                $data = json_decode($step->content, true);
                if ($data) { 
                    $this->addTableToWord($section, $data, $step->title); 
                }
            } else {
                $this->addAcademicContent($section, $step->content);
            }
        }

        $fileName = "Tesis_" . time() . ".docx";
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        return response()->streamDownload(function () use ($objWriter) {
            $objWriter->save('php://output');
        }, $fileName);
    }

    // --- MÉTODOS DE SOPORTE ---

    private function addPortadaProyecto($section, $project) {
        $section->addText(mb_strtoupper($project->university->nombre), ['bold' => true, 'size' => 18], ['alignment' => Jc::CENTER]);
        $section->addText(mb_strtoupper($project->faculty ?? 'FACULTAD DE INGENIERÍA'), ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
        $section->addText(mb_strtoupper($project->school ?? 'ESCUELA DE INGENIERÍA'), ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        
        if ($project->university->logo_path) {
            $path = storage_path('app/public/' . $project->university->logo_path);
            if (file_exists($path)) {
                $section->addImage($path, ['height' => 110, 'alignment' => Jc::CENTER]);
            }
        }

        $section->addTextBreak(1);
        $section->addText("PROYECTO DE TESIS PARA OBTENER EL TÍTULO PROFESIONAL DE:", ['size' => 11], ['alignment' => Jc::CENTER]);
        $section->addText(str_repeat('.', 55), null, ['alignment' => Jc::CENTER]);

        $section->addTextBreak(1);
        $section->addText(mb_strtoupper($project->title), ['bold' => true, 'size' => 12], [
            'alignment' => Jc::CENTER, 
            'borderTopSize' => 6, 
            'borderBottomSize' => 6,
            'spaceBefore' => 200, 'spaceAfter' => 200
        ]);

        $section->addTextBreak(2);
        $this->addCenteredField($section, "Línea de Investigación:", $project->academic_line);
        $this->addCenteredField($section, "Autores:", "Br. " . $project->user->name . ($project->second_author ? "\nBr. " . $project->second_author : ""));
        $this->addCenteredField($section, "Asesor:", $project->advisor);

        $section->addTextBreak(2);
        $section->addText("TRUJILLO — PERÚ", ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addText(date('Y'), ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
    }

    private function addGeneralidadesProyecto($section, $project) {
        $section->addPageBreak();
        $section->addText("GENERALIDADES O PROGRAMA DE ESTUDIO", ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);
        $section->addText("Título de Proyecto: " . $project->title);
        $section->addText("Responsables: Br. " . $project->user->name . ($project->second_author ? ", Br. " . $project->second_author : ""));
        
        $section->addTextBreak(2);
        $table = $section->addTable(['alignment' => Jc::CENTER]);
        $table->addRow();
        $table->addCell(4500)->addText("____________________\nPresidente", null, ['alignment' => Jc::CENTER]);
        $table->addCell(4500)->addText("____________________\nSecretario", null, ['alignment' => Jc::CENTER]);
        $table->addRow(); $table->addCell(4500)->addTextBreak(2);
        $table->addRow();
        $table->addCell(4500)->addText("____________________\nVocal", null, ['alignment' => Jc::CENTER]);
        $table->addCell(4500)->addText("____________________\nAsesor", null, ['alignment' => Jc::CENTER]);
    }

    private function addAcreditacion($section, $project) {
        $section->addPageBreak();
        $section->addText("ACREDITACIÓN", ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(3);
        $text = "El " . ($project->advisor ?? 'Asesor') . ", que suscribe, asesor de la Tesis con Título “" . mb_strtoupper($project->title) . "”, desarrollado por los Br. " . $project->user->name . " acredita haber realizado las observaciones pertinentes.";
        $section->addText($text, null, ['alignment' => Jc::BOTH, 'indentation' => ['firstLine' => 1417]]);
    }

    private function addPresentacion($section, $project) {
        $section->addPageBreak();
        $section->addText("PRESENTACIÓN", ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);
        $section->addText("Señores miembros del Jurado Dictaminador:");
        $section->addText("Dando cumplimiento a las normas del Reglamento de Grados y Títulos...", null, ['indentation' => ['firstLine' => 1417]]);
    }

    private function addDedicatoria($section, $project) {
        $section->addPageBreak();
        $section->addText("DEDICATORIA", ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(4);
        $section->addText("A mis padres...", ['italic' => true], ['alignment' => Jc::RIGHT, 'indentation' => ['left' => 4000]]);
        $section->addText(mb_strtoupper($project->user->name), ['bold' => true], ['alignment' => Jc::RIGHT]);
    }

    private function addIndice($section, $project) {
        $section->addPageBreak();
        $section->addText("ÍNDICE GENERAL", ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);
        // Genera el índice automático de Word
        $section->addTOC(['name' => 'Arial', 'size' => 11]);
    }

    private function addAcademicContent($section, $content) {
        if (empty(trim($content))) return;
    
        // 1. Limpieza de etiquetas que rompen el XML (colgroup y col)
        $content = preg_replace('/<colgroup[^>]*>.*?<\/colgroup>/is', '', $content);
        $content = preg_replace('/<col[^>]*>/is', '', $content);
    
        // 2. PROCESAR IMÁGENES BASE64
        // Buscamos todas las etiquetas img
        if (str_contains($content, 'src="data:image')) {
            $content = preg_replace_callback('/<img[^>]+src="data:image\/([^;]+);base64,([^">]+)"[^>]*>/', function($matches) {
                $extension = $matches[1]; // png, jpg, etc.
                $base64Data = $matches[2];
                $imageData = base64_decode($base64Data);
                
                // Creamos un nombre temporal único
                $tempName = 'temp_ai_' . uniqid() . '.' . $extension;
                $tempPath = storage_path('app/public/temp/' . $tempName);
                
                // Aseguramos que la carpeta temp exista en Trujillo
                if (!file_exists(storage_path('app/public/temp'))) {
                    mkdir(storage_path('app/public/temp'), 0755, true);
                }
    
                // Guardamos la imagen físicamente
                file_put_contents($tempPath, $imageData);
    
                // Devolvemos el HTML con la ruta local (esto lo entiende mejor PHPWord)
                return '<img src="' . $tempPath . '" style="width:300px; height:auto;" />';
            }, $content);
        }
    
        // 3. Procesar el HTML resultante
        try {
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, '<div>' . $content . '</div>', false, false);
        } catch (\Exception $e) {
            $section->addText("Error en imagen o formato: " . $e->getMessage());
            $section->addText(strip_tags($content));
        }
    
        $section->addTextBreak(1);
    }

    /**
     * Detecta si el paso es una tabla (Cronograma o Presupuesto)
     */
    private function isPlanningStep($title) {
        $t = strtolower($title);
        return str_contains($t, 'cronograma') || str_contains($t, 'presupuesto');
    }

    /**
     * Renderiza tablas profesionales en Word basadas en JSON
     */
private function addTableToWord($section, $data, $title) {
        $tableStyle = [
            'borderSize'  => 6, 
            'borderColor' => '000000', 
            'cellMargin'  => 80,
            'alignment'   => Jc::CENTER // Centra la tabla en la página
        ];
        
        // Estilo de celda para centrado vertical y horizontal
        $cellStyle = ['valign' => 'center'];
        $paragraphStyle = ['alignment' => Jc::CENTER, 'spaceAfter' => 0];

        $table = $section->addTable($tableStyle);
        $isCronograma = str_contains(strtolower($title), 'cronograma');

        if ($isCronograma) {
            // CABECERA CRONOGRAMA
            $table->addRow();
            $table->addCell(4000, $cellStyle)->addText("ACTIVIDADES / MESES", ['bold' => true, 'size' => 10], $paragraphStyle);
            foreach(['E','F','M','A','M','J','J','A','S','O','N','D'] as $m) {
                $table->addCell(400, $cellStyle)->addText($m, ['bold' => true, 'size' => 10], $paragraphStyle);
            }

            // FILAS CRONOGRAMA
            foreach($data as $row) {
                $table->addRow();
                $table->addCell(4000, $cellStyle)->addText($row['actividad'], ['size' => 10], ['alignment' => Jc::LEFT]);
                foreach($row['meses'] as $check) {
                    $table->addCell(400, $cellStyle)->addText($check ? 'X' : '', ['bold' => true], $paragraphStyle);
                }
            }
        } else {
            // CABECERA PRESUPUESTO
            $table->addRow();
            $table->addCell(5000, $cellStyle)->addText("DESCRIPCIÓN DEL ÍTEM", ['bold' => true], $paragraphStyle);
            $table->addCell(1500, $cellStyle)->addText("CANT.", ['bold' => true], $paragraphStyle);
            $table->addCell(2000, $cellStyle)->addText("P. UNIT.", ['bold' => true], $paragraphStyle);
            $table->addCell(2000, $cellStyle)->addText("TOTAL", ['bold' => true], $paragraphStyle);

            $items = $data['items'] ?? $data;
            foreach($items as $item) {
                $table->addRow();
                $table->addCell(5000, $cellStyle)->addText($item['item'] ?? $item['actividad'], null, $paragraphStyle);
                $table->addCell(1500, $cellStyle)->addText($item['cant'] ?? '1', null, $paragraphStyle);
                $table->addCell(2000, $cellStyle)->addText(number_format($item['precio'] ?? 0, 2), null, $paragraphStyle);
                $total = ($item['cant'] ?? 1) * ($item['precio'] ?? 0);
                $table->addCell(2000, $cellStyle)->addText(number_format($total, 2), ['bold' => true], $paragraphStyle);
            }
        }
    }

    private function addCenteredField($section, $label, $value) {
        $section->addText($label, ['bold' => true], ['alignment' => Jc::CENTER]);
        $section->addText($value ?? 'No especificado', null, ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);
    }
}