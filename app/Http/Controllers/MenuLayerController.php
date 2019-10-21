<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Menu;

class MenuLayerController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param mixed $menu
     * @param $layer
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($menu, $layer)
    {
        $menu = Menu::findOrFail($menu);

        return ItemResource::collection($menu->getItemsInLayer($layer));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $menu
     * @param $layer
     * @return \Illuminate\Http\Response
     */
    public function destroy($menu, $layer)
    {
        $menu = Menu::findOrFail($menu);

        $menu->deleteItemsInLayer($layer);

        return response()->json(true);
    }
}
