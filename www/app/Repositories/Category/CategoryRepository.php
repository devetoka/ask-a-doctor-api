<?php


namespace App\Repositories\Category;


use App\Exceptions\Custom\CustomException;
use App\Models\Category;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{


    public function __construct(Category $model, Request $request)
    {
        parent::__construct($model, $request);

    }



    public function users()
    {
        return $this->find($this->request->category_id)->users();
    }

    public function questions()
    {
        return $this->find($this->request->category_id)->questions();
    }
}