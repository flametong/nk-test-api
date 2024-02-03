<?php

namespace App\Models;

use App\Helpers\RequestHandler;
use App\Storage\Db;
use PDO;
use PDOException;

class Goods
{
    public static function getGoods(array $params): array
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

        $goods = Db::raw($query);

        if (!empty($search)) {
            $stringToFind = '%' . $search . '%';
            $goods->bindParam(':search', $stringToFind);
        }

        $goods->execute();

        $result = $goods->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getGoodsByBarcode(string $barcode): array
    {
        $query = Db::raw('
            SELECT *
            FROM goods 
            WHERE barcode = :barcode
        ');

        $query->bindParam(':barcode', $barcode);

        $query->execute();

        $goods = $query->fetchAll(PDO::FETCH_ASSOC)[0];

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
            Db::getPdo()->beginTransaction();

            $queryGoods = Db::raw('
                INSERT IGNORE INTO goods (title, inn, barcode, description, price) 
                VALUES (:title, :inn, :barcode, :description, :price)
            ');

            $queryGoods->bindParam(':title', $title);
            $queryGoods->bindParam(':inn', $inn);
            $queryGoods->bindParam(':barcode', $barcode);
            $queryGoods->bindParam(':description', $description);
            $queryGoods->bindParam(':price', $price);

            $queryGoods->execute();

            $goodsId = Db::getPdo()->lastInsertId();

            $queryCategories = Db::raw('
                INSERT IGNORE INTO goods_categories (goods_id, category_id) 
                VALUES (:goods_id, :category_id)
            ');

            $queryCategories->bindParam(':goods_id', $goodsId);
            $queryCategories->bindParam(':category_id', $categoryId);

            foreach ($categoryIds as $categoryId) {
                $queryCategories->execute();
            }

            Db::getPdo()->commit();
        } catch (PDOException $e) {
            Db::getPdo()->rollBack();
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

        $query = Db::raw('
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
        Db::getPdo()->beginTransaction();

        $goodsCategoriesQuery = Db::raw('
            DELETE FROM goods_categories
            WHERE goods_id = :goods_id
        ');

        $goodsCategoriesQuery->bindParam(':goods_id', $id, PDO::PARAM_INT);

        $goodsQuery = Db::raw('
            DELETE FROM goods
            WHERE id = :id
        ');

        $goodsQuery->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $goodsCategoriesQuery->execute();
            $goodsQuery->execute();

            Db::getPdo()->commit();
        } catch (PDOException $e) {
            Db::getPdo()->rollBack();
            RequestHandler::doResponse('error', $e->getMessage(), 500);
        }
    }
}
