<?php

namespace App\Admin\Controller;

use Sonata\AdminBundle\Controller\CRUDController;

class CustomViewCRUDController extends CRUDController
{
    public function listAction()
    {
        return $this->renderWithExtraParams('admin/custom_view.html.twig');
    }
}