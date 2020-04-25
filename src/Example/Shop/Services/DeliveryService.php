<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Services;


use kosuha606\EnvironmentModel\EnvironmentModelManager;
use kosuha606\EnvironmentModel\Example\Shop\Model\Delivery;

class DeliveryService
{
    public function findDeliveryById($id)
    {
        $delivery = EnvironmentModelManager::getInstance()->getProvider()->one(Delivery::class, [
            'where' => [
                ['=', 'id', $id]
            ]
        ]);

        return $delivery;
    }
}