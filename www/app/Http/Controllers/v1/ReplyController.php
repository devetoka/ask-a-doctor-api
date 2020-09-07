<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests\Reply\ReplyRequest;
use App\Http\Resources\Reply\ReplyResource;
use App\Http\Response\ApiResponse;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\Reply\ReplyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReplyController extends Controller
{
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;
    private $replyRepository;

    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        ReplyRepositoryInterface $replyRepository
    )
    {
        $this->replyRepository = $replyRepository;
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
     * @param ReplyRequest $request
     * @return void
     */
    public function store(ReplyRequest $request, $reply_id)
    {
        return ApiResponse::sendResponse(
            $this->replyRepository->createReply($reply_id, $request->only(['content'])),
            trans('controllers.reply.replyToQuestion')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReplyRequest $request
     * @param $question_id
     * @return void
     */
    public function replyToQuestion(ReplyRequest $request, $question_id)
    {
        return ApiResponse::sendResponse(
            $this->questionRepository->storeReply($question_id, $request->only(['content'])),
            trans('controllers.reply.replyToQuestion')
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $reply_id
     * @return void
     */
    public function show($reply_id)
    {
        $reply = $this->replyRepository->find($reply_id);
        return ApiResponse::sendResponse(
            new ReplyResource($reply),
            trans('controller.reply.show')
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ReplyRequest $request
     * @param $reply_id
     * @return void
     */
    public function update(ReplyRequest $request, $reply_id)
    {
        //TODO: only creator and moderator can edit a question
        $this->replyRepository->update($reply_id,
            $request->only(['content']));
        return ApiResponse::sendResponse(
            [],
            trans('controller.reply.update')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $reply_id
     * @return void
     */
    public function destroy($reply_id)
    {
        //TODO: only creator of a reply can delete this reply
        $this->replyRepository->delete($reply_id);
        return ApiResponse::sendResponse(
            [],
            trans('controller.reply.destroy')
        );
    }
}
