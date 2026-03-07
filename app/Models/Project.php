<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Clase Project
 * Representa el trabajo de investigación (tesis) del alumno.
 */
class Project extends Model
{
    /**
     * Atributos asignables masivamente.
     */
    
    protected $fillable = [
        'uuid',            // Identificador único universal para URLs seguras (getRouteKeyName)
        'user_id',         // ID del alumno propietario del proyecto
        'university_id',   // ID de la universidad (define las reglas y formato JSON)
        'classroom_id',    // ID del aula (si el proyecto es guiado por un asesor)
        
        'title',           // Título de la investigación (se define en la Etapa 2 del Setup)
        'faculty',          // NUEVO: Ej. Facultad de Ingeniería
        'school',           // NUEVO: Ej. Escuela de Ingeniería de Sistemas
        'academic_line',    // NUEVO: Línea de investigación (opcional)

        'document_type',   // Tipo: 'thesis_project' (Proyecto) o 'final_report' (Informe/Tesis)
        'academic_status', // Estado: 'draft', 'under_review', 'approved', etc.
        'access_type',     // Privacidad: 'public' o 'private'
        
        'settings',        // Configuración extra del proyecto (JSON: fuentes, márgenes, etc.)
        'general_data',    // ETAPA 1: Datos técnicos (JSON: área, objeto, problema, lugar, tiempo)
        'setup_step',      // Rastreador del Wizard: 1 (Datos), 2 (Título), 3 (Finalizado)
        
        'ai_word_limit',   // Límite total de palabras permitidas por la IA para este proyecto
        'ai_words_used',   // Contador de palabras generadas por la IA hasta el momento
        
        'payment_status',  // Estado del pago: 'pending', 'paid', 'trial'
        'expires_at',      // Fecha de vencimiento del acceso al editor
    ];

    /**
     * Casts de tipos.
     */
    protected $casts = [
        'settings' => 'array',
        'general_data' => 'array',
        'expires_at' => 'datetime',
        'ai_word_limit' => 'integer',
        'ai_words_used' => 'integer',
        'setup_step' => 'integer',
    ];

    /**
     * Genera automáticamente un UUID al crear el proyecto.
     */
    protected static function booted()
    {
        static::creating(function ($project) {
            $project->uuid = (string) Str::uuid();
        });
    }

    /* |--------------------------------------------------------------------------
       | Relaciones
       |-------------------------------------------------------------------------- */

    /** El autor de la investigación */
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    /** Universidad que rige el formato (UPAO, UCV, etc.) */
    public function university(): BelongsTo { return $this->belongsTo(University::class); }

    /** Aula si pertenece a un curso de tesis */
    public function classroom(): BelongsTo { return $this->belongsTo(Classroom::class); }

    /** Historial de palabras consumidas por la IA */
    public function usageLogs(): HasMany { return $this->hasMany(IaUsageLog::class); }

    /** * ESTRUCTURA DE LA TESIS: Pasos individuales (I, II, III...)
     * He mantenido solo una declaración para evitar el FatalError.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(ProjectStep::class, 'project_id')->orderBy('order', 'asc');
    }
    
    /**
     * Notas dejadas por el asesor en este capítulo
     */
    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }
    
    /* |--------------------------------------------------------------------------
       | Lógica de Negocio
       |-------------------------------------------------------------------------- */

    /**
     * Crea automáticamente los pasos según el JSON de la Universidad.
     */
    public function generateStructure($classroom = null, $customSteps = null)
    {
        // CASO NUEVO: El alumno editó los nombres manualmente en el Setup
        if (!empty($customSteps)) {
            foreach ($customSteps as $step) {
                $this->steps()->create([
                    'order' => $step['orden'],
                    'title' => $step['titulo'], // Guardamos el nombre editado por el alumno
                    'internal_type' => $this->normalizeKey($step['titulo']), 
                    'structured_data' => [
                        'secciones' => $step['secciones'] ?? [],
                        'instrucciones' => $step['instrucciones'] ?? ''
                    ],
                    'status' => 'pending'
                ]);
            }
            return;
        }


        // CAMINO A: El alumno tiene un aula asignada (Prioridad Asesor)
        if ($classroom && $classroom->classroomSteps()->exists()) {
            
            // Obtenemos el JSON base por si el docente dejó instrucciones vacías
            $map = ['thesis_project' => 'proyecto_tesis', 'final_report' => 'informe_final'];
            $jsonKey = $map[$this->document_type] ?? $this->document_type;
            $baseFormat = $this->university->reglas_json['formatos'][$jsonKey]['pasos'] ?? [];
    
            foreach ($classroom->classroomSteps as $cStep) {
                // Buscamos las instrucciones originales en el JSON usando el step_key
                $originalStep = collect($baseFormat)->firstWhere('key', $cStep->step_key);
                
                $this->steps()->create([
                    'order' => $cStep->order,
                    'title' => $cStep->custom_name ?? ($originalStep['titulo'] ?? $cStep->step_key),
                    'internal_type' => $cStep->step_key,
                    'availability_mode' => $cStep->availability_mode,
                    'available_at' => $cStep->available_at,
                    'structured_data' => [
                        // Si el docente no puso tips extras, usamos la guía base del JSON
                        'instrucciones' => $cStep->additional_instructions ?: ($originalStep['instrucciones'] ?? ''),
                        'secciones' => $originalStep['secciones'] ?? []
                    ],
                    'status' => 'pending'
                ]);
            }
            return;
        }
    
        // CAMINO B: El alumno está solo (Lógica actual del JSON)
        $rules = $this->university->reglas_json;
        $map = ['thesis_project' => 'proyecto_tesis', 'final_report' => 'informe_final'];
        $jsonKey = $map[$this->document_type] ?? $this->document_type;
        $format = $rules['formatos'][$jsonKey] ?? null;
    
        if ($format && isset($format['pasos'])) {
            foreach ($format['pasos'] as $step) {
                $this->steps()->create([
                    'order' => $step['orden'],
                    'title' => $step['titulo'],
                    'structured_data' => [
                        'secciones' => $step['secciones'] ?? [],
                        'instrucciones' => $step['instrucciones'] ?? ''
                    ],
                    'status' => 'pending'
                ]);
            }
        }
    }

    /**
     * Función auxiliar para normalizar el título a un slug/key interno
     */
    private function normalizeKey($title) {
        return Str::slug($title, '_');
    }
    
    /** Accessor: Consumo diario de IA */
    public function getDailyUsageAttribute(): int
    {
        return (int) $this->usageLogs()
            ->whereDate('created_at', now()->toDateString())
            ->sum('palabras_generadas');
    }

    /** Validador: ¿Puede seguir usando la IA? */
    public function hasAvailableQuota(): bool
    {
        if ($this->ai_words_used >= $this->ai_word_limit) return false;
        if ($this->daily_usage >= 15000) return false;
        return true;
    }

    /**
     * Indica a Laravel que el identificador en la URL es el UUID.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}