<?php

namespace App\Contracts\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function search(int $perPage = 15, array $request = []): LengthAwarePaginator;
}
