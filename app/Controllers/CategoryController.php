<?php

namespace App\Controllers;

use App\Services\CategoryService;

class CategoryController
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    public function getCategories(array $params = [])
    {
        $categories = $this->categoryService->getCategories();

        header('Content-Type: application/json');
        
        echo json_encode($categories, JSON_PRETTY_PRINT);
    }
}