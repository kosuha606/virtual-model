<?php

namespace src;

use kosuha606\VirtualModel\Example\MemoryModelProvider;
use kosuha606\VirtualModel\Test\EntityForDecorator;
use kosuha606\VirtualModel\VirtualModelManager;
use kosuha606\VirtualModel\VirtualModelProviderDecorator;
use PHPUnit\Framework\TestCase;

/**
 * В качестве типа 'adapter' передаем VirtualModelProviderAdapter
 * @package src
 */
class VirtualModelProviderDecoratorTest extends TestCase
{
    /** @var MemoryModelProvider */
    private $provider;

    protected function setUp()
    {
        parent::setUp();
        $this->provider = new MemoryModelProvider();
        $this->provider->memoryStorage = [
            EntityForDecorator::class => [
                [
                    'id' => 1,
                    'name' => 'Hello',
                ]
            ]
        ];

        VirtualModelManager::getInstance()->setProvider(
            new VirtualModelProviderDecorator(
                EntityForDecorator::KEY,
                $this->provider
            )
        );
    }

    /**
     * Тест выборки данных через декоратор
     * @throws \Exception
     */
    public function testFetch()
    {
        $models = EntityForDecorator::many(['where' => ['all']]);
        self::assertEquals(1, count($models));

        $oneModel = EntityForDecorator::one(['where' => ['all']]);
        self::assertEquals('Hello', $oneModel->name);
    }

    public function testCreate()
    {
        $newModel = EntityForDecorator::create([
            'id' => 2,
            'name' => 'New Entity'
        ]);
        $newModel->save();

        $models = EntityForDecorator::many(['where' => ['all']]);
        self::assertEquals(2, count($models));
    }

    public function testDelete()
    {
        $newModel = EntityForDecorator::one(['where' => [
            ['=', 'name', 'New Entity']
        ]]);
        $newModel->delete();

        $models = EntityForDecorator::many(['where' => ['all']]);
        self::assertEquals(1, count($models));
    }
}