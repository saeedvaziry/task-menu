<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Resources\ItemResource;
use App\Item;
use Illuminate\Support\Facades\DB;

class ItemChildrenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ItemStoreRequest $request
     * @param $item
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(ItemStoreRequest $request, $item)
    {
        $item = Item::findOrFail($item);

        $items = $item->addChildren($request->validated());

        return ItemResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $item
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($item)
    {
        $item = Item::findOrFail($item);

        return ItemResource::collection($item->children);
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
            $item->removeAllChildren(); // this action needs db transaction because it executes one query per each children
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return response()->json(true);
    }
}
