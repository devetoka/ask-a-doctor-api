<?php


namespace App\Repositories\Reply;


use App\Repositories\BaseRepositoryInterface;

interface ReplyRepositoryInterface extends BaseRepositoryInterface
{
    public function createReply($reply_id, array $attributes);
}