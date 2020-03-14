<?php


namespace App\Service\Products;


use App\Entity\Basket;
use App\Entity\ConnectionFormOrder;
use App\Service\AmoCRM\AmoHelper;
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

    public function __construct(Environment $twig, AmoHelper $amoHelper, Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->amoHelper = $amoHelper;
        $this->mailer = $mailer;
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
            )
        ;

        return $message;
    }

    public function handle(ProductsRequestData $productsRequestData)
    {
        $this->mailer->send($this->createMessage($productsRequestData));
//        $this->amoHelper->createIncomingLead(
//          'TEst Testov',
//          '+79238492384',
//          'test@test.ru',
//          'test tariff',
//          'test test',
//          '',
//          '',
//          [ $this->twig->render(
//              'crm_notes/products/basket.txt.twig',
//              ['data' => $productsRequestData]
//          )]
//        );
    }

    public function setOptions($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

}