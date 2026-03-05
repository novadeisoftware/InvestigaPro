<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Agrupa todos los menús para el Sidebar (usado si no usas el Controller)
     */
    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menú',
                'items' => [
                    ['icon' => 'grid-icon', 'name' => 'Panel de Control', 'path' => '/dashboard'],
                    ['icon' => 'calendar-icon', 'name' => 'Agenda', 'path' => '/calendar'],
                ]
            ]
        ];
    }

    /**
     * Diccionario de Iconos SVG sincronizado con SidebarController
     */
    public static function getIconSvg($iconName)
    {
        $icons = [
            // Iconos principales
            'grid-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',
            'bot-icon'      => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2a2 2 0 0 1 2 2v1h2a3 3 0 0 1 3 3v2.17l.71.7a1 1 0 0 1 0 1.42l-.71.71V16a3 3 0 0 1-3 3h-2v1a2 2 0 0 1-4 0v-1H7a3 3 0 0 1-3-3v-3.17l-.71-.7a1 1 0 0 1 0-1.42l.71-.71V8a3 3 0 0 1 3-3h2V4a2 2 0 0 1 2-2zm-3.5 9a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm7 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z" fill="currentColor"/></svg>',
            'cart-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0 0 20 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" fill="currentColor"/></svg>',
            'calendar-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z" fill="currentColor"/></svg>',
            'user-circle-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="currentColor"/></svg>',
            'task-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" fill="currentColor"/></svg>',
            'list-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z" fill="currentColor"/></svg>',
            'table-icon'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 10.0833H15.416V13.9165H10V10.0833ZM20.75 5.5V18.5C20.75 19.75 19.75 20.75 18.5 20.75H5.5C4.25 20.75 3.25 19.75 3.25 18.5V5.5C3.25 4.25 4.25 3.25 5.5 3.25H18.5C19.75 3.25 20.75 4.25 20.75 5.5Z" fill="currentColor"/></svg>',
            'page-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z" fill="currentColor"/></svg>',
            'chat-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-5H6V7h12v2z" fill="currentColor"/></svg>',
            'call-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-2.2 2.2a15.045 15.045 0 0 1-6.59-6.59l2.2-2.21c.28-.26.36-.65.25-1C8.7 6.45 8.5 5.25 8.5 4.01c0-.55-.45-1-1-1H4.01c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.58c0-.56-.45-1.05-1-1.05z" fill="currentColor"/></svg>',
            'mail-icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/></svg>',
            'pie-chart-icon'=> '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 2v20c-5.07-.5-9-4.79-9-10s3.93-9.5 9-10zm2 0v8.5L21.99 6c-1.45-2.56-3.99-4.38-7-4.87zM13 12.5V22c3.01-.49 5.55-2.31 7-4.87L13 12.5z" fill="currentColor"/></svg>',
            'box-cube-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 16.5c0 .38-.21.71-.53.88l-7.97 4.43c-.16.09-.33.14-.5.14s-.34-.05-.5-.14l-7.97-4.43A.996.996 0 0 1 3 16.5v-9c0-.38.21-.71.53-.88l7.97-4.43c.16-.09.33-.14.5-.14s.34.05.5.14l7.97 4.43c.32.17.53.5.53.88v9zM12 4.15L6.04 7.5 12 10.85l5.96-3.35L12 4.15zM5 15.91l6 3.34v-6.35l-6-3.34v6.35zm8 3.34l6-3.34V9.56l-6 3.34v6.35z" fill="currentColor"/></svg>',
            'plug-in-icon'  => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 15v-3c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2h-3c0-1.1-.9-2-2-2s-2 .9-2 2H4c-1.1 0-2 .9-2 2v3h1c1.1 0 2 .9 2 2s-.9 2-2 2H2v3c0 1.1.9 2 2 2h3c0 1.1.9 2 2 2s2-.9 2-2h3c1.1 0 2-.9 2-2v-1h1c1.1 0 2-.9 2-2z" fill="currentColor"/></svg>',
            'research-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.32 15.14l-4.32-6.17V4h1V2h-6v2h1v4.97L5.68 15.14c-.45.64-.68 1.4-.68 2.18C5 19.35 6.65 21 8.68 21h6.64c2.03 0 3.68-1.65 3.68-3.68 0-.78-.23-1.54-.68-2.18zM8.68 19c-.93 0-1.68-.75-1.68-1.68 0-.36.1-.71.3-1l3.7-5.29V4h2v7.03l3.7 5.29c.2.29.3.64.3 1 0 .93-.75 1.68-1.68 1.68H8.68z" fill="currentColor"/></svg>',
            'classroom-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm0 2v10h14V6H5zm2 2h10v2H7V8zm0 4h7v2H7v-2zm2 7h6v2H9v-2z" fill="currentColor"/></svg>',
            'university-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3L1 9L12 15L21 10.09V17H23V9L12 3Z" fill="currentColor"/><path d="M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" fill="currentColor"/></svg>',
            'project-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10zM7 10h10v2H7v-2zm0 3h7v2H7v-2z" fill="currentColor"/></svg>',
            'classroom-group-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm-5 4V9h10v2h-1v2h1v7h-2v-4h-1v4h-2v-4h-1v4H7v-7h1v-2H7zm-3 9v-3c0-1.1.9-2 2-2h1v2H6v3H4zm16 0v-3h-1v-2h1c1.1 0 2 .9 2 2v3h-2z" fill="currentColor"/></svg>',
            'pizarra-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 5c-1.1046 0-2 .8954-2 2v9c0 1.1046.8954 2 2 2h16c1.1046 0 2-.8954 2-2V7c0-1.1046-.8954-2-2-2H4zm0 2h16v8H4V7zm3 12a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1z" fill="currentColor"/></svg>',
            'subscription-icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4V10h16v8zm0-10H4V6h16v2zm-5 4l1.25 2.75L19 13l-2.75 1.25L15 17l-1.25-2.75L11 13l2.75-1.25L15 12z" fill="currentColor"/></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>';
    }
}