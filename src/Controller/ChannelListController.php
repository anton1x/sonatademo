<?php

namespace App\Controller;

use App\Entity\TVPlan;
use App\Service\Smotreshka\SmotreshkaHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChannelListController extends AbstractController
{
    /**
     * @Route("/channel_list/{id}", name="channel_list")
     */
    public function list(TVPlan $plan, SmotreshkaHelper $helper)
    {
        $response = $helper->getFormattedInfo($plan);

        if ($response)
            return $this->json($response);

        throw $this->createNotFoundException();
    }
}
