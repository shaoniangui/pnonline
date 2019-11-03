<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function weappLogin(Request $request)
    {

        $code = $request->code;
        // 根据 code 获取微信 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($code);

        if (isset($data['errcode'])) {
            return $this->response->errorUnauthorized('code已过期或不正确');
        }

        //整理微信用户数据
        $weappOpenid = $data['openid'];
        $weixinSessionKey = $data['session_key'];
        $nickname = $request->nickname;
        $avatar = str_replace('/132', '/0', $request->avatar);//拿到分辨率高点的头像
        $country = $request->country?$request->country:'';
        $province = $request->province?$request->province:'';
        $city = $request->city?$request->city:'';
        $gender = $request->gender == '1' ? '1' : '2';//没传过性别的就默认女的吧，体验好些
        $language = $request->language?$request->language:'';

        //找到 openid 对应的用户
        $user = User::where('weapp_openid', $weappOpenid)->first();
        //没有，就注册一个用户
        if (!$user) {
            $user = User::create([
                'weapp_openid' => $weappOpenid,
                'weapp_session_key' => $weixinSessionKey,
                'password' => $weixinSessionKey,
                'avatar' => $request->avatar?$this->avatarUpyun($avatar):'',
                'weapp_avatar' => $avatar,
                'nickname' => $nickname,
                'country' => $country,
                'province' => $province,
                'city' => $city,
                'gender' => $gender,
                'language' => $language,
            ]);
        }
        //如果注册过的，就更新下下面的信息
        $attributes['updated_at'] = now();
        $attributes['weixin_session_key'] = $weixinSessionKey;
        $attributes['avatar'] = $request->avatar?$this->avatarUpyun($avatar):'';
        $attributes['weapp_avatar'] = $attributes['avatar'];
        if ($nickname) {
            $attributes['nickname'] = $nickname;
        }
        if ($request->gender) {
            $attributes['gender'] = $gender;
        }
        // 更新用户数据
        $user->update($attributes);
        // 直接为用户创建token并设置有效期
        $createToken = $user->createToken($user->weapp_openid);
        $createToken->token->expires_at = Carbon::now()->addDays(30);
        $createToken->token->save();
        $token = $createToken->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => "Bearer",
            'expires_in' => Carbon::now()->addDays(30),
            'data' => $user,
        ], 200);
    }

    //我保存到又拍云了，版权归腾讯所有。。。头条闹的
    private function avatarUpyun($avatar)
    {
        $avatarfile = file_get_contents($avatar);
        $filename = '/avatars/' . uniqid() . '.png';//微信的头像链接我也不知道怎么获取后缀，直接保存成png的了
        Storage::disk('upyun')->write($filename, $avatarfile);
        $wexinavatar = config('filesystems.disks.upyun.protocol') . '://' . config('filesystems.disks.upyun.domain') . '/' . $filename;
        return $wexinavatar;//返回链接地址
    }

    //个人主页数据
    public function getUserIndex(Request $request)
    {
        $user = User::find($request->id);

        return response()->json([
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'coverImg' => json_decode($user->coverImg)
        ], 200);
    }
}
