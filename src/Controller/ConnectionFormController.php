<?php

namespace App\Controller;

use App\Entity\ConnectionFormOrder;
use App\Form\ConnectionFormType;
use App\Service\ConnectionFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConnectionFormController extends AbstractController
{
    /**
     * @Route("/connection/form", name="connection_form")
     * @param Request $request
     * @param ConnectionFormHandler $handler
     * @return void
     */
    public function index(Request $request, ConnectionFormHandler $handler)
    {
        $form = $this->createForm(ConnectionFormType::class);
//        $form->handleRequest($request);
        $form->submit(
            array_merge(
                ['email' => null, 'name' => null, 'phone' => null, 'type' => ConnectionFormOrder::TYPE_INDEX, 'note' => null],
                $request->request->all()
            ),
            false);

        if ($form->isSubmitted() && $form->isValid()) {
           $data = $form->getData();
           $handler->handle($data);
           return $this->makeSuccessRequest();
        }

        return $this->makeFailRequest();

    }

    private function makeSuccessRequest()
    {
        return $this->json(['success' => true]);
    }

    private function makeFailRequest()
    {
        return $this->json(['success' => false]);
    }
}
