<?php


namespace App\Controller;


use App\Basket\BasketManager;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    private $basket;

    public function __construct(BasketManager $basket)
    {
        $this->basket = $basket;
    }

    /**
     * @Route(name="basket_add", path="/add/{product}")
     */
    public function add(Product $product)
    {
        dump($this->basket);
        $this->basket->basket->addItem(BasketManager::createBasketItem($product));
        $this->basket->save();
        return $this->render('products_list.twig', [
           'items' => [$product],
        ]);
    }

    public function list()
    {

    }
}