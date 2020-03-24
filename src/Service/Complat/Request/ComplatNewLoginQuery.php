<?php


namespace App\Service\Complat\Request;


use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Service\Complat\BasketConverter;
use App\Service\Products\ProductsRequestData;
use JMS\Serializer\Annotation as JMS;

/**
 * Class ComplatNewLoginQuery
 * @package App\Service\Complat\Request
 */
class ComplatNewLoginQuery implements ComplatQuery
{

    /**
     * @var array
     * @JMS\SerializedName("Data")
     */
    private $data;

    private $products;

    private $command = 'new_login';

    /**
     * @var string
     * @JMS\Exclude()
     */
    private $endpoint = 'https://rosfonstat.complat.ru/rosfon_get.php';


    public function __construct(ProductsRequestData $requestData)
    {
        $this->products = BasketConverter::convert($requestData->basket);
        $this->data = $requestData->getSource();
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }


    public function getRequestHeaders()
    {
        return [
            'Content-type' => 'application/x-www-form-urlencoded',
        ];
    }




}