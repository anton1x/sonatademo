<?php


namespace App\Admin;

use App\Entity\AdditionalServicePlan;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdditionalServicePlanAdmin extends ProductAdmin
{
    protected $context = 'additional';

    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

    }

    protected function getRootCategoryCode()
    {
        return AdditionalServicePlan::ROOT_CATEGORIES;
    }


}