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
    }

}