<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class userController extends Controller
{
    public function list(Request $request)
    {
        return response()->json([
            'code' => 20000,
            'data' => array(
                'total' => User::all()->count(),
                'items' => User::all()
            )
        ], 200);
    }
}