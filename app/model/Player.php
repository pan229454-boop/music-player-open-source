<?php
namespace app\model;

use think\facade\Db;

class Player extends Base
{
	public static function add($data)
    {
        $self = new static();
        $check = $self->insert([
        			'id'=> $data['id'],
					'user_id'=> $data['uid'],
					'name'=> $data['name'],
					'endtime' => date("Y-m-d H:i:s"),
                    'create_time' => date("Y-m-d H:i:s")]);
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
	
    public static function songSheets($playerId){
        return Db::name('player_song_sheet')->alias('pss')
            ->join('song_sheet ss', 'ss.id=pss.song_sheet_id')
            ->field('ss.*')->where('pss.player_id', $playerId)
            ->order('pss.taxis asc')
            ->select();
    }
	
	public
    static function checkPlayerKey($key)
    {
		$self = new static();
		if(!$row=$self->where("id",$key)->find()){
			return true;
		}else{
			return false;
		}
    }
	
	public static function checkPlayerUid($id, $uid)
{
    // 直接使用静态方法进行查询
    $exists = static::where('id', $id)
                    ->where('user_id', $uid)
                    ->count() > 0;

    // 如果存在记录，则返回 false，否则返回 true
    return !$exists;
}
}