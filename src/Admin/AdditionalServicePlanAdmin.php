<?php


namespace App\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdditionalServicePlanAdmin extends ProductAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

    }

    protected function getRootCategoryCode()
    {
        return 'additional';
    }


}