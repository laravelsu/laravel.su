<?php

namespace Tests\Unit\Models\Scopes;

use App\Models\Post;
use App\Models\Scopes\PublishedScope;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PublishedScopeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $testUser = User::factory()->create();

        Post::factory()->create(['user_id' => $testUser->id, 'published_at' => Carbon::now()->subDay()]);
        Post::factory()->create(['user_id' => $testUser->id, 'published_at' => Carbon::now()]);
        Post::factory()->create(['user_id' => $testUser->id, 'published_at' => Carbon::now()->addDay()]);
    }

    public function testFiltersCorrectlyForPosts(): void
    {
        Post::addGlobalScope(new PublishedScope);

        $publishedPosts = Post::all();
        $this->assertCount(2, $publishedPosts);
        $this->assertTrue($publishedPosts->first()->published_at->lessThan(Carbon::now()));
    }
}
