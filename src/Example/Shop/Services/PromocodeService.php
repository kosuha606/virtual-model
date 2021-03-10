<?php

namespace kosuha606\VirtualModel\Example\Shop\Services;

use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModel\Example\Shop\Model\Promocode;

class PromocodeService
{
    /**
     * @param int $id
     * @return Promocode
     */
    public function findPromocodeById($id)
    {
        /** @var Promocode $promocode */
        $promocode = VirtualModelManager::getInstance()->getProvider()->one(Promocode::class, [
            'where' => [
                ['=', 'id', $id]
            ]
        ]);

        return $promocode;
    }
}