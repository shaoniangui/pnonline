<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class manageController extends Controller
{
    public function login(Request $request)
    {
        return response()->json([
            'code' => 20000,
            'data' => "admin-token"
        ], 200);
    }
    public function info(Request $request)
    {
        $info = array(
            "roles" => array('admin'),
            "introduction" => 'I am a super administrator',
            "avatar" => 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif',
            "name" => 'Super Admin'
        );
        return response()->json([
            'code' => 20000,
            'data' => $info
        ], 200);
    }
    public function logout(Request $request)
    {
        return response()->json([
            'code' => 20000,
            'data' => "success"
        ], 200);
    }
}
