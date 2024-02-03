<?php

namespace App\Models;

use App\Helpers\RequestHandler;
use App\Storage\Db;
use PDO;

class Category
{
    public static function getCategories(array $params)
    {
        $query = '
            SELECT
                categories.id        AS id,
                categories.title     AS title,
                categories.uri       AS uri,
                COUNT(categories.id) AS goods_count
            FROM categories
                LEFT JOIN goods_categories 
                       ON goods_categories.category_id = categories.id
        ';

        if (!empty($params['search'])) {
            $search = $params['search'];
            $query .= ' WHERE categories.title LIKE :search OR categories.uri LIKE :search';
        }

        $query .= ' GROUP BY categories.id';

        $categories = Db::raw($query);

        if (!empty($search)) {
            $stringToFind = '%' . $search . '%';
            $categories->bindParam(':search', $stringToFind);
        }

        $categories->execute();

        $result = $categories->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function getCategoryById(int $id): array|false
    {
        $query = Db::raw('
            SELECT id, title, uri
            FROM categories
            WHERE categories.id = :id
        ');

        $query->bindParam(':id', $id);

        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public static function addCategory(array $requestBody): void
    {
        $query = Db::raw('
            INSERT IGNORE INTO categories (title, uri)
            VALUES (:title, :uri)
        ');

        $query->bindParam(':title', $requestBody['title']);
        $query->bindParam(':uri', $requestBody['uri']);

        $query->execute();
    }

    public static function updateCategory(int $id, array $requestBody): void
    {
        $updateFields = [];
        $values = ['id' => $id];

        foreach (['title', 'uri'] as $field) {
            if (isset($requestBody[$field])) {
                $updateFields[] = "$field = :$field";
                $values[$field] = $requestBody[$field];
            }
        }

        if (empty($updateFields)) {
            return;
        }

        $query = Db::raw('
            UPDATE categories
            SET ' . implode(', ', $updateFields) . '
            WHERE id = :id
        ');

        foreach ($values as $key => $value) {
            $query->bindParam(':' . $key, $values[$key]);
        }

        $query->execute();
    }

    public static function deleteCategory(int $id): void
    {
        Db::getPdo()->beginTransaction();

        $goodsCategoriesQuery = Db::raw('
            DELETE FROM goods_categories
            WHERE category_id = :category_id
        ');

        $goodsCategoriesQuery->bindParam(':category_id', $id, PDO::PARAM_INT);

        $goodsQuery = Db::raw('
            DELETE FROM categories
            WHERE id = :id
        ');

        $goodsQuery->bindParam(':id', $id, PDO::PARAM_INT);

        try {
            $goodsCategoriesQuery->execute();
            $goodsQuery->execute();

            Db::getPdo()->commit();
        } catch (\Exception $e) {
            Db::getPdo()->rollBack();
            RequestHandler::doResponse('error', $e->getMessage(), 500);
        }
    }
}
