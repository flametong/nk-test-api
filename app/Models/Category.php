<?php

namespace App\Models;

use App\Storage\Db;
use PDO;

class Category
{
    public static function getCategories()
    {
        $categories = Db::getPdo()->query('
            SELECT
                categories.id        AS id,
                categories.title     AS title,
                categories.uri       AS uri,
                COUNT(categories.id) AS goods_count
            FROM categories
                LEFT JOIN goods_categories 
                       ON goods_categories.category_id = categories.id
            GROUP BY categories.id
        ')->fetchAll(PDO::FETCH_ASSOC);

        return $categories;
    }
}
