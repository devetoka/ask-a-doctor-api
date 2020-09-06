<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\Question\QuestionRequest;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Response\ApiResponse;
use App\Models\Question;
use App\Repositories\Question\QuestionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionController extends Controller
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
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
     * @param QuestionRequest $request
     * @return Response
     */
    public function store(QuestionRequest $request)
    {
        return ApiResponse::sendResponse(
            new QuestionResource($this->questionRepository->create($request->only(['title', 'description', 'category_id']))),
            trans('controller.question.store')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $question_id
     * @return Response
     */
    public function show($question_id)
    {
        //check if an id was sent or a slug
        if(str_contains($question_id, 'qtn'))
            $question = $this->questionRepository->find($question_id);
        else
            $question = $this->questionRepository->findBySlug($question_id);
        return ApiResponse::sendResponse(
            new QuestionResource($question),
            trans('controller.question.show')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionRequest $request
     * @param $question_id
     * @return void
     */
    public function update(QuestionRequest $request, $question_id)
    {
        //TODO: only creator and moderator can edit a question
        $this->questionRepository->update($question_id,
            $request->only(['title', 'description', 'category_id']));
        return ApiResponse::sendResponse(
            [],
            trans('controller.question.update')
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $question_id
     * @return void
     */
    public function destroy($question_id)
    {
        //TODO: only creator of a question can delete a question
        $this->questionRepository->delete($question_id);
        return ApiResponse::sendResponse(
            [],
            trans('controller.question.destroy')
        );
    }
}
