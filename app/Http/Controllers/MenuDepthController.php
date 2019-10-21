<?php

namespace App\Http\Controllers;

use App\Menu;

class MenuDepthController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param mixed $menu
     * @return \Illuminate\Http\Response
     */
    public function show($menu)
    {
        $menu = Menu::findOrFail($menu);

        return response()->json([
            'depth' => $menu->depth
        ]);
    }
}
