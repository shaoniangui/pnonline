<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Topic;
use App\Node;
use App\User;

class TopicController extends Controller
{
    public function list(Request $request)
    {
        // 查询用户
        if($request->user_id){
            $user = auth('api')->user();
            $user_id = $request->user_id;

            // 自己角度,不过滤打码
            if($user->id == $user_id)
            {
                $topicList = $user->topics()->orderBy('created_at', 'desc')->paginate($request->limit);
            }else{
            // 他人角度:需过滤打码的
                $topicList = User::find($user_id)->topics()->orderBy('created_at', 'desc')->where('mosaic',0)->paginate($request->limit);
            }
        }
        // 查询节点
        if($request->node_id){
            $node_id = $request->node_id;
            // 全部话题
            if ($node_id == 'all') {
                $topicList = Topic::orderBy('created_at', 'desc')->paginate($request->limit);
            } else {
                //指定节点话题 不确定要不要括号
                $topicList = Node::find($node_id)->topics()->orderBy('created_at', 'desc')->paginate($request->limit);
            }
        }
        foreach ($topicList as $topic) {
            $topic->user;
            $topic->node;
            // 话题的喜欢数
            $topic->likeNum = count($topic->likers()->get());
            // 话题的评论数
            $topic->commentNum = count($topic->comments()->get());
        }

        return response()->json([
            'data' => $topicList
        ], 200);
    }
    // 获取话题详情
    public function detail(Request $request)
    {
        // 前端传递过来的话题id
        $id = $request->id;
        // 通过话题id找到该话题
        $topic = Topic::find($id);

        // 通过话题找到评论
        $topic->load('comments.owner'); //预加载评论
        $comments = $topic->getComments();
        // 格式化数据
        if (count($comments)>0) {
            $comments['root'] = $comments[''];
            unset($comments['']);
        } else {
            $comments=array('root'=>[]);
        }
        //给文字分享量增加
        if($request->source=='share'){
            $topic->share_num = $topic->share_num + 1 ;
        }
        // 阅读量增加
        $topic->read_num = $topic->read_num + 1;
        $topic->save();
        // 整合文章数据
        $topic->user;
        $topic->node;
        $topic->likeNum = count($topic->likers()->get());
        $topic->commentNum = count($topic->comments()->get());
        $topic->shareNum = $topic->share_num;
        $topic->hasLike = $topic->user->hasLiked($topic);
        // 整合评论数据
        $topic->replies = $comments;
        return response()->json([
            'data' => $topic
        ], 200);
    }
    // 发布话题
    public function create(Request $request)
    {
        $user = auth('api')->user();
        // 新建话题
        $topic = new Topic;
        $topic->title = $request->title;
        $topic->content = $request->content;
        $topic->author_id = $user->id;
        $topic->node_id = $request->node_id;
        $topic->mosaic = (int)$request->mosaic;
        $res = $topic->save();

        //测试关联是否成功
        // $forUser = $user->topics;
        // $forNode = Node::find($request->node_id)->topics;

        if ($res) {
            return response()->json([
           'ret'=>0,
           'topic_id'=>$topic->id
        ]);
        }
    }

    // 删除话题
    public function delete(Request $request)
    {
        // 前端传递过来的话题id
        $id = $request->id;
        $user= auth('api')->user();
        // 通过话题id找到该话题
        $topic = $topic = Topic::find($id);
        // 判断用户
        if($user->id == $topic->user->id){
            $ret = 0;
            $res = $topic->delete();
        }else{
            $ret = 1;
            $res = '你不是该内容的发布者';
        }
        return response()->json([
            'ret'=>$ret,
            'res'=>$res
        ]);
    }

    // 喜欢话题
    public function like(Request $request)
    {
        $id = $request->id;
        $topic = Topic::find($id);
        $res = $topic->user->like($topic);
        return response()->json([
            'data' => $res
        ], 200);
    }

    // 取消喜欢话题
    public function unlike(Request $request)
    {
        $id = $request->id;
        $topic = Topic::find($id);
        $res = $topic->user->unlike($topic);
        return response()->json([
            'data' => $res
        ], 200);

    }
}
