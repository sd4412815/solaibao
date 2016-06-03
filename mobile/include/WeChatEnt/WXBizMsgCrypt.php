<?php

/**
 * 对公众平台发送给公众账号的消息加解密.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */


include_once "sha1.php";
include_once "xmlparse.php";
include_once "pkcs7Encoder.php";
include_once "errorCode.php";

/**
 * 1.第三方回复加密消息给公众平台；
 * 2.第三方收到公众平台发送的消息，验证消息的安全性，并对消息进行解密。
 */
class WXBizMsgCrypt
{
	const WXENT_API 	= 'https://qyapi.weixin.qq.com/cgi-bin/';
	const secrect 		= 'uZoq9zw4rirsP5Dz5BBSLEG88BxgiKBI8rECOvyu5kauxNFiwOEtztompNjWE54W';
	private $m_sToken;
	private $m_sEncodingAesKey;
	private $m_sCorpid;



	/**
	 * 构造函数
	 * @param $token string 公众平台上，开发者设置的token
	 * @param $encodingAesKey string 公众平台上，开发者设置的EncodingAESKey
	 * @param $Corpid string 公众平台的Corpid
	 */
	public function WXBizMsgCrypt($appId)
	{
		$weixin_info = model('WeChatEnt')->init($appId);
		if(empty($weixin_info)){
		    self::error('您输入的ID有误，请重新输入');
			exit;
		}
		$this->m_sToken 			= $weixin_info['wc_user_token'];
		$this->m_sEncodingAesKey 	= $weixin_info['wc_encoding_aeskey'];
		$this->m_sCorpid 			= $weixin_info['wc_corp_id'];
	}


	/*
	 *  OAuth2验证
	 * @param string code
	 * @param int userid
	 * yuanzb
	 */
	public function getUserId($code){
		if (empty($code)) {
			return 'code为空';
		}
		$token=self::getToken();
		if (empty($token)) {
			return 'token无效';
		}
		$url = self::WXENT_API . 'user/getuserinfo?access_token='.$token.'&code='.$code;
		$rlt = self::https_request( $url);
		$id=json_decode($rlt)->UserId;
		return $id;
	}


	/**
	 * 获取token by yuanzb
	 */
	public function getToken($secrect=null)
	{
		if($secrect==null){
			$url= self::WXENT_API .'gettoken?corpid='.$this->m_sCorpid.'&corpsecret='.self::secrect;
		}else{
			$url= self::WXENT_API .'gettoken?corpid='.$this->m_sCorpid.'&corpsecret='.$secrect;
		}
		$token =$this->https_request($url);
		$token = json_decode($token,true);
		return $token['access_token'];
	}


	/**
	 * 获得用户发过来的消息（消息内容和消息类型 ）
	 *
	 * @access public
	 * @return void
	 */
	

	public function beginInit() {

		$sReqMsgSig 	= $sVerifyMsgSig = $_GET['msg_signature'];
		$sReqTimeStamp 	= $sVerifyTimeStamp = $_GET['timestamp'];
		$sReqNonce 		= $sVerifyNonce = $_GET['nonce'];
		$sReqData 		= file_get_contents("php://input");
		$sVerifyEchoStr = $_GET['echostr'];//接收微信传给我的数据。

		if($sVerifyEchoStr){
			$sEchoStr = "";
			$errCode = $this->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);//传给这个验证,这里都是微信提供的方法了。
			if ($errCode == 0) {
				print((string)$sEchoStr);
			} else {
				print($errCode . "\n\n");
			}
			exit;
		}
	}
	
    /*
	*验证URL
    *@param sMsgSignature: 签名串，对应URL参数的msg_signature
    *@param sTimeStamp: 时间戳，对应URL参数的timestamp
    *@param sNonce: 随机串，对应URL参数的nonce
    *@param sEchoStr: 随机串，对应URL参数的echostr
    *@param sReplyEchoStr: 解密之后的echostr，当return返回0时有效
    *@return：成功0，失败返回对应的错误码
	*/
	public function VerifyURL($sMsgSignature, $sTimeStamp, $sNonce, $sEchoStr, &$sReplyEchoStr)
	{
		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);
		//verify msg_signature
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $sEchoStr);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($sEchoStr, $this->m_sCorpid);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sReplyEchoStr = $result[1];

		return ErrorCode::$OK;
	}
	/**
	 * 将公众平台回复用户的消息加密打包.
	 * <ol>
	 *    <li>对要发送的消息进行AES-CBC加密</li>
	 *    <li>生成安全签名</li>
	 *    <li>将消息密文和安全签名打包成xml格式</li>
	 * </ol>
	 *
	 * @param $replyMsg string 公众平台待回复用户的消息，xml格式的字符串
	 * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
	 * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
	 * @param &$encryptMsg string 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,
	 *                      当return返回0时有效
	 *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function EncryptMsg($sReplyMsg, $sTimeStamp, $sNonce, &$sEncryptMsg)
	{
		$pc = new Prpcrypt($this->m_sEncodingAesKey);

		//加密
		$array = $pc->encrypt($sReplyMsg, $this->m_sCorpid);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}

		if ($sTimeStamp == null) {
			$sTimeStamp = time();
		}
		$encrypt = $array[1];

		//生成安全签名
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		$signature = $array[1];

		//生成发送的xml
		$xmlparse = new XMLParse;
		$sEncryptMsg = $xmlparse->generate($encrypt, $signature, $sTimeStamp, $sNonce);
		return ErrorCode::$OK;
	}


	/**
	 * 检验消息的真实性，并且获取解密后的明文.
	 * <ol>
	 *    <li>利用收到的密文生成安全签名，进行签名验证</li>
	 *    <li>若验证通过，则提取xml中的加密消息</li>
	 *    <li>对消息进行解密</li>
	 * </ol>
	 *
	 * @param $msgSignature string 签名串，对应URL参数的msg_signature
	 * @param $timestamp string 时间戳 对应URL参数的timestamp
	 * @param $nonce string 随机串，对应URL参数的nonce
	 * @param $postData string 密文，对应POST请求的数据
	 * @param &$msg string 解密后的原文，当return返回0时有效
	 *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function DecryptMsg($sMsgSignature, $sTimeStamp = null, $sNonce, $sPostData, &$sMsg)
	{
		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);

		//提取密文
		$xmlparse = new XMLParse;
		$array = $xmlparse->extract($sPostData);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		if ($sTimeStamp == null) {
			$sTimeStamp = time();
		}

		$encrypt = $array[1];
		$touser_name = $array[2];

		//验证安全签名
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($encrypt, $this->m_sCorpid);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sMsg = $result[1];

		return ErrorCode::$OK;
	}

	/*增加代码 by yuanzb 2016-4-13 11:28:35 */
	/**
	 * curl实现get post
	 * @param string $url 链接
	 * @param string $post_data_string  post方式时参数data null=get方式
	 * @param bool $isSSL 是否安全链接
	 * @param bool $isJSON 是否传递json类型数据
	 * @param int $timeout 超时时间
	 * @return boolean|mixed
	 */
	/*public function requestByCurl($url, $post_data_string=NULL,$isSSL=FALSE, $timeout=5,$isJSON=FALSE){
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
	}*/

	/**curl实现get post by yuanzb
	 * @param $url  $url 链接
	 * @param null $data post方式时参数data null=get方式
	 * @param bool $isJSON 是否传递json类型数据
	 * @return mixed
	 */
	public function https_request($url, $data=NULL, $isJSON=FALSE){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if ($isJSON){
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json; charset=utf-8',
					'Content-Length: ' . strlen($data))
			);
		}else{
			curl_setopt($curl, CURLOPT_HEADER, FALSE);
		}
		if(!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

	/*增加代码 by yuanzb 2016-4-13 11:28:35 */
//yang
	/**
	 * 错误输出
	 */
	public function error($content=NULL){
		if($content==null){
		    $content='参数有误！';
		}
		$content=(string)$content;
		echo '
			<body style=" background:#ccc; font-family:\'微软雅黑\'; color: #333; font-size: 50px;">
				   <div style="margin-top:300px;text-align: center;">
				        <h1>: (</h1>
						<h3>'.$content.'</h3>
				   </div>
		    </body>';
	}
//yang
}

