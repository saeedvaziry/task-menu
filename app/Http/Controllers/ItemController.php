<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemUpdateRequest;
use App\Http\Resources\ItemResource;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store()
    {
        // This method requires menu id. And i think this is a dublicate of MenuItemController.
        // Because same functionalities are implemented already (MenuItemController).
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $item
     * @return ItemResource
     */
    public function show($item)
    {
        $item = Item::findOrFail($item);

        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ItemUpdateRequest $request
     * @param mixed $item
     * @return ItemResource
     */
    public function update(ItemUpdateRequest $request, $item)
    {
        $item = Item::findOrFail($item);

        $item->update($request->validated());

        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $item
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($item)
    {
        $item = Item::findOrFail($item);

        try {
            DB::beginTransaction();
            $item->removeAllChildren(); // to remove an item, we must remove all children first.
            $item->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(true);
    }
}
