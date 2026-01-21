<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Define los items principales del menú (Sección superior)
     */
    public static function getMainNavItems()
    {
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Panel de Control',
                'subItems' => [
                    ['name' => 'Vista General', 'path' => '/dashboard'],
                ],
            ],
            [
                'icon' => 'calendar',
                'name' => 'Agenda Logística',
                'path' => '/calendar',
            ],
            [
                'icon' => 'user-profile',
                'name' => 'Mi Perfil',
                'path' => '/profile', // Ruta oficial de Jetstream
            ],
            [
                'name' => 'Formularios',
                'icon' => 'forms',
                'subItems' => [
                    ['name' => 'Elementos de Formulario', 'path' => '/form-elements', 'pro' => false],
                ],
            ],
            [
                'name' => 'Tablas',
                'icon' => 'tables',
                'subItems' => [
                    ['name' => 'Tablas Básicas', 'path' => '/basic-tables', 'pro' => false]
                ],
            ],
        ];
    }

    /**
     * Define items secundarios (Sección "Otros")
     */
    public static function getOthersItems()
    {
        return [
            [
                'icon' => 'charts',
                'name' => 'Gráficos',
                'subItems' => [
                    ['name' => 'Gráfico de Líneas', 'path' => '/line-chart', 'pro' => false],
                    ['name' => 'Gráfico de Barras', 'path' => '/bar-chart', 'pro' => false]
                ],
            ],
            [
                'icon' => 'ui-elements',
                'name' => 'Elementos UI',
                'subItems' => [
                    ['name' => 'Alertas', 'path' => '/alerts', 'pro' => false],
                    ['name' => 'Avatares', 'path' => '/avatars', 'pro' => false],
                    ['name' => 'Botones', 'path' => '/buttons', 'pro' => false],
                ],
            ],
        ];
    }

    /**
     * Agrupa todos los menús para el Sidebar
     */
    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menú',
                'items' => self::getMainNavItems()
            ],
            [
                'title' => 'Otros',
                'items' => self::getOthersItems()
            ]
        ];
    }

    /**
     * Determina si un item del menú está activo basado en la URL
     */
    public static function isActive($path)
    {
        // Limpiamos las barras para evitar errores de comparación
        $cleanPath = ltrim($path, '/');
        // Retorna true si la URL actual coincide o empieza con el path
        return request()->is($cleanPath) || request()->is($cleanPath . '/*');
    }

    /**
     * Diccionario de Iconos SVG
     */
    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',
            'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"></path></svg>',
            'user-profile' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="currentColor"/></svg>',
            'forms' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 3H5C3.89 3 3 3.89 3 5V19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.89 20.1 3 19 3ZM19 19H5V5H19V19ZM7 7H17V9H7V7ZM7 11H17V13H7V11ZM7 15H14V17H7V15Z" fill="currentColor"/></svg>',
            'tables' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 10.0833H15.416V13.9165H10V10.0833ZM20.75 5.5V18.5C20.75 19.75 19.75 20.75 18.5 20.75H5.5C4.25 20.75 3.25 19.75 3.25 18.5V5.5C3.25 4.25 4.25 3.25 5.5 3.25H18.5C19.75 3.25 20.75 4.25 20.75 5.5Z" fill="currentColor"/></svg>',
            'charts' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM9 17H7V10H9V17ZM13 17H11V7H13V17ZM17 17H15V13H17V17Z" fill="currentColor"/></svg>',
            'ui-elements' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L4.5 20.29L5.21 21L12 18L18.79 21L19.5 20.29L12 2Z" fill="currentColor"/></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}