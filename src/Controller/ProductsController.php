<?php


namespace App\Controller;

use App\Entity\AddressObject;
use App\Entity\BaseProduct;
use App\Entity\NewsItem;
use App\Pagination\PaginatedItemsList;
use App\ViewOptions\HeaderOptions;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    
    /**
     * @Route(name="test2", path="/test2")
     * @Route(name="docs", path="/docs")
     */
    public function indexAction(HeaderOptions $viewHeaderOptions)
    {
        $viewHeaderOptions
            ->setSlidered()
            //->setFullInner()
            //->setOption('banner', '/files/headers/docs.jpg')
            ->setOption('body_class', 'geralt')
        ;
        return $this->render('layout.html.twig', []);

    }



    /**
     * @Route(name="test", path="/test")
     */
    public function testAction(SerializerInterface $serializer)
    {
        $repoProduct = $this->getDoctrine()->getRepository(BaseProduct::class);
        $repoAddress = $this->getDoctrine()->getRepository(AddressObject::class);
        $result['addresses'] = $repoAddress->findAll();
        $result['products'] = $repoProduct->getAllProductsGroupedByCategory();

        $resultSerialized = $serializer->serialize($result, 'json', SerializationContext::create()->setSerializeNull(true));

        return new Response($resultSerialized, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

}