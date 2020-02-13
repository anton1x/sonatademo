<?php


namespace App\Controller;

use App\Entity\AddressObject;
use App\Entity\BaseProduct;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    
    /**
     * @Route(name="products_index", path="/")
     */
    public function indexAction(SerializerInterface $serializer)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(AddressObject::class);

        $items = $repo->findAll();
        $result = $serializer->serialize($items, 'json', SerializationContext::create()->setGroups(['calculator']));

        return new Response($result, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);

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

        $resultSerialized = $serializer->serialize($result, 'json');

        return new Response($resultSerialized, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

}