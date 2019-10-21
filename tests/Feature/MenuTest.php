<?php

namespace Tests\Feature;

use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create menu
     *
     * @return void
     */
    public function testCreateMenu()
    {
        $response = $this->post('/api/menus', [
            'field' => 'value',
            'max_depth' => 5,
            'max_children' => 5,
        ]);

        $this->assertDatabaseHas('menus', [
            'field' => 'value',
            'max_depth' => 5,
            'max_children' => 5,
        ]);

        $response->assertStatus(201)->assertJson([
            'data' => [
                'field' => 'value'
            ]
        ]);
    }

    /**
     * Test get menu
     */
    public function testGetMenu()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->get('/api/menus/' . $menu->id);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'field' => $menu->field
            ]
        ]);
    }

    /**
     * Test update menu
     */
    public function testUpdateMenu()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->put('/api/menus/' . $menu->id, [
            'field' => 'new-value'
        ]);

        $this->assertDatabaseHas('menus', [
            'field' => 'new-value',
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [
                'field' => 'new-value'
            ]
        ]);
    }

    /**
     * Test delete menu
     */
    public function testDeleteMenu()
    {
        $menu = factory(Menu::class)->create();

        $response = $this->delete('/api/menus/' . $menu->id);

        $response->assertStatus(200);
    }
}
