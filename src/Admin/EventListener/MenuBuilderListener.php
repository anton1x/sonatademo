<?php


namespace App\Admin\EventListener;


use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $child = $menu->addChild('reports', [
            'label' => 'Настройки',
            'route' => 'settings_create',
        ])->setExtras([
            'icon' => '<i class="fa fa-gears"></i>',
        ]);
    }
}