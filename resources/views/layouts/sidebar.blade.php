@php
    /**
     * CAPA DE DATOS PREVIA:
     * Obtenemos la ruta actual (URI) para comparar contra los paths del menú.
     * Esto permite marcar visualmente qué página está visitando el usuario.
     */
    $currentPath = request()->path();
@endphp

{{-- 
    BLOQUE DE SEGURIDAD @isset:
    Asegura que $menuGroups (inyectada por AppServiceProvider) exista.
    Si el ViewComposer fallara, la aplicación no se rompería.
--}}
@isset($menuGroups)
<aside id="sidebar"
    {{-- 
        ESTADO FRONTEND (Alpine.js):
        x-data: Define el estado reactivo del sidebar.
        openSubmenus: Objeto que rastrea qué carpetas están abiertas.
    --}}
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200"
    x-data="{
        openSubmenus: {},
        init() {
            // Se ejecuta automáticamente al cargar el DOM.
            this.initializeActiveMenus();
        },
        /**
         * initializeActiveMenus:
         * Recorre el array de menús mediante Blade y genera lógica JS.
         * Si detecta que el usuario está en una sub-ruta, abre el menú padre automáticamente.
         */
        initializeActiveMenus() {
            const currentPath = '{{ $currentPath }}';
            @foreach ($menuGroups as $groupIndex => $menuGroup)
                @foreach ($menuGroup['items'] as $itemIndex => $item)
                    @if (isset($item['subItems']))
                        @foreach ($item['subItems'] as $subItem)
                            if (currentPath === '{{ ltrim($subItem['path'], '/') }}' ||
                                window.location.pathname === '{{ $subItem['path'] }}') {
                                this.openSubmenus['{{ $groupIndex }}-{{ $itemIndex }}'] = true;
                            }
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        },
        /**
         * toggleSubmenu:
         * Cierra todos los menús y abre solo el seleccionado (estilo acordeón).
         */
        toggleSubmenu(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            const newState = !this.openSubmenus[key];
            if (newState) { this.openSubmenus = {}; } 
            this.openSubmenus[key] = newState;
        },
        isSubmenuOpen(groupIndex, itemIndex) {
            return this.openSubmenus[groupIndex + '-' + itemIndex] || false;
        }
    }"
    {{-- 
        DINAMISMO DE ANCHO (Tailwind + Alpine):
        Cambia el ancho entre 290px (expandido) y 90px (colapsado) basándose
        en los estados globales del $store.sidebar.
    --}}
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
        <a href="/">
            {{-- Logo para escritorio/expandido --}}
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="dark:hidden" src="/images/logo/logo.svg" alt="Logo" width="150" height="40" />
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="hidden dark:block" src="/images/logo/logo-dark.svg" alt="Logo" width="150" height="40" />
            
            {{-- Favicon para modo colapsado --}}
            <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                src="/images/logo/logo-icon.svg" alt="Logo" width="32" height="32" />
        </a>
    </div>

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                {{-- ITERACIÓN DE GRUPOS (Ej: MENU, SOPORTE) --}}
                @foreach ($menuGroups as $groupIndex => $menuGroup)
                    <div>
                        {{-- Títulos de sección: Solo visibles si hay espacio --}}
                        <h2 class="mb-4 text-xs uppercase flex leading-[20px] text-gray-400"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'lg:justify-center' : 'justify-start'">
                            <template x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                        </h2>

                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $itemIndex => $item)
                                <li>
                                    {{-- CASO A: El item tiene sub-categorías (Dropdown) --}}
                                    @if (isset($item['subItems']))
                                        <button @click="toggleSubmenu({{ $groupIndex }}, {{ $itemIndex }})"
                                            class="menu-item group w-full flex items-center"
                                            :class="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ? 'menu-item-active' : 'menu-item-inactive'">
                                            
                                            {{-- Inyección de SVG desde el MenuHelper --}}
                                            <span class="mr-3">{!! App\Helpers\MenuHelper::getIconSvg($item['icon']) !!}</span>
                                            
                                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" class="flex items-center gap-2">
                                                {{ $item['name'] }}
                                                
                                                {{-- BADGE RED: Resalta categorías principales nuevas --}}
                                                @if(!empty($item['new']))
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-600 text-white uppercase tracking-tighter">new</span>
                                                @endif
                                            </span>

                                            {{-- Flecha indicadora de estado abierto/cerrado --}}
                                            <svg x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="ml-auto w-4 h-4 transition-transform duration-200"
                                                :class="{'rotate-180': isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }})}"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                                        </button>

                                        {{-- LISTADO DE HIJOS: Animado mediante Alpine x-show --}}
                                        <div x-show="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)">
                                            <ul class="mt-2 space-y-1 ml-9 border-l border-gray-100 dark:border-gray-800">
                                                @foreach ($item['subItems'] as $subItem)
                                                    <li>
                                                        <a href="{{ $subItem['path'] }}" 
                                                           class="menu-dropdown-item flex items-center justify-between {{ request()->is(ltrim($subItem['path'], '/')) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive' }}">
                                                            <span>{{ $subItem['name'] }}</span>

                                                            {{-- BADGE GREEN: Resalta funciones específicas --}}
                                                            @if(!empty($subItem['new']))
                                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-500 text-white uppercase">new</span>
                                                            @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        {{-- CASO B: Item simple (Link directo) --}}
                                        <a href="{{ $item['path'] }}" class="menu-item group flex items-center {{ request()->is(ltrim($item['path'] ?? '', '/')) ? 'menu-item-active' : 'menu-item-inactive' }}">
                                            <span class="mr-3">{!! App\Helpers\MenuHelper::getIconSvg($item['icon']) !!}</span>
                                            
                                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" class="flex items-center gap-2">
                                                {{ $item['name'] }}
                                                
                                                {{-- BADGE BLUE: Resalta secciones independientes --}}
                                                @if(!empty($item['new']))
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500 text-white uppercase">new</span>
                                                @endif
                                            </span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>
    </div>
</aside>

{{-- OVERLAY: Oscurece el fondo en móviles para enfocar el sidebar --}}
<div x-show="$store.sidebar.isMobileOpen" @click="$store.sidebar.setMobileOpen(false)"
    class="fixed z-50 h-screen w-full bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
@endisset