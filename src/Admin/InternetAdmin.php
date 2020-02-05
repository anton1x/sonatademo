<?php


namespace App\Admin;


use App\Entity\AddressObject;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InternetAdmin extends ProductAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->tab('Основное')
                ->with('Интернет', ['class' => 'col-md-6'])
                    ->add('speed', IntegerType::class, [
                        'label' => 'Скорость'
                    ])
                ->end()
                ->with('Адреса', ['class' => 'col-md-6'])
                    ->add('assigned_address_objects', EntityType::class, [
                        'class' => AddressObject::class,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => false,
                    ])
                ->end()
            ->end();
    }


}