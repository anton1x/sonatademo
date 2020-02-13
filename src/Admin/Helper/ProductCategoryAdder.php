<?php

namespace App\Admin\Helper;

use App\Admin\ProductAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class ProductCategoryAdder
{
    private $formMapper;
    private $admin;

    public function __construct(FormMapper $formMapper, ProductAdmin $admin)
    {
        $this->formMapper = $formMapper;
        $this->admin = $admin;
    }

    public function addChildrenChoice($code)
    {

    }


}