<?php

namespace kosuha606\VirtualModel\Example\Shop\Services;

use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModel\Example\Shop\Model\Payment;

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