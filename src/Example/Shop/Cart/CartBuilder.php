<?php

namespace kosuha606\VirtualModel\Example\Shop\Cart;


use kosuha606\VirtualModel\Example\Shop\Model\Cart;
use kosuha606\VirtualModel\Example\Shop\Model\Delivery;
use kosuha606\VirtualModel\Example\Shop\Model\Payment;
use kosuha606\VirtualModel\Example\Shop\Services\CartService;
use kosuha606\VirtualModel\Example\Shop\Services\ProductService;

/**
 * Построитель корзины, основная идея в том, чтобы
 * используя простые типы данных создавать корзину
 * с помощью простого интерфейса
 *
 * @package kosuha606\Model\iteration2\cart
 */
class CartBuilder
{
    /** @var Cart */
    private $cart;

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(
        CartService $cartService,
        ProductService $productService
    ) {
        $this->cart = new Cart();
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    /**
     * @param int $productId
     * @param int $qty
     * @throws \Exception
     */
    public function addProductById($productId, $qty)
    {
        $product = $this->productService->findProductById($productId);
        $product->actions = $this->productService->findAllActions();
        $this->cart->addProduct($product, $qty);
    }

    public function setPromocodeById($promocodeId)
    {
        $promocode = $this->cartService->getPromocodeById($promocodeId);
        $this->cart->applyPromocode($promocode);
    }

    public function setPaymentById($paymentId)
    {
        /** @var Payment $payment */
        $payment = $this->cartService->getPaymentById($paymentId);
        $this->cart->setPayment($payment);
    }

    public function setDeliveryById($deliveryId)
    {
        /** @var Delivery $delivery */
        $delivery = $this->cartService->getDeliveryById($deliveryId);
        $this->cart->setDelivery($delivery);
    }

    public function getCart()
    {
        return $this->cart;
    }
}