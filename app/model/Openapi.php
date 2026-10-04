<?php
namespace app\model;

use think\facade\Db;
use think\exception\DbException;

class Openapi extends Base
{
    public static function Token($token)
    {
        $exists = Db::name('users')->where('token2', $token)->count();
        return $exists > 0;
    }
}
