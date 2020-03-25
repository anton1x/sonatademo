<?php

namespace App\Controller;

use App\Entity\NewsItem;
use App\News\NewsContext;
use App\Pagination\PaginatedItemsList;
use App\ViewOptions\HeaderOptions;
use JMS\Serializer\SerializerInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    private $title;
    private $context;



    public function __construct($itemType, $title)
    {
        $this->context = new NewsContext($itemType);
        $this->title = $title;
    }


    public function list(SerializerInterface $serializer, Request $request, HeaderOptions $viewHeaderOptions): Response
    {
        $repo = $this->getDoctrine()->getRepository(NewsItem::class);

        $page = $request->get('page', 1);
        $itemsPerPage = $this->getParameter($this->context->getType() . '.list.items_per_page');

        //$paginated = $repo->getPaginatedNewsList($page, 1);
        $paginated = new PaginatedItemsList(
            $repo->createNewsListQuery($this->context->getType()),
            $page,
            $itemsPerPage
        );

        if($request->get('_json', false) != false) {
            $resultSerialized = $serializer->serialize(
                $paginated,
                'json'
            );
            return new Response($resultSerialized, Response::HTTP_OK, [
                'Content-Type' => 'application/json'
            ]);
        }

        $viewHeaderOptions
            ->setOption('banner', '/files/headers/news.jpg')
            ->setFullInner()
            ->setOption('body_class', 'desktop_gray')
        ;

        return $this->render('news/index.html.twig', [
            'paginated' => $paginated,
            'item_type' => $this->context->getType(),
            'title' => $this->getTitle(),
            'routes' => $this->context->getRouteCollection(),
        ]);
    }


    public function view(NewsItem $newsItem, HeaderOptions $viewHeaderOptions): Response
    {
        $viewHeaderOptions
            ->setFullInner()
        ;

        return $this->render('news/view.html.twig', [
            'item' => $newsItem,
            'item_type' => $this->context->getType(),
            'title' => $this->getTitle(),
            'routes' => $this->context->getRouteCollection(),
        ]);
    }

    private function getTitle()
    {
        return $this->title;
    }


}
