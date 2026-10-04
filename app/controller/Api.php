<?php

namespace {
}
namespace app\controller {
    class Api extends Common {
        public function clearCache() {
            if (\think\facade\Cache::clear()) {
                $result = ['code' => 0, 'msg' => '成功清除所有缓存'];
            } else {
                $result = ['code' => -1, 'msg' => '清除缓存失败'];
            }
            return json($result);
        }
        public function playerinfo() {
            $act = input('get.');
            $id = $act['id'];
            if (!$id) {
                $result['code'] = -1;
                return json($result);
            }
            if ((bool)\app\model\Player::checkPlayerKey($id)) {
                $result['code'] = -1;
                return json($result);
            }
            $player = \app\model\Player::where('id', $id)->find();
            $data = ['plays' => $player['plays'] + 1, 'endtime' => date('Y-m-d H:i:s')];
            \app\model\Player::where('id', '=', $id)->data($data)->update();
            $data2 = ['player_id' => $id, 'user_id' => $player['user_id'], 'side' => 'ios', 'create_time' => date('Y-m-d H:i:s')];
            \app\model\Plays::add($data2);
            $cache = \think\facade\Cache::get('info' . $id);
            if ($cache) {
                return response($act['jsoncallback'] . '(' . $cache . ')');
            }
            $result = [];
            $songSheetList = [];
            $songSheets = \app\model\PlayerSongSheet::alias('pss')->join('song_sheet ss', 'ss.id=pss.song_sheet_id')->field('ss.*')->where('pss.player_id', $id)->order('pss.taxis asc')->select();
            foreach ($songSheets as $key => $item) {
                $songs = \app\model\Song::where('song_sheet_id', $item['id'])->order('taxis asc')->select();
                $songlists = [];
                foreach ($songs as $key2 => $item2) {
                    $songlists[$key2] = ['type' => $item2['type'], 'id' => $item2['song_id'], 'name' => $item2['name'], 'cover' => $item2['album_cover'], 'artist' => $item2['artist_name'], 'album' => $item2['album_name'], 'url' => $item2['location'], 'lyric' => $item2['lyric']];
                }
                $songSheetList[$key] = ['SheetName' => $item['name'], 'author' => $item['author'], 'songs' => $songlists];
            }
            $result = ['playerName' => $player['name'], 'showGreeting' => $player['show_greeting'], 'switchopen' => $player['switchopen'], 'time' => $player['time'], 'showLrc' => $player['show_lrc'], 'showMsg' => $player['showmsg'], 'defaultAlbum' => $player['default_album'], 'randomPlayer' => $player['random_player'], 'defaultVolume' => $player['default_volume'], 'greeting' => $player['greeting'], 'autoPlayer' => $player['auto_player'], 'Sheetlist' => $songSheetList, 'showNotes' => $player['show_notes']];
            $result = json_encode($result);
            \think\facade\Cache::set('info' . $id, $result);
            return response($act['jsoncallback'] . '(' . $result . ')');
        }
        public function musicUrl() {
            $data = input('get.');
            if (!(isset($data['sign']) && isset($data['songId']) && isset($data['type']) && isset($data['id']))) {
                return abort(400, 'Invalid sign');
            }
            $skipCheck = false;
            try {
                $authSettings = \PHPMailer\ServerAuthSettings::read();
                $skipCheck = ($authSettings['skip_check'] ?? false) === true;
            } catch (\Throwable $e) {
                return abort(503, '服务器授权配置无法读取');
            }
            $responseData = [];
            if (!$skipCheck) {
            try {
                if (\PHPMailer\ShyMusic::enabled()) return redirect(\PHPMailer\ShyMusic::audio($data['type'], $data['songId']));
            } catch (\Exception $e) {
                return response('音乐接口不可用，请联系站点管理员检查配置', 502);
            }
            $queryParams = http_build_query(['qq' => \think\facade\Config::get('api.qq'), 'skey' => \think\facade\Config::get('api.skey'), 'demo' => $_SERVER['HTTP_HOST']]);
            $response = file_get_contents('https://auth.cenguigui.cn/MusicApi.php?' . $queryParams);
            if ($response === false) {
                die('请求失败，请稍后再试。');
            }
            $responseData = json_decode($response, true);
            }
            if (!$skipCheck && ($responseData['status'] ?? null) == 'error' && ($responseData['message'] ?? null) == '域名未授权') {
                $url = 'https://cdn.cenguigui.cn/Api/tts/2024-12-14-185658_111237.mp3';
            } elseif (!$skipCheck && ($responseData['status'] ?? null) == 'error' && ($responseData['message'] ?? null) == 'skey有误') {
                $url = 'https://cdn.cenguigui.cn/Api/tts/2024-12-14-190011_184063.mp3';
            } else {
                $type = $data['type'];
                $id = $data['songId'];
                $json = send_get(\think\facade\Config::get('api.music') . '?input=' . $id . '&filter=id&type=' . $type . '&page=1&url=' . $_SERVER['SERVER_NAME']);
                $musicData = json_decode($json, true);
                $url = $musicData['url'] ?? $url;
            }
            return redirect($url);
        }
        private function generateSign($songId, $timestamp) {
            $key = 'mqyybfqyyds';
            $baseString = $songId . ':' . $timestamp . ':' . $key;
            $charArray = [];
            for ($i = 0; $i < strlen($baseString); ++$i) {
                $charCode = ord($baseString[$i]);
                $charCode = $charCode + $i % 7 * 3 ^ $i % 5;
                $charArray[] = $charCode;
            }
            $hexString = '';
            foreach ($charArray as $num) {
                $hexString .= str_pad(dechex($num), 2, '0', STR_PAD_LEFT);
            }
            return strrev($hexString);
        }
        private function getOriginalDomain($domain) {
            if (strpos($domain, 'http://') !== false) {
                $domain = str_replace('http://', '', $domain);
            } else {
                $domain = str_replace('https://', '', $domain);
            }
            $strdomain = explode('/', $domain);
            return $strdomain[0];
        }
        private function getTopLevelDomain($domain) {
            $parts = explode('.', $domain);
            $count = count($parts);
            if ($count > 2) {
                $tld = array_slice($parts, -2);
                return implode('.', $tld);
            }
            return implode('.', array_slice($parts, -2));
        }
        public function musicLyric() {
            $data = input('get.');
            if (($data['type'] ?? '') !== 'local') {
                try {
                    if (\PHPMailer\ShyMusic::enabled()) {
                        $callback = $data['jsoncallback'] ?? '';
                        if (!is_string($callback) || !preg_match('/^[A-Za-z_$][A-Za-z0-9_$]*(?:\.[A-Za-z_$][A-Za-z0-9_$]*)*$/D', $callback)) return response('Invalid callback', 400);
                        $result = ['file' => 'null', 'time' => date('Y-m-d H:i:s'), 'type' => 'lrc', 'txt' => \PHPMailer\ShyMusic::lyric($data['type'] ?? '', $data['songId'] ?? '')];
                        return response($callback . '(' . json_encode($result, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ')')->contentType('application/javascript');
                    }
                } catch (\Exception $e) {
                    return response('音乐歌词接口不可用，请联系站点管理员检查配置', 502);
                }
            }
            $cache = \think\facade\Cache::get('musicLyric' . $data['type'] . $data['id']);
            if ($data['type'] == 'local') {
                $id = $data['id'];
                $url = $data['url'];
                $result = send_get($url);
                $result = ['file' => 'null', 'time' => date('Y-m-d h:i:s', time()), 'type' => 'lrc', 'txt' => $result];
            } else {
                $player = \app\model\Player::where('id', '=', $data['id'])->find();
                $userInfo = \app\model\Users::where('uid', '=', $player['user_id'])->find();
                if ($userInfo['power'] == 2) {
                    $json = send_get(\think\facade\Config::get('api.music') . '?input=' . $data['songId'] . '&filter=id&type=' . $data['type'] . '&page=1&url=' . $_SERVER['SERVER_NAME']);
                    $result = json_decode($json, true);
                    $result = ['file' => 'null', 'time' => date('Y-m-d h:i:s', time()), 'type' => 'lrc', 'txt' => $result['lrc']];
                } elseif ($data['type'] == 'kugou') {
                    $ksctext = send_get(\think\facade\Config::get('api.getksc') . '?s=' . $data['songId']);
                    if ($ksctext == '') {
                        $json = send_get(\think\facade\Config::get('api.music') . '?input=' . $data['songId'] . '&filter=id&type=' . $data['type'] . '&page=1&url=' . $_SERVER['SERVER_NAME']);
                        $result = json_decode($json, true);
                        $result = ['file' => 'null', 'time' => date('Y-m-d h:i:s', time()), 'type' => 'lrc', 'txt' => $result['lrc']];
                    } else {
                        $result = ['file' => \think\facade\Config::get('api.getksc') . '?s=' . $data['songId'], 'time' => date('Y-m-d h:i:s', time()), 'type' => 'ksc', 'txt' => $ksctext];
                    }
                } else {
                    $ksc = isset($data['ksc']) ? $data['ksc'] : null;
                    preg_replace('# #', '', $ksc);
                    $res = @file_get_contents($ksc, null, null, 0, 10);
                    if ($res) {
                        $ksctext = send_get($ksc);
                        $result = ['file' => $ksc, 'time' => date('Y-m-d h:i:s', time()), 'type' => 'ksc', 'txt' => $ksctext];
                    } else {
                        $json = send_get(\think\facade\Config::get('api.music') . '?input=' . $data['songId'] . '&filter=id&type=' . $data['type'] . '&page=1&url=' . $_SERVER['SERVER_NAME']);
                        $result = json_decode($json, true);
                        $result = ['file' => 'null', 'time' => date('Y-m-d h:i:s', time()), 'type' => 'lrc', 'txt' => $result['lrc']];
                    }
                }
                \think\facade\Cache::set('musicLyric' . $data['type'] . $data['id'], json_encode($result));
            }
            $result = json_encode($result);
            return response($data['jsoncallback'] . '(' . $result . ')');
        }
        public function mainColor() {
            $url = input('get.url');
            $cache = \think\facade\Cache::get('mainColor' . $url);
            if ($cache) {
                return response($cache);
            }
            $result = 'var cont =';
            if ($url != null && $url != '') {
                [$r, $g, $b] = mainColor($url);
                $result .= "'" . $r . ',' . $g . ',' . $b . "'";
                $grayLevel = $r * 0.299 + $g * 0.587 + $b * 0.114;
                if ($grayLevel >= 150) {
                    $result .= ";font_color='0,0,0';";
                } else {
                    $result .= ";font_color='255,255,255';";
                }
            } else {
                $result .= "'0,0,0';font_color='255,255,255';";
            }
            \think\facade\Cache::set('mainColor' . $url, $result);
            return $result;
        }
        public function PlayerJs($id) {
            $player = \app\model\Player::where('id', '=', $id)->find();
            $user = \app\model\Users::where('uid', '=', $player['user_id'])->find();
            $theme = $user['power'] == 2 ? 1 : $player['theme'];
            $filePath = getcwd() . '/static/guiloveyou/' . $theme . '/player/js12355555555/player.js';
            $loadJQuery = $player['jquery'];
            $playerIdScript = "var playerId = '" . $id . "';";
            $playerScriptContent = json_encode(file_get_contents($filePath));
            if ($loadJQuery == 1) {
                $script = "$playerIdScript\nvar script = document.createElement(\"script\");\nscript.src = \"https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/jquery/3.6.0/jquery.min.js\";\nscript.onload = function() {\n    loadPlayerScript();\n};\ndocument.head.appendChild(script);\n\nfunction loadPlayerScript() {\n    var playerScript = document.createElement(\"script\");\n    playerScript.textContent = $playerScriptContent;\n    document.head.appendChild(playerScript);\n}";
            } else {
                $script = "$playerIdScript\nvar playerScript = document.createElement(\"script\");\nplayerScript.textContent = $playerScriptContent;\ndocument.head.appendChild(playerScript);";
            }
            return response($script)->contentType('application/javascript');
        }
        public function PlayerCss($id) {
            $player = \app\model\Player::where('id', '=', $id)->find();
            $user = \app\model\Users::where('uid', '=', $player['user_id'])->find();
            $theme = $user['power'] == 2 ? 1 : $player['theme'];
            $url = get_domain() . 'static/guiloveyou/' . $theme . '/player/css/player.css';
            return redirect($url);
        }
    }
}
