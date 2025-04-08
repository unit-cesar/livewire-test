<?php

/**
 *
 * php artisan test
 * php artisan test --filter=PostsTest
 *
 */

namespace Tests\Feature\Livewire;

use App\Livewire\Posts\Posts;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(Posts::class)
            ->set('postData.title', 'Test Post')
            ->set('postData.content', 'This is a test post content.')
            ->set('postData.is_published', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'content' => 'This is a test post content.',
            'is_published' => true,
            'user_id' => $user->id,
        ]);
    }

    public function test_edit_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        Livewire::test(Posts::class)
            ->call('edit', $post->id)
            ->set('postData.title', 'Updated Title')
            ->set('postData.content', 'Updated content.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ]);
    }

    public function test_delete_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        Livewire::test(Posts::class)
            ->call('confirmDelete', $post->id)
            ->assertSet('confirmationPostId', $post->id)
            ->call('delete')
            ->assertHasNoErrors();

        // $this->assertDatabaseMissing('posts', [
        //     'id' => $post->id,
        // ]);

        // Registro excluído via SoftDeletes
        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_unauthorized_user_cannot_edit_or_delete_post()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $post = Post::factory()->for($anotherUser)->create();

        $this->actingAs($user);

        Livewire::test(Posts::class)
            ->call('edit', $post->id)
            ->assertForbidden();

        Livewire::test(Posts::class)
            ->call('confirmDelete', $post->id)
            ->call('delete')
            ->assertForbidden();
    }
}
