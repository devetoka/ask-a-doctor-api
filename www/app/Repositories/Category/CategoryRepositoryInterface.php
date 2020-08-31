<?php
namespace App\Repositories\Category;


use App\Repositories\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function users(); // get users who selected this category

    public function questions(); // get questions under this category
}