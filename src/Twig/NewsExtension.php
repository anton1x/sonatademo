<?php

namespace App\Twig;

use App\Entity\NewsItem;
use App\News\NewsContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NewsExtension extends AbstractExtension
{

    /**
     * @var \App\Repository\NewsItemRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var ParameterBagInterface
     */
    private $bag;


    public function __construct(EntityManagerInterface $manager, Environment $environment, ParameterBagInterface $bag)
    {
        $this->repository = $manager->getRepository(NewsItem::class);
        $this->environment = $environment;
        $this->bag = $bag;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('news_widget', [$this, 'newsWidget']),
        ];
    }

    public function newsWidget($count, $useImages, $type, $category = null)
    {
        $list = $this->repository->createNewsListQuery($type, $category)
            ->setMaxResults($count)
            ->execute()
        ;

        $context = new NewsContext($type);

        return $this->environment->render('news/_widget.html.twig', [
            'list' => $list,
            'useImages' => $useImages,
            'context' => $context,
        ]);
    }
}
