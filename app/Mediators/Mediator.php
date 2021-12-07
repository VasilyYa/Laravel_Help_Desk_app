<?php

namespace App\Mediators;

use App\Repositories\RepositoryInterface;
use App\Services\ServiceInterface;

class Mediator
{
    /** @var RepositoryInterface  */
    public RepositoryInterface $repository;

    /** @var ServiceInterface  */
    public ServiceInterface $service;

    public function __construct(RepositoryInterface $repository, ServiceInterface $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }
}
