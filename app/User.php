<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use \Overtrue\LaravelFollow\Traits\CanLike;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable,CanLike;

    /**
     * 获得此用户创建的话题。
     */
    public function topics()
    {
        // 关联Topic模型
        return $this->hasMany('App\Topic', 'author_id', 'id');
    }

    /**
     * 获得此用户创建的心情。
     */
    public function moods()
    {
        // 关联Mood模型
        return $this->hasMany('App\Moods', 'author_id', 'id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'id',
          'avatar',//我用来把微信头像的/0清晰图片，存到又拍云上
          'weapp_openid',
          'nickname',
          'weapp_avatar',
          'country',
          'province',
          'city',
          'language',
          'location',
          'gender',
          'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
