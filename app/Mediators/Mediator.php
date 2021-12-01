<?php

namespace App\Mediators;

use App\Repositories\Repository;
use App\Services\Service;

class Mediator
{
    /** @var Repository  */
    public Repository $repository;

    /** @var Service  */
    public Service $service;

    public function __construct(Repository $repository, Service $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
}
