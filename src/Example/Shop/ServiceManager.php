<?php

namespace kosuha606\VirtualModel\Example\Shop;

use kosuha606\VirtualModel\Example\Shop\Services\CartService;
use kosuha606\VirtualModel\Example\Shop\Services\DeliveryService;
use kosuha606\VirtualModel\Example\Shop\Services\OrderService;
use kosuha606\VirtualModel\Example\Shop\Services\PaymentService;
use kosuha606\VirtualModel\Example\Shop\Services\ProductService;
use kosuha606\VirtualModel\Example\Shop\Services\PromocodeService;
use kosuha606\VirtualModel\Example\Shop\Services\UserService;

/**
 * @property UserService $userService
 * @property ProductService $productService
 * @property CartService $cartService
 * @property PaymentService $paymentService
 * @property DeliveryService $deliveryService
 * @property PromocodeService $promocodeService
 */
class ServiceManager
{
    private static $instance;

    private $services = [];

    private $type;

    public function __construct($type)
    {
        $this->services = [
            'userService' => new UserService(),
        ];

        $this->services['paymentService'] = new PaymentService();
        $this->services['deliveryService'] = new DeliveryService();
        $this->services['promocodeService'] = new PromocodeService();
        $this->services['orderService'] = new OrderService($this->services['userService']);
        $this->services['productService'] = new ProductService($this->services['orderService']);
        $this->services['cartService'] = new CartService(
            $this->services['orderService'],
            $this->services['paymentService'],
            $this->services['deliveryService'],
            $this->services['promocodeService'],
        );

        $this->type = $type;
    }

    public function __get($name)
    {
        return $this->services[$name];
    }

    public static function getInstance($type = 'bad')
    {
        if (!self::$instance) {
            self::$instance = new self($type);
        }
        self::$instance->type = $type;

        return self::$instance;
    }
}