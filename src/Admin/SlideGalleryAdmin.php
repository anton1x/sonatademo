<?php

declare(strict_types=1);

namespace App\Admin;

use App\Application\Sonata\MediaBundle\Entity\Media;
use App\Entity\Sliders\SlideAbstract;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SlideGalleryAdmin extends AbstractAdmin
{



    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('title')
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
                ->add('title', null, [
                    'required' => true,
                ])
                ->add('code', null, [
                    'required' => true,
                    'label' => 'Внутренний код',
                ])
            ->end()
            ;
    }


    private function getAdditionalImagesChoices()
    {
        $result = [];

        foreach ($this->getSubject()->getAdditionalImages() as $image) {
            $result [ $image->getName() ] = $image;
        }

        return $result;
    }

}
