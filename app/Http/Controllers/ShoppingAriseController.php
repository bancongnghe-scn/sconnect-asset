<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShoppingAriseController extends Controller
{
    public function createShoppingArise(Request $request)
    {
        $request->validate([
            'name'                         => 'required|string',
            'assets'                       => 'required|array',
            'assets.*.asset_type_id'       => 'required|integer',
            'assets.*.quantity_registered' => 'required|integer',
        ]);
    }
}
