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

    public function resetStatusToDefault(Issue $model): bool
    {
        return $this->setStatusOpened($model);
    }
    public function setStatusOpened(Issue $model): bool
    {
        return $this->update($model, ['status_id' => 1]);
    }
    public function setStatusWaitForClientAnswer(Issue $model): bool
    {
        return $this->update($model, ['status_id' => 2]);
    }
    public function setStatusWaitForManagerAnswer(Issue $model): bool
    {
        return $this->update($model, ['status_id' => 3]);
    }
    public function setStatusClosed(Issue $model): bool
    {
        return $this->update($model, ['status_id' => 4]);
    }

}
