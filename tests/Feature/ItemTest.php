<?php

namespace Tests\Feature;

use App\Item;
use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var mixed
     */
    protected $menu;

    protected $item;

    /**
     * Initial tests
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->menu = factory(Menu::class)->create();

        $this->item = factory(Item::class)->create([
            'menu_id' => $this->menu->id
        ]);
    }

    /**
     * Test add menu items
     *
     * @return void
     */
    public function testAddItemChildren()
    {
        $response = $this->post('/api/items/' . $this->item->id . '/children', [
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
    public function testGetItemChildren()
    {
        $this->item->addChildren([
            [
                'field' => 'value',
            ],
            [
                'field' => 'value2'
            ]
        ]);

        $response = $this->get('/api/items/' . $this->item->id . '/children');

        $response->assertStatus(200)->assertJson([
            'data' => [
                [
                    'field' => 'value'
                ],
                [
                    'field' => 'value2'
                ],
            ]
        ]);
    }

    /**
     * Test delete all menu items
     *
     * @return void
     */
    public function testDeleteItemsChildren()
    {
        $this->item->addChildren([
            [
                'field' => 'value',
            ],
            [
                'field' => 'value2'
            ]
        ]);

        $response = $this->delete('/api/items/' . $this->item->id . '/children');

        $response->assertStatus(200);
    }
}
