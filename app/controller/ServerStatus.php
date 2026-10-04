<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Db;
use think\facade\Request;

class ServerStatus extends BaseController
{
    /**
     * 获取服务器进程列表
     */
    public function getStatus()
    {
        $data = [];
        
        // Linux 系统
        if (PHP_OS === 'Linux' || stripos(PHP_OS, 'LINUX') !== false) {
            $output = shell_exec('ps aux --no-header 2>/dev/null');
            if ($output) {
                $lines = explode("\n", trim($output));
                foreach ($lines as $line) {
                    $parts = preg_split('/\s+/', $line, 10);
                    if (count($parts) >= 11) {
                        $data[] = [
                            'pid' => $parts[1],
                            'user' => $parts[0],
                            'cpu' => $parts[2],
                            'mem' => $parts[3],
                            'command' => $parts[10]
                        ];
                    }
                }
            }
        }
        // Windows 系统
        elseif (stripos(PHP_OS, 'WIN') !== false) {
            $output = shell_exec('wmic process get ProcessId,Name,CPUUsage,WorkingSetSize 2>nul');
            if ($output) {
                $lines = explode("\n", trim($output));
                foreach ($lines as $line) {
                    $parts = preg_split('/\s+/', trim($line));
                    if (count($parts) >= 4 && is_numeric($parts[0])) {
                        $data[] = [
                            'pid' => $parts[0],
                            'user' => 'SYSTEM',
                            'cpu' => $parts[2],
                            'mem' => round($parts[3] / 1024 / 1024, 2) . ' MB',
                            'command' => $parts[1]
                        ];
                    }
                }
            }
        }
        
        return json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
    /**
     * 停止进程
     */
    public function stopProcess()
    {
        $pid = Request::param('pid');
        
        if (!$pid || !is_numeric($pid)) {
            return json(['status' => 'error', 'message' => '无效的进程ID']);
        }
        
        $result = false;
        
        // Linux 系统
        if (PHP_OS === 'Linux' || stripos(PHP_OS, 'LINUX') !== false) {
            exec("kill -9 $pid 2>&1", $output, $return_var);
            $result = ($return_var === 0);
        }
        // Windows 系统
        elseif (stripos(PHP_OS, 'WIN') !== false) {
            exec("taskkill /PID $pid /F 2>&1", $output, $return_var);
            $result = ($return_var === 0);
        }
        
        return json([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? '进程已停止' : '停止进程失败'
        ]);
    }
}
