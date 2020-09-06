<?php


namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseRepository implements BaseRepositoryInterface
{

    protected $model;
    /**
     * @var Request
     */
    protected $request;
    protected $user;

    public function __construct(Model $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
        $this->user = $request->user('api');
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findorFail($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        return $this->find($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

}