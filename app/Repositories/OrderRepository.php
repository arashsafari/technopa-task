<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected function model(): string
    {
        return Order::class;
    }

    /**
     * search
     *
     * @param int $perPage
     * @param array $query
     * @return LengthAwarePaginator
     */
    public function search(int $perPage = 15, array $request = []): LengthAwarePaginator
    {
        $dbQuery = $this->model->newQuery();

        $request = collect($request);

        $dbQuery->when($request->get('status'),  function (Builder $query) use ($request) {
            $status = $request->get('status');
            $query->whereStatus($status);
        });

        $dbQuery->when(!is_null($request->get('min')), function (Builder $query) use ($request) {
            $min = $request->get('min');
            $query->where('amount', '>=', $min);
        });

        $dbQuery->when(!is_null($request->get('max')), function (Builder $query) use ($request) {
            $max = $request->get('max');
            $query->where('amount', '<=', $max);
        });

        $dbQuery->when($request->get('national_code') or $request->get('phone_number'), function (Builder $query) use ($request) {
            $nationalCode = $request->get('national_code');
            $phoneNumber = $request->get('phone_number');

            $query->whereHas('user', function (Builder $userQuery) use ($nationalCode, $phoneNumber) {
                $userQuery->when($nationalCode,  function (Builder $userQuery) use ($nationalCode) {
                    $userQuery->where('national_code', 'LIKE', "%$nationalCode%");
                });
                $userQuery->when($phoneNumber,  function (Builder $userQuery) use ($phoneNumber) {
                    $userQuery->where('phone_number', 'LIKE', "%$phoneNumber%");
                });
            });
        });

        return $dbQuery->paginate($perPage);
    }
}
