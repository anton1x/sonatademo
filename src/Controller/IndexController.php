<?php

namespace App\Controller;

use App\Form\ConnectionFormType;
use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", options={"_menu_managed" = true})
     * @param HeaderOptions $viewHeaderOptions
     * @return Response
     */
    public function index(HeaderOptions $viewHeaderOptions)
    {
        $viewHeaderOptions->setSlidered();

        return $this->render('index/index.html.twig', [
            'form' => $this->createForm(ConnectionFormType::class)->createView(),
        ]);
    }
}
