<?php

namespace app\controller;

use think\Request;
use app\model\SongModel;

class SongController
{
    // 查询歌曲列表接口
    public function getSongList(Request $request)
    {
        // 假设传递了歌单ID
        $songSheetId = $request->param('songSheetId');
        
        // 查询该歌单下的歌曲
        $songs = Song::where('song_sheet_id', $songSheetId)
                      ->order('sort_order', 'asc')  // 假设有排序字段
                      ->select();
        
        // 返回JSON格式的响应
        return json([
            'status' => 'success',
            'data'   => $songs
        ]);
    }
}
