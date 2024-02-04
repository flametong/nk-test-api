<?php

namespace App\Services\Api;

use App\Helpers\RequestHandler;
use App\Interfaces\IDbHandler;
use App\Models\Category;

class CategoryService
{
    public function __construct(private IDbHandler $db)
    {
        Category::setDb($this->db);
    }

    public function getCategories(array $params)
    {
        $categories = Category::getCategories($params);

        return $categories;
    }

    public function postCategory(array $requestBody): void
    {
        $this->checkEmptyRequestBody($requestBody);

        Category::addCategory($requestBody);
    }

    public function putCategory(int $id, array $requestBody): void
    {
        $this->checkEmptyRequestBody($requestBody);
        $this->checkEmptyCategoryById($id);

        Category::updateCategory($id, $requestBody);
    }

    public function deleteCategory(int $id): void
    {
        $this->checkEmptyCategoryById($id);

        Category::deleteCategory($id);
    }

    private function checkEmptyRequestBody(array $requestBody): void
    {
        if (empty($requestBody)) {
            RequestHandler::doResponse('error', 'No data provided', 400);
            die();
        }
    }

    private function checkEmptyCategoryById(int $id)
    {
        $category = Category::getCategoryById($id);

        if (empty($category)) {
            RequestHandler::doResponse('error', 'Category not found', 404);
            die();
        }
    }
}