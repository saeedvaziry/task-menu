<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    /**
     * Menu fillable
     *
     * @var array
     */
    protected $fillable = [
        'field', 'max_depth', 'max_children'
    ];

    /**
     * Menu items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    /**
     * Menu items
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rootItems()
    {
        return $this->hasMany('App\Item')->whereNull('parent_item_id');
    }

    /**
     * Add new items
     *
     * @param array $items
     * @return array
     * @throws \Exception
     */
    public function addItems($items = [])
    {
        $addedItemsIds = [];
        try {
            DB::beginTransaction();
            foreach ($items as $item) {
                array_push($addedItemsIds, $this->items()->create($item)->id);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $this->items()->whereIn('id', $addedItemsIds)->get();
    }

    /**
     * @throws \Exception
     */
    public function deleteMenu()
    {
        try {
            DB::beginTransaction();
            $this->items()->delete(); // delete all items
            $this->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param $layer
     */
    public function getItemsInLayer($layer)
    {
        //
    }

    /**
     * @param $layer
     */
    public function deleteItemsInLayer($layer)
    {
        //
    }

    /**
     * @return mixed
     */
    public function getDepthAttribute()
    {
        return $this->depth($this->rootItems);
    }

    /**
     * @param array $items
     * @return mixed
     */
    private function depth($items = [])
    {
        $depth = [0];
        foreach ($items as $key => $item) {
            if ($item->children) {
                $depth[$key] = $this->depth($item->children) + 1;
            }
        }

        return max($depth);
    }
}
