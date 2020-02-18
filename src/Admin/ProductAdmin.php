<?php

namespace App\Admin;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Form\Type\PriceType;
use App\Repository\CategoryRepository;
use App\Repository\ProductsRepository;
use Metadata\ClassMetadata;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use App\Application\Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

abstract class ProductAdmin extends AbstractAdmin
{
    protected $context = 'products';
    protected $categoryRepository;

    public function __construct($code, $class, $baseControllerName, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($code, $class, $baseControllerName);
    }

    protected function getCategoryManager()
    {
        return $this->getConfigurationPool()->getContainer()->get('sonata.classification.manager.category');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $classInterfaces = class_implements($this->getClass());
        $formMapper
            ->tab('Основное')
                ->with('Product', [
                    'class' => 'col-md-6',
                    'label' => 'Основное',
                ])
                    ->add('title', TextType::class)
//                    ->add('category', CategorySelectorType::class, [
//                        'class' => Category::class,
//                        'required' => true,
//                        'by_reference' => false,
//                        'context' =>  $this->getConfigurationPool()->getContainer()->get('sonata.classification.manager.context')->find($this->context),
//                        'model_manager' => $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getModelManager(),
//                        'category' => new Category(),
//                        'btn_add' => false,
//                    ])

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

                    ->add('description', SimpleFormatterType::class, [
                        'format' => 'richhtml',
                        'ckeditor_context' => 'default',
                        'ckeditor_image_format' => 'big',
                        'required' => false,
                    ])
                ->end()

                ->with('Цена', [
                    'class' => 'col-md-6'
                ])
                    ->add('price', PriceType::class, [
                        'label' => false
                    ])
                ->end()
            ->end()
        ;

    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('category', ChoiceFilter::class, [], ChoiceType::class,
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
                    ]);
//        $datagridMapper->add('category', ChoiceFilter::class, [
//            'mapping_type' => \Doctrine\ORM\Mapping\ClassMetadata::MANY_TO_ONE,
//            'field_name' => 'category',
//        ], null, [
//            'class' => Category::class,
//            'group_by' => 'parent.name',
//            'query_builder' => function (CategoryRepository $repository) {
//                //return null;
//                return $repository->getQueryBuilderForFilterCategoryByContext($this->context);
//            }
//        ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('category')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
                'label' => 'Действия',
            ]);
    }


    /**
     * @return array|string
     */
    abstract protected function getRootCategoryCode();


}