<?php


namespace App\Service\Products;

use JMS\Serializer\Annotation as JMS;

/**
 * Class ProductsResponseData
 * @package App\Service\Products
 * @JMS\ExclusionPolicy("all")
 */
class ProductsResponseData
{
    /**
     * @JMS\Expose()
     */
    private $error = false;

    /**
     * @JMS\Expose()
     * @JMS\SerializedName("login")
     */
    private $complatLogin;

    /**
     * @JMS\Expose()
     */
    private $shouldShowPayment = false;

    /**
     * @JMS\Expose()
     */
    private $amount = 0;


    public static function create(ProductsRequestData $data)
    {
        $new = new self();
        $new->complatLogin = $data->getComplatLogin();

        if ($new->complatLogin !== null && $data->getContact()['type'] == "self") {
            $new->shouldShowPayment = true;
        }

        $new->amount = $data->basket->getFirstPaymentValue();

        //dump($new);
        return $new;
    }

    public static function createErrorResponse()
    {
        $new = new self();
        $new->error = true;

        return $new;
    }

    public function getRequest()
    {
        return $this->data;
    }

    public function isError()
    {
        return $this->error;
    }
}