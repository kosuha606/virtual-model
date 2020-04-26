<?php

namespace kosuha606\EnvironmentModel\Example\Shop\Services;

use kosuha606\EnvironmentModel\VirtualModelManager;
use kosuha606\EnvironmentModel\Example\Shop\Model\Payment;

class PaymentService
{
    public function findPaymentById($id)
    {
        $payment = VirtualModelManager::getInstance()->getProvider()->one(Payment::class, [
            'where' => [
                ['all']
            ]
        ]);

        return $payment;
    }
}