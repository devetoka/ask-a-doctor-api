<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\CategoryUserRequest;
use App\Http\Response\ApiResponse;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryUserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryUserRequest $request
     * @return void
     */
    public function store(CategoryUserRequest $request)
    {
        $userCategories = $this->userRepository->attachCategories($request->categories);
        return ApiResponse::sendResponse([],
            trans('user.categories.store'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryUser  $categoryUser
     * @return Response
     */
    public function show(CategoryUser $categoryUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryUser  $categoryUser
     * @return Response
     */
    public function edit(CategoryUser $categoryUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\CategoryUser  $categoryUser
     * @return Response
     */
    public function update(Request $request, CategoryUser $categoryUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CategoryUserRequest $request
     * @return void
     */
    public function destroy(CategoryUserRequest $request)
    {
        //
        $userCategories = $this->userRepository->detachCategories($request->categories);
        return ApiResponse::sendResponse([],
            trans('user.categories.destroy'));
    }
}
