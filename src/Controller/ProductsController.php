<?php


namespace App\Controller;

use App\Entity\AddressObject;
use App\Entity\BaseProduct;
use App\Entity\MenuSchemaItem;
use App\Entity\NewsItem;
use App\Pagination\PaginatedItemsList;
use App\Service\Products\Calculator;
use App\Validator\RecaptchaValidatorCustom;
use App\ViewOptions\HeaderOptions;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
            ->setShortInner();

        return $this->render('products/index.html.twig', [
            'jsonList' => $resultSerialized,
        ]);

    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Calculator $calculator
     * @param ValidatorInterface $validator
     * @return Response
     * @Route(name="calculator_send", path="/connect/send")
     */
    public function sendAction(Request $request, SerializerInterface $serializer, Calculator $calculator, ValidatorInterface $validator)
    {
        $isValid = $validator->validate(null, new IsTrue())->count() == 0;

        if (!$isValid) {
            throw new ValidatorException('wrong recaptcha');
        }


        $data = $serializer->deserialize($request->getContent(), 'array', 'json');

        $answer = $calculator->parseCalculatorAnswer($data);

        $json = $serializer->serialize($answer->toResponse(), 'json');

        throw new AccessDeniedException();

        return new Response($json, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }


}