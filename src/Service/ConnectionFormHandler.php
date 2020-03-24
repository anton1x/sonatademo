<?php


namespace App\Service;


use App\Entity\ConnectionFormOrder;
use App\Service\AmoCRM\AmoHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ConnectionFormHandler
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var AmoHelper
     */
    private $amoHelper;
    /**
     * @var array
     */
    private $options;

    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger, Environment $twig, AmoHelper $amoHelper)
    {

        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->twig = $twig;
        $this->amoHelper = $amoHelper;

        $this->setOptions();

    }

    public function setOptions($options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    private function createMessage(ConnectionFormOrder $order)
    {
        $message = (new \Swift_Message($order->getHeaderText()))
            ->setFrom($this->options['from'])
            ->setTo($this->options['to'])
            ->setBody(
                $this->twig->render('mails/connection_order.html.twig', ['item' => $order]),
                'text/html'
            )
        ;

        if ($order->getEmail()) {
            $message->setReplyTo([$order->getEmail()]);
        }

        return $message;
    }


    public function handle(ConnectionFormOrder $order)
    {
        $this->mailer->send($this->createMessage($order));
        $this->amoHelper->createIncomingLead(
            $order->getName(),
            $order->getPhone(),
            $order->getEmail(),
            '',
            '',
            '',
            '',
            [$order->getCrmMessage()]
        );
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'from' => 'antongaran@mail.ru',
            'to' => 'antongaran@mail.ru'
        ]);
    }
}