<?php

namespace App\Services;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Model;

class IssueService extends Service
{

    protected function getModelClass(): string
    {
        return Issue::class;
    }

    public function setStatusOpened(Model $model): bool
    {
        return $this->update($model, ['status_id' => 1]);
    }
    public function setStatusWaitForClientAnswer(Model $model): bool
    {
        return $this->update($model, ['status_id' => 2]);
    }
    public function setStatusWaitForManagerAnswer(Model $model): bool
    {
        return $this->update($model, ['status_id' => 3]);
    }
    public function setStatusClosed(Model $model): bool
    {
        return $this->update($model, ['status_id' => 4]);
    }
}
