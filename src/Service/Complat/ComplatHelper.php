<?php


namespace App\Service\Complat;


use App\Service\Complat\Request\ComplatNewLoginQuery;
use App\Service\Complat\Request\ComplatQuery;
use App\Service\Products\ProductsRequestData;
use App\Service\Products\ProductsResponseData;
use JMS\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ComplatHelper
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(SerializerInterface $serializer, HttpClientInterface $httpClient)
    {
        $this->serializer = $serializer;
        $this->httpClient = $httpClient;
    }

    private function createNewLoginQuery(ProductsRequestData $requestData)
    {
        return new ComplatNewLoginQuery($requestData);
    }



    public function doNewLoginQuery(ProductsRequestData $requestData)
    {
        $query = $this->createNewLoginQuery($requestData);
        $response = $this->send($query);


        if (isset($response['data'])) {
           $data = $response['data'];
            if (isset($data['status']) && $data['status'] == 0) {
                $requestData->setComplatLogin($data['login'] ?? '');
            }
        }

    }



    protected function send(ComplatQuery $query) {
        $serialized = $this->serializer->serialize($query, 'json');

        $response = $this->httpClient->request($query->getMethod(), $query->getEndpoint(), [
            'headers' => $query->getRequestHeaders(),
            'body' => ['data' => $serialized]
        ]);

        try {
            $result = $response->toArray();
        } catch (\Exception $exception) {
            $result = [
                'data' => [
                    'status' => 1
                ]
            ];
        }

        return $result;

    }
}