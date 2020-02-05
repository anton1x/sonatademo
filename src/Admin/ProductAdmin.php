<?php

namespace App\Admin;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Form\Type\PriceType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use App\Application\Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductAdmin extends AbstractAdmin
{
    private $context = 'products';

    public function __construct($code, $class, $baseControllerName, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $classInterfaces = class_implements($this->getClass());
        $formMapper
            ->tab('Основное')
                ->with('Product', ['class' => 'col-md-6'])
                    ->add('title', TextType::class)
                    ->add('category', CategorySelectorType::class, [
                        'class' => Category::class,
                        'required' => true,
                        'by_reference' => false,
                        'context' =>  $this->getConfigurationPool()->getContainer()->get('sonata.classification.manager.context')->find($this->context),
                        'model_manager' => $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getModelManager(),
                        'category' => new Category(),
                        'btn_add' => false,
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


                ->with('Картинка', ['class' => 'col-md-6'])
                    ->add('image', MediaType::class, [
                        'context' => 'default',
                        'provider' => 'sonata.media.provider.image'
                    ])
                ->end()
            ->end()
        ;

    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('category');
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



}