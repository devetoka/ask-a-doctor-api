<?php


namespace App\Repositories\User;


use App\Exceptions\Custom\CustomException;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    private $user;
    /**
     * @var Request
     */
    private $authUser;
    private $model;

    public function __construct(User $user, Request $authUser)
    {
        $this->model = $user;
        $this->user = $authUser->user('api');
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

    public function categories()
    {
        return $this->user->categories();
    }
    public function getUserCategoriesByUserId($id)
    {
        return $this->find($id)->categories();
    }

    public function profileSetting()
    {
        return $this->user->profileSetting();
    }
    public function getUserProfileSettingByUserId($id)
    {
        return $this->find($id)->profileSetting();
    }

    public function interests()
    {
        return $this->user->interests();
    }

    public function getUserInterestsByUserId($id)
    {
        return $this->find($id)->interests();
    }

    public function questions()
    {
        return $this->user->questions();
    }

    public function getUserQuestionsByUserId($id)
    {
        return $this->find($id)->questions();
    }

    public function getUserRepliesByUserId($id)
    {
        return $this->find($id)->replies();
    }

    public function replies()
    {
        return $this->user->replies();
    }

    public function attachCategories(array $categories)
    {
        try{
            return $this->user->categories()->attach($categories);
        }catch(\Exception $exception){
            throw new CustomException($exception->getMessage());
        }
    }

    public function detachCategories(array $categories)
    {
        try{
            return $this->user->categories()->detach($categories);
        }catch(\Exception $exception){
            throw new CustomException($exception->getMessage());
        }
    }
}