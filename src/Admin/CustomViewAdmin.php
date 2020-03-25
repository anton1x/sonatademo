<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

final class CustomViewAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'custom_view';
    protected $baseRouteName = 'custom_view';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}