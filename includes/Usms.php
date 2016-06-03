<?php

/**
 * User: Yuan
 * Date: 16-5-14
 * Time: 上午2:11
 */


class Usms{
    private $mobile;

    /**
     * 初始化函数返回值
     *
     * @return array
     */
    public static function iniFuncRlt() {
        $rlt ['status'] = false;
        $rlt ['msg'] = '';
        $rlt ['data'] = '';
        return $rlt;
    }
    

    /**
     * 注册手机验证码
     * @param string $mobile
     * @param string $send_code
     * @return Ambigous <string, multitype:>
     */
    public static function  sendSmsReg($mobile,$send_code){
        $rlt = self::iniFuncRlt ();

        $mobile_code = self::randomkeys (6);

        $content = '[搜来宝] 尊敬的用户，您的验证码是：'.$mobile_code.'，跨境海淘，尽在搜来宝！ ';
        $sendRlt = self::sendSms($mobile, $send_code, $mobile_code, $content);
        if ($sendRlt['status']) {
            $rlt['status']=true;
            $rlt ['msg'] = $sendRlt['msg'];
            $rlt['data']=$mobile_code;
        } else {
            $rlt  =$sendRlt;
        }

        return $rlt;

    }

    /**
     * 找回密码手机验证码
     * @param string $mobile
     * @param string $send_code
     * @return Ambigous <string, multitype:>
     */
    public static function  RetrievePwd($mobile,$send_code){
        $rlt = self::iniFuncRlt ();

        $mobile_code = self::randomkeys (6);

        $content = '[搜来宝] 您正在对您的账号进行密码找回操作，验证码：'.$mobile_code.'，如非本人操作，请联系客服：'.$GLOBALS['_CFG']['service_phone'];

        $sendRlt = self::sendSms($mobile, $send_code, $mobile_code, $content);
        if ($sendRlt['status']) {
            $rlt['status']=true;
            $rlt ['msg'] = $sendRlt['msg'];
            $rlt['data']=$mobile_code;
        } else {
            $rlt  =$sendRlt;
        }

        return $rlt;

    }

    private static function sendSms($mobile, $post_send_code, $real_send_code, $content){
        $send_code = $post_send_code;
        $mobile_code = $real_send_code;

        $rlt = self::iniFuncRlt();

        $checkRlt = self::check($mobile, $send_code);
        if (!$checkRlt['status'] ) {
            return $checkRlt;
        }

        if (DEBUG_MODE!=0){
            $_SESSION['mobile_code'] = '123123';
            $rlt['status']=true;
            $rlt['msg']='调试中，验证码 123123';
            return $rlt;
        }else {

            $sendRlt = self::_sendSms($mobile, $content);
            if ($sendRlt['status']) {
                $_SESSION['mobile'] = $mobile;
                $_SESSION['mobile_code'] = $mobile_code;
                $rlt = $sendRlt;
            } else {
                $rlt = $sendRlt;
            }
        }
        return $rlt;
    }

    public static function randomkeys($length, $pattern = '1234567890') {
        $output = '';

        $output .= $pattern {mt_rand ( 0, strlen ( $pattern ) - 1 )};
        for($a = 1; $a < $length; $a ++) {
            $output .= $pattern {mt_rand ( 0, strlen ( $pattern ) - 1 )}; // 生成php随机数
        }
        while (substr($output, 0,1) == '0'){
            $output =self::randomkeys($length,$pattern);
        }

        return $output;
    }

    /**
     * 发送短信验证码
     * @param string $mobile
     * @param string $content
     * @return Ambigous <string, multitype:>
     */
    private static function _sendSms($mobile, $content){
        $rlt = self::iniFuncRlt();

        $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        $post_data = "account=cf_xiche&password=" . md5('xc.2015') . "&mobile=" . $mobile . "&content=" . rawurlencode($content);

        $sendRlt = self::requestByCurl($target, $post_data);//  UTool::curlPost ( $target, $post_data );
        $gets = self::xml_to_array($sendRlt);
        if ($gets ['SubmitResult'] ['code'] == 2) {
            $rlt ['status'] = true;
            $rlt ['msg'] = '验证码已发送，有效期5分钟';
        } else {
//                $rlt ['msg']=$gets ['SubmitResult'];
            $rlt ['msg'] = '短信验证服务器忙，请稍后再试' . $gets ['SubmitResult'] ['code'] . $mobile;
        }
        $rlt['status'] = true;

        return $rlt;
    }

    /**
     * 检查发送验证码前
     * @param string $mobile
     * @param string $send_code
     * @return string|Ambigous <string, boolean>
     */
    public static function check($mobile, $send_code)
    {
        $rlt = self::iniFuncRlt();
        $isTel = preg_match('/^1\d{10}$/', $mobile);

        if (empty ($_SESSION['send_code']) || !$isTel ) {
            $rlt ['msg'] = '请输入有效手机号！';
            return $rlt;
        }

        if (! preg_match ( '/^1\d{10}$/', $mobile )) {
            $rlt ['msg'] = '手机号码格式不正确';
            return  $rlt ;
        }

        if (empty ( $_SESSION['send_code']) or $send_code != $_SESSION['send_code']) {
            // 防用户恶意请求
            $rlt ['msg'] = '请求超时，请刷新页面后重试';
            return  $rlt ;
        }

        $rlt['status']=true;
        return $rlt;
    }

    /**
     * curl实现get post
     * @param string $url 链接
     * @param string $post_data_string  post方式时参数data null=get方式
     * @param bool $isSSL 是否安全链接
     * @param bool $isJSON 是否传递json类型数据
     * @param int $timeout 超时时间
     * @return boolean|mixed
     */
    public static function requestByCurl($url, $post_data_string=NULL,$isSSL=FALSE, $timeout=5,$isJSON=FALSE){
        if(empty($url) || $timeout <=0){
            return false;
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);

        if ($isJSON){
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: ' . strlen($post_data_string))
            );
        }else{
            curl_setopt($curl, CURLOPT_HEADER, FALSE);
        }

        if(!empty($post_data_string)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data_string);
        }

        if ($isSSL == TRUE){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, (int)$timeout);

        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    public static function xml_to_array($xml) {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all ( $reg, $xml, $matches )) {
            $count = count ( $matches [0] );
            for($i = 0; $i < $count; $i ++) {
                $subxml = $matches [2] [$i];
                $key = $matches [1] [$i];
                if (preg_match ( $reg, $subxml )) {
                    $arr [$key] = self::xml_to_array ( $subxml );
                } else {
                    $arr [$key] = $subxml;
                }
            }
        }
        return $arr;
    }

}