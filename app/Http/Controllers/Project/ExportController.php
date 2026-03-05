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
        $paragraphs = preg_split('/<\/?p[^>]*>/', $content, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($paragraphs as $text) {
            $cleanText = strip_tags($text);
            if (!empty(trim($cleanText))) {
                $section->addText(htmlspecialchars($cleanText), null, [
                    'alignment' => Jc::BOTH, 'lineHeight' => 1.5,
                    'indentation' => ['firstLine' => 1417], 'spaceAfter' => 240
                ]);
            }
        }
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
        // Estilo de tabla con bordes negros finos
        $tableStyle = [
            'borderSize'  => 6, 
            'borderColor' => '000000', 
            'cellMargin'  => 80,
            'alignment'   => Jc::CENTER
        ];
        
        $table = $section->addTable($tableStyle);
        $isCronograma = str_contains(strtolower($title), 'cronograma');

        if ($isCronograma) {
            // CABECERA CRONOGRAMA
            $table->addRow();
            $table->addCell(4000)->addText("ACTIVIDADES / MESES", ['bold' => true, 'size' => 10]);
            foreach(['E','F','M','A','M','J','J','A','S','O','N','D'] as $m) {
                $table->addCell(400)->addText($m, ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
            }

            // FILAS CRONOGRAMA
            foreach($data as $row) {
                $table->addRow();
                $table->addCell(4000)->addText($row['actividad'], ['size' => 10]);
                foreach($row['meses'] as $check) {
                    $table->addCell(400)->addText($check ? 'X' : '', ['bold' => true], ['alignment' => Jc::CENTER]);
                }
            }
        } else {
            // CABECERA PRESUPUESTO
            $table->addRow();
            $table->addCell(5000)->addText("DESCRIPCIÓN DEL ÍTEM", ['bold' => true]);
            $table->addCell(1500)->addText("CANT.", ['bold' => true], ['alignment' => Jc::CENTER]);
            $table->addCell(2000)->addText("P. UNIT.", ['bold' => true], ['alignment' => Jc::RIGHT]);
            $table->addCell(2000)->addText("TOTAL", ['bold' => true], ['alignment' => Jc::RIGHT]);

            // FILAS PRESUPUESTO (Asumiendo estructura {items: [], total: 0})
            $items = $data['items'] ?? $data; // Ajustar según tu JSON
            foreach($items as $item) {
                $table->addRow();
                $table->addCell(5000)->addText($item['item'] ?? $item['actividad']);
                $table->addCell(1500)->addText($item['cant'] ?? '1', [], ['alignment' => Jc::CENTER]);
                $table->addCell(2000)->addText(number_format($item['precio'] ?? 0, 2), [], ['alignment' => Jc::RIGHT]);
                $total = ($item['cant'] ?? 1) * ($item['precio'] ?? 0);
                $table->addCell(2000)->addText(number_format($total, 2), ['bold' => true], ['alignment' => Jc::RIGHT]);
            }
        }
    }

    private function addCenteredField($section, $label, $value) {
        $section->addText($label, ['bold' => true], ['alignment' => Jc::CENTER]);
        $section->addText($value ?? 'No especificado', null, ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);
    }
}