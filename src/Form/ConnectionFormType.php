<?php

namespace App\Form;

use App\Entity\ConnectionFormOrder;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('phone', TextType::class, [
                'required' => true
            ])
            ->add('email', TextType::class, [
                'required' => false,
            ])
            ->add('type', HiddenType::class, [
                'data' => $options['type'],
            ])
            ->add('note', TextareaType::class)
            ->add('recaptcha', EWZRecaptchaType::class, [
                'attr' => array(
                    'options' => array(
                        'theme' => 'light',
                        'type'  => 'image',
                        'size'  => 'small',
                        'async' => true,
                    )
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'type' => ConnectionFormOrder::TYPE_INDEX,
            'data_class' => ConnectionFormOrder::class,
            'method' => 'post',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'connection_order',
            'allow_extra_fields' => true
        ]);
    }
}
