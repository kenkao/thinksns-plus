<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\MessageResponseBody;
use App\Http\Controllers\Controller;
use App\Models\ImUser;
use App\Models\User;
use Illuminate\Http\Request;

class ImController extends Controller
{
    /**
     * 获取聊天服务器信息.
     *
     * @author martinsun <syh@sunyonghong.com>
     * @datetime 2017-01-18T16:08:41+080
     *
     * @version  1.0
     *
     * @param Request $request 请求类
     *
     * @return mixed 返回结果
     */
    public function getImUserInfo(Request $request)
    {
        $user = $request->attributes->get('user');
        $ImUser = new ImUser();
        $data = $ImUser->usersPost(['uid' => $user->id, 'name' => $user->name]);

        return app(MessageResponseBody::class, [
            'code'   => 0,
            'status' => true,
            'data'   => $data,
        ])->setStatusCode(200);
    }

    /**
     * 修改用户资料.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-17T17:25:45+0800
     *
     * @param Request $request [description]
     *
     * @return mixed 返回结果
     */
    public function profile(Request $request)
    {
        $user = $request->attributes->get('user');
        $profileData = $request->all();
        $profileSettings = UserProfileSetting::whereIn('profile', array_keys($profileData))->get();
        $datas = [];
        foreach ($profileSettings as $profile) {
            $datas[$profile->id] = ['user_profile_setting_data' => $request->input($profile->profile)];
        }
        $user->syncData($datas);

        return app(MessageResponseBody::class, [
            'code'   => 0,
            'status' => true,
        ])->setStatusCode(201);
    }
}