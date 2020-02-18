<?php


namespace App\Admin;

use App\Entity\AddressObject;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TVPlanAdmin extends ProductAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->tab('Основное')
                ->with('Настройки ТВ', ['class' => 'col-md-6'])
                    ->add('channelCount', IntegerType::class, [
                        'label' => 'Количество каналов'
                    ])
                    ->add('includeTheatres', CheckboxType::class, [
                        'label' => 'Включает online-кинотеатры',
                        'required' => false,
                    ])
                ->end()
                ->with('Адреса', ['class' => 'col-md-6'])
                    ->add('assigned_address_objects', EntityType::class, [
                        'class' => AddressObject::class,
                        'multiple' => true,
                        'expanded' => true,
                        'label' => false,
                        'show_select_all' => true,
                        'show_unselect_all' => true,
                    ])
                ->end()
            ->end();
    }

    protected function getRootCategoryCode()
    {
        return 'tv';
    }


}