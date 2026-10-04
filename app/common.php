<?php

function curl($url, $paras = [])
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if (isset($paras['Header'])) {
        $Header = $paras['Header'];
    } else {
        $Header[] = 'Accept:*/*';
        $Header[] = 'Accept-Encoding:gzip,deflate,sdch';
        $Header[] = 'Accept-Language:zh-CN,zh;q=0.8';
        $Header[] = 'Connection:close';
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $Header);
    if (isset($paras['ctime'])) {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $paras['ctime']);
    } else {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    }
    if (isset($paras['rtime'])) {
        curl_setopt($ch, CURLOPT_TIMEOUT, $paras['rtime']);
    }
    if (isset($paras['post'])) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $paras['post']);
    }
    if (isset($paras['header'])) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if (isset($paras['cookie'])) {
        curl_setopt($ch, CURLOPT_COOKIE, $paras['cookie']);
    }
    if (isset($paras['refer'])) {
        if ($paras['refer'] == 1) {
            curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $paras['refer']);
        }
    }
    if (isset($paras['ua'])) {
        curl_setopt($ch, CURLOPT_USERAGENT, $paras['ua']);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
    }
    if (isset($paras['nobody'])) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (isset($paras['GetCookie'])) {
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        preg_match_all('/Set-Cookie: (.*?);/m', $result, $matches);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $headerSize);
        $body = substr($result, $headerSize);
        $ret = ['cookie' => $matches, 'body' => $body, 'Header' => $header, 'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)];
        curl_close($ch);
        return $ret;
    }
    $ret = curl_exec($ch);
    if (isset($paras['loadurl'])) {
        $Headers = curl_getinfo($ch);
        if (isset($Headers['redirect_url'])) {
            $ret = $Headers['redirect_url'];
        } else {
            $ret = false;
        }
    }
    curl_close($ch);
    return $ret;
}
function send_get($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $httpheader[] = 'Accept:application/json';
    $httpheader[] = 'Accept-Encoding:gzip,deflate,sdch';
    $httpheader[] = 'Accept-Language:zh-CN,zh;q=0.8';
    $httpheader[] = 'Connection:close';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($referer) {
        if ($referer == 1) {
            curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
    }
    if ($ua) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (MSIE 9.0; Windows NT 6.1; Trident/5.0)');
    }
    if ($nobaody) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}
function Post($curlPost, $url)
{
    $curlPost = http_build_query($curlPost);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $return = curl_exec($curl);
    curl_close($curl);
    return $return;
}
function GetCurl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $rtn = curl_exec($ch);
    curl_exec($ch);
    return $rtn;
}
function daddslashes($string, $force = 0, $strip = false)
{
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if (!MAGIC_QUOTES_GPC || $force) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}
function check_Hash($value, $hash)
{
    return password_verify($value, $hash);
}
function make_Hash($value)
{
    return password_hash($value, PASSWORD_DEFAULT);
}
function real_ip()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] as $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}
function ip_city_str($str)
{
    return str_replace(['省', '市'], '', $str);
}
function get_ip_city($ip)
{
    $ip = empty($ip) ? request()->ip() : $ip;
    $url = 'https://api.cenguigui.cn/api/UserInfo/bt.php?ip=';
    $city = file_get_contents($url . $ip);
    $city = json_decode($city, true);
    if (isset($city['data']['location'])) {
        $result = $city['data']['location'];
    } else {
        $result = '未知地址';
    }
    return $result;
}
function get_domain()
{
    $siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
    return $siteurl;
}
function authcode($string, $operation = '', $key = '', $expiry = 0)
{
    $ckey_length = 4;
    $key = md5($key ? $key : 'zyzs');
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), $ckey_length * -1)) : '';
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = [];
    for ($i = 0; $i <= 255; ++$i) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    $j = $i = 0;
    while ($i < 256) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
        ++$i;
    }
    $a = $j = $i = 0;
    while ($i < $string_length) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ $box[($box[$a] + $box[$j]) % 256]);
        ++$i;
    }
    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        }
        return '';
    }
    return $keyc . str_replace('=', '', base64_encode($result));
}
function drawings_js($money, $drawings)
{
    $drawings = $money * $drawings;
    return $drawings;
}
function getFilesize($filesize)
{
    if ($filesize >= 1048576) {
        $filesize = round($filesize / 1048576 * 100) / 100 . 'GB';
    } elseif ($filesize >= 1024) {
        $filesize = round($filesize / 1024 * 100) / 100 . 'MB';
    } else {
        $filesize = $filesize . 'KB';
    }
    return $filesize;
}
function my_sort($arrays, $sort_key, $sort_order = SORT_DESC, $sort_type = SORT_NUMERIC)
{
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $key_arrays[] = $array[$sort_key];
                continue;
            }
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }
    return false;
}
function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = '@')
{
    if (empty($string)) {
        return false;
    }
    $array = [];
    if ($type == 0 || $type == 1 || $type == 4) {
        $strlen = $length = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $strlen, 'utf8');
            $strlen = mb_strlen($string);
        }
    }
    switch ($type) {
        case 0:
            for ($i = $bengin; $i < $bengin + $len; ++$i) {
                if (isset($array[$i])) {
                    $array[$i] = '*';
                }
            }
            $string = implode('', $array);
            break;
        case 1:
            $array = array_reverse($array);
            for ($i = $bengin; $i < $bengin + $len; ++$i) {
                if (isset($array[$i])) {
                    $array[$i] = '*';
                }
            }
            $string = implode('', array_reverse($array));
            break;
        case 2:
            $array = explode($glue, $string);
            $array[0] = hideStr($array[0], $bengin, $len, 1);
            $string = implode($glue, $array);
            break;
        case 3:
            $array = explode($glue, $string);
            $array[1] = hideStr($array[1], $bengin, $len, 0);
            $string = implode($glue, $array);
            break;
        case 4:
            $left = $bengin;
            $right = $len;
            $tem = [];
            for ($i = 0; $i < $length - $right; ++$i) {
                if (isset($array[$i])) {
                    $tem[] = $left <= $i ? '*' : $array[$i];
                }
            }
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; ++$i) {
                $tem[] = $array[$i];
            }
            $string = implode('', $tem);
            break;
    }
    return $string;
}
function whits($num)
{
    return $num < 10000 ? $num : sprintf('%.1f', $num / 10000) . '万';
}
function time_tran($the_time)
{
    $now_time = date('Y-m-d H:i:s', time());
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    }
    if ($dur < 60) {
        return $dur . '秒前';
    }
    if ($dur < 3600) {
        return floor($dur / 60) . '分钟前';
    }
    if ($dur < 86400) {
        return floor($dur / 3600) . '小时前';
    }
    if ($dur < 2592000) {
        return floor($dur / 86400) . '天前';
    }
    if ($dur < 31104000) {
        return floor($dur / 2592000) . '月前';
    }
    if ($dur < 3110400000.0) {
        return floor($dur / 31104000) . '年前';
    }
    return $the_time;
}
function mainColor($image)
{
    $imageInfo = getimagesize($image);
    $imgType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
    $imageFun = 'imagecreatefrom' . ($imgType == 'jpg' ? 'jpeg' : $imgType);
    $i = $imageFun($image);
    $rColorNum = $gColorNum = $bColorNum = $total = 0;
    for ($x = 50; $x < imagesx($i) - 50; ++$x) {
        for ($y = 50; $y < imagesy($i) - 50; ++$y) {
            $rgb = imagecolorat($i, $x, $y);
            $r = $rgb >> 16 & 255;
            $g = $rgb >> 8 & 255;
            $b = $rgb & 255;
            $rColorNum += $r;
            $gColorNum += $g;
            $bColorNum += $b;
            ++$total;
        }
    }
    return [round($rColorNum / $total), round($gColorNum / $total), round($bColorNum / $total)];
}
function is_peie_num($int)
{
    switch ($int) {
        case 1:
            $num = 1;
            break;
        case 2:
            $num = 3;
            break;
        case 3:
            $num = 5;
            break;
        case 4:
            $num = 10;
            break;
    }
    return $num;
}
function get_url_ico($url)
{
    if (strpos($url, 'http') !== 0) {
        $url = 'http://' . $url;
    }
    $response = \think\facade\Request::get($url);
    $html = $response->getContent();
    $regex = '/<link\s+rel="(?:icon|shortcut icon)"\s+href="([^"\s]+)"/i';
    if (preg_match($regex, $html, $matches)) {
        $faviconUrl = $matches[1];
        if (strpos($faviconUrl, 'http') !== 0) {
            $faviconUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . $faviconUrl;
        }
        if (\think\facade\Request::get($faviconUrl)->isSuccess()) {
            return $faviconUrl;
        }
    }
    return '/favicon.ico';
}
function url_exists($url)
{
    $head = @get_headers($url);
    return is_array($head) ? true : false;
}
function remote_file_exists($url)
{
    $executeTime = ini_get('max_execution_time');
    ini_set('max_execution_time', 0);
    $headers = @get_headers($url);
    ini_set('max_execution_time', $executeTime);
    if ($headers) {
        $head = explode(' ', $headers[0]);
        if (!empty($head[1]) && (int)$head[1] < 400) {
            return true;
        }
    }
    return false;
}
function Pay($orderid, $name, $price, $type, $shop, $shopid)
{
    $epay_config = ['partner' => \think\facade\Config::get('web.epay_id'), 'key' => \think\facade\Config::get('web.epay_key'), 'sign_type' => strtoupper('MD5'), 'input_charset' => strtolower('utf-8'), 'transport' => 'http', 'apiurl' => \think\facade\Config::get('web.epay_url')];
    $parameter = ['pid' => trim($epay_config['partner']), 'type' => $type, 'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . url('Epay/' . $shop . '_Notify'), 'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . url('Epay/' . $shop . '_Return'), 'out_trade_no' => $orderid, 'name' => $name, 'money' => $price, 'shop' => $shop, 'shopid' => $shopid, 'sitename' => \think\facade\Config::get('web.webname')];
    $alipaySubmit = new AlipaySubmit($epay_config);
    $html_text = $alipaySubmit->buildRequestForm($parameter, 'get');
    return $html_text;
}
require_once '../extend/Epay/epay_submit.class.php';
define('music_api', 'https://musicapi.cenguigui.cn');
