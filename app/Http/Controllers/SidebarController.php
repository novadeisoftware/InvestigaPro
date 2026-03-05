<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
    /**
     * Retorna la estructura cruda del menú lateral.
     * * Este método centraliza toda la navegación de la aplicación.
     * Se utiliza a través de un View Composer para inyectar estos datos
     * automáticamente en la vista del sidebar sin repetir código.
     *
     * @return array Estructura jerárquica de grupos, items y sub-items.
     */
    public function getMenuDataRaw()
    {
        return [
            // Grupo principal de navegación
            [
                'title' => 'Menu',
                'items' => [

                    [
                        'icon' => 'grid-icon',
                        'name' => 'Panel de Control',
                        'path' => '/dashboard', // Item simple sin sub-items
                    ],
                    /*[
                        'icon' => 'bot-icon', // Icono representativo para la IA
                        'name' => 'AI Assistant',
                        'new' => true, // Atributo para mostrar badge de "Nuevo" en la UI
                        'subItems' => [
                            ['name' => 'Text Generator', 'path' => '/text-generator'],
                            ['name' => 'Image Generator', 'path' => '/image-generator'],
                            ['name' => 'Code Generator', 'path' => '/code-generator'],
                            ['name' => 'Video Generator', 'path' => '/video-generator'],
                        ],
                    ],*/
                   /* [
                        'icon' => 'cart-icon',
                        'name' => 'E-commerce',
                        'new' => true,
                        'subItems' => [
                            ['name' => 'Products', 'path' => '/products-list'],
                            ['name' => 'Add Product', 'path' => '/add-product'],
                            ['name' => 'Billing', 'path' => '/billing'],
                            ['name' => 'Invoices', 'path' => '/invoices'],
                            ['name' => 'Single Invoice', 'path' => '/single-invoice'],
                            ['name' => 'Create Invoice', 'path' => '/create-invoice'],
                            ['name' => 'Transactions', 'path' => '/transactions'],
                            ['name' => 'Single Transaction', 'path' => '/single-transaction'],
                        ],
                    ],*/
                    [
                        'icon' => 'project-icon',
                        'name' => 'Investigaciones',
                        'path' => '/project', // Item simple sin sub-items
                    ],
                    [
                        'icon' => 'pizarra-icon',
                        'name' => 'Aulas',
                        'path' => '/classroom', // Item simple sin sub-items
                    ],
                    [
                        'icon' => 'calendar-icon',
                        'name' => 'Calendar',
                        'path' => '/calendar', // Item simple sin sub-items
                    ],
                    /*[
                        'icon' => 'task-icon',
                        'name' => 'Task',
                        'subItems' => [
                            ['name' => 'List', 'path' => '/task-list', 'pro' => false],
                            ['name' => 'Kanban', 'path' => '/task-kanban', 'pro' => false],
                        ],
                    ],*/
                    [
                        'icon' => 'list-icon',
                        'name' => 'Forms',
                        'subItems' => [
                            ['name' => 'Form Elements', 'path' => '/form-elements', 'pro' => false],
                            ['name' => 'Form Layout', 'path' => '/form-layout', 'pro' => false],
                        ],
                    ],
                    [
                        'icon' => 'table-icon',
                        'name' => 'Tables',
                        'subItems' => [
                            ['name' => 'Basic Tables', 'path' => '/basic-tables', 'pro' => false],
                            ['name' => 'Data Tables', 'path' => '/data-tables', 'pro' => false],
                        ],
                    ],
                    /*[
                        'icon' => 'page-icon',
                        'name' => 'Pages',
                        'subItems' => [
                            ['name' => 'File Manager', 'path' => '/file-manager', 'pro' => false],
                            ['name' => 'Pricing Tables', 'path' => '/pricing-tables', 'pro' => false],
                            ['name' => 'Faqs', 'path' => '/faq', 'pro' => false],
                            ['name' => 'API Keys', 'path' => '/api-keys', 'new' => true],
                            ['name' => 'Integrations', 'path' => '/integrations', 'new' => true],
                            ['name' => 'Blank Page', 'path' => '/blank', 'pro' => false],
                            ['name' => '404 Error', 'path' => '/error-404', 'pro' => false],
                        ],
                    ],*/
                ],
            ],

            // Sección de utilidades y configuración del sistema
            [
                'title' => 'Configuración',
                'items' => [
                    [
                        'icon' => 'subscription-icon',
                        'name' => 'Planes',
                        'path' => '/subscription',
                    ],
                    [
                        'icon' => 'user-circle-icon',
                        'name' => 'Usuarios',
                        'path' => '/admin/users',
                    ],
                    [
                        'icon' => 'university-icon',
                        'name' => 'Universidades',
                        'path' => '/admin/university',
                    ],
                    [
                        'icon' => 'pie-chart-icon',
                        'name' => 'Charts',
                        'subItems' => [
                            ['name' => 'Line Chart', 'path' => '/line-chart', 'pro' => false],
                            ['name' => 'Bar Chart', 'path' => '/bar-chart', 'pro' => false],
                            ['name' => 'Pie Chart', 'path' => '/pie-chart', 'pro' => false],
                        ],
                    ],
                    [
                        'icon' => 'box-cube-icon',
                        'name' => 'UI Elements',
                        'subItems' => [
                            ['name' => 'Alerts', 'path' => '/alerts', 'pro' => false],
                            ['name' => 'Avatar', 'path' => '/avatars', 'pro' => false],
                            ['name' => 'Badge', 'path' => '/badge', 'pro' => false],
                            ['name' => 'Buttons', 'path' => '/buttons', 'pro' => false],
                            ['name' => 'Images', 'path' => '/image', 'pro' => false],
                            ['name' => 'Videos', 'path' => '/videos', 'pro' => false],
                        ],
                    ],
                ],
            ],
            // Sección dedicada a la atención al cliente
            [
                'title' => 'Support',
                'items' => [
                    [
                        'icon' => 'chat-icon',
                        'name' => 'Chat',
                        'path' => '/chat',
                    ],
                    [
                        'icon' => 'call-icon',
                        'name' => 'Support Ticket',
                        'new' => true,
                        'subItems' => [
                            ['name' => 'Ticket List', 'path' => '/support-tickets'],
                            ['name' => 'Ticket Reply', 'path' => '/support-ticket-reply'],
                        ],
                    ],
                    [
                        'icon' => 'mail-icon',
                        'name' => 'Email',
                        'subItems' => [
                            ['name' => 'Inbox', 'path' => '/inbox', 'pro' => false],
                            ['name' => 'Details', 'path' => '/inbox-details', 'pro' => false],
                        ],
                    ],
                ],
            ],
        ];
    }
}