<?php

namespace App\Form\Type;

use App\Entity\ValueObject\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('connection_price', IntegerType::class, [
                'label' => 'Цена подключения'
            ])

            ->add('monthly_price', IntegerType::class, [
                'label' => 'Цена ежемесячная'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Price::class,
        ]);
    }
}
