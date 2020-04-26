<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;

/**
 * Элемент корзины
 * @package kosuha606\Model\iteration2\model
 */
class CartItem
{
    public $price;

    public $productId;

    public $qty;

    public function __construct($data = [])
    {
        foreach ($data as $attr => $value) {
            $this->$attr = $value;
        }
    }

    public function getTotal()
    {
        return $this->price*$this->qty;
    }
}