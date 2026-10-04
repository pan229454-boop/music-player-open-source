<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;

class Advanced extends BaseController
{
    /**
     * 高级设置页面
     */
    public function index()
    {
        $this->checkLogin();
        $userInfo = \app\model\Users::getLoginUser();
        
        if($userInfo['power'] != 0) {
            return redirect('/');
        }
        
        return View::fetch();
    }
    
    /**
     * 创建用户
     */
    public function createUser()
    {
        $this->checkLogin();
        $userInfo = \app\model\Users::getLoginUser();
        
        if($userInfo['power'] != 0) {
            return json(['status' => 'error', 'message' => '无权限操作']);
        }
        
        $data = Request::param();
        
        // 验证必填字段
        if(empty($data['username']) || empty($data['password'])) {
            return json(['status' => 'error', 'message' => '用户名和密码不能为空']);
        }
        
        // 检查用户名是否已存在
        $exists = Db::name('users')->where('username', $data['username'])->find();
        if($exists) {
            return json(['status' => 'error', 'message' => '用户名已存在']);
        }
        
        // 生成密钥
        $skey = md5(uniqid());
        $sid = md5(uniqid());
        $token = md5(uniqid());
        $token2 = md5(uniqid());
        
        // 哈希密码
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        // 插入用户
        $result = Db::name('users')->insert([
            'username' => $data['username'],
            'password' => $hashedPassword,
            'qq' => $data['qq'] ?? '',
            'mail' => $data['mail'] ?? '',
            'power' => intval($data['power'] ?? 2),
            'pie' => intval($data['pie'] ?? 0),
            'skey' => $skey,
            'sid' => $sid,
            'token' => $token,
            'limit2' => '0',
            'token2' => $token2,
            'regtime' => date('Y-m-d H:i:s'),
            'regip' => Request::ip(),
        ]);
        
        if($result) {
            return json(['status' => 'success', 'message' => '用户创建成功']);
        } else {
            return json(['status' => 'error', 'message' => '创建失败，请重试']);
        }
    }
}
