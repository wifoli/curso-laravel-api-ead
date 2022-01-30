<?php

namespace App\Repositories;

use App\Models\Support;
use App\Repositories\Traits\RepositoryTrait;

class SupportRepository
{
    use RepositoryTrait;

    protected $entity;

    public function __construct(Support $support)
    {
        $this->entity = $support;
    }

    public function getMySupports(array $filters = [])
    {
        $filters['user'] = true;

        return $this->getSupports($filters);
    }

    public function getSupports(array $filters = [])
    {
        return $this->entity
            ->where(function ($query) use ($filters) {
                if (isset($filters['lesson']))
                    $query->where('lesson_id', $filters['lesson']);

                if (isset($filters['status']))
                    $query->where('status', $filters['status']);

                if (isset($filters['filter'])) {
                    $filter = $filters['filter'];
                    $query->where('description', 'LIKE', "%{$filter}%");
                }

                if (isset($filters['user'])) {
                    $user = $this->getUserAuth();
                    $query->where('user_id', $user->id);
                }
            })
            ->with('replies')
            ->orderBy('updated_at')
            ->get();
    }

    public function createNewSupport(array $data): Support
    {
        $support = $this->getUserAuth()
            ->supports()
            ->create([
                'lesson_id' => $data['lesson'],
                'status' => $data['status'],
                'description' => $data['description'],
            ]);

        return $support;
    }
}
