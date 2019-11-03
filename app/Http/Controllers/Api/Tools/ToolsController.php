<?php

namespace App\Http\Controllers\Api\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    // 未调通,需要程序发布后才可获取到小程序码
    public function getcode(Request $request){
        $miniProgram = \EasyWeChat::miniProgram();
        $response = $miniProgram->app_code->getUnlimit('scene-value', [
            'page'  => $request->page,
            'width' => 300,
        ]);
        // $response 成功时为 EasyWeChat\Kernel\Http\StreamResponse 实例，失败为数组或你指定的 API 返回类型
        return $response;
        // 保存小程序码到文件
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            // $filename = $response->save('/path/to/directory');
            $domain = "http://" . config('filesystems.disks.upyun.domain');
            $file_path = Storage::disk('upyun')->put('/miniprogramCode', $response);
            return $domain ."/$file_path";
        }
    }
}
