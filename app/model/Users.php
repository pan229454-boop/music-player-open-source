<?php
namespace app\model;

use think\facade\Cookie;
use think\facade\Config;
use think\facade\Db;

class Users extends Base
{
    public static function cleanupInactiveUsers()
    {
        $negativetime = Config::get('web.negativetime');
        $ninetyDaysAgo = date('Y-m-d H:i:s', strtotime("-{$negativetime} days"));
        $users = Db::name('users')
            ->where('power', 2)
            ->select();
        $deletedUsers = []; 
        foreach ($users as $user) {
            if ($user['time'] < strtotime($ninetyDaysAgo)) {
                Db::name('users')->where('uid', $user['uid'])->delete();
                $deletedUsers[] = [
                    'uid' => $user['uid'],
                    'username' => $user['username']
                ]; 
            }
        }
        return $deletedUsers;
    }

    public static function search()
    { 
        $page = input('page/d');
        $pageSize = input('limit/d');
        $page = ($page < 1) ? 1 : $page;
        $pageSize = ($pageSize < 1 || $pageSize > 50) ? 10 : $pageSize;
        $self = new static();
        $query = $self->alias('a');  
        $username = input('username');
        !empty($username) && $query->where('a.username', '=', $username);
        $query2 = clone $query;
        return [
            'total' => $query2->count('a.uid'),
            'list' => $query->page($page, $pageSize)->order('a.regtime desc')->select()
        ];
    }

    public static function qqlogin($data)
    {
        $self = new static();
        $token = $data['openid'];
        $userInfo = $self->where('token', '=', $token)->find();
        $str = $userInfo['username'] . $userInfo['password'] . time() . real_ip();
        $sign = md5($str . md5('11144452888'));
        Cookie::set('pskey', authcode($userInfo['uid'], 'ENCODE', md5($str)));
        Cookie::set('skey', md5($str));
        Cookie::set('sid', $sign);
        $update = $self->where('uid', '=', $userInfo['uid'])
            ->update([
                'skey' => md5($str),
                'sid' => $sign,
                'dlip' => real_ip(),
                'city' => get_ip_city(real_ip()),
                'time' => time()
            ]);
        return $update ? true : false;
    }

    public static function reg($data)
    {
        $self = new static();
        $insert['username'] = $data['username'];
        $insert['password'] = password_hash($data['password'], PASSWORD_BCRYPT); // 使用 password_hash 代替 md5
        $insert['qq'] = $data['qq'];
        $insert['mail'] = $data['mail'];
        $insert['skey'] = '0';
        $insert['regtime'] = date("Y-m-d H:i:s");
        $insert['regip'] = real_ip();
        $insert['time'] = time();
        $insert['token2'] = strtoupper(md5(bin2hex(random_bytes(16))));
        $insert['limit2'] = Config::get('web.reglimit2');
        $insert['power'] = 2;
        $insert['pie'] = Config::get('web.regpie');
        $check = $self->insert($insert);
        return $check ? true : false;
    }

    public static function login($userInfo)
    {
        $self = new static();
        $str = $userInfo['username'] . $userInfo['password'] . time() . real_ip();
        $sign = md5($str . md5('11144452888'));
        
        $userInfoFromDb = $self->where('username', '=', $userInfo['username'])->find();
        if ($userInfoFromDb && password_verify($userInfo['password'], $userInfoFromDb['password'])) { // 使用 password_verify 验证密码
            Cookie::set('pskey', authcode($userInfoFromDb['uid'], 'ENCODE', md5($str)));
            Cookie::set('skey', md5($str));
            Cookie::set('sid', $sign);
            $update = $self->where('uid', '=', $userInfoFromDb['uid'])
                ->update([
                    'skey' => md5($str),
                    'sid' => $sign,
                    'dlip' => real_ip(),
                    'city' => get_ip_city(real_ip()),
                    'time' => time()
                ]);
            return $update ? true : false;
        }
        return false;
    }

    public static function checkUsername($username)
    {
        $self = new static();
        return !$self->where("username", $username)->find();
    }

    public static function checkQqToken($token)
    {
        $self = new static();
        return (bool) $self->where("token", $token)->find();
    }

    public static function checkQq($qq)
    {
        $self = new static();
        return !$self->where("qq", $qq)->find();
    }

    public static function checkPass($userInfo)
    {
        $self = new static();
        $row = $self->where('username', $userInfo['username'])->find();
        return $row && password_verify($userInfo['password'], $row['password']);
    }

    public static function updateQQ($uid, $newQQ)
    {
        $user = self::where('uid', '=', $uid)->field('qq')->find();
        if (!$user) {
            return ['code' => -1, 'message' => '用户不存在'];
        }
        if ($user['qq'] === $newQQ) {
            return ['code' => 1, 'message' => '新QQ号不能与旧QQ号相同'];
        }
        $update = self::where('uid', '=', $uid)->update(['qq' => $newQQ]);
        return $update !== false ? ['code' => 0, 'message' => 'QQ号修改成功'] : ['code' => 2, 'message' => 'QQ号修改失败'];
    }

    public static function change_Password($uid, $data)
    {
        $self = new static();
        $user_row = $self->where('uid', '=', $uid)->field('password')->find();
        
        $rule = [
            'password'  => 'require|alphaNum|length:5,20',
        ];
        $message = [
            'password.require' => '密码不能为空',
            'password.alphaNum' => '密码只能字母和数字',
            'password.length' => '密码长度必须在5到20之间',
        ];

        $validate = new \think\Validate($rule, $message);
        $result = ['code' => -1, 'msg' => '操作失败'];

        if (!$validate->check($data)) {
            $result = ['code' => -1, 'msg' => $validate->getError()];
        } elseif (password_verify($data['outpass'], $user_row['password'])) {
            $new_pass = password_hash($data['password'], PASSWORD_BCRYPT); // 使用 password_hash 加密新密码
            if ($self->where('uid', '=', $uid)->update(['password' => $new_pass])) {
                Cookie::delete('skey');
                Cookie::delete('sid');
                Cookie::delete('pskey');
                Cookie::delete('user_token');
                $result = ['code' => 0, 'message' => '修改密码成功，请重新登录'];
            } else {
                $result = ['code' => 1, 'message' => '密码更新失败，请重试'];
            }
        } else {
            $result = ['code' => 1, 'message' => '原密码不正确！'];
        }

        return $result;
    }

    public static function findpassword($data)
    {
        $self = new static();
        $user_row = $self->where('mail', '=', $data['mail'])->field('password')->find();
        if (!$user_row) {
            return ['code' => -1, 'msg' => '该邮箱未注册'];
        }

        $new_pass = password_hash($data['password'], PASSWORD_BCRYPT); // 使用 password_hash 加密新密码
        $rule = [
            'password'  => 'require|alphaNum|length:5,20',
        ];
        $message = [
            'password.require' => '密码不能为空',
            'password.alphaNum' => '密码只能字母和数字',
            'password.length' => '密码长度必须在5到20之间',
        ];

        $validate = new \think\Validate($rule, $message);
        if (!$validate->check($data)) {
            return ['code' => -1, 'msg' => $validate->getError()];
        } elseif ($self->where('mail', '=', $data['mail'])->update(['password' => $new_pass])) {
            return ['code' => 0, 'msg' => '密码重置成功，请重新登录'];
        } else {
            return ['code' => -1, 'msg' => '密码重置失败，请重试'];
        }
    }
    public static function updateByUid($uid, $data)
    {
        $self = new static();
        if ($result = $self->where('uid', '=', $uid)->update($data)) {
            return $result;
        } else {
            return false;
        }
    }
    
    public static function updateBytoken($token, $data)
    {
        $self = new static();
        if ($result = $self->where('token', '=', $token)->update($data)) {
            return $result;
        } else {
            return false;
        }
    }
    
    public static function updateByUsername($username, $data)
    {
        $self = new static();
        if ($result = $self->where('username', '=', $username)->update($data)) {
            return $result;
        } else {
            return false;
        }
    }

    public static function delByUid($uid)
    {
        $self = new static();
        if ($result = $self->where('uid', '=', $uid)->delete()) {
            return $result;
        } else {
            return false;
        }
    }

  /*  public
    static function getLoginUser()
    {
        $self = new static();
		$skey = cookie("skey");
        $pskey = authcode(cookie('pskey'), 'DECODE', $skey);
        //获取登录Cookie
        $uid = $pskey;
        //组成查询条件
        $where['uid'] = $uid;
        //通过Cookie存储的数据查询数据库记录
        if (!$uid || !$userInfo = $self->where($where)->find()) {
            //不存在
            return false;
        } else {
            //存在则返回该用户的这条记录内容
            return $userInfo;
        }
    }*/
    public static function getLoginUser()
{
    $self = new static();
    $skey = Cookie::get('skey');
    $pskey = authcode(Cookie::get('pskey'), 'DECODE', $skey);

    // 获取登录用户的 UID
    $uid = $pskey;

    if (!$uid) {
        return false;
    }

    // 组成查询条件并查询数据库
    $userInfo = $self->where('uid', $uid)->find();

    return $userInfo ?: false;
}

    public static function checkLogin()
    {
        $skey = Cookie::get('skey');
        if (!$skey) {
            return [
                'url' => '/console#/user/login',
                'msg' => '请登录后再进行操作！'
            ];
        } else {
            $self = new static();
            $sid = Cookie::get('sid');
            $pskey = authcode(Cookie::get('pskey'), 'DECODE', $skey);
            $user = $self->where('uid', '=', $pskey)->find();
            if ($sid == $user['sid']) {
                if ($user['skey'] === $skey) {
                    return null;
                } else {
                    return [
                        'url' => '/console#/user/login',
                        'msg' => '请登录后再进行操作！'
                    ];
                }
            } else {
                Cookie::delete('skey');
                Cookie::delete('sid');
                Cookie::delete('pskey');
                return [
                    'url' => '/console#/user/login',
                    'msg' => '您的账号在别处登录，请重新登录！'
                ];
            }
        }
    }
}