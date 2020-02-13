<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\AddressObject;
use App\Entity\ConnectionType;
use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class ConnectionTypeAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('name')
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
            ->add('name')
            ->add('addresses', EntityType::class, [
                'by_reference' => false,
                'class' => AddressObject::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('devices', EntityType::class, [
                'class' => Device::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function ($item) {
                    /**
                     * @var Device $item
                     */
                    return sprintf('(%s) %s' , $item->getCategory()->getName(), $item->getTitle());
                },
                'query_builder' => function (DeviceRepository $repository) {
                    return $repository
                        ->createQueryBuilderFindByCategoryCode()
                        ->setParameter('code', ConnectionType::DEVICES_INTERNET_CATEGORY)
                        ;
                },

            ])
        ;

    }


    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('name')
            ;
    }
}
