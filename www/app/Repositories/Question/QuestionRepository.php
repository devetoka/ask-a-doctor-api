<?php


namespace App\Repositories\Question;


use App\Models\Question;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $model, Request $request)
    {
        parent::__construct($model, $request);
        $this->model->creating(function($model){
            $model->user_id = $this->user->id;
            $model->id = preg_replace('/\./', '', uniqid('qtn', true));
            $model->slug = Str::slug($this->request->title);
        });
        $this->model->updating(function($model){
            $model->slug = Str::slug($this->request->title);
        });
    }

    public function findBySlug($slug)
    {
       return  $this->model->whereSlug($slug)->firstOrFail();
    }

    public function storeReply($question_id, $reply)
    {
        $data = [
            'user_id' => $this->user->id,
            'id' => preg_replace('/\./', '', uniqid('rpy', true))
        ];
        return $this->find($question_id)->replies()->create(array_merge($reply, $data));
    }
}