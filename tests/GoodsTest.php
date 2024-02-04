<?php

namespace Tests;

use App\Models\Goods;
use App\Storage\Db;
use PHPUnit\Framework\TestCase;

class GoodsTest extends TestCase
{
    private $dbHandlerMock;

    protected function setUp(): void
    {
        $config = [
            'dsn'      => 'mysql:host=localhost;dbname=nk_test;charset=utf8',
            'user'     => 'root',
            'password' => '',
        ];

        $this->dbHandlerMock = new Db($config);

        Goods::setDb($this->dbHandlerMock);
    }

    public function testGoodsGetting()
    {
        $expectedGoods = [
            "id"              => 1,
            "goods_title"     => "Bread",
            "description"     => "Bread description",
            "price"           => "79.90",
            "category_titles" => "bakery,food",
        ];

        $actualGoods = Goods::getGoods()[0];

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testGoodsGettingById()
    {
        $expectedGoods = [
            "id"              => 4,
            "goods_title"     => "Yogurt",
            "description"     => "Yogurt description",
            "price"           => "39.90",
            "category_titles" => "dairy,food",
        ];

        $actualGoods = Goods::getGoodsById(4);

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testGoodsSearching()
    {
        $expectedGoods = [
            "id"              => 13,
            "goods_title"     => "Yogurt",
            "description"     => "Yogurt description",
            "price"           => "39.90",
            "category_titles" => "dairy,food",
        ];

        $actualGoods = Goods::getGoods(['search' => 'yogurt'])[0];

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testGoodsAdding()
    {
        $addingGoods = [
            "title"        => "Milk",
            "inn"          => "8223013901",
            "barcode"      => "2113324701011",
            "description"  => "Milk description",
            "price"        => 69.90,
            "category_ids" => ["1", "3"]
        ];

        Goods::addGoods($addingGoods);

        $actualGoods = end(Goods::getGoods());

        $expectedGoods = [
            "id"              => 23,
            "goods_title"     => "Milk",
            "description"     => "Milk description",
            "price"           => "69.90",
            "category_titles" => "dairy,food",
        ];

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testGoodsUpdating()
    {
        $addingGoods = [
            "description"  => "Bun description extended",
            "price"        => 59.90,
        ];

        Goods::updateGoods(16, $addingGoods);

        $actualGoodsLast = Goods::getGoodsById(16);

        $expectedGoodsLast = [
            "id"              => 16,
            "goods_title"     => "Bun",
            "description"     => "Bun description extended",
            "price"           => "59.90",
            "category_titles" => "bakery,food",
        ];

        $this->assertEquals($expectedGoodsLast, $actualGoodsLast);
    }

    public function testGoodsDeleting()
    {
        Goods::deleteGoods(19);
        $actualGoods = Goods::getGoodsById(19);

        $this->assertEquals(false, $actualGoods);
    }
}
