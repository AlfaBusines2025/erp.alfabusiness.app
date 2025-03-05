<?php

namespace Workdo\DairyCattleManagement\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{

    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'DairyCattleManagement';
        $menu = $event->menu;
        $menu->add([
            'category' => 'Operations',
            'title' => __('Animals'),
            'icon' => 'horse',
            'name' => 'dairycattle',
            'parent' => null,
            'order' => 704,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'dairycattle manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Animal'),
            'icon' => 'home',
            'name' => 'animal',
            'parent' => 'dairycattle',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'animal.index',
            'module' => $module,
            'permission' => 'animal manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Health'),
            'icon' => 'home',
            'name' => 'health',
            'parent' => 'dairycattle',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'health.index',
            'module' => $module,
            'permission' => 'health manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Breeding'),
            'icon' => 'home',
            'name' => 'breeding',
            'parent' => 'dairycattle',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'breeding.index',
            'module' => $module,
            'permission' => 'breeding manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Weight'),
            'icon' => 'home',
            'name' => 'weight',
            'parent' => 'dairycattle',
            'order' => 25,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'weight.index',
            'module' => $module,
            'permission' => 'weight manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Daily Milk Sheet'),
            'icon' => 'home',
            'name' => 'dailymilksheet',
            'parent' => 'dairycattle',
            'order' => 30,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dailymilksheet.index',
            'module' => $module,
            'permission' => 'dailymilksheet manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Milk Inventory'),
            'icon' => 'home',
            'name' => 'milkinventory',
            'parent' => 'dairycattle',
            'order' => 35,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'milkinventory.index',
            'module' => $module,
            'permission' => 'milkinventory manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Commulative Milk Sheet'),
            'icon' => 'home',
            'name' => 'commulativemilksheet',
            'parent' => 'dairycattle',
            'order' => 40,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'commulativemilksheet.index',
            'module' => $module,
            'permission' => 'commulativemilksheet manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Milk Product'),
            'icon' => 'home',
            'name' => 'milkproduct',
            'parent' => 'dairycattle',
            'order' => 45,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'milkproduct.index',
            'module' => $module,
            'permission' => 'milkproduct manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Feed Management'),
            'icon' => '',
            'name' => 'feed-management',
            'parent' => 'dairycattle',
            'order' => 50,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => $module,
            'permission' => 'feeds manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Feed Types'),
            'icon' => '',
            'name' => 'feed-types',
            'parent' => 'feed-management',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'feeds_type.index',
            'module' => $module,
            'permission' => 'feed type manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Feed Schedule'),
            'icon' => '',
            'name' => 'feed-schedule',
            'parent' => 'feed-management',
            'order' => 15,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'feeds_schedule.index',
            'module' => $module,
            'permission' => 'feed schedule manage'
        ]);
        $menu->add([
            'category' => 'Operations',
            'title' => __('Feed Consumption'),
            'icon' => '',
            'name' => 'feed-consumption',
            'parent' => 'feed-management',
            'order' => 20,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'feeds_consumption.index',
            'module' => $module,
            'permission' => 'feed consumption manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Vaccination'),
            'icon' => '',
            'name' => 'vaccination',
            'parent' => 'dairycattle',
            'order' => 55,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'vaccinations.index',
            'module' => $module,
            'permission' => 'vaccination manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Sales & Distribution'),
            'icon' => '',
            'name' => 'sales-distribution',
            'parent' => 'dairycattle',
            'order' => 60,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'sales_distribution.index',
            'module' => $module,
            'permission' => 'sales-distribution manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Expense Tracking'),
            'icon' => '',
            'name' => 'expense-tracking',
            'parent' => 'dairycattle',
            'order' => 65,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'expense_tracking.index',
            'module' => $module,
            'permission' => 'expense tracking manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Calving & Birth Records'),
            'icon' => '',
            'name' => 'birth-records',
            'parent' => 'dairycattle',
            'order' => 70,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'birth_records.index',
            'module' => $module,
            'permission' => 'birth record manage'
        ]);

        $menu->add([
            'category' => 'Operations',
            'title' => __('Equipment Management'),
            'icon' => '',
            'name' => 'equipment-management',
            'parent' => 'dairycattle',
            'order' => 75,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'dairy-equipments.index',
            'module' => $module,
            'permission' => 'dairy-equipment manage'
        ]);

    }
}
