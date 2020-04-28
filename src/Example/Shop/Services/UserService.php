<?php

namespace kosuha606\VirtualModel\Example\Shop\Services;


use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModel\Example\Shop\Model\User;

class UserService
{
    /** @var User */
    private $user;

    public function login($userId)
    {
        $user = VirtualModelManager::getInstance()->getProvider()->one(User::class, [
            'where' => [
                ['=', 'id', $userId]
            ]
        ]);
        $this->user = $user;
    }

    public function current()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}