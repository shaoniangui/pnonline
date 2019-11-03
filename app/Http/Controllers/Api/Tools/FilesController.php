<?php

namespace App\Http\Controllers\Api\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        if($file->getClientSize() > 2000000 )
        {
            return response()->json([
                'message'=>'文件大小不能超过2M',
                'ret'=>1
            ],500);
        }
        $allow_ext = array('jpg','jpeg','png');
        $file_ext = strtolower($file->getClientOriginalExtension());
        if(!in_array($file_ext,$allow_ext))
        {
            return response()->json([
                'message'=>'只允许上传图片',
                'ret'=>1
            ],500);
        }
            return $this->imageUpyun($file);
    }



    //保存到又拍云
    private function imageUpyun($imageFile)
    {
        $domain = "http://" . config('filesystems.disks.upyun.domain');
        $file_path = Storage::disk('upyun')->put('/images', $imageFile);
        return $domain ."/$file_path";
        // $wexinavatar = config('filesystems.disks.upyun.protocol') . '://' . config('filesystems.disks.upyun.domain') . '/' . $filename;
        // return $wexinavatar;//返回链接地址
    }
}
