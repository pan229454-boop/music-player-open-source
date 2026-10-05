<?php
namespace PHPMailer;
/** Multi-source storage. Not activated in controllers until integration is complete. */
class MusicSourceRegistry
{
    private static function path() { return root_path() . 'storage/music-sources.json'; }
    public static function read()
    {
        if (!is_file(self::path())) {
            $old = MusicApiSettings::read();
            $sources = ['legacy' => ['id'=>'legacy','name'=>'原接口','provider'=>'legacy','enabled'=>true,'endpoint'=>'','key'=>'']];
            if (!empty($old['key'])) $sources['shy_migrated'] = ['id'=>'shy_migrated','name'=>'ShyMusic（原配置）','provider'=>'shymusic','enabled'=>($old['provider'] ?? '') === 'shymusic','endpoint'=>$old['endpoint'],'key'=>$old['key']];
            return $sources;
        }
        $raw = file_get_contents(self::path());
        $data = $raw === false ? null : json_decode($raw, true);
        if (!is_array($data)) throw new \RuntimeException('音乐接口列表无法读取');
        self::validate($data);
        return $data;
    }
    private static function validate(array $sources)
    {
        if (!isset($sources['legacy']) || count($sources)>50) throw new \InvalidArgumentException('必须保留原接口，接口数量最多50个');
        foreach ($sources as $id=>$s) {
            if (!is_string($id) || !preg_match('/^[a-z][a-z0-9_]{0,63}$/D',$id) || !is_array($s) || ($s['id'] ?? null)!==$id) throw new \InvalidArgumentException('接口标识不正确');
            if (!is_string($s['name'] ?? null) || trim($s['name'])==='' || strlen($s['name'])>120 || preg_match('/[\x00-\x1f\x7f]/',$s['name']) || !is_bool($s['enabled'] ?? null)) throw new \InvalidArgumentException('接口名称或状态不正确');
            if ($id==='legacy') {
                if (($s['provider'] ?? '')!=='legacy' || ($s['key'] ?? null)!=='' || ($s['endpoint'] ?? null)!=='') throw new \InvalidArgumentException('原接口配置不正确');
            } else {
                if (($s['provider'] ?? '')!=='shymusic') throw new \InvalidArgumentException('暂不支持此适配协议');
                self::validateEndpoint($s['endpoint'] ?? null);
                $key=$s['key'] ?? null;
                if (!is_string($key) || $key==='' || strlen($key)>512 || preg_match('/[\x00-\x20\x7f]/',$key)) throw new \InvalidArgumentException('接口密钥格式不正确');
            }
        }
    }
    public static function validateEndpoint($url)
    {
        if (!is_string($url) || strlen($url)>2048 || !filter_var($url,FILTER_VALIDATE_URL) || preg_match('/[\x00-\x20\x7f]/',$url)) throw new \InvalidArgumentException('接口地址不正确');
        $p=parse_url($url);
        if (!in_array($p['scheme'] ?? '', ['http','https'],true) || isset($p['user']) || isset($p['pass']) || isset($p['query']) || isset($p['fragment']) || empty($p['host'])) throw new \InvalidArgumentException('接口地址仅允许HTTP/HTTPS，不可包含密钥、查询参数或用户凭据');
        return $p;
    }
    public static function publicAddresses($host)
    {
        $ips=filter_var($host,FILTER_VALIDATE_IP) ? [$host] : gethostbynamel($host);
        if (!$ips) throw new \RuntimeException('接口域名无法解析');
        foreach ($ips as $ip) if (!filter_var($ip,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) throw new \RuntimeException('接口不允许访问内网或保留地址');
        return $ips;
    }
    public static function publicList($enabledOnly=false)
    {
        $out=[];
        foreach(self::read() as $s) {
            if($enabledOnly && !$s['enabled']) continue;
            $s['has_key']=$s['key']!==''; unset($s['key']);
            $s['platforms']=is_array($s['platforms'] ?? null) && count($s['platforms']) ? array_values(array_unique(array_merge(['netease'],$s['platforms']))) : ($s['provider']==='shymusic' ? ['netease'] : ['netease','qq','kugou','qishui']);
            $out[]=$s;
        }
        return $out;
    }
    public static function get($id)
    {
        if(!is_string($id)) throw new \InvalidArgumentException('接口标识不正确');
        $sources=self::read();
        if(!isset($sources[$id])) throw new \RuntimeException('音乐接口不存在');
        if(!$sources[$id]['enabled']) throw new \RuntimeException('音乐接口已关闭');
        return $sources[$id];
    }
    public static function upsert(array $input)
    {
        $sources = self::read();
        $id = $input['id'] ?? '';
        if (!is_string($id)) throw new \InvalidArgumentException('接口标识不正确');
        if ($id === '') $id = 'source_' . bin2hex(random_bytes(12));
        elseif (!isset($sources[$id])) throw new \InvalidArgumentException('接口不存在，请通过新增操作创建');
        $old = $sources[$id] ?? null;
        $key = $input['key'] ?? '';
        if (!is_string($key)) throw new \InvalidArgumentException('密钥格式不正确');
        if ($key === '') $key = $old['key'] ?? '';
        $sources[$id] = ['id'=>$id, 'name'=>$input['name'] ?? '', 'provider'=>$id === 'legacy' ? 'legacy' : ($input['provider'] ?? ''), 'enabled'=>$input['enabled'] ?? null, 'endpoint'=>$id === 'legacy' ? '' : ($input['endpoint'] ?? ''), 'key'=>$id === 'legacy' ? '' : $key, 'platforms'=>is_array($input['platforms'] ?? null) ? array_values($input['platforms']) : ($old['platforms'] ?? ['netease'])];
        self::save($sources);
        return $id;
    }
    public static function setEnabled($id, $enabled)
    {
        if (!is_string($id) || !is_bool($enabled)) throw new \InvalidArgumentException('接口状态格式不正确');
        $sources = self::read();
        if (!isset($sources[$id])) throw new \InvalidArgumentException('接口不存在');
        $sources[$id]['enabled'] = $enabled;
        self::save($sources);
    }
    public static function save(array $sources)

    {
        self::validate($sources);
        $dir=dirname(self::path());
        if(!is_dir($dir) && !mkdir($dir,0700,true) && !is_dir($dir)) throw new \RuntimeException('无法创建音乐接口配置目录');
        $tmp=tempnam($dir,'sources-');
        if($tmp===false) throw new \RuntimeException('音乐接口配置目录不可写');
        chmod($tmp,0600);
        $raw=json_encode($sources,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if($raw===false || file_put_contents($tmp,$raw,LOCK_EX)===false || !rename($tmp,self::path())) { @unlink($tmp); throw new \RuntimeException('音乐接口配置保存失败'); }
        chmod(self::path(),0600);
    }
}
