<?php

namespace kosuha606\VirtualModel\Example\Shop\Services;


use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModel\Example\Shop\Model\Delivery;

class DeliveryService
{
    public function findDeliveryById($id)
    {
        $delivery = VirtualModelManager::getInstance()->getProvider()->one(Delivery::class, [
            'where' => [
                ['=', 'id', $id]
            ]
        ]);

        return $delivery;
    }
}