<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class IssueRepositoryWithCaching extends RepositoryWithCaching
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
        $keyName = $this->keyPrefix . '-NotAttachedOrderedDescByUpdatedOnPage' . (request('page') ?? '1');

        return Cache::tags($this->tagName)
            ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
                return $this->startCondition()
                    ->where('manager_id', null)
                    ->orderByDesc('updated_at')
                    ->paginate($perPage);
            });
    }

    public function getAllPaginatorOrdDescByUpdated(int $perPage): LengthAwarePaginator
    {
        $keyName = $this->keyPrefix . '-AllOrderedDescByUpdatedAtOnPage' . (request('page') ?? '1');

        return Cache::tags($this->tagName)
        ->remember($keyName, self::CACHE_TTL, function () use ($perPage) {
            return $this->startCondition()
                ->orderByDesc('updated_at')
                ->paginate($perPage);
        });
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

        $keyName = $this->keyPrefix . '-AllForUser' . $user->id . 'OnPage'. (request('page') ?? '1');
        return Cache::tags($this->tagName)
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
    }

    public function getAllInactive(int $daysOfInactivity): Collection
    {
        return $this->startCondition()->query()
            ->where('updated_at','<=', now()->addDays(-$daysOfInactivity))
            ->get();
    }
}
