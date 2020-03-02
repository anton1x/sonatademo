<?php

namespace App\Controller;

use App\Entity\ConnectionFormOrder;
use App\Entity\Developer;
use App\Form\ConnectionFormType;
use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevelopersController extends AbstractController
{
    /**
     * @Route("/about/developers", name="about_developers")
     * @param HeaderOptions $viewHeaderOptions
     * @return Response
     */
    public function index(HeaderOptions $viewHeaderOptions)
    {
        $viewHeaderOptions
            ->setFullInner()
            ->setOption('banner', '/files/headers/developers.jpg')
            ;

        $list = $this->getDoctrine()->getRepository(Developer::class)->findAllSorted();
        $form = $this->createForm(ConnectionFormType::class, null, ['type' => ConnectionFormOrder::TYPE_DEVELOPERS])->createView();
        return $this->render('developers/index.html.twig', [
            'list' => $list,
            'form' => $form,
        ]);
    }
}
