<?php


namespace App\Basket;


use App\Basket\Entity\Basket;
use App\Basket\Entity\BasketItem;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BasketManager
{
    private $session;
    private $basket;
    private const SESSION_BASKET_ID = 'basket';


    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function initFromSession()
    {
        if(!$this->session->isStarted())
            $this->session->start();
        $this->basket = $this->session->get(self::SESSION_BASKET_ID, new Basket());
    }

    private function updateSession()
    {
        if(is_null($this->basket))
            throw new \Exception('Basket not initialised');

        $this->session->set(self::SESSION_BASKET_ID, $this->basket);
    }

    public function save()
    {
        try {
            $this->updateSession();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getBasket()
    {
        return $this->basket;
    }

    public static function createBasketItem(Product $product)
    {
        return new BasketItem($product);
    }

}