<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Services;

use kosuha606\EnvironmentModel\EnvironmentModelManager;
use kosuha606\EnvironmentModel\Example\Shop\Model\Promocode;

class PromocodeService
{
    /**
     * @param $id
     * @return Promocode
     */
    public function findPromocodeById($id)
    {
        /** @var Promocode $promocode */
        $promocode = EnvironmentModelManager::getInstance()->getProvider()->one(Promocode::class, [
            'where' => [
                ['=', 'id', $id]
            ]
        ]);

        return $promocode;
    }
}