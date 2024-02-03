<?php

namespace App\Controllers;

use App\Helpers\RequestHandler;
use App\Services\CategoryService;

class CategoryController
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    public function getCategories(array $params = [])
    {
        $categories = $this->categoryService->getCategories($params);

        header('Content-Type: application/json');
        
        echo json_encode($categories, JSON_PRETTY_PRINT);
    }

    public function postCategory()
    {
        $requestBody = RequestHandler::getRawBody();

        $this->categoryService->postCategory($requestBody);

        RequestHandler::doResponse('success', 'Categories added successfully');
    }

    public function putCategory(int $id)
    {
        $requestBody = RequestHandler::getRawBody();

        $this->categoryService->putCategory($id, $requestBody);

        RequestHandler::doResponse('success', 'Categories updated successfully');
    }

    public function deleteCategory(int $id)
    {
        $this->categoryService->deleteCategory($id);

        RequestHandler::doResponse('success', 'Categories deleted successfully');
    }
}