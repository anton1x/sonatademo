<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Sliders\SlideAbstract;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;

final class SlideGalleryAdmin extends AbstractAdmin
{



    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('code')
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
            ->with('Слайды', [
                'class' => 'col-md-8',
            ])
            ->add('slides', CollectionType::class, [
                'label' => false,
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'multiple' => true,
                'class' => 'col-md-6',
                'sortable' => 'position',
            ])
            ->end()
            ->with('Параметры', [
                'class' => 'col-md-4'
            ])
                ->add('code', null, [
                    'required' => true,
                    'label' => 'Внутренний код',
                ])
            ->end()
            ;
    }

}
