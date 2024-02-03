<?php

require_once ROOT . '/routes/Router.php';

use App\Controllers\CategoryController;
use App\Controllers\GoodsController;

use App\Helpers\DependencyContainer;

use App\Services\Api\CategoryService;
use App\Services\Api\GoodsService;

$container = DependencyContainer::getInstance($smsService);

$notificationService = $container->getNotificationService();

$goodsController    = new GoodsController(new GoodsService(), $notificationService);
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

Router::post(
    '/categories',
    function () use ($categoryController) {
        return $categoryController->postCategory();
    }
);

Router::put(
    '/categories/{id}',
    function ($id) use ($categoryController) {
        return $categoryController->putCategory($id);
    }
);

Router::delete(
    '/categories/{id}',
    function ($id) use ($categoryController) {
        return $categoryController->deleteCategory($id);
    }
);

Router::run();
