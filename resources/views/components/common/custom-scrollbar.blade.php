@once
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #adb1b6; /* Color base */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; /* slate-400 para que se note el hover */
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155; /* slate-700 */
    }

    /* Ocultar scrollbar si se desea pero manteniendo funcionalidad */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endonce