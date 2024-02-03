<?php

namespace Tests;

use App\Models\Goods;
use App\Storage\Db;
use PHPUnit\Framework\TestCase;

class GoodsTest extends TestCase
{
    protected function setUp(): void
    {
        Db::getInstance();
    }

    public function testGetGoodsReturnsExpectedJsonFormat()
    {
        $expectedGoodsFirst = [
            "id"              => 1,
            "goods_title"     => "Bread",
            "description"     => "Bread description",
            "price"           => "79.90",
            "category_titles" => "bakery,food",
        ];

        $actualGoodsFirst = Goods::getGoods([])[0];

        $this->assertEquals($expectedGoodsFirst, $actualGoodsFirst);
    }
}
