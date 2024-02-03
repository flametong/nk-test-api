<?php

namespace App\Services;

use App\Helpers\RequestHandler;
use App\Models\Goods;
use App\Models\GoodsCategory;

class GoodsService
{
    public function getGoods(array $params): array
    {
        $goods = Goods::getGoods($params);

        return $goods;
    }

    public function postGoods(array $requestBody): void
    {
        $this->checkEmptyRequestBody($requestBody);

        Goods::addGoods($requestBody);
    }

    public function putGoods(int $id, array $requestBody): void
    {
        $this->checkEmptyRequestBody($requestBody);

        Goods::updateGoods($id, $requestBody);
        GoodsCategory::updateGoodsCategories($id, $requestBody);
    }

    public function deleteGoods(int $id): void
    {
        Goods::deleteGoods($id);
    }

    private function checkEmptyRequestBody(array $requestBody): void
    {
        if (empty($requestBody)) {
            RequestHandler::doResponse('error', 'No data provided', 400);
            die();
        }
    }
}
