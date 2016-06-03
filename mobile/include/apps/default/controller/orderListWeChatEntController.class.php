<?php
/**
 * User: Yuan
 * Date: 2016-04-11
 * Time: 9:46
 */
class orderListWeChatEntController extends CommonController
{
    const appId=32;

    public function index()
    {
        $weixin= new WXBizMsgCrypt(self::appId);
        $weixin->beginInit();
    }


    public function orderlist()
    {
        $weixin= new WXBizMsgCrypt(self::appId);
        $user_id=$weixin->getUserId($_GET['code']);
        if(empty($user_id)){
            $weixin->error('参数错误');
        }
        
//        $user_id='24';

        echo $user_id;

    }


}