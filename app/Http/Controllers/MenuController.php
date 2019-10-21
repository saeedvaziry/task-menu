<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuStoreRequest;
use App\Http\Requests\MenuUpdateRequest;
use App\Http\Resources\MenuResource;
use App\Menu;

class MenuController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param MenuStoreRequest $request
     * @return MenuResource
     */
    public function store(MenuStoreRequest $request)
    {
        $menu = Menu::create($request->validated());

        return new MenuResource($menu);
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $menu
     * @return MenuResource
     */
    public function show($menu)
    {
        $menu = Menu::findOrFail($menu);

        return new MenuResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MenuUpdateRequest $request
     * @param mixed $menu
     * @return MenuResource
     */
    public function update(MenuUpdateRequest $request, $menu)
    {
        $menu = Menu::findOrFail($menu);
        $menu->field = $request->field;
        $menu->save();

        return new MenuResource($menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $menu
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($menu)
    {
        $menu = Menu::findOrFail($menu);
        $menu->deleteMenu();

        return response()->json(true);
    }
}
