<?php
namespace app\controller;

use app\BaseController;
use app\model\Users;
use think\facade\View;
use think\facade\Request;
use think\facade\Db;

class Admin extends BaseController
{
    /**
     * 高级设置页面
     */
    public function advanced()
    {
        $this->checkLogin();
        $this->checkPower();
        return View::fetch('admin/advanced/index');
    }
    
    /**
     * 创建用户
     */
    public function createUser()
    {
        $this->checkLogin();
        $this->checkPower();
        
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
