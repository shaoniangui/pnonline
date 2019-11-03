<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Overtrue\LaravelFollow\Traits\CanBeLiked;

class Topic extends Model
{
    use CanBeLiked;
    
    const TABLE = 'topic';
    protected $table = self::TABLE;
    
    /**
     * 获得此话题所属的用户。
     */
    public function user()
    {
        // 反向关联User模型
        return $this->belongsTo('App\User', 'author_id', 'id');
    }

    /**
     * 获得此话题所属的节点。
     */
    public function node()
    {
        // 反向关联Node模型
        return $this->belongsTo('App\Node', 'node_id', 'id');
    }

    /**
     * (多态)获得此话题下的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

    /**
     * 获取这篇话题的评论以parent_id来分组
     * @return static
     */
    public function getComments()
    {
        // 两种方式
        // 无限层,以key来寻找
        return $this->comments()->with('owner')->get()->groupBy('parent_id');
        // 最多两层,对象嵌套
        // return $this->comments()->with('owner','replies.owner')->where('parent_id',null)->orderBy('created_at')->get();
    }

}
