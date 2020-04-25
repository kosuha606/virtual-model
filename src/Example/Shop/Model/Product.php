<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Model;



use kosuha606\EnvironmentModel\EnvironmentModel;
use kosuha606\EnvironmentModel\Example\Shop\ServiceManager;
use kosuha606\EnvironmentModel\Example\Shop\Services\ProductService;

/**
 * Продукт
 * @property $salePrice
 */
class Product extends EnvironmentModel
{
    /** @var ProductService */
    private $productService;

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

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param Action[] $actions
     */
    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * @return ProductRests[]
     */
    public function getRests(): array
    {
        return $this->rests;
    }

    /**
     * @param ProductRests[] $rests
     */
    public function setRests(array $rests): void
    {
        $this->rests = $rests;
    }
}