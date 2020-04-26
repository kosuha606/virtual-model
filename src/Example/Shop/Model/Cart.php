<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

use kosuha606\EnvironmentModel\Example\Shop\ServiceManager;
use kosuha606\EnvironmentModel\Example\Shop\Services\CartService;

/**
 * Корзина
 * @NOTICE Корзина должна каждый раз быть построена при каждой загрузке приложения
 * @NOTICE Нужен враппер который будет заниматься построением корзины
 * @package kosuha606\Model\iteration2\model
 */
class Cart
{
    /**
     * @var CartItem[]
     */
    public $items = [];

    /**
     * @var Promocode
     */
    public $promocode;

    /**
     * @var Delivery
     */
    public $delivery;

    /**
     * @var Payment
     */
    public $payment;

    /**
     * @var CartService
     */
    public $cartService;

    public function __construct($config = [])
    {
        $this->cartService = ServiceManager::getInstance()->cartService;
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function complete()
    {
        return $this->cartService->calculateTotals($this);
    }

    public function applyPromocode(Promocode $promocode)
    {
        $this->promocode = $promocode;
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function getTotals()
    {
        return $this->cartService->calculateTotals($this);
    }

    /**
     * @param Product $product
     * @param int $qty
     * @throws \Exception
     */
    public function addProduct(Product $product, $qty = 1)
    {
        if ($product->hasFreeRests($qty)) {
            $this->items[] = new CartItem([
                'price' => $product->sale_price,
                'productId' => $product->id,
                'qty' => $qty
            ]);
        }
    }

    /**
     * @param Delivery $delivery
     */
    public function setDelivery(Delivery $delivery): void
    {
        $this->delivery = $delivery;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }
}