<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class UserRepositoryWithCaching extends RepositoryWithCaching
{

    protected function getModelClass(): string
    {
        return User::class;
    }

    public function getAllExceptAdminsPaginator(int $perPage): LengthAwarePaginator
    {
        $keyName = $this->keyPrefix . '-AllExceptOnPage' . (request('page') ?? '1');

        return Cache::tags($this->tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
                return $this->startCondition()
                    ->where('role_id', '!=', 4)
                    ->orderBy('id')
                    ->paginate($perPage);
            });
    }

    public function getAllExceptIdPaginator(int $perPage, int $exceptId): LengthAwarePaginator
    {
        $keyName = $this->keyPrefix . '-AllExceptId' . $exceptId . 'OnPage' . (request('page') ?? '1');

        return Cache::tags($this->tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($perPage, $exceptId) {
                return $this->startCondition()
                    ->where('id', '!=', $exceptId)
                    ->orderBy('id')
                    ->paginate($perPage);
            });
    }

    public function getAllManagers(): Collection
    {
        $keyName = $this->keyPrefix . '-AllManagers';

        return cache()->remember($keyName, self::CACHE_TTL, function () {
            return $this->startCondition()
                ->where('role_id', 2)
                ->orderBy('id')
                ->get();
        });
    }

    public function getAllSeniorManagers(): Collection
    {
        $keyName = $this->keyPrefix . '-AllManagers';

        return cache()->remember($keyName, self::CACHE_TTL, function () {
            return $this->startCondition()
                ->where('role_id', 3)
                ->orderBy('id')
                ->get();
        });
    }

    public function getManagerOf(Issue $issue): ?Model
    {
        if (!isset($issue->manager_id)) {

            return null;

        } else {

            return $this->getById($issue->manager_id);
        }
    }

}
