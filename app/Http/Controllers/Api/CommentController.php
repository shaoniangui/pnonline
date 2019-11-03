<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Topic;
use App\Moods;
use App\Comment;

class CommentController extends Controller
{
    // 发布评论
    public function post(Request $request)
    {
        //类型:话题,心情
        //话题
        if ($request->type == 'topic') {
            // 通过前端传递的话题id找到该话题
            $topic = Topic::find($request->topic_id);
            // 用户对象
            $user = auth('api')->user();

            // 创建评论
            $res = $topic->comments()->save(new Comment([
                'content' => request('content'),
                'user_id' => $user->id,
                'parent_id' => request('parent_id'),
                ]));

            return response()->json([
                'ret' => 0,
                'message'=>'评论成功',
                'res'=>$res
            ]);
        //心情
        } elseif ($request->type == 'mood') {
            // 通过前端传递的话题id找到该心情
            $mood = Moods::find($request->mood_id);
            // 用户对象
            $user = auth('api')->user();

            // 创建评论
            $res = $mood->comments()->save(new Comment([
                'content' => request('content'),
                'user_id' => $user->id,
                'parent_id' => request('parent_id'),
                ]));

            return response()->json([
                'ret' => 0,
                'message'=>'评论成功',
                'res'=>$res
            ]);
        }
    }
}
