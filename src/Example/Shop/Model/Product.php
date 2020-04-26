<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;



use kosuha606\EnvironmentModel\VirtualModel;
use kosuha606\EnvironmentModel\Example\Shop\ServiceManager;
use kosuha606\EnvironmentModel\Example\Shop\Services\ProductService;

/**
 * Продукт
 */
class Product extends VirtualModel
{
    /** @var ProductService */
    private $productService;

    /**
     * Виртуальный атритут за который действительно продается товар
     * @var int
     */
    private $sale_price;

    public function attributes(): array
    {
        return [
            'id',
            'name',
            'price',
            'price2B',
            'actions',
            'rests',
        ];
    }

    public function __construct($environment = 'db')
    {
        $this->productService = ServiceManager::getInstance()->productService;
        parent::__construct($environment);
    }

    /**
     * Проверяет имеются ли свободные остатки по
     * продукту
     * @param $qty
     * @return bool
     * @NOTICE Переделал, теперь происходит делегирование логики к дружественному классу-сервису
     * @throws \Exception
     */
    public function hasFreeRests($qty)
    {
        return $this->productService->hasFreeRests($this, $qty);
    }

    /**
     * Получить цену за которую нужно продать товар
     * @return float|int
     */
    public function getSalePrice()
    {
        return $this->productService->calculateProductSalePrice($this);
    }
}