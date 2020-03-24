<?php

namespace App\Tests;

use App\Entity\BasketItem;
use App\Repository\ProductsRepository;
use App\Service\Complat\ComplatHelper;
use App\Service\Products\Calculator;
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

    public function testSendToComplat()
    {
        //$complatHelper = self::$container->get(ComplatHelper::class);

       $json = file_get_contents('./tests/Json/complat.json', true);

       $this->assertTrue(strlen($json) > 0);


        $url="https://rosfonstat.complat.ru/rosfon_get.php";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['data'=>$json]));
        curl_setopt($curl, CURLOPT_URL, $url);

        $response = curl_exec($curl);

//        $response = $this->httpClient->request($query->getMethod(), $query->getEndpoint(), [
//            'headers' => $this->getRequestHeaders(),
//            'body' => ['data' => $serialized]
//        ]);

        dump($response);


        $this->assertTrue(strlen($response) > 0, "Response body to complat is empty");


    }

//    public function testSendToMe()
//    {
//        //$complatHelper = self::$container->get(ComplatHelper::class);
//
//        $json = '
//        {
//
//
//    "Data": {
//
//
//        "addressId": 6,
//
//
//        "internet": {
//
//
//            "internetPlan": 8,
//
//
//            "staticIp": true,
//
//
//            "optDevice": 32,
//
//
//            "wifiDevice": 33
//
//
//        },
//
//
//        "phone": {
//
//
//            "phoneTariff": 48,
//
//
//            "dectDevices": [
//
//
//                {
//
//
//                    "id": "46",
//
//
//                    "count": 1
//
//
//                }
//
//
//            ],
//
//
//            "tableDevices": []
//
//
//        },
//
//
//        "vision": {
//
//
//            "visionHomeTariff": 50,
//
//
//            "devicesHome": [
//
//
//                {
//
//
//                    "id": "37",
//
//
//                    "count": 1
//
//
//                }
//
//
//            ],
//
//
//            "visionParkingTariff": 54,
//
//
//            "devicesParking": [
//
//
//                {
//
//
//                    "id": "40",
//
//
//                    "count": 1
//
//
//                }
//
//
//            ],
//
//
//            "poePort": true
//
//
//        },
//
//
//        "contact": {
//
//
//            "type": "phone",
//
//
//            "use_connect_time": false,
//
//
//            "checkbox_already_client": false,
//
//
//            "checkbox_from_other_operator": true,
//
//
//            "input_fio": "2342342",
//
//
//            "input_phone": "+7 (234) 324-34-32"
//
//
//        }
//
//
//    },
//
//
//    "products": [
//
//
//        {
//
//
//            "id": 8,
//
//
//            "price": 468,
//
//
//            "count": 1,
//
//
//            "type": "internet_basic"
//
//
//        },
//
//
//        {
//
//
//            "id": 3,
//
//
//            "price": 128,
//
//
//            "count": 1,
//
//
//            "type": "additional_internet"
//
//
//        },
//
//
//        {
//
//
//            "id": 32,
//
//
//            "price": 5500,
//
//
//            "count": 1,
//
//
//            "type": "devices_internet_ont"
//
//
//        },
//
//
//        {
//
//
//            "id": 33,
//
//
//            "price": 3300,
//
//
//            "count": 1,
//
//
//            "type": "devices_internet_wifi"
//
//
//        },
//
//
//        {
//
//
//            "id": 48,
//
//
//            "price": 132,
//
//
//            "count": 1,
//
//
//            "type": "additional_phone"
//
//
//        },
//
//
//        {
//
//
//            "id": 46,
//
//
//            "price": 14490,
//
//
//            "count": 1,
//
//
//            "type": "devices_additional_phone_dect"
//
//
//        },
//
//
//        {
//
//
//            "id": 50,
//
//
//            "price": 169,
//
//
//            "count": 1,
//
//
//            "type": "additional_vision_home"
//
//
//        },
//
//
//        {
//
//
//            "id": 37,
//
//
//            "price": 3990,
//
//
//            "count": 1,
//
//
//            "type": "devices_additional_vision_home"
//
//
//        },
//
//
//        {
//
//
//            "id": 58,
//
//
//            "price": 5255,
//
//
//            "count": 1,
//
//
//            "type": "additional_vision_poe"
//
//
//        },
//
//
//        {
//
//
//            "id": 54,
//
//
//            "price": 0,
//
//
//            "count": 1,
//
//
//            "type": "additional_vision_parking"
//
//
//        },
//
//
//        {
//
//
//            "id": 40,
//
//
//            "price": 7300,
//
//
//            "count": 1,
//
//
//            "type": "devices_additional_vision_parking"
//
//
//        }
//
//
//    ],
//
//
//    "command": "new_login"
//
//
//}
//        ';
//
//        $url = "http://nginx/dummy";
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['data' => $json]));
//        curl_setopt($curl, CURLOPT_URL, $url);
//
//        $response = curl_exec($curl);
//
//        print($response);
//        print(curl_error($curl));
//
//        $this->assertTrue(strlen($response) > 0, "Response body to dummy is empty");
//
//
//    }
}
