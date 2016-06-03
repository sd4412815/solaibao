<?php
//yang
class goodsListWeChatEntController extends CommonController
{

    protected $token='ZoPh7PfQDI3gCqnsK';
    protected $EncodingAESKey='pWEtOh3KXtRvdTHyckrcu2z5DyaSMUtvmQDu8Cw35EI';
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
        if(!empty($user_id)){
        	$weixin->error();
        
        }
        
       // $user_id='24';
       // echo  $user_id;

    }
}
?> 