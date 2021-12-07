<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends Repository
{

    protected function getModelClass(): string
    {
        return User::class;
    }

    public function getAllExceptAdminsOnPage(int $perPage): LengthAwarePaginator
    {
        return $this->startCondition()
            ->where('role_id', '!=', 4)
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllExceptIdOnPage(int $perPage, int $exceptId): LengthAwarePaginator
    {
        return $this->startCondition()
            ->where('id', '!=', $exceptId)
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function getAllManagers(): Collection
    {
        return $this->startCondition()
            ->where('role_id', 2)
            ->orderBy('id')
            ->get();
    }

    public function getAllSeniorManagers(): Collection
    {
        return $this->startCondition()
            ->where('role_id', 3)
            ->orderBy('id')
            ->get();
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
