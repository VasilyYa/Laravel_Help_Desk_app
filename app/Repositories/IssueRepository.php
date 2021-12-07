<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        return $this->startCondition()
            ->where('manager_id', null)
            ->orderByDesc('updated_at')
            ->paginate($perPage);
    }

    public function getAllPaginatorOrdDescByUpdated(int $perPage): LengthAwarePaginator
    {
        return $this->startCondition()
            ->orderByDesc('updated_at')
            ->paginate($perPage);
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

        $query = $this->startCondition();
        if ($user->isClient()) {
            $query = $query->where('client_id', $user->id);
        }
        if ($user->isManager()) {
            $query = $query = Issue::query()
                ->where('manager_id', $user->id);
        }
        return $query->orderByDesc('updated_at')->paginate($perPage);
    }

    public function getAllInactive(int $daysOfInactivity): Collection
    {
        return $this->startCondition()->query()
            ->where('updated_at', '<=', now()->addDays(-$daysOfInactivity))
            ->get();
    }
}
