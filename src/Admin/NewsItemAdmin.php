<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class NewsItemAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('title', null, [
                'label' => 'Заголовок'
            ])
            ->add('publishedAt', 'datetime', [
                'format' => 'd.m.Y H:i',
                'label' => 'Дата публикации'
            ])

            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
                'label' => 'Действия',
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->tab('Основное')
                ->with('Дата публикации', [
                    'class' => 'col-md-6',
                ])
                    ->add('publishedAt', DateTimePickerType::class,
                        [
                            'label' => 'Дата публикации',
                        ]
                        )
                    ->add('isActive', CheckboxType::class, [
                        'label' => 'Активность',
                        'required' => false,
                    ])
                ->end()
                ->with('Основное')
                    ->add('title')
                    ->add('preview')
                    ->add('body', SimpleFormatterType::class, [
                        'format' => 'richhtml',
                        'ckeditor_context' => 'default',
                        'ckeditor_image_format' => 'big',
                    ])

                ->end()
            ->end()
            ;
    }

}
