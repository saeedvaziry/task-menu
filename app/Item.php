<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    /**
     * Item fillable
     *
     * @var array
     */
    protected $fillable = [
        'menu_id', 'parent_item_id', 'field'
    ];

    /**
     * Get menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }

    /**
     * Get parent item
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentItem()
    {
        return $this->belongsTo('App\Item', 'parent_item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Item', 'parent_item_id');
    }

    /**
     * Add new children
     *
     * @param array $items
     * @return array
     * @throws \Exception
     */
    public function addChildren($items = [])
    {
        $addedItemsIds = [];
        try {
            DB::beginTransaction();
            foreach ($items as $item) {
                $item['menu_id'] = $this->menu_id;
                array_push($addedItemsIds, $this->children()->create($item)->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $this->children()->whereIn('id', $addedItemsIds)->get();
    }

    /**
     * Remove items children and children's children and ... :)
     *
     * @param Item|null $item
     */
    public function removeAllChildren(Item $item = null)
    {
        if (!$item) {
            $item = $this;
        }
        if ($item->children) {
            foreach ($item->children as $child) {
                $this->removeAllChildren($child);
            }
        }
        $item->children()->delete();
    }
}
