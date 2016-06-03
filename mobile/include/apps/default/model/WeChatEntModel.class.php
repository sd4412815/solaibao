<?php
/**
 * User: Yuan
 * Date: 2016-04-19
 * Time: 10:12
 */

/* 微信model */
defined('IN_ECTOUCH') or die('Deny Access');

class WeChatEntModel extends BaseModel {

    /**
     * @param $appName 应用名称
     * @return bool    微信连接数据
     */
    public function init($appId){
        $sql="SELECT * FROM ". $this->pre."weixin_ctoken where wc_agent_id='".$appId."'";
        $data=$this->row($sql);
        return $data;
    }





}