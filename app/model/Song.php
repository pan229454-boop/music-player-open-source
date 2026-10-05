<?php
namespace app\model;

use think\facade\Config;

class Song extends Base
{
	
    public static function findMusicInfo($data){
        try {
            $sourceId = $data['music_source'] ?? 'legacy';
            $source = \PHPMailer\MusicSourceRegistry::get($sourceId);
            if ($source['provider'] === 'shymusic') {
                $result = (new \PHPMailer\MusicSourceService($sourceId))->info($data['type'] ?? '', $data['songid'] ?? '');
                $result['music_url'] = '/AdminAjax/Song/act/preview?' . http_build_query(['music_source'=>$sourceId, 'type'=>$data['type'] ?? '', 'songid'=>$data['songid'] ?? '']);
                return $result;
            }
        } catch (\Throwable $e) {
            return ['code' => -1, 'msg' => $e->getMessage()];
        }
		$json = send_get(Config::get('api.music').'?input='.$data['songid'].'&filter=id&type='.$data['type'].'&page=1&url='. $_SERVER['SERVER_NAME']);
		$data=json_decode($json,true);
        if($data==''){
			return '';
		}else{
			$arr = [
				"code" => 0,
				"song_name" => $data['title'],
				"artist_name" => $data['author'],
				"album_name" => $data['title'],
				"album_cover" => $data['pic'],
				"music_url" => $data['url'],
				"location" => '',
				"lyric" => '',
			];
			return $arr;
		}
		
    }
	
	
	public static function findSheetInfo($type, $songsheetid) {
    $song_list = [];

    switch ($type) {
        case 'wy':
            $post_data = ['s' => '0', 'id' => $songsheetid, 'n' => '1000', 't' => '0'];
            $url = "https://music.163.com/api/v6/playlist/detail";
            $data = Post($post_data, $url);
            $arr = json_decode($data, true);
            if (isset($arr['playlist']['tracks'])) {
                $songs = $arr['playlist']['tracks'];
                foreach ($songs as $value) {
                    $song_list[] = [
                        'type' => 'netease',
                        'song_id' => $value['id'],
                        'name' => $value['name'],
                        'artist_name' => $value['ar'][0]['name'],
                        'album_cover' => $value['al']['picUrl'] . '?param=300x300',
                        'album_name' => $value['al']['name'],
                    ];
                }
            } 
            break;

        case 'kg': 
            $data = send_get('https://www.kugou.com/yy/special/song/sid=' . $songsheetid);
            $arr = json_decode($data, true);
            if (isset($arr['data'])) {
                $songs = $arr['data'];
                foreach ($songs as $value) {
                    $song_list[] = [
                        'type' => 'kugou',
                        'song_id' => $value['HASH'],
                        'name' => $value['songname'],
                        'artist_name' => $value['singername'],
                        'album_cover' => str_replace('{size}', '150', $value['authors'][0]['sizable_avatar']),
                        'album_name' => $value['album_name'],
                    ];
                }
            }
            break;

        case 'qq': 
            $urls = [
                'https://c.y.qq.com/v8/fcg-bin/fcg_v8_toplist_cp.fcg?page=detail&tpl=macv4&type=top&topid=' . $songsheetid . '&g_tk=5381&loginUin=0&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=0',
                'https://c.y.qq.com/qzone/fcg-bin/fcg_ucc_getcdinfo_byids_cp.fcg?type=1&json=1&utf8=1&onlysong=0&new_format=1&disstid=' . $songsheetid . '&g_tk_new_20200303=637541790&g_tk=637541790&loginUin=0&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq.json&needNewCode=0'
            ];

            $dataFetched = false;

            foreach ($urls as $url) {
                $get_curl = send_get($url, '', 'https://c.y.qq.com/');
                $arr = json_decode($get_curl, true);

                if ($arr) {
                    if (isset($arr["songlist"])) {
                        $songs = $arr["songlist"];
                        foreach ($songs as $value) {
                            $song_list[] = [
                                'type' => 'qq',
                                'song_id' => $value["data"]["songmid"],
                                'name' => $value["data"]["songname"],
                                'artist_name' => $value["data"]["singer"][0]["name"],
                                'album_name' => $value['data']['albumname'],
                                'album_cover' => 'https://y.gtimg.cn/music/photo_new/T002R300x300M000' . $value["data"]["albummid"] . '.jpg',
                            ];
                        }
                        $dataFetched = true;
                        break;
                    } elseif (isset($arr['cdlist'][0]['songlist'])) {
                        $songs = $arr['cdlist'][0]['songlist'];
                        foreach ($songs as $value) {
                            foreach ($value['singer'] as $val) {
                                $song_list[] = [
                                    'type' => 'qq',
                                    'song_id' => $value['mid'],
                                    'name' => $value['name'],
                                    'album_name' => $value['album']['name'],
                                    'artist_name' => $val['name'],
                                    'album_cover' => 'https://y.gtimg.cn/music/photo_new/T002R300x300M000' . $value['album']['mid'] . '.jpg',
                                ];
                            }
                        }
                        $dataFetched = true;
                        break;
                    }
                }
            }
            if (!$dataFetched) {
                $song_list = [];
            }
            break;
        default:
            return ''; 
    }
    return !empty($song_list) ? $song_list : '';
}
} 