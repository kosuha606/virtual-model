<?php

use kosuha606\VirtualModel\Example\MemoryModelProvider;
use kosuha606\VirtualModel\Example\Product\Product;
use kosuha606\VirtualModel\VirtualModelManager;
use PHPUnit\Framework\TestCase;

class CrudTest extends TestCase
{
    private $provider;

    public function setUp()
    {
        $this->provider = new MemoryModelProvider();

        VirtualModelManager::getInstance()->setProvider($this->provider);
    }

    public function tearDown()
    {
        unset($this->provider);
    }

    /**
     *
     * @throws Exception
     */
    public function testCreate()
    {
        $product = Product::create([
            'id' => 3,
            'name' => 'Новый товар',
        ]);

        // Сначала бд в памяти пустая
        $storageCntBeforeSave = count($this->provider->memoryStorage);
        $this->assertEquals(0, $storageCntBeforeSave);

        $product->save();

        // После сохранения в БД есть запись о товаре
        $storageCntAfterSave = count($this->provider->memoryStorage[Product::class]);
        $this->assertEquals(1, $storageCntAfterSave);
    }

    /**
     * @throws Exception
     */
    public function testRead()
    {
        $product = Product::create([
            'id' => 3,
            'name' => 'Новый товар',
        ]);

        $product->save();

        $readedProduct = Product::one([
            'where' => [
                ['=', 'id', 3]
            ]
        ]);

        $this->assertEquals('Новый товар', $readedProduct->name);
    }

    /**
     * @throws Exception
     */
    public function testUpdate()
    {
        $product = Product::create([
            'id' => 3,
            'name' => 'Новый товар',
        ]);

        $product->save();

        $readedProduct = Product::one([
            'where' => [
                ['=', 'id', 3]
            ]
        ]);

        $readedProduct->name = 'Измененный продукт';
        $readedProduct->save();

        $updatedProduct = Product::one([
            'where' => [
                ['=', 'id', 3]
            ]
        ]);

        $this->assertEquals('Измененный продукт', $updatedProduct->name);
    }

    /**
     * @throws Exception
     */
    public function testDelete()
    {
        $product = Product::create([
            'id' => 3,
            'name' => 'Новый товар',
        ]);

        $product->save();

        $storageCntBeforeDelete = count($this->provider->memoryStorage[Product::class]);
        $this->assertEquals(1, $storageCntBeforeDelete);

        $product->delete();

        $storageCntAfterDelete = count($this->provider->memoryStorage[Product::class]);
        $this->assertEquals(0, $storageCntAfterDelete);
    }
}