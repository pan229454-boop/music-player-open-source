<?php
namespace app\controller;
 
use app\BaseController;
use think\facade\View; 
use think\facade\Config;
use think\facade\Db;
use think\facade\Cache;
use think\facade\Request;
use think\facade\Session;
use app\model\Users; 
use app\model\Player;
use app\model\SongSheet;
use app\model\Song;
use app\model\Plays;
use app\model\PlayerAuth;
use app\model\PlayerSongSheet;
use app\model\Links;

class AdminAjax extends Common
{
   public function cleanupUsers()
{
    $deletedUsers = Users::cleanupInactiveUsers();
    
    if (!empty($deletedUsers)) {
        return json([
            'status' => 'success', 
            'message' => '清理成功', 
            'deleted_users' => $deletedUsers
        ]);
    } else {
        return json([
            'status' => 'error', 
            'message' => '清理失败或没有需要删除的用户'
        ]);
    }
}
    
    public function reset(Request $request)
    {
        $this->checkLogin();
        $userInfo = Users::getLoginUser();
        $newToken = $this->generateNewToken();
        $result = Db::name('users')->where('uid', $userInfo['uid'])->update(['token2' => $newToken]); 
        if ($result) {
            return json(['success' => true, 'newToken' => $newToken]);
        } else {
            return json(['success' => false], 500);
        }
    }

    private function generateNewToken()
    {
        $randomString = bin2hex(random_bytes(16)); 
        return strtoupper(md5($randomString)); 
    }
    
	public function encode_netease_data($data)
    {
        $_key = '7246674226682325323F5E6544673A51';
        $data = json_encode($data);
        if (function_exists('openssl_encrypt')) {
            $data = openssl_encrypt($data, 'aes-128-ecb', pack('H*', $_key));
        } else {
            $_pad = 16 - (strlen($data) % 16);
            $data = base64_encode(mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                hex2bin($_key),
                $data . str_repeat(chr($_pad), $_pad),
                MCRYPT_MODE_ECB
            ));
        }
        $data = strtoupper(bin2hex(base64_decode($data)));
        return ['eparams' => $data];
    }
	
	private function getFilePath($fileName, $content)
	{
		$path = dirname(__FILE__) . "\\$fileName";
		if (!file_exists($path)) {
			file_put_contents($path, $content);
		}
		return $path;
	}

	private function getCupUsageVbsPath()
	{
		return $this->getFilePath(
			'cpu_usage.vbs',
			"On Error Resume Next
			Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
			WScript.Echo(objProc.LoadPercentage)"
		);
	} 

	private function getMemoryUsageVbsPath()
	{
		return $this->getFilePath(
			'memory_usage.vbs',
			"On Error Resume Next
			Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
			Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
			For Each objOS in colOS
			Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
			Next"
		);
	}

	public function getCpuUsage()
	{
		$path = $this->getCupUsageVbsPath();
		exec("cscript -nologo $path", $usage);
		return $usage[0];
	}

	public function getMemoryUsage()
	{
		$path = $this->getMemoryUsageVbsPath();
		exec("cscript -nologo $path", $usage);
		$memory = json_decode($usage[0], true);
		$memory['usage'] = Round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100);
		return $memory;
	}

	public function getLinuxCount()
{
    $maxAttempts = 5;
    $attempt = 0;
    $cpu_usage = 'N/A';
    $load_avg = 'N/A';
    $cpu_cores = intval(trim(shell_exec('nproc')));
    $output = null;
    while ($attempt < $maxAttempts) {
        $output = shell_exec('top -b -n 1');
        if ($output) {
            break; 
        }
        $attempt++;
    }
    $mem_total = $mem_used = $cpu_idle = $load_avg_1m = 'N/A';

    if ($output) {
        preg_match('/%Cpu\(s\):\s+([0-9.]+) us,.* ([0-9.]+) id/', $output, $cpu_matches);
        preg_match('/MiB Mem :\s+([0-9.]+) total,.* ([0-9.]+) used/', $output, $mem_matches);
        preg_match('/load average:\s+([0-9.]+),\s+([0-9.]+),\s+([0-9.]+)/', $output, $load_matches);

        $cpu_idle = isset($cpu_matches[2]) ? $cpu_matches[2] : 'N/A';
        if ($cpu_idle != 'N/A' && is_numeric($cpu_idle)) {
            $cpu_usage = round(100 - floatval($cpu_idle), 2);
        }
        $load_avg_1m = isset($load_matches[1]) ? $load_matches[1] : 'N/A';
        $mem_total = isset($mem_matches[1]) ? $mem_matches[1] : 'N/A';
        $mem_used = isset($mem_matches[2]) ? $mem_matches[2] : 'N/A';
        if ($cpu_cores > 0) {
            $load_avg_percent = round(min(floatval($load_avg_1m) / $cpu_cores * 100, 100), 2);
        } else {
            $load_avg_percent = 'N/A';
        }
        $load_avg = $load_avg_percent;
    }
    if ($mem_total != 'N/A' && $mem_total > 0) {
        $mem_usage = round(100 * $mem_used / $mem_total, 2);
    } else {
        $mem_usage = 0;
    }

    $result = [
        'mem' => $mem_usage . '%',
        'memRealUsed' => $this->getFilesize(floatval($mem_used) * 1024 * 1024), 
        'memTotal' => $this->getFilesize(floatval($mem_total) * 1024 * 1024), 
        'cpu' => $cpu_usage . '%',
        'load_avg' => $load_avg . '%',
    ];

    return $result;
}

private function getFilesize($size)
{
    // 将内存大小转换为合适的格式
    if ($size < 1024) {
        return $size . ' B';
    } elseif ($size < 1048576) {
        return round($size / 1024, 2) . ' KiB';
    } elseif ($size < 1073741824) {
        return round($size / 1048576, 2) . ' MiB';
    } else {
        return round($size / 1073741824, 2) . ' GiB';
    }
}
   public function menu()
    {
		$this->checkLogin();
		$userInfo = Users::getLoginUser();
		if($userInfo['power']==0){
			$userInfo['lv']='管理员';
			$home_head=[
				[
					'jump'	=>	'/',
					'title'	=>	'<i class=\"layui-icon layui-icon-console\"></i> 控制面板'
				],[
					'jump'	=>	'/help',
					'title'	=>	'帮助文档'
				],[
					'jump'	=>	'/webset',
					'title'	=>	'网站设置'
				],[
					'jump'	=>	'/users/act/list',
					'title'	=>	'用户列表'
				],[
					'jump'	=>	'/links/act/list',
					'title'	=>	'广告列表'
				],[
					'jump'	=>	'/orders/act/list',
					'title'	=>	'订单列表'
				],
				[
					'jump'	=>	'/serverstate',
					'title'	=>	'服务器管理'
				],
				[
