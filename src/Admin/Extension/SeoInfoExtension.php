<?php


namespace App\Admin\Extension;

use App\Form\Type\SeoInfoType;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;

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