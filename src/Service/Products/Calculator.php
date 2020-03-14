<?php


namespace App\Service\Products;


use App\Repository\AddressObjectRepository;
use App\Repository\ProductsRepository;
use App\Service\Products\Reducers\ReducerInterface;

class Calculator
{
    /**
     * @var AddressObjectRepository
     */
    private $addressObjectRepository;
    /**
     * @var ProductsRepository
     */
    private $productsRepository;
    /**
     * @var ReducerInterface[]
     */
    private $reducers = [];
    /**
     * @var ProductsHandler
     */
    private $handler;

    public function __construct(
        AddressObjectRepository $addressObjectRepository,
        ProductsRepository $productsRepository,
        iterable $reducers,
        ProductsHandler $handler
    )
    {
        $this->addressObjectRepository = $addressObjectRepository;
        $this->productsRepository = $productsRepository;

        foreach ($reducers as $reducer) {
            $this->reducers[] = $reducer;
        }

        $this->handler = $handler;
    }

    public function getCalculatorData()
    {

        $products = $this->productsRepository->getAllProducts();
        $groupedProducts = $this->productsRepository->groupListByCategoryCode($products);

        $result = [
            'addresses' => $this->addressObjectRepository->getSortedList(),
            'products' => $groupedProducts,
            'products_all' => $products,
        ];

        return $result;

    }

    protected function createRequestDataObject($data, $productsCollection)
    {
        return new ProductsRequestData($data, $productsCollection);
    }

    protected function applyReducers(ProductsRequestData $requestData)
    {

        foreach ($this->reducers as $reducer) {
            $reducer->init($requestData);
            $reducer->reduce();
            $requestData->setReduced();
        }

        return $requestData;
    }

    public function parseCalculatorAnswer($answer)
    {
        $dataObject = $this->createRequestDataObject($answer, $this->getCalculatorData());
        $this->applyReducers($dataObject);

        if (count($dataObject->getInvalidEntities()) == 0) {
            $this->handler->handle($dataObject);
        }

        return $dataObject;
    }
}