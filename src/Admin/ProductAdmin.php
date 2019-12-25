<?php

namespace App\Admin;

use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Entity\Address;
use App\Entity\City;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminExtensionInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\ClassificationBundle\Form\ChoiceList\CategoryChoiceLoader;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class ProductAdmin extends AbstractAdmin
{
    private $context = 'products';
    private $dispatcher;

    public function __construct($code, $class, $baseControllerName, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->dispatcher = $dispatcher;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        dump($this->getSubject());
        $formMapper
            ->tab('Основное')
            ->with('Product', ['class' => 'col-md-6'])
            ->add('title', TextType::class)
            ->add('category', \App\Application\Sonata\ClassificationBundle\Form\Type\CategorySelectorType::class, [
                'context' =>  $this->getConfigurationPool()->getContainer()->get('sonata.classification.manager.context')->find($this->context),
                'model_manager' => $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getModelManager(),
                'class' => $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getClass(),
                'category' => $this->getSubject()->getCategory() ? $this->getSubject()->getCategory() : new Category(),
                'required' => false,
                'by_reference' => false,
                'placeholder' => $this->getSubject()->getCategory(),
                'btn_add' => false
            ])

            ->add('content', SimpleFormatterType::class, [
                'format' => 'richhtml',
                'ckeditor_context' => 'default',
                'ckeditor_image_format' => 'big',
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
//        $formMapper->add('title', TextType::class);
//        $formMapper->add('category', \App\Application\Sonata\ClassificationBundle\Form\Type\CategorySelectorType::class, [
//            'context' =>  $this->getConfigurationPool()->getContainer()->get('sonata.classification.manager.context')->find($this->context),
//            'model_manager' => $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getModelManager(),
//            'class' => $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getClass(),
//            'category' => $this->getSubject()->getCategory() ? $this->getSubject()->getCategory() : new Category(),
//            'required' => false,
//            'by_reference' => false,
//            'placeholder' => $this->getSubject()->getCategory(),
//            'btn_add' => false
//        ]);
//        $formMapper->add('description', TextareaType::class);
//        $formMapper->with('Картинка', ['class' => 'col-md-6'])
//            ->add('image', MediaType::class, [
//                'context' => 'default',
//                'provider' => 'sonata.media.provider.image'
//            ])
//            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('category');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->addIdentifier('category');
    }



}