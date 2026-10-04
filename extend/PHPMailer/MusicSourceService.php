<?php
namespace PHPMailer;
/** Explicit-source ShyMusic adapter. Does not mutate global or process-wide settings. */
class MusicSourceService
{
    private $source;
    public function __construct($id) { $this->source=MusicSourceRegistry::get($id); }
    public function provider() { return $this->source['provider']; }
    private function request($operation,$id=null,$name=null)
    {
        if($this->provider()!=='shymusic') throw new \RuntimeException('原接口应由原解析流程处理');
        if(!function_exists('curl_init')) throw new \RuntimeException('需启用PHP cURL扩展');
        $p=['type'=>$operation,'shykey'=>$this->source['key']];
        if($id!==null) {
            if(!is_scalar($id)||!preg_match('/^[0-9]{1,24}$/D',(string)$id)) throw new \InvalidArgumentException('歌曲ID不正确');
            $p['id']=(string)$id;
        }
        if($operation==='wyy') {
            if(!is_string($name)||trim($name)===''||strlen($name)>300) throw new \InvalidArgumentException('搜索关键词不正确');
            $p+=['name'=>trim($name),'page'=>1,'limit'=>50];
        }
        if($operation==='wyy_url') $p['down']=1;
        $body='';$location='';
        $endpoint=MusicSourceRegistry::validateEndpoint($this->source['endpoint']);
        $ips=MusicSourceRegistry::publicAddresses($endpoint['host']);
        $port=$endpoint['port'] ?? ($endpoint['scheme']==='https' ? 443 : 80);
        $ch=curl_init($this->source['endpoint'].'?'.http_build_query($p));
        curl_setopt($ch,CURLOPT_RESOLVE,[$endpoint['host'].':'.$port.':'.$ips[0]]);
        curl_setopt($ch,CURLOPT_PROXY,'');
        curl_setopt_array($ch,[CURLOPT_FOLLOWLOCATION=>false,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>25,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2,
            CURLOPT_HEADERFUNCTION=>function($ch,$line)use(&$location){if(stripos($line,'Location:')===0)$location=trim(substr($line,9));return strlen($line);},
            CURLOPT_WRITEFUNCTION=>function($ch,$chunk)use(&$body){if(strlen($body)+strlen($chunk)>2097152)return 0;$body.=$chunk;return strlen($chunk);}]);
        $ok=curl_exec($ch);$status=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
        if($ok===false)throw new \RuntimeException('音乐API连接失败或响应过大');
        if($operation==='wyy_url'&&in_array($status,[301,302,303,307,308],true)&&$location!=='')return $this->audioUrl($location);
        if($status<200||$status>=300)throw new \RuntimeException('音乐API请求失败');
        $data=json_decode($body,true);
        if(is_array($data)&&isset($data['code'])&&!in_array($data['code'],[0,200,'0','200'],true))throw new \RuntimeException('接口拒绝请求，请检查套餐权限');
        if($operation==='wyy_url')return $this->audioUrl(is_array($data)?($data['url']??($data['data']['url']??'')):trim($body));
        if(!is_array($data))throw new \RuntimeException('音乐API响应格式不正确');
        return $data;
    }
    private function platform($type) { if($type!=='netease')throw new \InvalidArgumentException('此接口目前仅支持网易云'); }
    private function audioUrl($url)
    {
        if(!is_string($url)||strlen($url)>8192||preg_match('/[\x00-\x20\x7f]/',$url)||!filter_var($url,FILTER_VALIDATE_URL))throw new \RuntimeException('播放地址无效');
        $p=parse_url($url);
        if(!in_array($p['scheme']??'',['http','https'],true)||!preg_match('/(^|\.)music\.126\.net$/iD',$p['host']??'')||isset($p['user'])||isset($p['pass'])||strpos($url,$this->source['key'])!==false||stripos($url,'shykey=')!==false)throw new \RuntimeException('不支持的播放地址');
        return $url;
    }
    public function search($type,$name)
    {
        $this->platform($type);$data=$this->request('wyy',null,$name);$rows=$data['data']??$data;$out=[];
        foreach($rows as $r)if(is_array($r)&&isset($r['id'],$r['name'])&&preg_match('/^[0-9]{1,24}$/D',(string)$r['id']))$out[]=['song_name'=>(string)$r['name'],'artist_name'=>is_string($r['singer']??null)?$r['singer']:'','type'=>'netease','id'=>(string)$r['id'],'music_source'=>$this->source['id']];
        return $out;
    }
    public function audio($type,$id){$this->platform($type);return $this->request('wyy_url',$id);}
    public function lyric($type,$id){$this->platform($type);$d=$this->request('wyy_lrc',$id);if(!is_string($d['lyric']??null))throw new \RuntimeException('歌词格式不正确');return $d['lyric'];}
    public function info($type,$id)
    {
        $this->platform($type);$d=$this->request('wyy_song_info',$id);$r=$d['data'][0]??null;
        if(!is_array($r)||!isset($r['name']))throw new \RuntimeException('未找到歌曲');
        $pic=$r['pic']??'';
        if(!is_string($pic)||!filter_var($pic,FILTER_VALIDATE_URL)||!in_array(parse_url($pic,PHP_URL_SCHEME),['http','https'],true)||stripos($pic,'shykey=')!==false||strpos($pic,$this->source['key'])!==false)$pic='';
        return ['code'=>0,'song_name'=>(string)$r['name'],'artist_name'=>is_string($r['singer']??null)?$r['singer']:'','album_name'=>is_string($r['album']??null)?$r['album']:'','album_cover'=>$pic,'music_url'=>'','location'=>'','lyric'=>'','music_source'=>$this->source['id']];
    }
}
