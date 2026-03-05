{{-- En manage-dashboard.blade.php --}}
<div class="p-8">
    @if($classroom)
        <div class="max-w-4xl mx-auto bg-white rounded-[3rem] p-10 border border-gray-100 shadow-xl">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="w-24 h-24 bg-brand-600 rounded-[2rem] flex items-center justify-center text-white shadow-lg shadow-brand-500/40">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-black text-gray-900 uppercase italic leading-none mb-2">¡Hola, {{ auth()->user()->name }}!</h2>
                    <p class="text-gray-500 font-medium">Actualmente estás trabajando en el aula <strong>{{ $classroom->name }}</strong>.</p>
                </div>
                <a href="{{ route('student.classroom.show', $classroom->id) }}" 
                   class="bg-gray-900 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-brand-600 transition-all shadow-xl shadow-gray-900/20 active:scale-95">
                    Entrar a mi Aula →
                </a>
            </div>
        </div>
    @else
        {{-- Mensaje para unirse si no tiene aula --}}
    @endif
</div>