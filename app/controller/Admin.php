<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Session;
use app\model\Users;
use app\model\Player;
use app\model\SongSheet;
use app\model\Song;
use app\model\PlayerAuth;
use app\model\Links;
use app\model\Pays;

class Admin extends Common
{
    public function index()
    {
		$this->checkLogin();
        return View::fetch();
    }

    public function help()
    {
		$this->checkLogin();
        return View::fetch();
    }
    
    public function changeqq()
    {
		$this->checkLogin();
        return View::fetch();
    }
    
    public function changepass()
    {
		$this->checkLogin();
        return View::fetch();
    }
    
	public function shop()
    {
		$this->checkLogin();
		$userInfo = Users::getLoginUser();
		$pays=Pays::where('uid','=',$userInfo['uid'])->order('addtime', 'desc')->limit(5)->select();
		View::assign('pays', $pays);
        return View::fetch();
    }
	
    public function webset()
    {
        $this->checkLogin();
		$this->checkPower();
        return View::fetch();
    }
    
    public function serverstate()
    {
        $this->checkLogin();
		$this->checkPower();
        return View::fetch();
    }

    public function advanced()
    {
        $this->checkLogin();
        $this->checkPower();
        if (!\think\facade\Session::has('mail_settings_csrf')) {
            \think\facade\Session::set('mail_settings_csrf', bin2hex(random_bytes(32)));
        }
        $csrf = \think\facade\Session::get('mail_settings_csrf');
        if (request()->isPost()) {
            $data = request()->post();
            if (!isset($data['csrf']) || !is_string($data['csrf']) || !is_string($csrf) || !hash_equals($csrf, $data['csrf'])) {
                return json(['code' => -1, 'msg' => '页面已失效，请刷新后重试']);
            }
            try {
                $action = $data['action'] ?? 'smtp';
                if (!is_string($action)) throw new \InvalidArgumentException('请求格式不正确');
                if ($action === 'music_source_save' || $action === 'music_source_toggle') {
                    $enabled = $data['source_enabled'] ?? '';
                    if (!is_string($enabled) || !in_array($enabled, ['0', '1'], true)) throw new \InvalidArgumentException('接口状态不正确');
                    if ($action === 'music_source_toggle') {
                        \PHPMailer\MusicSourceRegistry::setEnabled($data['source_id'] ?? '', $enabled === '1');
                    } else {
                        \PHPMailer\MusicSourceRegistry::upsert([
                            'id' => $data['source_id'] ?? '',
                            'name' => $data['source_name'] ?? '',
                            'provider' => $data['source_provider'] ?? '',
                            'endpoint' => $data['source_endpoint'] ?? '',
                            'key' => $data['source_key'] ?? '',
                            'enabled' => $enabled === '1'
                        ]);
                    }
                    return json(['code' => 0, 'msg' => '接口配置已保存，尚未验证连接和套餐权限', 'sources' => \PHPMailer\MusicSourceRegistry::publicList()]);
                }
                if ($action === 'music_api') {
                    return json(['code'=>-1,'msg'=>'请使用多接口管理列表保存配置']);
                    $provider = $data['music_provider'] ?? '';
                    $endpoint = $data['music_endpoint'] ?? '';
                    $key = $data['music_key'] ?? '';
                    if (!is_string($key)) throw new \InvalidArgumentException('音乐API密钥格式不正确');
                    $old = \PHPMailer\MusicApiSettings::read();
                    if ($key === '') $key = $old['key'] ?? '';
                    $config = ['provider' => $provider, 'endpoint' => $endpoint, 'key' => $key];
                    \PHPMailer\MusicApiSettings::save($config);
                    if (\PHPMailer\MusicApiSettings::read() !== $config) throw new \RuntimeException('音乐API配置写入校验失败');
                    return json(['code' => 0, 'msg' => '音乐API配置已保存；未验证套餐权限', 'music_provider' => $provider, 'has_music_key' => $key !== '']);
                }
                if ($action === 'server_auth_switch') {
                    $value = $data['skip_check'] ?? '0';
                    if (!is_string($value) || !in_array($value, ['0', '1'], true)) throw new \InvalidArgumentException('授权开关值不正确');
                    $settings = \PHPMailer\ServerAuthSettings::read();
                    $settings['skip_check'] = $value === '1';
                    \PHPMailer\ServerAuthSettings::save($settings);
                    $saved = \PHPMailer\ServerAuthSettings::read();
                    if (($saved['skip_check'] ?? false) !== $settings['skip_check']) throw new \RuntimeException('授权开关写入校验失败');
                    return json(['code' => 0, 'skip_check' => $saved['skip_check'], 'msg' => $settings['skip_check'] ? '已跳过本站前置授权检查，不代表获得上游服务授权' : '已恢复本站前置授权检查']);
                }
                if ($action === 'server_auth') {
                    $qq = $data['auth_qq'] ?? '';
                    $key = $data['auth_skey'] ?? '';
                    if (!is_string($qq) || !preg_match('/^[1-9][0-9]{4,19}$/D', $qq)) throw new \InvalidArgumentException('请输入5到20位数字授权QQ');
                    if (!is_string($key) || strlen($key) > 512 || preg_match('/[\x00-\x20\x7f]/', $key)) throw new \InvalidArgumentException('授权密钥格式不正确，不能包含空白字符');
                    $settings = \PHPMailer\ServerAuthSettings::read();
                    if ($key === '') {
                        if (($settings['qq'] ?? '') !== $qq) throw new \InvalidArgumentException('首次配置或更换授权QQ时，请填写授权密钥');
                        $key = $settings['skey'] ?? '';
                    }
                    if ($key === '') throw new \InvalidArgumentException('首次配置请填写授权密钥');
                    \PHPMailer\ServerAuthSettings::save(array_merge($settings, ['qq' => $qq, 'skey' => $key]));
                    return json(['code' => 0, 'msg' => '服务器授权配置已保存，尚未验证外部授权是否有效']);
                }
                if ($action === 'template') {
                    foreach (['template_title', 'template_body', 'homepage'] as $key) {
                        if (!isset($data[$key]) || !is_string($data[$key])) throw new \InvalidArgumentException('模板格式不正确');
                    }
                    $title = trim($data['template_title']);
                    $body = trim($data['template_body']);
                    $homepage = trim($data['homepage']);
                    if ($title === '' || strlen($title) > 200 || preg_match('/[\r\n\x00]/', $title)) throw new \InvalidArgumentException('标题不能为空且不能含换行，最大200字节');
                    if ($body === '' || strlen($body) > 10000 || strpos($body, '{code}') === false || strpos($body, "\0") !== false) throw new \InvalidArgumentException('正文必须包含 {code}，最大10000字节');
                    if (strlen($homepage) > 2048 || !filter_var($homepage, FILTER_VALIDATE_URL) || !in_array(strtolower((string)parse_url($homepage, PHP_URL_SCHEME)), ['http', 'https'], true)) throw new \InvalidArgumentException('主页链接必须是有效的 HTTP 或 HTTPS 网址');
                    $settings = \PHPMailer\MailSettings::read();
                    $settings['template_title'] = $title;
                    $settings['template_body'] = $body;
                    $settings['homepage'] = $homepage;
                    \PHPMailer\MailSettings::save($settings);
                    return json(['code' => 0, 'msg' => '邮件模板已保存']);
                }
                if ($action === 'test') {
                    $email = $data['test_email'] ?? '';
                    if (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) throw new \InvalidArgumentException('请输入有效的测试收件邮箱');
                    $settings = \PHPMailer\MailSettings::read();
                    if (empty($settings['password']) || empty($settings['host']) || empty($settings['from'])) throw new \InvalidArgumentException('请先保存 SMTP 配置');
                    $last = (int)\think\facade\Session::get('mail_test_last', 0);
                    if (time() - $last < 30) throw new \InvalidArgumentException('测试发送间隔至少30秒');
                    \think\facade\Session::set('mail_test_last', time());
                    try { $result = \PHPMailer\SendEmail::SendCode('123456', $email); }
                    catch (\Throwable $e) { $result = false; }
                    return json(['code' => $result === true ? 0 : -1, 'msg' => $result === true ? 'SMTP已接受测试邮件，请检查收件箱和垃圾邮件（示例验证码123456）' : '测试发送失败，请检查 SMTP 配置、授权码及服务器网络']);
                }
                if ($action !== 'smtp') throw new \InvalidArgumentException('未知操作');
                foreach (['host', 'from', 'username', 'from_name', 'password', 'port', 'security'] as $key) {
                    if (isset($data[$key]) && !is_scalar($data[$key])) throw new \InvalidArgumentException('配置格式不正确');
                }
                $host = trim((string)($data['host'] ?? ''));
                $from = trim((string)($data['from'] ?? ''));
                $username = trim((string)($data['username'] ?? ''));
                $name = trim((string)($data['from_name'] ?? ''));
                $port = filter_var($data['port'] ?? '', FILTER_VALIDATE_INT);
                $security = (string)($data['security'] ?? 'ssl');
                if (!preg_match('/^[a-zA-Z0-9](?:[a-zA-Z0-9.-]{0,251}[a-zA-Z0-9])?$/D', $host)) throw new \InvalidArgumentException('请输入 SMTP 服务器域名，不要填写网址或路径');
                if (!filter_var($from, FILTER_VALIDATE_EMAIL)) throw new \InvalidArgumentException('请输入有效的发件邮箱');
                if ($username === '' || strlen($username) > 254 || preg_match('/[\r\n]/', $username)) throw new \InvalidArgumentException('请输入有效的 SMTP 登录账号');
                if ($name === '' || strlen($name) > 150 || preg_match('/[\r\n]/', $name)) throw new \InvalidArgumentException('请输入有效的发件人名称');
                if ($port === false || $port < 1 || $port > 65535) throw new \InvalidArgumentException('端口必须为 1 到 65535');
                if (!in_array($security, ['ssl', 'tls'], true)) throw new \InvalidArgumentException('请选择 SSL 或 STARTTLS 加密');
                $old = \PHPMailer\MailSettings::read();
                $password = (string)($data['password'] ?? '');
                if ($password === '') {
                    if (!empty($old['password']) && (($old['from'] ?? '') !== $from || ($old['username'] ?? '') !== $username || ($old['host'] ?? '') !== $host)) {
                        throw new \InvalidArgumentException('更换邮箱、账号或服务器时，请重新填写授权码');
                    }
                    $password = $old['password'] ?? '';
                }
                if ($password === '' || strlen($password) > 512 || preg_match('/[\r\n\x00]/', $password)) throw new \InvalidArgumentException('首次配置请填写有效的邮箱授权码');
                \PHPMailer\MailSettings::save(array_merge($old, ['host' => $host, 'from' => $from, 'username' => $username, 'from_name' => $name, 'port' => $port, 'security' => $security, 'password' => $password]));
                return json(['code' => 0, 'msg' => '邮件配置已保存，后续发送将使用新配置（尚未验证 SMTP 连通性）']);
            } catch (\Throwable $e) {
                return json(['code' => -1, 'msg' => $e->getMessage()]);
            }
        }
        View::assign('mailSettingsCsrf', $csrf);
        return View::fetch('admin/advanced/index');
    }

    public function mailSettingsData()
    {
        $this->checkLogin();
        $this->checkPower();
        try {
            $settings = \PHPMailer\MailSettings::read();
            $csrf = \think\facade\Session::get('mail_settings_csrf');
            if (!is_string($csrf) || strlen($csrf) !== 64) {
                $csrf = bin2hex(random_bytes(32));
                \think\facade\Session::set('mail_settings_csrf', $csrf);
            }
            // Persist before returning the token used by all three forms.
            \think\facade\Session::save();
            $auth = \PHPMailer\ServerAuthSettings::read();
            $settings['auth_qq'] = $auth['qq'] ?? '';
            $settings['skip_check'] = ($auth['skip_check'] ?? false) === true;
            $hasAuthKey = !empty($auth['skey']);
            $hasPassword = !empty($settings['password']);
            unset($settings['password']);
            $music = \PHPMailer\MusicApiSettings::read();
            $settings['music_provider'] = $music['provider'] ?? 'legacy';
            $settings['music_endpoint'] = $music['endpoint'] ?? 'http://shybot.top/v2/music/api/';
            $settings['has_music_key'] = !empty($music['key']);
            $settings['music_sources'] = \PHPMailer\MusicSourceRegistry::publicList();
            return json(['code' => 0, 'data' => $settings, 'has_password' => $hasPassword, 'has_auth_key' => $hasAuthKey, 'csrf' => $csrf])->header(['Cache-Control' => 'no-store, private']);
        } catch (\Throwable $e) {
            return json(['code' => -1, 'msg' => $e->getMessage()]);
        }
    }


    public function userinfo()
    {
		$this->checkLogin();
		$userInfo = Users::getLoginUser();
		$sheets=SongSheet::where('user_id',$userInfo['uid'])->count();
		View::assign('sheets', $sheets);
        return View::fetch();
    }
	
	public function QqLoginSet()
	{
		$userInfo = Users::getLoginUser();
		header("Location: https://music-api2.qsdurl.cn/qqlogin/api.php?callback=".get_domain()."/Admin/set_callback/uid/".$userInfo['uid']);
		exit();
	}
	
	public function set_callback($uid)
	{
		$data = input();
		$update['token']=$data['openid'];
		if(!$data['openid'] || !$data['callback']){
			View::assign('alert', '参数不完整');
			View::assign('url', '/console#/userinfo');
			exit(View::fetch('common/error_no_console'));
		}elseif(Users::checkQqToken($data['openid'])){
			View::assign('alert', '此QQ已被其他用户绑定');
			View::assign('url', '/console#/userinfo');
			exit(View::fetch('common/error_no_console'));
		}elseif(Users::updateByUid($uid, $update)){
			View::assign('alert', '绑定QQ成功');
			View::assign('url', '/console#/userinfo');
			exit(View::fetch('common/error_no_console'));
		}
        return $result;
	}
	
	public function QqLogin()
	{
		header("Location: https://music.cxy0.cn/qqlogin/api.php?callback=".get_domain()."Admin/login_callback");
		exit();
	}
	
	public function login_callback()
	{
		$data = input();
		if(!$data['openid'] || !$data['callback']){
			View::assign('alert', '参数不完整');
			View::assign('url', '/console#/user/login');
			exit(View::fetch('common/error_no_console'));
		}elseif(!Users::checkQqToken($data['openid'])){
			View::assign('alert', '此QQ未被任何用户绑定');
			View::assign('url', '/console#/user/login');
			exit(View::fetch('common/error_no_console'));
		}elseif(Users::qqlogin($data)){
			View::assign('alert', 'QQ登录成功');
			View::assign('url', '/console#/');
			return View::fetch('common/error_no_console');
		}
        return $result;
	}
	
	public function Player($id)
{
    $this->checkLogin(); // 检查用户是否登录

    // 确保 $id 传递正确
    $key = $id;
    $userInfo = Users::getLoginUser();

    // 检查播放器 ID 是否为空
    if (empty($key)) {
        View::assign('alert', '页面不存在');
        View::assign('url', '#/');
        return View::fetch('common/error');
    }

    // 检查播放器是否存在
    if (Player::checkPlayerKey($key)) {
        View::assign('alert', '播放器不存在');
        View::assign('url', '#/');
        return View::fetch('common/error');
    }

    // 检查用户权限
    if (Player::checkPlayerUid($key, $userInfo['uid']) && $userInfo['power'] !== 0) {
        View::assign('alert', '你没有权限管理这个播放器');
        View::assign('url', '#/');
        return View::fetch('common/error');
    }

    // 获取播放器信息
    $data = Player::where('id', $key)->find();
    $this->getSide();
    View::assign('entity', $data);

    // 获取播放器歌单
    $list = Player::songSheets($key);
    View::assign('selectedSongSheetList', $list);

    // 获取播放器授权信息
    $auths = PlayerAuth::where('player_id', $key)->select();
    View::assign('auths', $auths);

    // 获取用户的所有歌单
    $userSongSheet = SongSheet::where('user_id', $userInfo['uid'])->select();
    $webSongSheet = SongSheet::where(1)->select();
    $gy = SongSheet::where('status', '=', '1')->orderRand()->limit(10)->select();

    View::assign('gy', $gy);
    View::assign('userSongSheet', $userSongSheet);
    View::assign('webSongSheet', $webSongSheet);

    return View::fetch();
}
	
	public function songSheet($id)
{
    $this->checkLogin(); // 确保用户已登录

    $key = $id;
    $userInfo = Users::getLoginUser();

    // 检查 ID 是否有效
    if (empty($key)) {
        View::assign('alert', '页面不存在');
        View::assign('url', '#/');
        return View::fetch('common/error');
    }

    // 检查歌单是否存在
    if (SongSheet::checkSheetKey($key)) {
        View::assign('alert', '歌单不存在');
        View::assign('url', '#/');
        return View::fetch('common/error');
    }

    // 检查用户是否有权限管理该歌单
    if (SongSheet::checkSheetUid($key, $userInfo['uid']) && $userInfo['power'] !== 0) {
        View::assign('alert', '你没有权限管理这个歌单');
        View::assign('url', '#/');
        return View::fetch('common/error');
    }

    // 获取歌单信息
    $entity = SongSheet::where('id', $key)->find();
    View::assign('entity', $entity);

    // 获取歌单关联歌曲和随机歌曲
    $songs = Song::where('song_sheet_id', $key)->order('taxis', 'asc')->select();
    $suiji = Song::orderRand()->limit(10)->select();
    View::assign('songs', $songs);
    View::assign('suiji', $suiji);

    // 设置网页标题并渲染视图
    View::assign('webTitle', '歌单配置');
    return View::fetch();
}

    public function logout()
    {
        $this->checkLogin();
        Cookie::delete('skey');
		Cookie::delete('sid');
		Cookie::delete('pskey');
        Cookie::delete('user_token');
        View::assign('alert', '成功注销登陆状态');
        View::assign('url', '/console#/user/login');
        return View::fetch('common/error');
    }
	
	public function layout()
    {
		$this->checkLogin();
		$links = Links::where(1)->order('id desc')->select();
		$linkcount = Links::where(1)->count();
		View::assign('links', $links);
		View::assign('linkcount', $linkcount);
        return View::fetch();
    }
	
	public function users($act)
    {
		$this->checkLogin();
		$this->checkPower();
		switch ($act) {
			case 'list':
				return View::fetch('admin/users/list');
			break;
			case 'edit':
				$data=input();
				$user=Users::where('uid','=',$data['uid'])->find();
				$player=Player::where('user_id','=',$data['uid'])->select();
				$sheet=SongSheet::where('user_id','=',$data['uid'])->select();
				View::assign('user', $user);
				View::assign('player', $player);
				View::assign('sheet', $sheet);
				return View::fetch('admin/users/edit');
			break;
		}
    }
    
    public function open($web)
    {
		$this->checkLogin();
		switch ($web) {
			case 'fav':
				return View::fetch('admin/open/web/fav');
			break;
		}
    }
    public function openmusic($music)
    {
		$this->checkLogin();
		switch ($music) {
			case 'search':
				return View::fetch('admin/open/music/search');
			break;
			case 'info':
				return View::fetch('admin/open/music/info');
			break;
			case 'url':
				return View::fetch('admin/open/music/url');
			break;
			case 'list':
				return View::fetch('admin/open/music/list');
			break;
		}
    }
    
 /*   public function open($music)
    {
		$this->checkLogin();
		switch ($music) {
			case 'fav':
				return View::fetch('admin/open/web/fav');
			break;
    }
  }*/
	
	public function links($act)
    {
		$this->checkLogin();
		$this->checkPower();
		switch ($act) {
			case 'list':
				return View::fetch('admin/links/list');
			break;
			case 'del':
				$data=input();
			break;
		}
    }
	
	public function orders($act)
    {
		$this->checkLogin();
		$this->checkPower();
		switch ($act) {
			case 'list':
				return View::fetch('admin/orders/list');
			break;
		}
    }
	
	public function user($act='login')
    {
		switch ($act) {
			case 'login':
				$check_Login = Users::checkLogin();
				if ($check_Login != null) {
					return View::fetch('user/login');
				} else {
					$this->userInfo = Users::getLoginUser();
					View::assign('userInfo', $this->userInfo);
					return View::fetch('user/lock');
				}
			break;
			case 'reg':
				$check_Login = Users::checkLogin();
				if ($check_Login != null) {
					return View::fetch('user/reg');
				} else {
					$this->userInfo = Users::getLoginUser();
					View::assign('userInfo', $this->userInfo);
					return View::fetch('user/lock');
				}
			break;
			case 'find':
				return View::fetch('user/find');
			break;
		}
    }
	
	public function system()
    {
		$this->checkLogin();
		return View::fetch('theme');
    }
	
	private ?Users $userInfo = null;
    public function checkLogin()
    {
        $check_Login = Users::checkLogin();
        if ($check_Login !== null) {
            View::assign('alert', $check_Login['msg']);
            View::assign('url', $check_Login['url']);
            exit(View::fetch('common/error'));
        } else {
            $this->userInfo = Users::getLoginUser(); // 将 Users 对象赋值给 $userInfo
            View::assign('userInfo', $this->userInfo);
        }
    }
	
	private
    function checkPower()
    {
        $userInfo = Users::getLoginUser();
		if($userInfo['power']!==0){
			View::assign('alert', '你没有权限进入这个页面');
            View::assign('url', '#/');
            exit(View::fetch('common/error'));
		}
    }
}
