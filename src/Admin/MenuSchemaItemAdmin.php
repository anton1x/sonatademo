<?php

declare(strict_types=1);

namespace App\Admin;

use Doctrine\DBAL\Types\ArrayType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class MenuSchemaItemAdmin extends AbstractAdmin
{

    private function getRoutesList()
    {
        $routes = $this->getConfigurationPool()->getContainer()->get('router')->getRouteCollection()->all();
        $filtered = array_keys(array_filter($routes, function ($item) {
            return $item->getOption('_menu_managed');
        }));

        return array_combine($filtered, $filtered);

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('label')
            ->add('attributes')
            ->add('route')
            ->add('extras')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('label')
            ->add('route')
            ->add('extras')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('label')
            ->add('class', TextType::class , [
                'empty_data' => '',
                'property_path' => 'attributes[class]',
            ])
            ->add('route')
            ->add('attached_routes', ChoiceType::class, [
                'choices' => $this->getRoutesList(),
                'multiple' => true,
                'property_path' => 'extras[attached_routes]',
                'translation_domain' => 'routes',
            ])
            ->add('extras')
            ;
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
}
