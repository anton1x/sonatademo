<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Sliders\HTMLSlide;
use App\Entity\Sliders\Slide;
use App\Entity\Sliders\SlideGallery;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SlideAdmin extends AbstractAdmin
{


    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
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
        $formMapper->with('Слайд', [
            'class' => 'col-md-6',
        ]);


        $formMapper->add('type', ChoiceFieldMaskType::class, [
           'choices' => Slide::getAvailableTypes(),
            'map' => [
                Slide::SLIDE_TYPE_HTML => ['styles', 'body'],
                Slide::SLIDE_TYPE_IMAGE => ['image', 'link'],
            ],
            'label' => 'Тип',
        ]);

        $formMapper->add('body', TextareaType::class, [
            'label' => 'HTML-код',
            'required' => false,
            'attr' => [
                'rows' => 5
            ],
        ]);
        $formMapper->add('styles', TextareaType::class, [
            'label' => 'CSS-стили',
            'required' => false,
            'attr' => [
                'rows' => 5
            ],
        ]);


        $formMapper->add('image', MediaType::class, [
            'context' => 'slider',
            'provider' => 'sonata.media.provider.image',
        ]);
        $formMapper->add('link', TextType::class, [
            'label' => 'Ссылка',
            'required' => false,
        ]);

        $formMapper->add('position', HiddenType::class);

        $formMapper->end();


    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('title')
        ;
    }



}
