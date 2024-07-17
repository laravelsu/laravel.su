<?php

namespace App\School;

use Illuminate\Support\Collection;

class Teacher
{
    /**
     * @var Collection Courses
     */
    public Collection $courses;

    public function __construct(public $name, public $image, iterable $courses = [])
    {
        $this->courses = new Collection($courses);
    }

    /**
     * @param string   $name
     * @param string   $image
     * @param iterable $courses
     *
     * @return self
     */
    public static function make(string $name, string $image, iterable $courses = []): Teacher
    {
        return new self($name, $image, $courses);
    }

    /**
     * @return $this
     */
    public function addCourse(Course $course): self
    {
        $this->courses->push($course);

        return $this;
    }
}
