<?php

namespace PHPMailer;

class SendEmail
{
    private static $demo = 'https://music.ovoeo.cn'; //第你的网址，只用于用户收到邮箱后访问主页
    public static $Host = 'smtp.qq.com'; //smtp服务器
    private static $From = 'cenguigui@qq.com'; //发送者的邮件地址
    private static $FromName = '笒鬼鬼音乐播放器'; //发送邮件的用户昵称
    private static $Username = 'cenguigui@qq.com'; //登录到邮箱的用户名
    private static $Password = '********'; //第三方登录的授权码，在邮箱里面设置

    /**
     * @desc 发送普通邮件
     * @param $title 邮件标题
     * @param $message 邮件正文
     * @param $emailAddress 邮件地址
     * @return bool|string 返回是否发送成功
     */
    public static function SendCode($code, $emailAddress)
    {
        try { $settings = MailSettings::read(); }
        catch (\Throwable $e) { return false; }
        $title = str_replace('{code}', (string)$code, $settings['template_title'] ?? '邮箱验证码');
        $body = str_replace('{code}', (string)$code, $settings['template_body'] ?? '尊敬的用户，您正在进行邮箱验证，本次请求的验证码为：{code}');
        return self::SendEmail($title, $body, $emailAddress);
    }

    public static function SendEmail($title = '测试邮件', $message = '你好,本邮件由笒鬼鬼音乐播放器发出', $emailAddress = 'cenguigui@qq.com')
    {
        try {
            $settings = MailSettings::read();
        } catch (\Throwable $e) {
            return '邮件配置读取失败';
        }
        $mail = new PHPMailer();
        // 3. 设置属性，告诉我们的服务器，谁跟谁发送邮件
        $mail->IsSMTP();            // 告诉服务器使用smtp协议发送
        $mail->Timeout = 15;
        $mail->SMTPAuth = true;        // 开启SMTP授权
        $mail->SMTPSecure = $settings['security'] ?? 'ssl';    // ssl加密 
        $mail->Port = $settings['port'] ?? 465;            // 使用465端口
        $mail->Host = $settings['host'] ?? self::$Host;    // 告诉我们的服务器使用163的smtp服务器发送
        $mail->From = $settings['from'] ?? self::$From;    // 发送者的邮件地址
        $mail->FromName = $settings['from_name'] ?? self::$FromName; // 发送邮件的用户昵称
        $mail->Username = $settings['username'] ?? self::$Username; // 登录到邮箱的用户名
        $mail->Password = $settings['password'] ?? self::$Password; // 第三方登录的授权码，在邮箱里面设置

        // 编辑发送的邮件内容
        $mail->IsHTML(true);            // 发送的内容使用html编写
        $mail->CharSet = 'utf-8';        // 设置发送内容的编码
        $mail->Subject = $title; // 设置邮件的标题
        // 美化邮件的HTML内容
        $htmlContent = '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        width: 100%;
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        border: 1px solid #ddd;
                        padding: 20px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px;
                        text-align: center;
                        font-size: 24px;
                        border-radius: 5px 5px 0 0;
                    }
                    .email-body {
                        padding: 20px;
                        font-size: 16px;
                        line-height: 1.6;
                        text-align: left;
                    }
                    .email-footer {
                        text-align: center;
                        padding: 10px;
                        font-size: 12px;
                        background-color: #f1f1f1;
                        border-radius: 0 0 5px 5px;
                        color: #777;
                    }
                    .email-footer a {
                        color: #4CAF50;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        ' . htmlspecialchars($title) . '
                    </div>
                    <div class="email-body">
                        <p>' . nl2br(htmlspecialchars($message)) . '</p>
                    </div>
                    <div class="email-footer">
                        <p>此邮件由' . htmlspecialchars($mail->From, ENT_QUOTES, 'UTF-8') . '自动发送。</p>
                        <p><a href="' . htmlspecialchars($settings['homepage'] ?? self::$demo, ENT_QUOTES, 'UTF-8') . '">访问我们的主页</a></p>
                    </div>
                </div>
            </body>
            </html>';
        // 将美化后的HTML内容设置为邮件的主体
        $mail->MsgHTML($htmlContent);
        // 添加收件人
        $mail->AddAddress($emailAddress);    // 收件人的邮件地址
        // 调用send方法，执行发送
        $result = $mail->Send();
        if ($result) {
            return true;
        } else {
            return $mail->ErrorInfo;
        }
    }
}
