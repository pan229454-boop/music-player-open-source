<?php
namespace PHPMailer;

class MailSettings
{
    private static function path()
    {
        return root_path() . 'storage/mail-settings.json';
    }

    public static function read()
    {
        $path = self::path();
        if (!is_file($path)) return [];
        $raw = file_get_contents($path);
        $data = $raw === false ? null : json_decode($raw, true);
        if (!is_array($data)) throw new \RuntimeException('邮件配置文件无法读取，请检查服务器文件权限或备份');
        return $data;
    }

    public static function save(array $data)
    {
        $dir = dirname(self::path());
        if (!is_dir($dir) && !mkdir($dir, 0700, true) && !is_dir($dir)) {
            throw new \RuntimeException('无法创建邮件配置目录，请检查 storage 目录写入权限');
        }
        $tmp = tempnam($dir, 'mail-');
        if ($tmp === false) throw new \RuntimeException('邮件配置目录不可写');
        chmod($tmp, 0600);
        $raw = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($raw === false || file_put_contents($tmp, $raw, LOCK_EX) === false || !rename($tmp, self::path())) {
            @unlink($tmp);
            throw new \RuntimeException('邮件配置保存失败，请检查 storage 目录权限');
        }
        chmod(self::path(), 0600);
    }
}
