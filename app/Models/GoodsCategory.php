<?php

namespace App\Models;

use App\Interfaces\IDbHandler;
use PDO;

class GoodsCategory
{
    private static ?IDbHandler $db = null;

    public static function setDb(IDbHandler $db): void
    {
        self::$db = $db;
    }

    public static function updateGoodsCategories(int $goodsId, array $requestBody): void
    {
        if (empty($requestBody['category_id_old']) || empty($requestBody['category_id_current'])) {
            return;
        }

        $categoryIdOld     = (int) $requestBody['category_id_old'];
        $categoryIdCurrent = (int) $requestBody['category_id_current'];

        $query = self::$db->raw('
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
