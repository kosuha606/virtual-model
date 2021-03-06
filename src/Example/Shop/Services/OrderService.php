<?php

namespace kosuha606\VirtualModel\Example\Shop\Services;


use kosuha606\VirtualModel\VirtualModelEntity;
use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModel\Example\Shop\Model\OrderReserve;
use kosuha606\VirtualModel\Example\Shop\Model\Product;

class OrderService
{
    /** @var UserService */
    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Product $product
     * @return int
     * @throws \Exception
     */
    public function findOrderReserveQtyByProduct(Product $product)
    {
        $reservedQty = 0;

        foreach ($this->getOrderReserve() as $item) {
            if ($item->productId === $product->id) {
                $reservedQty += $item->qty;
            }
        }

        return $reservedQty;
    }

    public function currentUser()
    {
        return $this->userService->current();
    }

    /**
     * @return OrderReserve[]
     * @throws \Exception
     */
    public function getOrderReserve()
    {
        return OrderReserve::many([
            'where' => [
                ['all']
            ]
        ]);
    }
}