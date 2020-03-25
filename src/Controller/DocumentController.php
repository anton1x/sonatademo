<?php

namespace App\Controller;

use App\Entity\DocumentGroup;
use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    /**
     * @Route("/about/documents", name="documents", options={"_menu_managed" = true})
     */
    public function index(HeaderOptions $viewHeaderOptions)
    {
        $viewHeaderOptions->setFullInner()
            ->setOption('banner', '/files/headers/docs.jpg')
        ;
        $groups = $this->getDoctrine()->getRepository(DocumentGroup::class)->findAll();


        return $this->render('document/index.html.twig', [
            'groups' => $groups,
        ]);
    }
}
