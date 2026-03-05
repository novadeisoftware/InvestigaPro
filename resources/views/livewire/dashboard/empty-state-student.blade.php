<div class="col-span-full py-24 bg-white dark:bg-gray-900 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center animate-fade-in">
    
    {{-- Icono Dinámico --}}
    <div class="w-20 h-20 bg-brand-50 dark:bg-brand-900/20 rounded-[2rem] flex items-center justify-center mb-6 shadow-sm rotate-3 group-hover:rotate-0 transition-transform">
        <svg class="w-10 h-10 text-brand-600 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    {{-- Mensaje para el Alumno --}}
    <div class="max-w-xs px-6">
        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-2 italic">
            ¿Listo para tu investigación?
        </h3>
        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">
            Aún no estás inscrito en ninguna aula virtual de **InvestigaPro**. Pide el código a tu asesor para comenzar.
        </p>
    </div>

    {{-- Botón de Acción --}}
    {{-- Nota: Este botón debe redirigir al tab de 'unirme' en tu vista de aulas --}}
    <a href="{{ route('classroom', ['tab' => 'joined_classrooms']) }}" 
       class="mt-8 flex items-center gap-2 px-8 py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-brand-500/30 active:scale-95 group">
        <span class="group-hover:translate-x-1 transition-transform">🚀</span>
        Unirme a un Aula
    </a>
</div>