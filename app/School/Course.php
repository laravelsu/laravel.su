<?php

namespace App\School;

class Course
{
    public function __construct(
        public string $name,
        public string $description,
        public string $image,
        public string $link
    )
    {
    }
}
