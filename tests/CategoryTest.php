<?php

namespace Tests;

use App\Models\Category;
use App\Storage\Db;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
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

        Category::setDb($this->dbHandlerMock);
    }

    public function testCategoryGetting()
    {
        $expectedGoods = [
            "id"          => 1,
            "title"       => "dairy",
            "uri"         => "dairy",
            "goods_count" => 2
        ];

        $actualGoods = Category::getCategories()[0];

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testCategoryGettingById()
    {
        $expectedGoods = [
            "id"          => 3,
            "title"       => "food",
            "uri"         => "food",
            "goods_count" => 4,
        ];

        $actualGoods = Category::getCategoryById(3);

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testCategorySearching()
    {
        $expectedGoods = [
            "id"          => 3,
            "title"       => "food",
            "uri"         => "food",
            "goods_count" => 4,
        ];

        $actualGoods = Category::getCategories(['search' => 'food'])[0];

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testCategoryAdding()
    {
        $addingGoods = [
            "title" => "clothes",
            "uri"   => "clothes"
        ];

        Category::addCategory($addingGoods);

        $actualGoods = end(Category::getCategories());

        $expectedGoods = [
            "id"          => 7,
            "title"       => "clothes",
            "uri"         => "clothes",
            "goods_count" => 1,
        ];

        $this->assertEquals($expectedGoods, $actualGoods);
    }

    public function testCategoryUpdating()
    {
        $addingGoods = [
            "title" => "technique"
        ];

        Category::updateCategory(6, $addingGoods);

        $actualGoodsLast = Category::getCategoryById(6);

        $expectedGoodsLast = [
            "id"          => 6,
            "title"       => "technique",
            "uri"         => "tech",
            "goods_count" => 2,
        ];

        $this->assertEquals($expectedGoodsLast, $actualGoodsLast);
    }

    public function testGoodsDeleting()
    {
        Category::deleteCategory(4);
        $actualGoods = Category::getCategoryById(4);

        $this->assertEquals(false, $actualGoods);
    }
}
