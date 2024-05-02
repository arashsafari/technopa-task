<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $where = [], array $columns = ['*'], array $relations = []): Collection;

    public function find(int $id, array $columns = ['*'], bool $withTrashed = false): ?Model;

    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model;
}
