<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\MenuSchemaItem;
use App\Repository\MenuSchemaItemRepository;
use Doctrine\DBAL\Types\ArrayType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class MenuSchemaItemAdmin extends AbstractAdmin
{

    private $routeCollection;

    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

    }

    private function getRoutesList()
    {
        $routeCollection = $this->getConfigurationPool()->getContainer()->get('router')->getRouteCollection();
        $routes = $routeCollection->all();
        $filtered = array_keys(array_filter($routes, function ($item) {
            return $item->getOption('_menu_managed');
        }));


        return array_combine($filtered, $filtered);

    }


    protected function configureListFields(ListMapper $listMapper): void
    {

        $listMapper
            ->add('leveledLabel', null, [
                'label' => 'Название',
                'sortable' => false,
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'up' => array(
                        'template' => 'admin/tree_sort/list__action_up.html.twig'
                    ),
                    'down' => array(
                        'template' => 'admin/tree_sort/list__action_down.html.twig'
                    )
                ],
            ]);

    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $routesList = $this->getRoutesList();

        $formMapper
            ->with('Основное', [ 'class' => 'col-md-6' ])
                ->add('label', null, [
                    'label' => 'Название',
                ])
                ->add('parent', EntityType::class, [
                    'class' => $this->getClass(),
                    'label' => 'Родительский элемент',
                    'required' => true,
                    'query_builder' => function ($repo) {
                        /**
                         * @var MenuSchemaItemRepository $repo
                         */
                        $qb =  $repo->createQueryBuilder('msi')
                            ->andWhere('msi.level < 2')
                        ;

                        if (null !== $this->getSubject()->getId()) {
                            $qb
                                ->andWhere('msi.id <> :this')
                                //->andWhere('msi.id <> 1')
                                ->setParameter('this', $this->getSubject()->getId())
                            ;
                        }

                        return $qb;
                    }
                ])
                ->add('route', ChoiceType::class, [
                    'label' => 'Маршрут',
                    'choices' => $routesList,
                    'translation_domain' => 'routes',
                    'required' => false,
                ])
                ->add('uri', null, [
                    'label' => 'Ссылка',
                    'required' => false,
                    'empty_data' => '',
                    'help' => 'При указании маршрута ссылка будет проигнорирована',
                ])
            ->end()

            ->with('Визуальное отображение', [ 'class' => 'col-md-6' ])
                ->add('class', TextType::class , [
                    'label' => 'CSS-класс',
                    'empty_data' => '',
                    'property_path' => 'attributes[class]',
                    'required' => false,
                ])
                ->add('link_class', TextType::class , [
                    'label' => 'CSS-класс для ссылки',
                    'empty_data' => '',
                    'property_path' => 'linkAttributes[class]',
                    'help' => 'используется для иконок',
                    'required' => false,
                ])
                ->add('showInMenus', ChoiceType::class, [
                    'choices' => [
                        'Верхнее меню' => 'top_menu',
                        'Нижнее меню' => 'bottom_menu'
                    ],
                    'multiple' => true,
                    'expanded' => true,
                    'empty_data' => [],
                ])
            ->end()
            ->with('Дополнительно', [ 'class' => 'col-md-6' ])
                ->add('attached_routes', ChoiceType::class, [
                    'label' => 'Связанные маршруты',
                    'choices' => $routesList,
                    'required' => false,
                    //'help' => 'используются для сложных разделов для корректного определения выбранного пункта меню',
                    'multiple' => true,
                    'property_path' => 'extras[attached_routes]',
                    'translation_domain' => 'routes',
                ])
            ->end()
            ;

    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('up', $this->getRouterIdParameter().'/up');
        $collection->add('down', $this->getRouterIdParameter().'/down');
    }



    public function createQuery($context = 'list')
    {
        $proxyQuery = parent::createQuery('list');
        $alias = $proxyQuery->getRootAliases()[0];
        // Default Alias is "o"
        // You can use `id` to hide root element
        $proxyQuery->where("{$alias} != 1");
        $proxyQuery->addOrderBy("{$alias}.root", 'ASC');
        $proxyQuery->addOrderBy("{$alias}.left", 'ASC');

        return $proxyQuery;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('label')
            ->add('attributes')
            ->add('route')
            ->add('extras')
            ;
    }

    public function preRemove($object)
    {
        if ($object->getChildren()->count() > 0) {
            foreach ($object->getChildren() as $childToRemove)
                $this->getModelManager()->delete($childToRemove);
        }
    }
}
