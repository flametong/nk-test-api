<?php

namespace App\Models;

use App\Helpers\RequestHandler;
use App\Interfaces\IDbHandler;
use PDO;
use PDOException;

class Goods
{
    private static ?IDbHandler $db = null;

    public static function setDb(IDbHandler $db): void
    {
        self::$db = $db;
    }

    public static function getGoods(array $params = []): array
    {
        $query = '
            SELECT
                goods.id                        AS id,
                goods.title                     AS goods_title,
                goods.description               AS description,
                goods.price                     AS price,
                GROUP_CONCAT(categories.title)  AS category_titles
            FROM goods
                LEFT JOIN goods_categories 
                       ON goods_categories.goods_id = goods.id
                LEFT JOIN categories
                       ON categories.id = goods_categories.category_id
        ';

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query .= ' WHERE goods.title LIKE :search OR goods.description LIKE :search';
        }

        $query .= ' GROUP BY goods.id';

        $goods = self::$db->raw($query);

        if (!empty($search)) {
            $stringToFind = '%' . $search . '%';
            $goods->bindParam(':search', $stringToFind);
        }

        $goods->execute();

        $result = $goods->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getGoodsById(int $id): array|false
    {
        $query = self::$db->raw('
            SELECT
                goods.id                        AS id,
                goods.title                     AS goods_title,
                goods.description               AS description,
                goods.price                     AS price,
                GROUP_CONCAT(categories.title)  AS category_titles
            FROM goods
                LEFT JOIN goods_categories 
                       ON goods_categories.goods_id = goods.id
                LEFT JOIN categories
                       ON categories.id = goods_categories.category_id
            WHERE goods.id = :id
            GROUP BY goods.id
        ');

        $query->bindParam(':id', $id);

        $query->execute();

        $goods = $query->fetch(PDO::FETCH_ASSOC);

        return $goods;
    }

    public static function addGoods(array $goods): void
    {
        $title       = $goods['title'];
        $inn         = $goods['inn'];
        $barcode     = $goods['barcode'];
        $description = $goods['description'];
        $price       = (float) $goods['price'];
        $categoryIds = $goods['category_ids'];

        try {
            self::$db->getPdo()->beginTransaction();

            $queryGoods = self::$db->raw('
                INSERT IGNORE INTO goods (title, inn, barcode, description, price) 
                VALUES (:title, :inn, :barcode, :description, :price)
            ');

            $queryGoods->bindParam(':title', $title);
            $queryGoods->bindParam(':inn', $inn);
            $queryGoods->bindParam(':barcode', $barcode);
            $queryGoods->bindParam(':description', $description);
            $queryGoods->bindParam(':price', $price);

            $queryGoods->execute();

            $goodsId = self::$db->getPdo()->lastInsertId();

            $queryCategories = self::$db->raw('
                INSERT IGNORE INTO goods_categories (goods_id, category_id) 
                VALUES (:goods_id, :category_id)
            ');

            $queryCategories->bindParam(':goods_id', $goodsId);

            foreach ($categoryIds as $categoryId) {
                $queryCategories->bindParam(':category_id', $categoryId);
                $queryCategories->execute();
            }

            self::$db->getPdo()->commit();
        } catch (PDOException $e) {
            self::$db->getPdo()->rollBack();
            RequestHandler::doResponse('error', $e->getMessage(), 500);
        }
    }

    public static function updateGoods(int $id, array $requestBody): void
    {
        $updateFields = [];
        $values = ['id' => $id];

        foreach (['title', 'description', 'price'] as $field) {
            if (isset($requestBody[$field])) {
                $updateFields[] = "$field = :$field";
                $values[$field] = $requestBody[$field];
            }
        }

        if (empty($updateFields)) {
            return;
        }

        $query = self::$db->raw('
            UPDATE goods 
            SET ' . implode(', ', $updateFields) . '
            WHERE id = :id
        ');

        foreach ($values as $key => $value) {
            $query->bindParam(':' . $key, $values[$key]);
        }

        $query->execute();
    }

    public static function deleteGoods(int $id): void
    {
        self::$db->getPdo()->beginTransaction();

        $goodsCategoriesQuery = self::$db->raw('
            DELETE FROM goods_categories
            WHERE goods_id = :goods_id
        ');

        $goodsCategoriesQuery->bindParam(':goods_id', $id, PDO::PARAM_INT);

        $goodsQuery = self::$db->raw('
            DELETE FROM goods
            WHERE id = :id
        ');

        $goodsQuery->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $goodsCategoriesQuery->execute();
            $goodsQuery->execute();

            self::$db->getPdo()->commit();
        } catch (PDOException $e) {
            self::$db->getPdo()->rollBack();
            RequestHandler::doResponse('error', $e->getMessage(), 500);
        }
    }
}
