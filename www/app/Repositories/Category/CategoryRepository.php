<?php


namespace App\Repositories\Category;


use App\Exceptions\Custom\CustomException;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryRepositoryInterface
{
    private $user;
    /**
     * @var Request
     */
    private $authUser;
    private $model;
    private $category_id;

    public function __construct(Category $model, Request $request)
    {
        $this->model = $model;
        $this->category_id = $request->category;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        return $this->model->update($id, $attributes);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }


    public function users()
    {
        return $this->find($this->category_id)->users();
    }

    public function questions()
    {
        return $this->find($this->category_id)->questions();
    }
}