<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class Service implements ServiceInterface
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    abstract protected function getModelClass(): string;

    protected function startCondition()
    {
        return clone $this->model;
    }


    public function create(array $data): Model
    {
        return $this->getModelClass()::create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->getModelClass()::destroy($id);
    }

    public function restore(Model $model): bool
    {
        return $model->restore();
    }
}
