<?php

declare(strict_types=1);

namespace App\Admin;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Entity\NewsItem;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelHiddenType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class NewsItemAdmin extends AbstractAdmin
{

    private $context = ['news', 'blog'];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('title', null, [
                'label' => 'Заголовок'
            ])
            ->add('category', ChoiceFilter::class, [], ChoiceType::class,
                [
                    'choices' => $this
                        ->getCategoryManager()
                        ->loadChildrenCategoriesByParentCode($this->getRootCategoryCode(), $this->context),
                    'choice_label' => function (Category $category) {
                        return $category->getName();
                    },
                    'group_by' => function(Category $category, $key, $value) {
                        return $category->getParent()->getName();
                    },
                ])
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->addIdentifier('title', 'text', [
                'label' => 'Заголовок'
            ])
            ->add('isActive', 'boolean', [
                'editable' => true,
                'label' => 'Опубликована'
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
                ->with('Параметры', [
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
                    ->add('category', ChoiceType::class, [
                        'choices' => $this
                            ->getCategoryManager()
                            ->loadChildrenCategoriesByParentCode($this->getRootCategoryCode(), $this->context),
                        'choice_label' => function (Category $category) {
                            return $category->getName();
                        },
                        'group_by' => function(Category $category, $key, $value) {
                            return $category->getParent()->getName();
                        },
                    ])
                ->end()
                ->with('Картинка', ['class' => 'col-md-6'])
                    ->add('image', MediaType::class, [
                        'context' => 'news',
                        'provider' => 'sonata.media.provider.image'
                    ])
                ->end()
                ->with('Основное')
                    ->add('title')
                    ->add('preview', SimpleFormatterType::class, [
                        'label' => 'Превью',
                        'format' => 'richhtml',
                        'ckeditor_context' => 'default',
                        'ckeditor_image_format' => 'big',
                    ])
                    ->add('body', SimpleFormatterType::class, [
                        'label' => 'Полный текст',
                        'format' => 'richhtml',
                        'ckeditor_context' => 'default',
                        'ckeditor_image_format' => 'big',
                    ])

                ->end()

            ->end()
            ;
    }

    /**
     * @param NewsItem $object
     */
    public function prePersist($object)
    {
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $object->setAuthor($currentUser);
    }


    protected function getCategoryManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('sonata.classification.manager.category');
    }

    /**
     * @return array
     * @todo Возможно стоит вынести данный блок в параметры и инжектить в сервис
     */
    protected function getRootCategoryCode()
    {
        return [
            'news',
            'blog',
        ];
    }

    protected function configureBatchActions($actions)
    {
        if (
            $this->hasRoute('edit') && $this->hasAccess('edit')
        ) {
            $actions['publish'] = [
                'ask_confirmation' => true
            ];
        }

        return $actions;
    }

}
