<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryResourceCollection;
use App\Http\Response\ApiResponse;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;


    public function __construct(
        CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = $this->categoryRepository->all();
        $categories = new CategoryResourceCollection($categories);
        return ApiResponse::sendResponse($categories,
            trans('controllers.category.index'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return void
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryRepository
            ->create($request->only(['name', 'description']));
        $category = new CategoryResource($category);
        return ApiResponse::sendResponse($category,
            trans('controllers.category,store'));
    }

    /**
     * Display the specified resource.
     *
     * @param $category_id
     * @return void
     */
    public function show($category_id)
    {
        $category = $this->categoryRepository->find($category_id);
        $category = new CategoryResource($category);
        return ApiResponse::sendResponse($category,
            trans('controllers.category.show'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Category  $category
     * @return Response
     */
    public function update(CategoryRequest $request, $category_id)
    {
        $this->categoryRepository->update($category_id, $request->only(['name', 'description']));
        return ApiResponse::sendResponse([],
            trans('controllers.category.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $category_id
     * @return void
     * @throws AuthorizationException
     */
    public function destroy($category_id)
    {
        if(request()->user('api')->role  !== 'admin')
            throw new AuthorizationException();
        return ApiResponse::sendResponse($this->categoryRepository->delete($category_id),
            trans('controllers.category.destroy'));
    }
}
