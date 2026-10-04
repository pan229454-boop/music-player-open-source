<?php
/* *
 * 类名：AlipayNotify
 * 功能：彩虹易支付通知处理类
 * 详细：处理易支付接口通知返回
 */

require_once("epay_core.function.php");
require_once("epay_md5.function.php");

class AlipayNotify {

    private array $alipay_config;
    private string $http_verify_url;

    public function __construct(array $alipay_config){
        $this->alipay_config = $alipay_config;
        $this->http_verify_url = $this->alipay_config['apiurl'].'api.php?';
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return bool 验证结果
     */
    public function verifyNotify(): bool {
        if(empty($_GET)) {
            return false;
        }

        // 生成签名结果
        $isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
        // 获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'true';
        // $responseTxt = $this->getResponse($_GET["trade_no"]);

        return preg_match("/true$/i", $responseTxt) && $isSign;
    }
    
    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return bool 验证结果
     */
    public function verifyReturn(): bool {
        if(empty($_GET)) {
            return false;
        }

        // 生成签名结果
        $isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
        // 获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
        $responseTxt = 'true';
        // $responseTxt = $this->getResponse($_GET["trade_no"]);

        return preg_match("/true$/i", $responseTxt) && $isSign;
    }
    
    /**
     * 获取返回时的签名验证结果
     * @param array $para_temp 通知返回来的参数数组
     * @param string $sign 返回的签名结果
     * @return bool 签名验证结果
     */
    private function getSignVeryfy(array $para_temp, string $sign): bool {
        // 除去待签名参数数组中的空值和签名参数
        $para_filter = paraFilter($para_temp);
        
        // 对待签名参数数组排序
        $para_sort = argSort($para_filter);
        
        // 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = createLinkstring($para_sort);
        
        return md5Verify($prestr, $sign, $this->alipay_config['key']);
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param string $trade_no 通知校验ID
     * @return string 服务器ATN结果
     */
    private function getResponse(string $trade_no): string {
        $partner = trim($this->alipay_config['partner']);
        $verify_url = $this->http_verify_url."act=order&pid=" . $partner . "&trade_no=" . $trade_no;
        $responseTxt = getHttpResponseGET($verify_url);
        $arr = json_decode($responseTxt, true);
        return isset($arr['status']) && $arr['status'] == 1 ? 'true' : 'false';
    }
}
?>
