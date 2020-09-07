<?php


namespace App\Repositories\Question;


use App\Repositories\BaseRepositoryInterface;

interface QuestionRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug($slug);

    public function storeReply($question_id, $reply);

}