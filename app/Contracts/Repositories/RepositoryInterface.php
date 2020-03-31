<?php

namespace App\Contracts\Repositories;

interface RepositoryInterface
{
    /**
     * Defines the associated model.
     *
     * @return string
     */
    public function model();

    /**
     * Returns the model instance
     *
     * @return mixed
     */
    public function getModel();

    /**
     * Returns the instance with the given id.
     *
     * @param  mixed $id
     *
     * @return mixed     the models instance
     */
    public function find($id);

    /**
     * Create a new model and persist it to the database
     *
     * @param  array  $attributes   new fields of the model
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update a given ID with the passed array of fields
     *
     * @param  mixed  $id       id of the object
     * @param  array   $fields   fields to update
     *
     * @return mixed
     */
    public function update($id, array $fields);

    /**
     * Returns all entries of this model in the database
     *
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * Returns the number of entries of this model in the database.
     *
     * @return int count
     */
    public function count(): int;

    /**
     * Delete entry with id
     *
     * @param  mixed    $id id of the entry
     *
     * @return bool true if deletion was successful
     */
    public function delete($id): bool;
}
