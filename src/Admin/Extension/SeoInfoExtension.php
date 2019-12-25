<?php


namespace App\Admin\Extension;

use App\Admin\SeoAdminType;
use App\Form\SeoInfoType;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SeoInfoExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('SEO')
                ->with('SEO')
                    ->add('seoInfo', SeoInfoType::class, [
                        'required' => false,
                        'label' => 'SEO-параметры'
                    ])
                ->end()
            ->end()
        ;
    }
}