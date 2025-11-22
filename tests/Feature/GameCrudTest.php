<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_view_games_on_dashboard()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('games');
        $response->assertViewHas('totalGames');
        $response->assertViewHas('totalCategories');
        $response->assertViewHas('totalUsers');
        $response->assertViewHas('categories');
    }

    public function test_user_can_create_game()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create();

        $data = [
            'title' => 'Test Game',
            'description' => 'A test game description',
            'release_year' => 2023,
            'category_id' => $category->id,
        ];

        $response = $this->post(route('games.store'), $data);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Game added successfully!');

        $this->assertDatabaseHas('games', $data);
    }

    public function test_user_can_update_game()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create();
        $game = Game::factory()->create(['category_id' => $category->id]);

        $updatedData = [
            'title' => 'Updated Game',
            'description' => 'Updated description',
            'release_year' => 2024,
            'category_id' => $category->id,
        ];

        $response = $this->put(route('games.update', $game), $updatedData);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Game updated successfully!');

        $this->assertDatabaseHas('games', array_merge(['id' => $game->id], $updatedData));
    }

    public function test_user_can_delete_game()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create();
        $game = Game::factory()->create(['category_id' => $category->id]);

        $response = $this->delete(route('games.destroy', $game));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Game deleted successfully!');

        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    public function test_game_creation_validation()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('games.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['title', 'release_year']);
    }

    public function test_game_update_validation()
    {
        $this->actingAs($this->user);

        $category = Category::factory()->create();
        $game = Game::factory()->create(['category_id' => $category->id]);

        $response = $this->put(route('games.update', $game), ['title' => '']);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['title']);
    }

    public function test_game_belongs_to_category()
    {
        $category = Category::factory()->create();
        $game = Game::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $game->category);
        $this->assertEquals($category->id, $game->category->id);
    }
}
