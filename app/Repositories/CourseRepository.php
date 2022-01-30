<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    protected $entity;

    public function __construct(Course $course)
    {
        $this->entity = $course;
    }

    public function getAllCourse()
    {
        return $this->entity
            ->with('modules.lessons.views')
            ->get();
    }

    public function getCourse(string $id)
    {
        return $this->entity
        ->with('modules.lessons')
        ->findOrFail($id);
    }
}
