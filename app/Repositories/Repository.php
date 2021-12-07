<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;


    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    abstract protected function getModelClass(): string;

    protected function startCondition()
    {
        return clone $this->model; //clone to reset the chain
    }

    public function getById(int $id): ?Model
    {
        return $this->startCondition()
            ->where('id', $id)
            ->first();
    }

    public function getByIdOrFail(int $id): ?Model
    {
        return $this->startCondition()->findOrFail($id);
    }

    public function getByIdWithTrashed(int $id): ?Model
    {
        return $this->startCondition()
            ->withTrashed()
            ->where('id', $id)
            ->first();
    }

    public function getAll(): Collection
    {
        return $this->startCondition()->all();
    }

    public function getAllPaginator(int $perPage): LengthAwarePaginator
    {
        return $this->startCondition()
            ->orderBy('id')
            ->paginate($perPage);
    }


}
