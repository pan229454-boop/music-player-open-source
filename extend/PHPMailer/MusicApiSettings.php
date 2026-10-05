<?php
namespace PHPMailer;

class MusicApiSettings
{
    private static function path()
    {
        return root_path() . 'storage/music-api-settings.json';
    }

    public static function read()
    {
        $path = self::path();
        if (!is_file($path)) return [];
        $raw = file_get_contents($path);
        $data = $raw === false ? null : json_decode($raw, true);
        if (!is_array($data)) throw new \RuntimeException('音乐API配置文件无法读取，请检查服务器文件权限或备份');
        if ($data) self::validate($data);
        return $data;
    }

    public static function validate(array $data)
    {
        if (!in_array($data['provider'] ?? null, ['legacy', 'shymusic'], true)) throw new \InvalidArgumentException('请选择音乐接口');
        $endpoint = $data['endpoint'] ?? '';
        // Only audited provider hosts are permitted: avoid SSRF and sending keys to arbitrary hosts.
        if (!is_string($endpoint) || !in_array($endpoint, ['http://shybot.top/v2/music/api/', 'https://shybot.top/v2/music/api/', 'https://cn2.shybot.top:48443/v2/music/api/'], true)) throw new \InvalidArgumentException('请选择支持的 ShyMusic 接口地址');
        $key = $data['key'] ?? '';
        if (!is_string($key) || strlen($key) > 512 || preg_match('/[\x00-\x20\x7f]/', $key)) throw new \InvalidArgumentException('音乐API密钥格式不正确');
        if ($data['provider'] === 'shymusic' && $key === '') throw new \InvalidArgumentException('启用ShyMusic前请填写密钥');
    }
    public static function save(array $data)
    {
        self::validate($data);
        $dir = dirname(self::path());
        if (!is_dir($dir) && !mkdir($dir, 0700, true) && !is_dir($dir)) {
            throw new \RuntimeException('无法创建音乐API配置目录，请检查 storage 目录写入权限');
        }
        $tmp = tempnam($dir, 'mail-');
        if ($tmp === false) throw new \RuntimeException('音乐API配置目录不可写');
        chmod($tmp, 0600);
        $raw = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($raw === false || file_put_contents($tmp, $raw, LOCK_EX) === false || !rename($tmp, self::path())) {
            @unlink($tmp);
            throw new \RuntimeException('音乐API配置保存失败，请检查 storage 目录权限');
        }
        chmod(self::path(), 0600);
    }
}
