<?php


namespace App\Repositories\User;


use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{

    public function profileSetting(); // gets the user's profile seetings

    public function interests(); // gets the user's  interests

    public function questions(); // gets user's questions

    public function replies(); // get user's replies

    public function categories(); // gets user's categories

    public function getUserCategoriesByUserId($id); // gets user's categories by id

    public function getUserProfileSettingByUserId($id); // get user's profile setting by id

    public function getUserInterestsByUserId($id);

    public function getUserQuestionsByUserId($id);

    public function getUserRepliesByUserId($id);

    public function attachCategories(array $categories); // adds many categories to a user

    public function detachCategories(array $categories);
 }