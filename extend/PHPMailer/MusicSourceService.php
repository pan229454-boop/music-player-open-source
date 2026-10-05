<?php
namespace PHPMailer;
/** Explicit-source ShyMusic adapter. Does not mutate global or process-wide settings. */
class MusicSourceService
{
    private $source;
    public function __construct($id) { $this->source=MusicSourceRegistry::get($id); }
    public function provider() { return $this->source['provider']; }
    private function request($operation,$id=null,$name=null,$raw=false)
    {
        if($this->provider()!=='shymusic') throw new \RuntimeException('原接口应由原解析流程处理');
        if(!function_exists('curl_init')) throw new \RuntimeException('需启用PHP cURL扩展');
        $p=['type'=>$operation,'shykey'=>$this->source['key']];
        if($id!==null) {
            if(!is_scalar($id)) throw new \InvalidArgumentException('歌曲标识不正确');
            $raw=(string)$id;
            if($operation==='qq_url') { $parts=explode('|',$raw); if(count($parts)<2) throw new \InvalidArgumentException('QQ歌曲参数不完整'); $p['mid']=$parts[0]; $p['media_mid']=$parts[1]; }
            elseif($operation==='kg_url') { $parts=explode('|',$raw); if(count($parts)<3) throw new \InvalidArgumentException('酷狗歌曲参数不完整'); $p['hash']=$parts[0]; $p['album_id']=$parts[1]; $p['album_audio_id']=$parts[2]; }
            else { if(!preg_match('/^[0-9]{1,24}$/D',$raw)) throw new \InvalidArgumentException('歌曲ID不正确'); $p['id']=$raw; }
        }
        if(in_array($operation,['wyy','qq','kugou','qishui'],true)) {
            if(!is_string($name)||trim($name)===''||strlen($name)>300) throw new \InvalidArgumentException('搜索关键词不正确');
            $p+=['name'=>trim($name),'page'=>1,'limit'=>50];
        }
        if(in_array($operation,['wyy_url','qq_url','kg_url','qishui_url'],true)) $p['down']=1;
        $body='';$location='';
        $endpoint=MusicSourceRegistry::validateEndpoint($this->source['endpoint']);
        $ips=MusicSourceRegistry::publicAddresses($endpoint['host']);
        $port=$endpoint['port'] ?? ($endpoint['scheme']==='https' ? 443 : 80);
        $ch=curl_init($this->source['endpoint'].'?'.http_build_query($p));
        curl_setopt($ch,CURLOPT_RESOLVE,[$endpoint['host'].':'.$port.':'.$ips[0]]);
        curl_setopt($ch,CURLOPT_PROXY,'');
        curl_setopt_array($ch,[CURLOPT_FOLLOWLOCATION=>false,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>25,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_SSL_VERIFYHOST=>2,
            CURLOPT_HEADERFUNCTION=>function($ch,$line)use(&$location){if(stripos($line,'Location:')===0)$location=trim(substr($line,9));return strlen($line);},
            CURLOPT_WRITEFUNCTION=>function($ch,$chunk)use(&$body){if(strlen($body)+strlen($chunk)>20971520)return 0;$body.=$chunk;return strlen($chunk);}]);
        $ok=curl_exec($ch);$status=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
        if($ok===false)throw new \RuntimeException('音乐API连接失败或响应过大');
        if(in_array($operation,['wyy_url','qq_url','kg_url','qishui_url'],true)&&in_array($status,[301,302,303,307,308],true)&&$location!=='')return $this->audioUrl($location);
        if($status<200||$status>=300)throw new \RuntimeException('音乐API请求失败');
        if($raw){ $ct=(strncmp($body,"\x00\x00\x00\x18ftyp",8)===0||strpos(substr($body,0,64),'ftyp')!==false)?'audio/mp4':'audio/mpeg'; return ['body'=>$body,'content_type'=>$ct,'length'=>strlen($body)]; }
        $data=json_decode($body,true);
        if(!in_array($operation,['wyy_url','qq_url','kg_url','qishui_url'],true)&&is_array($data)&&isset($data['code'])&&!in_array($data['code'],[0,1,200,'0','1','200'],true)&&!isset($data['url'])&&!isset($data['data']['url']))throw new \RuntimeException('音乐接口返回错误');
        if(in_array($operation,['wyy_url','qq_url','kg_url','qishui_url'],true)){
            $u='';
            if(is_array($data)){
                $u=$data['url']??'';
                if($u==='' && isset($data['data']) && is_array($data['data'])) $u=$data['data']['url']??($data['data'][0]['url']??'');
                if($u==='' ) $u=$data['play_url']??($data['audio_url']??($data['mp3']??''));
            } else $u=trim($body);
            return $this->audioUrl($u);
        }
        if(!is_array($data))throw new \RuntimeException('音乐API响应格式不正确');
        return $data;
    }
    private function platform($type) { if(!in_array($type,['netease','qq','kugou','qishui'],true)) throw new \InvalidArgumentException('此接口暂不支持该音乐平台'); }
    private function audioUrl($url)
    {
        if(!is_string($url)||strlen($url)>8192||preg_match('/[\x00-\x20\x7f]/',$url)||!filter_var($url,FILTER_VALIDATE_URL))throw new \RuntimeException('播放地址无效');
        $p=parse_url($url);
        if(!in_array($p['scheme']??'',['http','https'],true)||isset($p['user'])||isset($p['pass'])||strpos($url,$this->source['key'])!==false||stripos($url,'shykey=')!==false)throw new \RuntimeException('不支持的播放地址');
        return $url;
    }
    public function search($type,$name)
    {
        $this->platform($type); $map=['netease'=>'wyy','qq'=>'qq','kugou'=>'kugou','qishui'=>'qishui'];
        $data=$this->request($map[$type],null,$name); $rows=$data['data']??($data['result']??$data); if(isset($rows['list']))$rows=$rows['list']; $out=[];
        if(!is_array($rows))return $out;
        foreach($rows as $r){ if(!is_array($r))continue; $id=$r['id']??($r['mid']??($r['hash']??null)); if($type==='qq' && isset($r['mid'],$r['media_mid'])) $id=$r['mid'].'|'.$r['media_mid']; if($type==='kugou' && isset($r['hash'],$r['album_id'],$r['album_audio_id'])) $id=$r['hash'].'|'.$r['album_id'].'|'.$r['album_audio_id']; $title=$r['name']??($r['songname']??($r['song_name']??'')); if($id===null||$title==='')continue; $out[]=['song_name'=>(string)$title,'artist_name'=>(string)($r['singer']??($r['artist']??'')),'type'=>$type,'id'=>(string)$id,'music_source'=>$this->source['id']]; }
        return $out;
    }
    public function streamAudio($type,$id)
    {
        $this->platform($type); $ops=['netease'=>'wyy_url','qq'=>'qq_url','kugou'=>'kg_url','qishui'=>'qishui_url'];
        return $this->request($ops[$type],$id,null,true);
    }
    public function audio($type,$id){$this->platform($type);$ops=['netease'=>'wyy_url','qq'=>'qq_url','kugou'=>'kg_url','qishui'=>'qishui_url'];return $this->request($ops[$type],$id);}
    public function lyric($type,$id){$this->platform($type);$d=$this->request('wyy_lrc',$id);if(!is_string($d['lyric']??null))throw new \RuntimeException('歌词格式不正确');return $d['lyric'];}
    public function info($type,$id)
    {
        $this->platform($type);
        $audio=$this->audio($type,$id);
        return ['code'=>0,'song_name'=>'','artist_name'=>'','album_name'=>'','album_cover'=>'','music_url'=>$audio,'location'=>$audio,'lyric'=>'','music_source'=>$this->source['id']];
    }
}
