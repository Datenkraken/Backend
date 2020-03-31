<?php

namespace App\Repositories;

use App\Contracts\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

abstract class EloquentRepository extends Repository implements EloquentRepositoryInterface
{
    /**
     * Returns the Eloquent model instance that is bound to this repository.
     *
     * @return \Illuminate\Database\Eloquent\Model instance
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Returns a builder instance for the model bound to this repository.
     *
     * @return \Illuminate\Database\Eloquent\Builder builder instance
     */
    public function getBuilder()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * Returns the instance with the given id.
     *
     * @param  mixed $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null     the models instance
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find($id)
    {
        return $this->getBuilder()->findOrFail($id);
    }

    /**
     * Create a new model and persist it to the database,
     * without caring about the 'id' (or primarykey field).
     *
     * @param  array  $attributes   new fields of the model
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->getBuilder()->create($attributes);
    }

    /**
     * Update a given ID with the passed array of fields
     *
     * @param  mixed  $id       id of the object
     * @param  array  $fields   fields to update
     *
     * @return \Illuminate\Database\Eloquent\Model instance
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update($id, array $fields)
    {
        $instance = $this->getBuilder()->findOrFail($id);
        $instance->fill($fields);
        $instance->save();
        return $instance;
    }

    /**
     * Tries to update the model instance if there exists one with the
     * given attributes, otherwise it will create a new instance with
     * the given attributes.
     *
     * @param  array  $attributes
     * @param  array  $values
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->getBuilder()->updateOrCreate($attributes, $values);
    }

    /**
     * Returns all entries of this model in the database
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->getBuilder()->get();
    }

    /**
     * Returns the number of entries of this model in the database.
     *
     * @return int count
     */
    public function count(): int
    {
        return $this->getBuilder()->count();
    }

    /**
     * Delete entry with id
     *
     * @param  mixed    $id id of the entry
     *
     * @return bool true if deletion was successful
     */
    public function delete($id): bool
    {
        $instance = $this->getBuilder()->findOrFail($id);
        return $instance->delete();
    }
}
