<?php

namespace Tests\Feature;

use App\Item;
use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuDepthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get menu depth
     *
     * @return void
     */
    public function testGetMenuDepth()
    {
        $menu = factory(Menu::class)->create();

        $item = factory(Item::class)->create([
            'menu_id' => $menu->id
        ]);

        // child 1
        $item->addChildren([
            [
                'field' => 'value'
            ]
        ]);

        // child 2
        $child2 = $item->addChildren([
            [
                'field' => 'value'
            ]
        ]);

        // child 2's child
        $child2[0]->addChildren([
            [
                'field' => 'value'
            ]
        ]);

        $response = $this->get('/api/menus/' . $menu->id . '/depth');

        $response->assertStatus(200)->assertJson([
            'depth' => 3
        ]);
    }
}
