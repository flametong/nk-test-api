<?php

namespace App\Models;

use App\Storage\Db;
use PDO;

class GoodsCategory
{
    public function addGoodsCategories(array $goodsCategories): void
    {
        $goodsId = (new Goods())->getGoodsByBarcode($goodsCategories['barcode'])['id'];

        $categoryIds = $goodsCategories['category_ids'];

        $query = Db::raw('
            INSERT IGNORE INTO goods_categories (goods_id, category_id) 
            VALUES (:goods_id, :category_id)
        ');

        $query->bindParam(':goods_id', $goodsId);
        $query->bindParam(':category_id', $categoryId);

        foreach ($categoryIds as $categoryId) {
            $query->execute();
        }
    }

    public static function updateGoodsCategories(int $goodsId, array $requestBody): void
    {
        if (empty($requestBody['category_id_old']) || empty($requestBody['category_id_current'])) {
            return;
        }

        $categoryIdOld     = (int) $requestBody['category_id_old'];
        $categoryIdCurrent = (int) $requestBody['category_id_current'];

        $query = Db::raw('
            UPDATE goods_categories
            SET category_id = :category_id_current
            WHERE goods_id = :goods_id
              AND category_id = :category_id_old
        ');

        $query->bindParam(':category_id_current', $categoryIdCurrent, PDO::PARAM_INT);
        $query->bindParam(':category_id_old', $categoryIdOld, PDO::PARAM_INT);
        $query->bindParam(':goods_id', $goodsId, PDO::PARAM_INT);

        $query->execute();
    }
}
