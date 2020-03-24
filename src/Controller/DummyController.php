<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DummyController extends AbstractController
{
    /**
     * @Route("/dummy", name="dummy", defaults={"a" = 1})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $a = json_decode($request->get('data'), true);
        $a = json_encode($a);
        return new Response($a);
    }
}
