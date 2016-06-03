<?php
/**
 * User: Yuan
 * Date: 2016-04-11
 * Time: 9:46
 */
class priceListWeChatEntController extends CommonController
{

    protected $token='2Bo1VqUWFaCvAcJ1HfTsNiu4EUUt1bz6';
    protected $EncodingAESKey='sAIG4Y0EN81Cyojr2cypXPglw1RimQUjTBTC7PnrEYt';
    protected $CorpID='wxd52129f308a5007e';

    public function index()
    {
        $weixin= new WXBizMsgCrypt($this->token, $this->EncodingAESKey, $this->CorpID);
        $echostr = $weixin->beginInit();

    }


    public function orderlist()
    {
        $weixin= new WXBizMsgCrypt($this->token, $this->EncodingAESKey, $this->CorpID);
        $user_id=$weixin->getUserId($_GET['code']);
        if(empty($user_id)){
            $weixin->error();
        }
        
//        $user_id='24';

        echo $user_id;

    }



}