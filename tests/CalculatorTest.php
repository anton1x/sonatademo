<?php

namespace App\Tests;

use App\Entity\BasketItem;
use App\Repository\ProductsRepository;
use App\Service\Products\Calculator;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalculatorTest extends WebTestCase
{
    /**
     * @var Calculator|object|null
     */
    private $calculator;
    /**
     * @var array
     */
    private $products;


    protected function setUp(): void
    {
        self::bootKernel();
        $this->calculator = self::$kernel->getContainer()->get(Calculator::class);
        $this->products = self::$container->get(ProductsRepository::class)->getAllProductsGroupedByCategory();
    }

    public function testWrongIds()
    {
        $request = [
            "internet" => [
                "internetPlan" => 832,
                "staticIp" => true,
                "optDevice" => 31234,
                "wifiDevice" => 33243423,
            ]
        ];

        $result = $this->calculator->parseCalculatorAnswer($request);

        $this->assertTrue(in_array('internetPlan', $result->getInvalidEntities()));
        $this->assertTrue(count($result->getInvalidEntities()) == 1);
    }

    public function testNullInternetPlan()
    {
        $request = [
            "internet" => [
                "internetPlan" => null,
                "staticIp" => true,
                "optDevice" => 31234,
                "wifiDevice" => 33243423,
            ]
        ];
        $result = $this->calculator->parseCalculatorAnswer($request);

        //var_dump($result->getInvalidEntities());

        $this->assertTrue(!isset($result->result['internet']));
        $this->assertTrue(!in_array('internetPlan', $result->getInvalidEntities()));
    }


    public function testTV()
    {

        $request = [
            "tv" => [
                "tvPlan" => array_rand($this->products['tv_basic']),
                "tvBox" => null,
                "addons" => array_rand($this->products['tv_addons'], 2),
                "theathers" => array_rand($this->products['tv_theatres'], 2)
            ]
        ];

        $result = $this->calculator->parseCalculatorAnswer($request);

        $this->assertTrue(count($result->getInvalidEntities()) == 0);


        //$this->assertTrue(isset($result->result['tv']['plan']));
        $this->assertCount(4, $result->basket->getItemsByProductCategoryCode(['tv_addons', 'tv_theatres']));

        $request = [
            "tv" => [
                "tvPlan" => array_rand($this->products['tv_basic']),
                "tvBox" => array_rand($this->products['devices_tv_box']),
                "addons" => array_rand($this->products['tv_addons'], 2),
                "theathers" => array_rand($this->products['tv_theatres'], 2)
            ]
        ];

        $result = $this->calculator->parseCalculatorAnswer($request);

        $this->assertTrue(count($result->basket->getItemsByProductCategoryCode(['devices_tv_box'])) == 1);
    }

    public function testPhone()
    {
        $request = [
            'phone' => [
                "phoneTariff" => 48,
                "dectDevices" => [
                    [
                        'id' => 44,
                        'count' => 3,
                    ]
                ],
                "tableDevices" => [
                    [
                        "id" => "43",
                        "count" => 1,
                    ]
                ]
            ]];

        $result = $this->calculator->parseCalculatorAnswer($request);


        //$this->assertTrue(isset($result->result['phone']['plan']));
        //$this->assertTrue($result->result['phone']['devices'][0] instanceof BasketItem);
        $this->assertCount(0, $result->getInvalidEntities());
        $this->assertCount(2, $result->basket->getItemsByProductCategoryCode(['devices_additional_phone_dect', 'devices_additional_phone_table']));
        
    }


    public function testBasketGetsItemsByProductCategoryCode()
    {
        $request = [
            'phone' => [
                "phoneTariff" => 48,
                "dectDevices" => [
                    [
                        'id' => 44,
                        'count' => 3,
                    ]
                ],
                "tableDevices" => [
                    [
                        "id" => "43",
                        "count" => 1,
                    ]
                ]
            ]];

        $result = $this->calculator->parseCalculatorAnswer($request);

        $dect = $result->basket->getItemsByProductCategoryCode('devices_additional_phone_dect');

        $notExistable = $result->basket->getItemsByProductCategoryCode('fdgdfgdfgdfg');


        $this->assertCount(1, $dect);

        $this->assertCount(0, $notExistable);
    }
}
