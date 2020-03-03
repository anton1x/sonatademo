<?php


namespace App\EventListener;


use App\ViewOptions\HeaderOptions;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class InjectViewOptions404
{

    /**
     * @var HeaderOptions
     */
    private $headerOptions;

    public function __construct(HeaderOptions $headerOptions)
    {
        $this->headerOptions = $headerOptions;
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event)
    {
        if (!is_array($event->getController()) || !$event->getController()[0] instanceof ExceptionController)
            return;

        $this->headerOptions
            ->setFullInner()
            ->setOption('banner', '/files/headers/404.jpg')
            ->setOption('body_class', 'desktop_gray')
        ;
    }
}