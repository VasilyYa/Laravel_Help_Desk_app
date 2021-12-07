<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class RepositoryWithCaching implements RepositoryInterface
{
    const CACHE_TTL = 60; //(sec.) default value
    protected string $tagName;
    protected string $keyPrefix;

    protected Model $model;


    public function __construct()
    {
        $this->model = app($this->getModelClass());

        $this->tagName = $this->getModelClass() . '-TAG'; // tag of entity
        $this->keyPrefix = $this->getModelClass();
    }

    abstract protected function getModelClass(): string;

    protected function startCondition()
    {
        return clone $this->model; //clone to reset the chain
    }

    public function getById(int $id): ?Model
    {
        $keyName = $this->keyPrefix . '-ById' . $id;

        return Cache::tags($this->tagName) // tag the cache
        ->remember($keyName, self::CACHE_TTL, function () use ($id) {
            return $this->startCondition()
                ->where('id', $id)
                ->first();
        });
    }

    public function getByIdOrFail(int $id): ?Model
    {
        $model = $this->getById($id);
        if (!is_null($model)) {
            return $model;
        } else {
            throw (new ModelNotFoundException)->setModel(get_class($model), $id);
        }
    }

    public function getByIdWithTrashed(int $id): ?Model
    {
        $keyName = $this->keyPrefix . '-ById' . $id . 'WithTrashed';

        return Cache::tags($this->tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($id) {
                return $this->startCondition()
                    ->withTrashed()
                    ->where('id', $id)
                    ->first();
            });
    }

    public function getAll(): Collection
    {
        $keyName = $this->keyPrefix . '-All';

        return Cache::tags($this->tagName)
            ->remember($keyName, self::CACHE_TTL, function () {
                return $this->startCondition()
                    ->all()
                    ->sortBy('id');
            });
    }

    public function getAllPaginator(int $perPage): LengthAwarePaginator
    {
        $keyName = $this->keyPrefix . '-AllOnPage' . (request('page') ?? '1');

        return Cache::tags($this->tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
                return $this->startCondition()
                    ->orderBy('id')
                    ->paginate($perPage);
            });
    }

    public function flushTaggedCache()
    {
        Cache::tags($this->tagName)->flush();
    }

}
