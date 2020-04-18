<?php


namespace App\Service\Products;


use App\Application\Sonata\ClassificationBundle\Entity\CategoryManager;
use App\Entity\Basket;
use App\Entity\ConnectionFormOrder;
use App\Service\AmoCRM\AddressConverter;
use App\Service\AmoCRM\AmoHelper;
use App\Service\AmoCRM\BasketConverter;
use App\Service\Complat\ComplatHelper;
use Swift_Mailer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ProductsHandler
{

    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var AmoHelper
     */
    private $amoHelper;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var array
     */
    private $options;
    /**
     * @var ComplatHelper
     */
    private $complatHelper;
    /**
     * @var CategoryManager
     */
    private $manager;
    /**
     * @var BasketConverter
     */
    private $amoBasketConverter;

    public function __construct(Environment $twig, AmoHelper $amoHelper, Swift_Mailer $mailer, ComplatHelper $complatHelper, BasketConverter $amoBasketConverter)
    {
        $this->twig = $twig;
        $this->amoHelper = $amoHelper;
        $this->mailer = $mailer;
        $this->complatHelper = $complatHelper;
        $this->amoBasketConverter = $amoBasketConverter;
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'from' => 'antongaran@mail.ru',
            'to' => 'antongaran@mail.ru'
        ]);
    }


    private function createMessage(ProductsRequestData $productsRequestData)
    {
        $message = (new \Swift_Message('Заявка на подключение'))
            ->setFrom($this->options['from'])
            ->setTo($this->options['to'])
            ->setBody(
                $this->twig->render(
                    'mails/products/basket.html.twig',
                    //'crm_notes/products/basket.txt.twig',
                    ['data' => $productsRequestData]
                ),
                'text/html'
            //'text/plain'
            );

        return $message;
    }

    public function handle(ProductsRequestData $productsRequestData)
    {
        if ($productsRequestData->needLoginCreate()) {
            $this->complatHelper->doNewLoginQuery($productsRequestData);
        }
        $addrConverter = new AddressConverter($productsRequestData);
        $this->mailer->send($this->createMessage($productsRequestData));

        $basketConverter = $this->amoBasketConverter->createWithBasket($productsRequestData->basket);
        //dump($basketConverter->getTv());
        //dump($basketConverter->getAdditional());


        //dump($this->generateNotes($productsRequestData));

        $this->amoHelper->createIncomingLead(
          $productsRequestData->getContactParam('input_fio'),
          $productsRequestData->getContactParam('input_phone'),
          $productsRequestData->getContactParam('input_email'),
          $basketConverter->getInternetPlan(),
          $addrConverter->getAddress(),
          $productsRequestData->getContactParam('input_apartment'),
          $productsRequestData->getContactParam('input_building'),
          $this->generateNotes($productsRequestData),
            $basketConverter->getAdditional(),
            $basketConverter->getTv(),
            $basketConverter->getDevices(),
            $productsRequestData->getComplatLogin(),
            $basketConverter->getBudget(),
            $productsRequestData->getConnectionDate(),
            $addrConverter->getLocation(),
            $productsRequestData->getContactParam('input_comment')
        );

    }

    private function generateNotes(ProductsRequestData $requestData)
    {
        $result = [];

        for ($i = 1; $i <= 5; $i++) {
            $render = $this->twig->render(
                'crm_notes/products/basket.txt.twig',
                [
                    'data' => $requestData,
                    'part' => $i,
                ]
            );

            $render = trim($render);

            if (mb_strlen($render) > 0) {
                $result[$i] = $render;
            }
        }

        return $result;
    }

    public function setOptions($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

}