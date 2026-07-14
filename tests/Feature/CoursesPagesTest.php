<?php

namespace Tests\Feature;

use Tests\TestCase;

class CoursesPagesTest extends TestCase
{
    public function testVideoPageContainsCatalogWithoutTeachers(): void
    {
        $this->get('/video')
            ->assertOk()
            ->assertSee('Улучшите навыки смотря видео')
            ->assertSee('Документация')
            ->assertSee(route('courses'), false)
            ->assertDontSee('Илья Чубаров');
    }

    public function testCoursesPageContainsTeachersWithoutVideoCatalog(): void
    {
        $this->get('/courses')
            ->assertOk()
            ->assertSee('Русскоязычные авторские курсы')
            ->assertSee('Илья Чубаров')
            ->assertDontSee('Смотреть видео')
            ->assertDontSee('В подборках')
            ->assertDontSee('Твой путь к освоению мощного фреймворка');
    }
}
