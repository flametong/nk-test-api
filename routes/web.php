<?php

require_once ROOT . '/routes/Router.php';

use App\Controllers\CategoryController;
use App\Controllers\GoodsController;

use App\Services\CategoryService;
use App\Services\GoodsService;

$goodsController = new GoodsController(new GoodsService());

$categoryController = new CategoryController(new CategoryService());

Router::get(
    '/goods',
    function ($params) use ($goodsController) {
        return $goodsController->getGoods($params);
    }
);

Router::post(
    '/goods',
    function () use ($goodsController) {
        return $goodsController->postGoods();
    }
);

Router::put(
    '/goods/{id}',
    function ($id) use ($goodsController) {
        return $goodsController->putGoods($id);
    }
);

Router::delete(
    '/goods/{id}',
    function ($id) use ($goodsController) {
        return $goodsController->deleteGoods($id);
    }
);

Router::get(
    '/categories',
    function ($params) use ($categoryController) {
        return $categoryController->getCategories($params);
    }
);

Router::run();
