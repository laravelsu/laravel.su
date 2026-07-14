<?php

namespace Tests\Unit\School;

use App\School\Courses;
use PHPUnit\Framework\TestCase;

class CoursesTest extends TestCase
{
    public function testIlyaChubarovIsTheFirstTeacherWithHisCourses(): void
    {
        $teacher = Courses::teachers()->first();

        $this->assertSame('Илья Чубаров', $teacher->name);
        $this->assertSame('/img/community/chubarov.jpg', $teacher->image);
        $this->assertSame(
            ['Temporal', 'Orchid', 'Livewire', 'Очереди', 'Итераторы'],
            $teacher->courses->pluck('name')->all()
        );
    }

    public function testTeacherCoursesAreNotDuplicatedInSliderItems(): void
    {
        $courses = Courses::items();

        $this->assertCount($courses->count(), $courses->pluck('link')->unique());
        $this->assertCount(1, $courses->where('name', 'Temporal'));
    }
}
