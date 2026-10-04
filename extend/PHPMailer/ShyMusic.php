<?php
namespace PHPMailer;
class ShyMusic
{
    public static function enabled()
    {
        return (MusicApiSettings::read()['provider'] ?? 'legacy') === 'shymusic';
    }
    private static function platform($type)
    {
        // Only Netease has been verified end-to-end against the purchased API.
        if ($type !== 'netease') throw new \InvalidArgumentException('当前ShyMusic仅启用已验证的网易云接口；其他平台请切换原接口');
        return 'wyy';
    }
    private static function request(array $params, $audio = false)
    {
        $settings = MusicApiSettings::read();
        if (($settings['provider'] ?? '') !== 'shymusic') throw new \RuntimeException('ShyMusic尚未启用');
        if (!function_exists('curl_init')) throw new \RuntimeException('服务器需启用PHP cURL扩展');
        $url = $settings['endpoint'] . '?' . http_build_query(array_merge($params, ['shykey' => $settings['key']]));
        $body = ''; $location = ''; $tooLarge = false;
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_FOLLOWLOCATION => false, CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 25,
            CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HEADERFUNCTION => function($ch, $line) use (&$location) {
                if (stripos($line, 'Location:') === 0) $location = trim(substr($line, 9));
                return strlen($line);
            },
            CURLOPT_WRITEFUNCTION => function($ch, $chunk) use (&$body, &$tooLarge) {
                if (strlen($body) + strlen($chunk) > 2097152) { $tooLarge = true; return 0; }
                $body .= $chunk; return strlen($chunk);
            }]);
        $ok = curl_exec($ch); $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
        if ($tooLarge || $ok === false) throw new \RuntimeException('音乐API连接失败或响应过大，请检查网络和接口地址');
        if ($audio && in_array($status, [301,302,303,307,308], true) && $location !== '') return self::audioUrl($location, $settings['key']);
        if ($status < 200 || $status >= 300) throw new \RuntimeException('音乐API请求失败，请检查套餐权限及接口地址');
        $decoded = json_decode($body, true);
        if (is_array($decoded) && isset($decoded['code']) && !in_array($decoded['code'], [0,200,'0','200'], true)) throw new \RuntimeException('音乐API拒绝请求，请检查密钥、套餐有效期及主备站权限');
        if ($audio) {
            $url = is_array($decoded) ? ($decoded['url'] ?? ($decoded['data']['url'] ?? '')) : trim($body);
            return self::audioUrl($url, $settings['key']);
        }
        if (!is_array($decoded)) throw new \RuntimeException('音乐API响应格式不正确');
        return $decoded;
    }
    private static function audioUrl($url, $key)
    {
        if (!is_string($url) || strlen($url) > 8192 || preg_match('/[\x00-\x20\x7f]/', $url) || !filter_var($url, FILTER_VALIDATE_URL)) throw new \RuntimeException('音乐API未返回有效播放地址');
        $p = parse_url($url);
        // This adapter only serves audited Netease CDN redirects, never provider URLs containing keys.
        if (!in_array($p['scheme'] ?? '', ['http','https'], true) || !preg_match('/(^|\.)music\.126\.net$/iD', $p['host'] ?? '') || isset($p['user']) || isset($p['pass']) || strpos($url, $key) !== false || stripos($url, 'shykey=') !== false) throw new \RuntimeException('音乐API返回了不支持的播放地址');
        return $url;
    }
    public static function search($type, $name)
    {
        $platform = self::platform($type);
        if (!is_string($name) || trim($name) === '' || strlen($name) > 300) throw new \InvalidArgumentException('请输入有效的搜索关键词');
        $data = self::request(['type' => $platform, 'name' => trim($name), 'page' => 1, 'limit' => 50]);
        $rows = $data['data'] ?? $data; $songs = [];
        if (!is_array($rows)) throw new \RuntimeException('搜索结果格式不正确');
        foreach ($rows as $row) {
            if (!is_array($row) || !isset($row['id'], $row['name']) || !preg_match('/^[0-9]{1,24}$/D', (string)$row['id'])) continue;
            $songs[] = ['song_name' => (string)$row['name'], 'type' => 'netease', 'id' => (string)$row['id'], 'artist_name' => is_string($row['singer'] ?? null) ? $row['singer'] : ''];
        }
        return $songs;
    }
    private static function songParams($type, $id, $suffix)
    {
        $platform = self::platform($type);
        if (!is_scalar($id) || !preg_match('/^[0-9]{1,24}$/D', (string)$id)) throw new \InvalidArgumentException('歌曲ID格式不正确');
        return ['type' => $platform . $suffix, 'id' => (string)$id];
    }
    public static function info($type, $id)
    {
        $data = self::request(self::songParams($type, $id, '_song_info'));
        $row = $data['data'][0] ?? null;
        if (!is_array($row) || !isset($row['name'])) throw new \RuntimeException('未找到歌曲信息');
        $pic = $row['pic'] ?? '';
        if (!is_string($pic) || !filter_var($pic, FILTER_VALIDATE_URL) || !in_array(parse_url($pic, PHP_URL_SCHEME), ['http','https'], true) || stripos($pic, 'shykey=') !== false) $pic = '';
        return ['code' => 0, 'song_name' => (string)$row['name'], 'artist_name' => is_string($row['singer'] ?? null) ? $row['singer'] : '', 'album_name' => is_string($row['album'] ?? null) ? $row['album'] : '', 'album_cover' => $pic, 'music_url' => '', 'location' => '', 'lyric' => ''];
    }
    public static function audio($type, $id)
    {
        return self::request(array_merge(self::songParams($type, $id, '_url'), ['down' => 1]), true);
    }
    public static function lyric($type, $id)
    {
        $data = self::request(self::songParams($type, $id, '_lrc'));
        if (!isset($data['lyric']) || !is_string($data['lyric'])) throw new \RuntimeException('歌词响应格式不正确');
        return $data['lyric'];
    }
}
