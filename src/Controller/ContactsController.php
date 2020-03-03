<?php

namespace App\Controller;

use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends AbstractController
{
    /**
     * @Route("/about/contacts", name="contacts")
     */
    public function index(HeaderOptions $headerOptions)
    {
        $headerOptions
            ->setFullInner()
            ->setOption('banner', '/files/headers/contacts.jpg')
            ;
        return $this->render('contacts/index.html.twig', [
        ]);
    }
}
