<?php

namespace App\Controllers;

use App\Helpers\RequestHandler;
use App\Services\GoodsService;

class GoodsController
{
    public function __construct(private GoodsService $goodsService)
    {
    }

    public function getGoods(?array $params)
    {
        $goods = $this->goodsService->getGoods($params);

        header('Content-Type: application/json');
        
        echo json_encode($goods, JSON_PRETTY_PRINT);
    }

    public function postGoods()
    {
        $requestBody = RequestHandler::getRawBody();

        $this->goodsService->postGoods($requestBody);

        RequestHandler::doResponse('success', 'Goods added successfully');
    }

    public function putGoods(int $id)
    {
        $requestBody = RequestHandler::getRawBody();

        $this->goodsService->putGoods($id, $requestBody);

        RequestHandler::doResponse('success', 'Goods updated successfully');
    }

    public function deleteGoods(int $id)
    {
        $this->goodsService->deleteGoods($id);

        RequestHandler::doResponse('success', 'Goods deleted successfully');
    }
}
