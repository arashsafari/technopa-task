<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SearchOrderRequest;
use App\Http\Resources\OrderCollection;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{

    public function __construct(public OrderRepositoryInterface $orderRepository)
    {
    }

    /**
     * search
     *
     * @return JsonResponse
     */
    public function search(SearchOrderRequest $request): JsonResponse
    {
        $orders = $this->orderRepository->search(
            perPage: 10,
            request: $request->all()
        );

        return apiResponse()
            ->message('orders list')
            ->data(new OrderCollection($orders))
            ->send();
    }
}
