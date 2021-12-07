<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends Repository
{

    protected function getModelClass(): string
    {
        return Role::class;
    }

}
