<?php

/**
 * Putting this here to help remind you where this came from.
 *
 * I'll get back to improving this and adding more as time permits
 * if you need some help feel free to drop me a line.
 *
 * * Twenty-Years Experience
 * * PHP, JavaScript, Laravel, MySQL, Java, Python and so many more!
 *
 *
 * @author  Simple-Pleb <plebeian.tribune@protonmail.com>
 * @website https://www.simple-pleb.com
 * @source https://github.com/simplepleb/thememanager-module
 *
 * @license MIT For Premium Clients
 *
 * @since 1.0
 *
 */

namespace Modules\Modulemanager\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {

            // Articles Dropdown
            $modules_menu = $menu->add('<i class="c-sidebar-nav-icon cil-puzzle"></i> Modules', [
                'class' => 'c-sidebar-nav-dropdown',
            ])
                ->data([
                    'order'         => 103,
                    'activematches' => [
                        'admin/modulemanager*',
                    ],
                    'permission' => ['view_posts', 'view_categories'],
                ]);
            $modules_menu->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            $modules_menu->add('List', [
                'route' => 'backend.modulemanager.index',
                'class' => 'c-sidebar-nav-item',
            ])
                ->data([
                    'order'         => 104,
                    'activematches' => 'admin/modulemanager',
                    'permission'    => ['edit_posts'],
                ])
                ->link->attr([
                    'class' => "c-sidebar-nav-link",
                ]);
            // Submenu: Posts
            $modules_menu->add('Builder', [
                'route' => 'backend.module_builder.builder.create',
                'class' => 'c-sidebar-nav-item',
            ])
                ->data([
                    'order'         => 105,
                    'activematches' => 'admin/modulemanager/create',
                    'permission'    => ['edit_posts'],
                ])
                ->link->attr([
                    'class' => "c-sidebar-nav-link",
                ]);
            // Submenu: Categories
            /*$modules_menu->add('<i class="c-sidebar-nav-icon fas fa-sitemap"></i> Categories', [
                'route' => 'backend.categories.index',
                'class' => 'c-sidebar-nav-item',
            ])
                ->data([
                    'order'         => 83,
                    'activematches' => 'admin/categories*',
                    'permission'    => ['edit_categories'],
                ])
                ->link->attr([
                    'class' => "c-sidebar-nav-link",
                ]);*/
        })->sortBy('order');

        return $next($request);
    }
}
