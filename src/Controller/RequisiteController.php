<?php

namespace App\Controller;

use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequisiteController extends AbstractController
{
    /**
     * @Route("/about/requisites", name="requisites")
     * @param HeaderOptions $headerOptions
     * @return Response
     */
    public function index(HeaderOptions $headerOptions)
    {

        $headerOptions
            ->setFullInner()
            ->setOption('banner','/files/headers/reqs.jpg')
            ;

        return $this->render('requisite/index.html.twig', [

        ]);
    }
}
