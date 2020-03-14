<?php


namespace App\Controller;

use App\Entity\AddressObject;
use App\Entity\BaseProduct;
use App\Entity\MenuSchemaItem;
use App\Entity\NewsItem;
use App\Pagination\PaginatedItemsList;
use App\Service\Products\Calculator;
use App\ViewOptions\HeaderOptions;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{

    /**
     * @Route(name="calculator_index", path="/connect")
     * @param Calculator $calculator
     * @param HeaderOptions $viewHeaderOptions
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function indexAction(Calculator $calculator, HeaderOptions $viewHeaderOptions, SerializerInterface $serializer)
    {

        $resultSerialized = $serializer->serialize($calculator->getCalculatorData(), 'json', SerializationContext::create()->setSerializeNull(true));

        $viewHeaderOptions
            ->setShortInner()
        ;

        return $this->render('products/index.html.twig', [
            'jsonList' => $resultSerialized,
        ]);

    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Calculator $calculator
     * @Route(name="calculator_send", path="/connect/send")
     * @return Response
     */
    public function sendAction(Request $request, SerializerInterface $serializer, Calculator $calculator)
    {

        $data = $serializer->deserialize($request->getContent(), 'array', 'json');

        dump($calculator->parseCalculatorAnswer($data));

        $this->createAccessDeniedException();
    }

    /**
     * @Route(name="test3", path="/test3")
     */
    public function test3(ArrayTransformerInterface $serializer)
    {
        $rep = $this->getDoctrine()->getRepository(MenuSchemaItem::class);
        $list = $rep->findBy(['level' => 1], ['left' => 'asc']);


        $result = $serializer->toArray($list);


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