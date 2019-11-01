<?php


namespace App\Application\Sonata\MediaBundle\Controller;


use http\Exception\InvalidArgumentException;
use Sonata\MediaBundle\Controller\MediaAdminController as BaseMediaAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MediaAdminController extends BaseMediaAdminController
{
    protected function preDelete(Request $request, $object)
    {
        dump($object);
        throw new InvalidArgumentException();
    }


}