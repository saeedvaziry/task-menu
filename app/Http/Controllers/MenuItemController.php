<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Resources\ItemResource;
use App\Menu;

class MenuItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param ItemStoreRequest $request
     * @param $menu
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(ItemStoreRequest $request, $menu)
    {
        $menu = Menu::findOrFail($menu);

        $items = $menu->addItems($request->input());

        return ItemResource::collection($items);
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $menu
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($menu)
    {
        $menu = Menu::findOrFail($menu);

        return ItemResource::collection($menu->items);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu)
    {
        $menu = Menu::findOrFail($menu);

        $menu->items()->delete();

        return response()->json(true);
    }
}
