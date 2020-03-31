<?php

namespace App\Contracts\Repositories;

interface EloquentRepositoryInterface extends RepositoryInterface
{
    /**
     * Returns a builder instance for the model bound to this repository.
     *
     * @return \Illuminate\Database\Eloquent\Builder builder instance
     */
    public function getBuilder();
}
