<?php

namespace App\Controllers;

use App\Helpers\RequestHandler;
use App\Services\Api\GoodsService;
use App\Services\Notifications\NotificationService;

class GoodsController
{
    public function __construct(
        private GoodsService        $goodsService,
        private NotificationService $notificationService
    ) {
    }

    public function getGoods(array $params = [])
    {
        $goods = $this->goodsService->getGoods($params);

        header('Content-Type: application/json');

        echo json_encode($goods, JSON_PRETTY_PRINT);
    }

    public function postGoods()
    {
        $requestBody = RequestHandler::getRawBody();

        $this->goodsService->postGoods($requestBody);

        $this->notificationService->sendNotification('123456789', 'Goods added successfully');

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

        $this->notificationService->sendNotification('123456789', 'Goods deleted successfully');

        RequestHandler::doResponse('success', 'Goods deleted successfully');
    }
}
