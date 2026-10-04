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
            if (!isset($data['csrf']) || !is_string($data['csrf']) || !hash_equals($csrf, $data['csrf'])) {
                return json(['code' => -1, 'msg' => '页面已失效，请刷新后重试']);
            }
            try {
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
                \PHPMailer\MailSettings::save(['host' => $host, 'from' => $from, 'username' => $username, 'from_name' => $name, 'port' => $port, 'security' => $security, 'password' => $password]);
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
            $hasPassword = !empty($settings['password']);
            unset($settings['password']);
            return json(['code' => 0, 'data' => $settings, 'has_password' => $hasPassword]);
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
