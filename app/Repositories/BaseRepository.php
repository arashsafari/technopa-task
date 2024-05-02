<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepositoryInterface;

use App\Exceptions\BadRequestException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    /**
     * @throws BadRequestException
     */
    public function __construct(public Application $app)
    {
        $this->makeModel();
    }

    abstract protected function model();

    /**
     * @throws BadRequestException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new BadRequestException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * all
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $where = [], array $columns = ['*'], array $relations = []): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query = $query->with($relations);
        }

        foreach ($where as $field => $value) {
            $query = $query->where($field, $value);
        }

        return $query->get($columns);
    }

    /**
     * find
     *
     * @param int $id
     * @param array|string[] $columns
     * @param bool $withTrashed
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*'], bool $withTrashed = false): ?Model
    {
        if ($withTrashed) {
            return $this->model->withTrashed()->where('id', $id)->first($columns);
        }

        return $this->model->find($id, $columns);
    }

    /**
     * find By
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Model|null
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }
}
