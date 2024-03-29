<?php

namespace App\Services\Api;

use App\Helpers\RequestHandler;
use App\Interfaces\IDbHandler;
use App\Models\Goods;
use App\Models\GoodsCategory;

class GoodsService
{
    public function __construct(private IDbHandler $db)
    {
        Goods::setDb($this->db);
        GoodsCategory::setDb($this->db);
    }

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
