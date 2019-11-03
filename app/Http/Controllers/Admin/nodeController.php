<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Node;

class nodeController extends Controller
{
    public function list(Request $request)
    {
        return response()->json([
            'code' => 20000,
            'data' => array(
                'total' => Node::all()->count(),
                'items' => Node::all()
            )
        ], 200);
    }

    public function create(Request $request)
    {
        $nodeName = $request->name;
        $newNode = new Node;
        $newNode->name = $nodeName;
        $newNode->save();
        return response()->json([
            'code' => 20000,
            'data' => 'success'
        ], 200);
    }

    public function edit(Request $request)
    {
        $nodeId = $request->nodeId;
        $nodeName = $request->newName;

        $node = Node::find($nodeId);
        $node->name = $nodeName;
        $node->save();
        return response()->json([
            'code' => 20000,
            'data' => 'success'
        ], 200);
    }

    public function del(Request $request)
    {
        $nodeId = $request->nodeId;
        Node::destroy($nodeId);
        return response()->json([
            'code' => 20000,
            'data' => 'success'
        ], 200);
    }

}
