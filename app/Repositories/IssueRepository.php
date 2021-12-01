<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class IssueRepository extends Repository
{

    protected function getModelClass(): string
    {
        return Issue::class;
    }

    /**
     * Все неприкрепленные заявки,
     * отсортированные в обратном порядке по дате изменения
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllNotAttachedPaginatorOrdDescByUpdated(int $perPage): LengthAwarePaginator
    {
        $tagName = $this->getModelClass() . '-NotAttachedOrderedDescByUpdatedOnPage';
        $keyName = $tagName . (request('page') ?? '1');
        Cache::tags($tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
                return $this->startCondition()
                    ->where('manager_id', null)
                    ->orderByDesc('updated_at')
                    ->paginate($perPage);
            });

        //retrieve tagged cache by key name
        return Cache::tags($tagName)->get($keyName);
    }

    /**
     * Все заявки, в которых участвует выбранный пользователь (как клиент или как менеджер),
     * отсортированные в обратном порядке по дате изменения
     *
     * @param int $perPage
     * @param User|Authenticatable|null $user
     * @return LengthAwarePaginator
     */
    public function getAllForUserPaginatorOrdDescByUpdated(int $perPage, User|Authenticatable $user = null): LengthAwarePaginator
    {
        if (!isset($user)) {
            return $this->getAllPaginatorOrdDescByUpdated($perPage);
        }

        $tagName = $this->getModelClass() . '-AllForUser' . $user->id . 'OnPage';
        $keyName = $tagName . (request('page') ?? '1');
        Cache::tags($tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($user, $perPage) {
                $query = $this->startCondition();
                if ($user->isClient()) {
                    $query = $query->where('client_id', $user->id);
                }
                if ($user->isManager()) {
                    $query = $query = Issue::query()
                        ->where('manager_id', $user->id);
                }

                return $query->orderByDesc('updated_at')->paginate($perPage);
            });

        //retrieve tagged cache by key name
        return Cache::tags($tagName)->get($keyName);
    }
}
