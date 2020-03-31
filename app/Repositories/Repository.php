<?php

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Foundation\Application;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var mixed
     */
    protected $model;

    /**
     * Repository constructor.
     *
     * @param \Illuminate\Foundation\Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
        $this->initializeModel($this->model());
    }

    /**
     * Returns the models class identifier.
     * Used for creating an instance of the given class.
     *
     * @return string The models classpath
     */
    abstract public function model();

    /**
     * Returns the model instance.
     *
     * @return mixed instance
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Initializes the given model and returns an instance.
     *
     * @param  mixed $model The model class identifier
     *
     * @return mixed        The model instance
     */
    protected function initializeModel($model)
    {
        return $this->model = $this->app->make($model);
    }
}
