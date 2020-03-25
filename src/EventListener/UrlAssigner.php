<?php


namespace App\EventListener;


use App\Entity\NewsItem;
use App\News\NewsItemTypeDetector;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class UrlAssigner
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var ParameterBag
     */
    private $bag;
    /**
     * @var NewsItemTypeDetector
     */
    private $detector;

    public function __construct(RouterInterface $router, ParameterBagInterface $bag)
    {
        $this->router = $router;
        $this->bag = $bag;
    }

    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof NewsItem) {
            return;
        }


        $url = $this->router->generate($this->bag->get($entity->getItemType().'.view.route'), ['id' => $entity->getId()]);


        $entity->setSystemURL($url);
    }
}