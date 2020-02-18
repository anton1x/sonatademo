<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\ConnectionType;
use App\Entity\PricingType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class AddressObjectAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('pricingType')
            ->add('connectionType')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->addIdentifier('title')
            ->add('address', null, [
                'label' => 'Адрес',
            ])
            ->add('pricingType.name', null, [
                'sortable' => true,
            ])
            ->add('connectionType.name', null, [
                'sortable' => true,
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('title')
            ->add('address')
            ->add('connectionType', EntityType::class, [
                'class' => ConnectionType::class,
                'label' => 'Тип подключения',
                //'by_reference' => false,
            ])
            ->add('pricingType', EntityType::class, [
                'class' => PricingType::class,
                'label' => 'Ценовая категория',
            ])
            ;
    }

}
