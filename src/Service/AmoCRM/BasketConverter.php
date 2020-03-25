<?php


namespace App\Service\AmoCRM;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Application\Sonata\ClassificationBundle\Entity\CategoryManager;
use App\Entity\AdditionalServicePlan;
use App\Entity\BaseProduct;
use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Device;
use App\Entity\InternetPlan;
use App\Entity\TVPlan;

class BasketConverter
{
    /**
     * @var Basket
     */
    private $basket;
    /**
     * @var CategoryManager
     */
    private $manager;

    public function __construct(CategoryManager $manager)
    {
        $this->manager = $manager;
    }

    public function createWithBasket(Basket $basket)
    {
        $new = clone $this;
        $new->setBasket($basket);

        return $new;
    }

    /**
     * @param Basket $basket
     */
    public function setBasket(Basket $basket): void
    {
        $this->basket = $basket;
    }

    private function formatProduct(BaseProduct $product) {
        return sprintf('[%s] %s', $product->getCategory()->getName(), $product->getTitle());
    }

    private function formatInternetPlan(InternetPlan $product) {
        return sprintf('[%s] %s', $product->getPricingType()->getName(), $product->getTitle());
    }

    private function getCategoryCodes(array $parents, string $context)
    {
        $cats = $this->manager->loadChildrenCategoriesByParentCode($parents, $context);

        return array_map(function ($item) {
            /**
             * @var Category $item
             */
            return $item->getCode();
        }, $cats);
    }

    private function getListing($parentCodes, $context)
    {
        $cats = $this->getCategoryCodes($parentCodes, $context);

        $result = $this->basket->getItemsByProductCategoryCode($cats);


        return array_map(function ($item) {
            return $this->formatProduct($item->getProduct());
        }, $result);
    }

    public function getInternetPlan(): string
    {
        $items = $this->basket->getItemsByProductCategoryCode('internet_basic');

        if (!count($items)) {
            return '';
        }

        /**
         * @var BasketItem $item
         */
        $item = array_shift($items);

        return  $this->formatInternetPlan($item->getProduct());
    }

    public function getDevices()
    {
        return $this->getListing(Device::ROOT_CATEGORIES, 'devices');
    }

    public function getTv()
    {
        return $this->getListing(TVPlan::ROOT_CATEGORIES, 'products');
    }

    public function getAdditional()
    {
        return $this->getListing(AdditionalServicePlan::ROOT_CATEGORIES, 'additional');
    }

    public function getBudget()
    {
        return $this->basket->getFirstPaymentValue();
    }
}