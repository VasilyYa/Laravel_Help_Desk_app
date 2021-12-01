<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class Repository implements RepositoryInterface
{
    //const CACHE_TTL = 84600; //default value
    const CACHE_TTL = 1; //TODO: change this test value

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
        $keyName = $this->getModelClass() . '-ById' . $id;
        cache()->remember($keyName,self::CACHE_TTL , function () use ($id) {
            return $this->startCondition()->where('id', $id)->first();
        });

        return cache()->get($keyName);
    }

    public function getByIdWithTrashed(int $id): ?Model
    {
        $keyName = $this->getModelClass() . '-ById' . $id;
        cache()->remember($keyName,self::CACHE_TTL , function () use ($id) {
            return $this->startCondition()->withTrashed()->where('id', $id)->first();
        });

        return cache()->get($keyName);
    }

    public function getByIdOrFail(int $id): ?Model
    {
        $keyName = $this->getModelClass() . '-ById' . $id;
        cache()->remember($keyName,self::CACHE_TTL , function () use ($id) {
            return $this->startCondition()->findOrFail($id);
        });

        return cache()->get($keyName);
    }

    public function getAll(): Collection
    {
        $keyName = $this->getModelClass() . '-All';
        cache()->remember($keyName,self::CACHE_TTL , function () {
            return $this->startCondition()->all();
        });

        return cache()->get($keyName);
    }

    public function getAllPaginator(int $perPage): LengthAwarePaginator
    {
        $tagName = $this->getModelClass() . '-AllOnPage';
        $keyName = $tagName . (request('page') ?? '1');
        Cache::tags($tagName) // tag many caches with one tag name
        ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
            return $this->startCondition()
                ->orderBy('id')
                ->paginate($perPage);
        });

        //retrieve tagged cache by key name
        return Cache::tags($tagName)->get($keyName);
    }

    public function getAllPaginatorOrdDescByUpdated(int $perPage): LengthAwarePaginator
    {
        $tagName = $this->getModelClass() . '-AllOrderedDescByUpdatedAtOnPage';
        $keyName = $tagName . (request('page') ?? '1');
        Cache::tags($tagName) // tag many caches with one tag name
        ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
            return $this->startCondition()
                ->orderByDesc('updated_at')
                ->paginate($perPage);
        });

        //retrieve tagged cache by key name
        return Cache::tags($tagName)->get($keyName);
    }

    public function resetCache(int $id = null)
    {
        //reset caches 'AllOnPage' using tag to reset many caches at once
        $tagName = $this->getModelClass() . '-AllOnPage';
        Cache::tags($tagName)->flush();

        //reset cache 'ById'
        if ($id) {
            cache()->forget($this->getModelClass() . '-ById' . $id);
        }

        //reset cache 'All'
        cache()->forget($this->getModelClass() . '-All');
    }


}
