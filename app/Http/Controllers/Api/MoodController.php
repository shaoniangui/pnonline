<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Moods;
use App\User;

class MoodController extends Controller
{
    public function list(Request $request)
    {

        // 查询用户
        if($request->user_id){
            $user = auth('api')->user();
            $user_id = $request->user_id;
            //自己角度,不过滤打码
            if($user->id == $user_id)
            {
                $moodList = $user->moods()->orderBy('created_at', 'desc')->paginate($request->limit);
            }else{
             //他人角度,需过滤打码
             $moodList = User::find($user_id)->moods()->orderBy('created_at', 'desc')->where('mosaic',0)->paginate($request->limit);
            }
        }else{
            $moodList = Moods::orderBy('created_at', 'desc')->paginate($request->limit);
        }
        foreach ($moodList as $mood) {
            $mood->user;
            // 心情的喜欢数
            $mood->likeNum = count($mood->likers()->get());
            // 心情的评论数
            $mood->commentNum = count($mood->comments()->get());
            $mood->imageData = json_decode($mood->imageData);
        }
        return response()->json([
            'data' => $moodList
        ], 200);
    }
    // 获取心情详情
    public function detail(Request $request)
    {
        // 前端传递过来的心情id
        $id = $request->id;
        // 通过心情id找到该心情
        $mood = Moods::find($id);

        // 通过心情找到评论
        $mood->load('comments.owner'); //预加载评论
        $comments = $mood->getComments();
        // 格式化数据
        if (count($comments)>0) {
            $comments['root'] = $comments[''];
            unset($comments['']);
        } else {
            $comments=array('root'=>[]);
        }
        //给文字分享量增加
        if ($request->source=='share') {
            $mood->share_num = $mood->share_num + 1 ;
        }
        // 阅读量增加
        $mood->read_num = $mood->read_num + 1;
        $mood->save();
        // 整合文章数据
        $mood->user;
        $mood->node;
        $mood->likeNum = count($mood->likers()->get());
        $mood->commentNum = count($mood->comments()->get());
        $mood->shareNum = $mood->share_num;
        $mood->hasLike = $mood->user->hasLiked($mood);
        // 整合评论数据
        $mood->replies = $comments;
        return response()->json([
            'data' => $mood
        ], 200);
    }
    // 发布心情
    public function create(Request $request)
    {
        $user = auth('api')->user();
        // 新建心情
        $mood = new Moods([
            'content'=>$request->content,
            'mosaic' => $request->mosaic,
            'author_id'=>$user->id,
            'imageData' => json_encode($request->imageData) //转为json字符存入
        ]);
        $mood->save();
        return response()->json([
           'ret'=>0,
           'res'=>$mood,
           'topic_id'=>$mood->id
        ]);
    }

    // 删除心情
    public function delete(Request $request)
    {
        // 前端传递过来的话题id
        $id = $request->id;
        $user= auth('api')->user();
        // 通过话题id找到该话题
        $mood = $mood = Moods::find($id);
        // 判断用户
        if($user->id == $mood->user->id){
            $ret = 0;
            $res = $mood->delete();
        }else{
            $ret = 1;
            $res = '你不是该内容的发布者';
        }
        return response()->json([
            'ret'=>$ret,
            'res'=>$res
        ]);
    }

    // 喜欢心情
    public function like(Request $request)
    {
        $id = $request->id;
        $mood = Moods::find($id);
        $res = $mood->user->like($mood);
        return response()->json([
            'data' => $res
        ], 200);
    }

    // 取消喜欢心情
    public function unlike(Request $request)
    {
        $id = $request->id;
        $mood = Moods::find($id);
        $res = $mood->user->unlike($mood);
        return response()->json([
            'data' => $res
        ], 200);
    }
}
