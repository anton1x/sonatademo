<?php


namespace App\Admin;

use App\Entity\Device;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DeviceAdmin extends ProductAdmin
{
    protected $context = 'devices';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper
            ->tab('Основное')
                ->with('Картинка', ['class' => 'col-md-6'])
                    ->add('image', MediaType::class, [
                        'context' => 'devices',
                        'provider' => 'sonata.media.provider.image'
                    ])
                ->end()
            ->end()
        ;
    }


    protected function getRootCategoryCode()
    {
        return Device::ROOT_CATEGORIES;
//        return [
//            'devices_internet',
//            'devices_tv',
//            'devices_additional_phone',
//            'devices_additional_vision'
//        ];
    }
}