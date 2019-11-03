<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Node;

class NodeController extends Controller
{
    public function list(Request $request)
    {
        return response()->json([
            'data' => Node::all()
        ], 200);
    }
}
