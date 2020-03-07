<?php


namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{

    private $factory;

    private $schema;

//    private $schema = [
//        ['label' => 'Главная',
//            'attributes' => [
//                'class' => 'menu_title',
//            ],
//          'route' => 'index',
//          'children' => false,
//            'show_in_menus' => ['top_menu'],
//        ],
//        ['label' => 'Новости и акции',
//            'attributes' => [
//                'class' => 'menu_title',
//            ],
//            'route' => 'news_list',
//            'extras' => [
//                    'attached_routes' => ['news_view'],
//                ],
//            'children' => false,
//        ],
//        [   'label' => 'О нас',
//            'attributes' => [
//                'class' => 'menu_title',
//            ],
//            'route' => 'contacts',
//            'children' => [
//                 [
//                     'label' => 'Документы',
//                    'route' => 'documents',
//                    'children' => false,
//                ],
//                [
//                  'label' => 'Реквизиты',
//                  'route' => 'requisites',
//                  'children' => false,
//                ],
//                [
//                    'label' => 'Лицензии',
//                    'route' => 'license',
//                    'children' => false,
//                ],
//                [
//                    'label' => 'Застройщикам и УК',
//                    'route' => 'about_developers',
//                    'children' => false,
//                ],
//                [
//                    'label' => 'Контакты',
//                    'route' => 'contacts',
//                    'children' => false,
//                ]
//            ],
//        ],
////        ['label' => 'Блог',
////            'attributes' => [
////                'class' => 'menu_title',
////            ],
////            'route' => 'blog_list',
////            'extras' => [
////                'attached_routes' => ['blog_view'],
////            ],
////            'children' => false,
////        ],
//        [
//            'label' => '',
//            'attributes' => [
//                'class' => 'icons',
//            ],
//            'linkAttributes' => [
//                'class' => 'vk'
//            ],
//            'show_in_menus' => ['bottom_menu'],
//            'uri' => 'https://vk.com',
//            'children' => false,
//        ]
//    ];
    /**
     * @var MenuSchemaProviderInterface
     */
    private $menuProvider;

    public function __construct(
        FactoryInterface $factory,
        MenuSchemaProviderInterface $menuProvider
    )
    {
        $this->factory = $factory;
        $this->schema = $menuProvider->getSchema();
    }

    public function createMainMenu($menuName = false): ItemInterface
    {
        $root = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'menu')
        ;

        $this->traverseSchema($this->schema, $root, $menuName);

        return $root;
    }

    public function createMobileTopMenu($menuName = false): ItemInterface
    {
        $root = $this->factory->createItem('root')
            ->setChildrenAttribute('id', 'menu_top_list')
        ;

        $this->traverseSchema($this->schema, $root, $menuName);
        return $root;
    }

    public function createTopMenu($menuName = false): ItemInterface
    {
        $root = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'menu')
        ;
        $this->traverseSchema($this->schema, $root, $menuName);
        return $root;
    }

    public function createSubMenu($menuName = false): ItemInterface
    {
        $root = $this->factory->createItem('root')
            ->setChildrenAttribute('id', 'header_submenu_wrapper')
        ;
        $this->traverseSchema($this->schema, $root, $menuName);
        return $root;
    }

    private function traverseSchema(array $schema, ItemInterface $root, $menuName = false)
    {
        $schema = $this->filterSchema($schema, $menuName);

        foreach ($schema as $params) {
            $menuItem = $this->createMenuItem($params);
            $root->addChild($menuItem);
            if (is_array($params['children'])) {
                $this->traverseSchema($params['children'], $menuItem, $menuName);
            }
        }
    }

    public function createMenuItem($params): ItemInterface
    {
        return $this->factory->createItem($params['label'], $params);
    }

    /**
     * @param array $schema
     * @param $menuName
     * @return array
     */
    private function filterSchema(array $schema, $menuName): array
    {
        $schema = array_filter($schema, function ($item) use ($menuName) {
            if (!isset($item['show_in_menus']) || !$menuName) {
                return true;
            }
            $result = in_array($menuName, $item['show_in_menus']);
            return $result;
        });
        return $schema;
    }
}