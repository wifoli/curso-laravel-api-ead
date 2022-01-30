<?php

namespace App\Repositories;

use App\Models\ReplySupport;
use App\Repositories\Traits\RepositoryTrait;

class ReplySupportRepository
{
    use RepositoryTrait;

    protected $entity;

    public function __construct(ReplySupport $replySupport)
    {
        $this->entity = $replySupport;
    }

    public function createReplyToSupport(array $data)
    {
        $user = $this->getUserAuth();

        return $this->entity
            ->create([
                'description' => $data['description'],
                'user_id' => $user->id,
                'support_id' => $data['support']
            ]);
    }
}
