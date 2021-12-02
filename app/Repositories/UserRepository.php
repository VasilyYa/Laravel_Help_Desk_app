<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository
{

    protected function getModelClass(): string
    {
        return User::class;
    }

    public function getAllManagers(): Collection
    {
        $keyName = $this->getModelClass() . '-AllManagers';
        cache()->remember($keyName,self::CACHE_TTL , function () {
            return $this->startCondition()
                ->where('role_id', 2)
                ->orderBy('id')
                ->get();
        });

        return cache()->get($keyName);
    }

    public function getAllSeniorManagers(): Collection
    {
        $keyName = $this->getModelClass() . '-AllSeniorManagers';
        cache()->remember($keyName,self::CACHE_TTL , function () {
            return $this->startCondition()
                ->where('role_id', 3)
                ->orderBy('id')
                ->get();
        });

        return cache()->get($keyName);
    }

    public function getManagerOf(Issue $issue): ?Model
    {
        if(!isset($issue->manager_id)) {

            return null;

        } else {

            return $this->getById($issue->manager_id);
        }
    }


}
