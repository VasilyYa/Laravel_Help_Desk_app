<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function getById(int $id): ?Model;
    public function getByIdOrFail(int $id): ?Model;
    public function getByIdWithTrashed(int $id): ?Model;

    public function getAll(): Collection;
    public function getAllOnPage(int $perPage): LengthAwarePaginator;

    public function flushTaggedCache();
}
