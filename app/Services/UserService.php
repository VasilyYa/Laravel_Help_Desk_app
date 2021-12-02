<?php

namespace App\Services;

use App\Models\User;

class UserService extends Service
{

    protected function getModelClass(): string
    {
        return User::class;
    }

}
