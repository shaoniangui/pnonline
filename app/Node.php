<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    /**
     * 获得此节点下的话题。
     */
    public function topics()
    {
        // 关联Topic模型
        return $this->hasMany('App\Topic','node_id','id');
    }
    //指定表名
    protected $table = 'node';

    //主键
    protected $primaryKey = 'id';

    //是否开启时间戳
    public $timestamps = true;
}
