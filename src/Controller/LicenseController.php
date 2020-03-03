<?php

namespace App\Controller;

use App\Entity\License;
use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LicenseController extends AbstractController
{
    /**
     * @Route("/about/license", name="license")
     * @param HeaderOptions $headerOptions
     * @return Response
     */
    public function index(HeaderOptions $headerOptions)
    {
        $headerOptions->setFullInner()
            ->setOption('banner', '/files/headers/certs.jpg')
            ;

        $list = $this->getDoctrine()->getRepository(License::class)->findAll();

        return $this->render('license/index.html.twig', [
            'list' => $list,
        ]);
    }
}
