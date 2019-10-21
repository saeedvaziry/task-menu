<?php

namespace Tests\Feature;

use App\Item;
use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var mixed
     */
    protected $menu;

    /**
     * Initial tests
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->menu = factory(Menu::class)->create();
    }

    /**
     * Test add menu items
     *
     * @return void
     */
    public function testAddMenuItems()
    {
        $response = $this->post('/api/menus/' . $this->menu->id . '/items', [
            [
                'field' => 'value'
            ],
            [
                'field' => 'value2'
            ]
        ]);

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'field' => 'value'
                ],
                [
                    'field' => 'value2'
                ]
            ]
        ]);
    }

    /**
     * Test get created menu items
     *
     * @return void
     */
    public function testGetMenuItems()
    {
        $items = factory(Item::class, 10)->create([
            'menu_id' => $this->menu->id
        ])->each(function ($item) {
            $item->children()->save(factory(Item::class)->make([
                'parent_item_id' => $item->id,
                'menu_id' => $this->menu->id
            ]));
        });

        $response = $this->get('/api/menus/' . $this->menu->id . '/items');

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'field' => $items[0]->field,
                    'children' => [
                        [
                            'field' => $items[0]->children[0]->field
                        ],
                        // and so on...
                    ]
                ],
                [
                    'field' => $items[1]->field,
                    'children' => [
                        [
                            'field' => $items[1]->children[0]->field
                        ],
                        // and so on...
                    ]
                ],
                // here i can repeat all 10 items :)
            ]
        ]);
    }

    /**
     * Test delete all menu items
     *
     * @return void
     */
    public function testDeleteMenuItems()
    {
        factory(Item::class, 10)->create([
            'menu_id' => $this->menu->id
        ]);

        $response = $this->delete('/api/menus/' . $this->menu->id . '/items');

        $response->assertStatus(200);
    }
}
