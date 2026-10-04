<?php

function get_system_fingerprint() {
    $mac_address = get_mac_address();
    $system_info = php_uname();
    $combined_info = $mac_address . $system_info;
    return hash('sha512', hash('sha256', $combined_info));
}

function get_mac_address() {
    if (stripos(PHP_OS, 'WIN') !== false) {
        @exec('getmac', $output);
        return !empty($output) ? substr($output[0], 0, 17) : '';
    } else {
        @exec('ifconfig -a', $output);
        foreach ($output as $line) {
            if (preg_match('/([a-fA-F0-9]{2}[:-]){5}[a-fA-F0-9]{2}/', $line, $matches)) {
                return $matches[0];
            }
        }
    }
    return '';
}

function custom_encrypt($string, $operation = 'VERIFY', $salt = '', $expiry = 0) {
    $key_length = 64;
    $iv_length = 32;
    $ckey_length = 8;
    $system_fingerprint = get_system_fingerprint();
    
    if (empty($system_fingerprint)) {
        throw new Exception("无法生成系统指纹");
    }
    
    if (empty($salt)) {
        $salt = bin2hex(random_bytes(16));
    }
    
    $fingerprint_salt = hash('sha512', $system_fingerprint);
    $key = hash_pbkdf2('sha512', $salt . $fingerprint_salt, $system_fingerprint, 10000, $key_length, true);
    $keya = substr($key, 0, $key_length / 2);
    $keyb = substr($key, $key_length / 2, $key_length / 2);
    $keyc = $ckey_length ? ($operation == 'VERIFY' ? substr($string, 0, $ckey_length) : substr(hash('sha512', microtime()), -$ckey_length)) : '';
    $cryptkey = substr($keya . hash('sha512', $keya . $keyc), 0, $key_length);
    $iv = substr(hash('sha512', $keyc . $keyb . $fingerprint_salt), 0, $iv_length);

    if ($operation == 'VERIFY') {
        $string = custom_decode(substr($string, $ckey_length), $system_fingerprint);
       //   $string = base64_decode(substr($string, $ckey_length));
    } else {
        $expiry = $expiry ? (time() + $expiry) : 0;
        $string = sprintf('%010d', $expiry) . hash('sha256', $string . $keyb) . $string;
    }

    $result = '';
    $string_length = strlen($string);
    $extra_iterations = 1; // 额外的循环次数

    for ($i = 0; $i < $string_length; $i++) {
        $char = ord($string[$i]);
        $key_char = ord($cryptkey[$i % $key_length]);
        for ($j = 0; $j < $extra_iterations; $j++) {
            $char = ($char ^ $key_char);
            $key_char = ord($cryptkey[$i % $key_length]);
        }

        $result .= chr($char ^ ord($iv[$i % $iv_length]));
    }

    if ($operation == 'VERIFY') {
        if (substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) {
            if (substr($result, 10, 64) == hash('sha256', substr($result, 74) . $keyb)) {
                return substr($result, 74);
            } else {
                throw new Exception("解密失败: 校验失败");
            }
        } else {
            throw new Exception("解密失败: 过期");
        }
    } else {
        return $salt . ':' . $keyc . custom_encode($result, $system_fingerprint);
         //return $salt . ':' . $keyc . str_replace('=', '', base64_encode($result));
    }
}

function custom_encode($data, $system_fingerprint) {
    $encoded = '';
    $fingerprint_length = strlen($system_fingerprint);
    $random_salt = bin2hex(random_bytes(8));
    $data .= $random_salt; 
    for ($i = 0; $i < strlen($data); $i++) {
        $char = ord($data[$i]) ^ ord($system_fingerprint[$i % $fingerprint_length]);
        $encoded .= sprintf('%02x', $char);
    }
    return $encoded . ':' . $random_salt;
}

function custom_decode($data, $system_fingerprint) {
    list($encoded_data, $random_salt) = explode(':', $data, 2);
    $decoded = '';
    $fingerprint_length = strlen($system_fingerprint);
    for ($i = 0; $i < strlen($encoded_data); $i += 2) {
        $char = hexdec(substr($encoded_data, $i, 2)) ^ ord($system_fingerprint[($i / 2) % $fingerprint_length]);
        $decoded .= chr($char);
    }
    return substr($decoded, 0, strlen($decoded) - 16);
}

function generate_random_string($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

$random_string='player_album=0; player_song=1; ocinkCurrTime=0; PHPSESSID=c8cb96025bc28cca57de2e2ccf0773fd; pskey=567bEyTaouHvffjLw%2BiqRQQ2YaKA7DgWC%2FWf9b3u; skey=b6395c3614eece3f72655dc3e19b5499; sid=407a43e8fb6016719e9f5b4cd81a90f2';
echo custom_encrypt($random_string, 'ENCODE', '', 0);

?>