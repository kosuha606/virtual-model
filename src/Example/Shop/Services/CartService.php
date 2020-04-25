<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Services;

use kosuha606\EnvironmentModel\Example\Shop\Model\Cart;

class CartService
{
    /** @var OrderService */
    private $orderService;

    /** @var PaymentService */
    private $paymentService;

    /** @var DeliveryService */
    private $deliveryService;

    private $promocodeService;

    public function __construct(
        OrderService $orderService,
        PaymentService $paymentService,
        DeliveryService $deliveryService,
        PromocodeService $promocodeService
    ) {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
        $this->deliveryService = $deliveryService;
        $this->promocodeService = $promocodeService;
    }

    public function calculateTotals(Cart $cart)
    {
        $price = 0;

        foreach ($cart->items as $item) {
            $price += $item->getTotal();
        }

        $user = $this->orderService->currentUser();

        if ($cart->payment) {
            $price += $cart->payment->comission;
        }

        if ($cart->delivery) {
            $price += $cart->delivery->price;
        }

        if ($user->personalDiscount) {
            $price -= $price*($user->personalDiscount/100);
        }

        if ($cart->promocode) {
            if ($price < $cart->promocode->amount) {
                throw new \Exception('Сумма промокода больше суммы корзины');
            }

            $price -= $cart->promocode->amount;
        }

        return $price;
    }

    public function getPromocodeById($promocodeId)
    {
        return $this->promocodeService->findPromocodeById($promocodeId);
    }

    public function getPaymentById($paymentId)
    {
        return $this->paymentService->findPaymentById($paymentId);
    }

    public function getDeliveryById($deliveryId)
    {
        return $this->deliveryService->findDeliveryById($deliveryId);
    }
}