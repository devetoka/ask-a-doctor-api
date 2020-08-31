<?php


namespace App\Repositories;


interface BaseRepositoryInterface
{
    public function all(); // gets all entities of the model

    public function find($id);//finds entity by id

    public function create(array $attributes); // creates a new entity

    public function  update($id, array $attributes);// updates an entity

    public function delete ($id); //deletes an entity
}