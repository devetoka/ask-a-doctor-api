<?php


namespace App\Repositories\Reply;


use App\Models\Reply;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReplyRepository extends BaseRepository implements ReplyRepositoryInterface
{
    public function __construct(Reply $model, Request $request)
    {
        parent::__construct($model, $request);
        $this->model->creating(function($model){
            $model->user_id = $this->user->id;
            $model->id = preg_replace('/\./', '', uniqid('rpy', true));
        });

    }


    public function createReply($reply_id, array $attributes)
    {
        return $this->find($reply_id)->replies()->create($attributes);
    }
}