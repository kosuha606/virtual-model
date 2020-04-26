<?php

use kosuha606\EnvironmentModel\Example\MemoryModelProvider;
use kosuha606\EnvironmentModel\Example\Shop\Model\Action;
use kosuha606\EnvironmentModel\Example\Shop\Model\Cart;
use kosuha606\EnvironmentModel\Example\Shop\Model\Delivery;
use kosuha606\EnvironmentModel\Example\Shop\Model\OrderReserve;
use kosuha606\EnvironmentModel\Example\Shop\Model\Payment;
use kosuha606\EnvironmentModel\Example\Shop\Model\Product;
use kosuha606\EnvironmentModel\Example\Shop\Model\ProductRests;
use kosuha606\EnvironmentModel\Example\Shop\Model\Promocode;
use kosuha606\EnvironmentModel\Example\Shop\Model\User;
use kosuha606\EnvironmentModel\Example\Shop\ServiceManager;
use kosuha606\EnvironmentModel\VirtualModelManager;
use PHPUnit\Framework\TestCase;

class ShopTest extends TestCase
{
    /** @var MemoryModelProvider  */
    private $provider;

    public function cartProvider()
    {
        return [
            'Просто заказ без акций и тп' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 1,],
                ],
                'expectedTotal' => 900,
                'config' => [],
            ],
            'с акцией' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 1,],
                ],
                'expectedTotal' => 540,
                'config' => [
                    'actions' => [
                        [
                            'productIds' => [1],
                            'percent' => 90, // Первый продукт скидка 90%, цена = 40
                        ],
                    ],
                ],
            ],
            'с промокодом' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 1,],
                ],
                'expectedTotal' => 800,
                'config' => [
                    'promocode' => ['amount' => 100, 'code' => '123'],
                ],
            ],
            'Персональная скидка' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 1,],
                ],
                'expectedTotal' => 900,
                'config' => [],
            ],
            'С доставкой' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 1,],
                ],
                'expectedTotal' => 1000,
                'config' => [
                    'delivery' => [
                        'price' => 100
                    ]
                ],
            ],
            'С оплатой' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 1,],
                ],
                'expectedTotal' => 955,
                'config' => [
                    'payment' => [
                        'comission' => 55
                    ]
                ],
            ],
            'Колво больше 1' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 3,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 2,],
                ],
                'expectedTotal' => 2200,
                'config' => [],
            ],
            'Недостаточные остатки' => [
                'products' => [
                    ['id' => 1, 'price' => 400, 'rests' => [10], 'qty' => 1,],
                    ['id' => 2, 'price' => 500, 'rests' => [10], 'qty' => 2,]
                ],
                'expectedTotal' => 0,
                'config' => [
                    'orderReserve' => [
                        [
                            'productId' => 1,
                            'qty' => 10
                        ],
                        [
                            'productId' => 2,
                            'qty' => 10
                        ],
                    ]
                ]
            ],
        ];
    }

    public function setUp()
    {
        $this->provider = new MemoryModelProvider();
        VirtualModelManager::getInstance()->setProvider($this->provider);
    }

    public function tearDown()
    {
        unset($this->provider);
    }

    /**
     * Основной процесс построения заказа
     * @dataProvider cartProvider
     * @throws Exception
     */
    public function testFirst(
        $products,
        $expectedTotal,
        $config
    ) {
        $cart = new Cart();
        $personalDiscount = 0;

        if (isset($config['personalDiscount'])) {
            $personalDiscount = $config['personalDiscount'];
        }

        $user = User::create(
            [
                'personalDiscount' => $personalDiscount,
            ]
        );
        ServiceManager::getInstance()->userService->setUser($user);

        $actions = [];
        if (isset($config['actions'])) {
            foreach ($config['actions'] as $action) {
                $this->provider->memoryStorage[Action::class][] = $action;
                $actions[] = Action::create($action);
            }
        }

        if (isset($config['orderReserve'])) {
            foreach ($config['orderReserve'] as $item) {
                $this->provider->memoryStorage[OrderReserve::class][] = $item;
            }
        }

        foreach ($products as $product) {
            $rests = [];
            foreach ($product['rests'] as $qty) {
                $rests[] = ProductRests::create(['qty' => $qty]);
            }
            $product['rests'] = $rests;
            $product['actions'] = $actions;
            $cart->addProduct(Product::create($product), $product['qty']);
        }

        if (isset($config['promocode'])) {
            $cart->applyPromocode(Promocode::create($config['promocode']));
        }

        if (isset($config['delivery'])) {
            $cart->setDelivery(Delivery::create($config['delivery']));
        }

        if (isset($config['payment'])) {
            $cart->setPayment(Payment::create($config['payment']));
        }

        $totals = $cart->getTotals();
        $this->assertEquals($expectedTotal, $totals);
    }
}