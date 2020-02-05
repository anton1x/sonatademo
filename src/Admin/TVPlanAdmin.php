<?php


namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
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
                ->end()
            ->end();
    }


}