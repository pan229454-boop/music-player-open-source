<?php
namespace app\controller;

use think\facade\Request;
use think\Response;
use think\facade\Db;
use think\facade\Config;
use app\model\Openapi;
use app\model\Users;
use app\model\Song;

class Webapi extends Common
{
   public function getFavicon()
{
    $url = Request::param('url', Config::get('api.music'));
    if (empty($url)) {
        return Response::create([
            'code' => 0,
            'msg'  => '请输入要获取Favicon的网址',
            'data' => ''
        ], 'json', 400)->header(['Content-Type' => 'application/json']);
    }
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $headers = @get_headers("https://" . $url);
        if ($headers && strpos($headers[0], "200")) {
            $url = "https://" . $url;
        } else {
            $url = "http://" . $url;
        }
    }
    try {
        $favicon_url = $this->getFaviconUrl($url);
        return $this->processFavicon($favicon_url);
    } catch (\Exception $e) {
        return Response::create([
            'code' => 0,
            'msg'  => '错误: ' . $e->getMessage(),
            'data' => ''
        ], 'json', 500)->header(['Content-Type' => 'application/json']);
    }
}

private function getFaviconUrl($url)
{
    $html = $this->curl_fav($url);

    // 匹配 rel="icon" 或 rel="shortcut icon"
    preg_match('/<link.*?rel=["\'](?:icon|shortcut icon)["\'].*?href=["\'](.*?)["\'].*?>/i', $html, $matches);

    if (!empty($matches)) {
        $favicon_url = $matches[1];  // 获取 href 部分

        // 根据不同的链接格式处理
        if (strpos($favicon_url, '//') === 0) {
            // 处理双斜杠开头的相对协议路径
            $favicon_url = 'http:' . $favicon_url;
        } elseif (strpos($favicon_url, '/') === 0) {
            // 处理相对于根路径的 URL
            $favicon_url = rtrim($url, '/') . $favicon_url;
        } elseif (strpos($favicon_url, './') === 0) {
            // 处理相对当前路径的 URL
            $favicon_url = rtrim($url, '/') . '/' . ltrim($favicon_url, './');
        } elseif (filter_var($favicon_url, FILTER_VALIDATE_URL) === false) {
            // 处理相对路径（不带 http 协议和根路径）
            $favicon_url = rtrim($url, '/') . '/' . ltrim($favicon_url, '/');
        }
    } else {
        // 如果找不到 link 标签，使用默认 favicon.ico
        $favicon_url = rtrim($url, '/') . '/favicon.ico';
    }

    return $favicon_url;
}

private function processFavicon($favicon_url)
{
    $favicon_content = $this->curl_fav($favicon_url);
    if (!$favicon_content) {
        throw new \Exception("无法获取图标");
    }
    return Response::create($favicon_content, 'html', 200)
        ->header(['Content-Type' => 'image/x-icon']); 
}

private function curl_fav($url)
{
    $ch = curl_init();
    if ($ch === false) {
        throw new \Exception("cURL 初始化失败");
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 14; REA-AN00 Build/HONORREA-AN00) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.193 Mobile Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $content = curl_exec($ch);
    if ($content === false) {
        $error = curl_error($ch);
        throw new \Exception("cURL 执行失败：" . $error);
    }
    curl_close($ch);
    return $content;
}
    
    public function search(Request $request)
    {
        $token = Request::param('token');
    $checkResult = $this->checkAndUpdateUserLimit($token);
    if ($checkResult !== true) {
        return $checkResult;
    }
        $s = Request::param('name');
        $type = Request::param('type');
        $songs = [];
        switch ($type ?? '') {
            case 'netease':
                $encode_netease_data = new \app\controller\AdminAjax();  
            $body = $encode_netease_data->encode_netease_data([
                'method' => 'POST',
                'url' => 'http://music.163.com/api/cloudsearch/pc',
                'params' => [
                    's' => $s,
                    'type' => 1,
                    'offset' => 1 * 10 - 10,
                    'limit' => 50
                ]
            ]);
                $response = send_get('http://music.163.com/api/linux/forward?eparams=' . $body['eparams'], 1, 'http://music.163.com/');
                $arr = json_decode($response, true);

                foreach ($arr['result']['songs'] ?? [] as $value) {
                    $songs[] = [
                        'song_name' => $value['name'],
                        'type' => 'netease',
                        'id' => $value['id'],
                        'artist_name' => $value['ar'][0]['name'] ?? ''
                    ];
                }
                break;
            case 'kugou':
                $response = send_get('http://mobilecdn.kugou.com/api/v3/search/song?keyword=' . urlencode($s) . '&format=json&page=1&pagesize=50', 0, 'http://m.kugou.com/v2/static/html/search.html');
                $arr = json_decode($response, true);

                foreach ($arr['data']['info'] ?? [] as $value) {
                    $songs[] = [
                        'song_name' => $value['songname'],
                        'type' => 'kugou',
                        'id' => $value['hash'],
                        'artist_name' => $value['singername'] ?? ''
                    ];
                }
                break;
            case 'qq':
                $url = "https://u.y.qq.com/cgi-bin/musicu.fcg";
                $searchid = base64_encode(random_bytes(8));
                $post = json_encode([
                    "comm" => [
                        "_channelid" => "19",
                        "_os_version" => "6.2.9200-2",
                        "authst" => "Q_H_L_5tvGesDV1E9ywCVIuapBeYL7IYKKtbZErLj5HeBkyXeqXtjfQYhP5tg",
                        "ct" => "19",
                        "cv" => "1873",
                        "guid" => "B69D8BC956E47C2B65440380380B7E9A",
                        "patch" => "118",
                        "psrf_access_token_expiresAt" => 1972395786,
                        "psrf_qqaccess_token" => "E81C8E6DCB9C9CB4C443F36EF69C8437",
                        "psrf_qqopenid" => "9DF150E2F0F95C5C3A9B54D5A80FEF1A",
                        "psrf_qqunionid" => "79B1E758D09B3762FA03A8CDB9697899",
                        "tmeAppID" => "qqmusic",
                        "tmeLoginType" => 2,
                        "uin" => "1569097443",
                        "wid" => "0"
                    ],
                    "music.search.SearchCgiService" => [
                        "method" => "DoSearchForQQMusicDesktop",
                        "module" => "music.search.SearchCgiService",
                        "param" => [
                            "grp" => 1,
                            "num_per_page" => 50,
                            "page_num" => 1,
                            "query" => $s,
                            "remoteplace" => "txt.newclient.history",
                            "search_type" => 0,
                            "searchid" => $searchid
                        ]
                    ]
                ]);

                $headers = [
                    "Content-Type: application/json; charset=UTF-8",
                    "Charset: UTF-8",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (Linux; Android 6.0.1; OPPO R9s Plus Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36",
                    "Connection: Keep-Alive",
                    "Accept-Encoding: gzip",
                    "Host: u.y.qq.com"
                ];
                $response = curl($url, ['post' => $post, 'Header' => $headers]);
                $json = json_decode($response, true);
                $arr = $json["music.search.SearchCgiService"]["data"]["body"]["song"]["list"] ?? [];

                foreach ($arr as $value) {
                    $songs[] = [
                        'song_name' => $value['title'],
                        'type' => 'qq',
                        'id' => $value['mid'],
                        'artist_name' => implode('/', array_column($value['singer'] ?? [], 'name'))
                    ];
                }
                break;
        }
        return json(['songs' => $songs]);
    }
    public function info(Request $request)
{
  $token = Request::param('token');
    $checkResult = $this->checkAndUpdateUserLimit($token);
    if ($checkResult !== true) {
        return $checkResult;
    }
    $type = Request::param('type');
    $id = Request::param('songId');
    $apiUrl = Config::get('api.music') . '?input=' . $id . '&filter=id&type=' . $type . '&page=1&url=' . $_SERVER['SERVER_NAME'];
    $json = send_get($apiUrl);
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return Response::create([
            'code' => 0,
            'msg'  => 'JSON解析失败',
            'data' => ''
        ], 'json', 500)->header(['Content-Type' => 'application/json']);
    }
    $formattedData = [
        'title'  => $data['title'] ?? '未知标题',
        'id'     => $data['id'] ?? '未知ID',
        'url'    => $data['url'] ?? '未知链接',
        'author' => $data['author'] ?? '未知作者',
        'pic'    => $data['pic'] ?? '未知图片',
        'lrc'    => $data['lrc'] ?? '无歌词',
    ];
    return Response::create([
        'code' => 1,
        'msg'  => '成功',
        'data' => $formattedData
    ], 'json')->header(['Content-Type' => 'application/json']);
}
public function url(Request $request)
    {
        $token = Request::param('token');
    $checkResult = $this->checkAndUpdateUserLimit($token);
    if ($checkResult !== true) {
        return $checkResult;
    }
        $type = Request::param('type');
        $id = Request::param('songId');

        $apiUrl = Config::get('api.music') . '?input=' . $id . '&filter=id&type=' . $type . '&page=1&url=' . $_SERVER['SERVER_NAME'];
        $json = send_get($apiUrl);
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::create([
                'code' => 0,
                'msg'  => 'JSON解析失败',
                'data' => ''
            ], 'json', 500)->header(['Content-Type' => 'application/json']);
        }
        $formattedData = [
            'url'    => $data['url'] ?? '未知链接',
        ];
        return Response::create([
            'code' => 1,
            'msg'  => '成功',
            'data' => $formattedData
        ], 'json')->header(['Content-Type' => 'application/json']);
    }
    public function playlist(Request $request)
{
    $token = Request::param('token');
    $sheetid = Request::param('sheetid');
    $type = Request::param('type');
    $checkResult = $this->checkAndUpdateUserLimit($token);
    if ($checkResult !== true) {
        return $checkResult;
    }
    $data = Request::post();
    $rule = [
        'sheetid' => 'require',
        'type'    => 'require'
    ];
    $message = [
        'sheetid.require' => '歌单ID不能为空',
        'type.require'    => '类型不能为空'
    ];
    $validate = new \think\Validate($rule, $message);

    if (!$validate->check($data)) {
        return json([
            'code' => -1,
            'msg'  => $validate->getError()
        ]);
    }
    if ($type === null) {
        return json([
            'code' => -1,
            'msg'  => '类型不能为空'
        ]);
    }
    $songs = Song::findSheetInfo($type, $sheetid);
    if (empty($songs)) {
        return json([
            'code' => -1,
            'msg'  => '未找到任何歌单'
        ]);
    }
    $songCount = count($songs);
    $typeArray = array_fill(0, $songCount, $type);
    return json([
        'code' => 1,
        'msg'  => '获取成功',
        'data' => [
            'songId'     => array_column($songs, 'song_id'),
            'songName'   => array_column($songs, 'name'),
            'albumName'  => array_column($songs, 'album_name'),
            'artistName' => array_column($songs, 'artist_name'),
            'type'       => $typeArray 
        ]
    ]);
}
private function checkAndUpdateUserLimit(string $token)
{
    if (!Openapi::Token($token)) {
        return Response::create([
            'code' => 0,
            'msg'  => '用户不存在',
            'data' => ''
        ], 'json', 403)->header(['Content-Type' => 'application/json']);
    }

    try {
        $record = Db::name('users')->where('token2', $token)->find();
        if (!$record) {
            return Response::create([
                'code' => 0,
                'msg'  => '用户不存在',
                'data' => ''
            ], 'json', 403)->header(['Content-Type' => 'application/json']);
        }
        if (in_array($record['power'], [0, 1])) {
            return true;
        }

        $newLimit = $record['limit2'] - 1;
        if ($newLimit < 0) {
            return Response::create([
                'code' => 0,
                'msg'  => '额度不足',
                'data' => ''
            ], 'json', 400)->header(['Content-Type' => 'application/json']);
        }

        Db::name('users')->where('token2', $token)->update(['limit2' => $newLimit]);
        return true;
    } catch (DbException $e) {
        return Response::create([
            'code' => 0,
            'msg'  => '数据库错误: ' . $e->getMessage(),
            'data' => ''
        ], 'json', 500)->header(['Content-Type' => 'application/json']);
    }
}
}
