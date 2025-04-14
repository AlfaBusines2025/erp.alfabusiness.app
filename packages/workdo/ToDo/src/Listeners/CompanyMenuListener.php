<?php

namespace Workdo\ToDo\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'ToDo';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Productivity',
            'title' => __('To Do'),
            'icon' => 'ti ti-checkbox',
            'name' => 'todo',
            'parent' => null,
            'order' => 1440,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'to-do.index',
            'module' => $module,
            'permission' => 'todo manage'
        ]);
    }
}
