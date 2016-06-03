<?php

/**
 * 功能描述 评论模型
 * ============================================================================
 * 文件名称：CommentModel.class.php
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class CommentModel extends BaseModel {

    /**
     * 查询评论内容
     *
     * @access  public
     * @params  integer     $id
     * @params  integer     $type
     * @params  integer     $page
     * @return  array
     */
    function assign_comment($id, $type, $rank = 0, $page = 1) {
        $rank_info = '';
        if ($rank == '1') {
            $rank_info = ' AND (comment_rank= 5 OR comment_rank = 4)';
        }
        if ($rank == '2') {
            $rank_info = ' AND (comment_rank= 2 OR comment_rank = 3)';
        }
        if ($rank == '3') {
            $rank_info = ' AND comment_rank= 1 ';
        }
        /* 取得评论列表 */
        $res = $this->row('SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0" . $rank_info);
        $count = $res['count'];
        $size = C('comments_number') > 0 ? C('comments_number') : 5;
        $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;
        $start = ($page - 1) * $size;
        $sql = 'SELECT * FROM ' . $this->pre .
                "comment WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0" . $rank_info .
                " ORDER BY comment_id DESC LIMIT $start , $size";
        $res = $this->query($sql);
        $arr = array();
        $ids = '';
        foreach ($res as $key => $row) {
            $ids .= $ids ? ",$row[comment_id]" : $row['comment_id'];
            $arr[$row['comment_id']]['id'] = $row['comment_id'];
            $arr[$row['comment_id']]['email'] = $row['email'];
            $arr[$row['comment_id']]['username'] = $row['user_name'];
            $arr[$row['comment_id']]['content'] = str_replace('\r\n', '<br />', htmlspecialchars($row['content']));
            $arr[$row['comment_id']]['content'] = nl2br(str_replace('\n', '<br />', $arr[$row['comment_id']]['content']));
            $arr[$row['comment_id']]['rank'] = $row['comment_rank'];
            $arr[$row['comment_id']]['add_time'] = local_date(C('time_format'), $row['add_time']);
        }
        /* 取得已有回复的评论 */
        if ($ids) {
            $sql = 'SELECT * FROM ' . $this->pre .
                    "comment WHERE parent_id IN( $ids )";
            $res = $this->query($sql);
            foreach ($res as $row) {
                $arr[$row['parent_id']]['re_content'] = nl2br(str_replace('\n', '<br />', htmlspecialchars($row['content'])));
                $arr[$row['parent_id']]['re_add_time'] = local_date(C('time_format'), $row['add_time']);
                $arr[$row['parent_id']]['re_email'] = $row['email'];
                $arr[$row['parent_id']]['re_username'] = $row['user_name'];
            }
        }
        /* 分页样式 */
        //$pager['styleid'] = isset(C('page_style'))? intval(C('page_style')) : 0;
        $pager['page'] = $page;
        $pager['size'] = $size;
        $pager['record_count'] = $count;
        $pager['page_count'] = $page_count;
        $pager['page_first'] = "javascript:gotoPage(1,$id,$type,$rank)";
        $pager['page_prev'] = $page > 1 ? "javascript:gotoPage(" . ($page - 1) . ",$id,$type,$rank)" : 'javascript:;';
        $pager['page_next'] = $page < $page_count ? 'javascript:gotoPage(' . ($page + 1) . ",$id,$type,$rank)" : 'javascript:;';
        $pager['page_last'] = $page < $page_count ? 'javascript:gotoPage(' . $page_count . ",$id,$type,$rank)" : 'javascript:;';
        $cmt = array('comments' => $arr, 'pager' => $pager);
        return $cmt;
    }

    /**
     * 添加评论内容
     *
     * @access public
     * @param object $cmt        	
     * @return void
     */
    function add_comment($cmt) {
        /* 评论是否需要审核 */
        $status = 1 - C('comment_check');

        $user_id = empty($_SESSION ['user_id']) ? 0 : $_SESSION ['user_id'];
        $email = empty($cmt->email) ? $_SESSION ['email'] : trim($cmt->email);
        $user_name = empty($cmt->username) ? $_SESSION ['user_name'] : '';
        $email = htmlspecialchars($email);
        $user_name = htmlspecialchars($user_name);

        /* 保存评论内容 */
        $sql = "INSERT INTO " . $this->pre . "comment(comment_type, id_value, email, user_name, content, comment_rank, add_time, ip_address, status, parent_id, user_id) VALUES " . "('" . $cmt->type . "', '" . $cmt->id . "', '$email', '$user_name', '" . $cmt->content . "', '" . $cmt->rank . "', " . gmtime() . ", '" . real_ip() . "', '$status', '0', '$user_id')";


        $result = $this->query($sql);
        clear_cache_files('comments_list.lbi');
        return $result;
    }

    /**
     * 获取商品总的评价详情 by yuanzb
     * @param type $id
     * @param type $type
     */
    function get_comment_info($id, $type) {

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0" .
                ' ORDER BY comment_id DESC';
        $result = $this->row($sql);
        $info['count'] = $result['count'];

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$id' AND comment_type = '$type' AND (comment_rank= 5 OR comment_rank = 4) AND status = 1 AND parent_id = 0" .
                ' ORDER BY comment_id DESC';
        $result = $this->row($sql);
        $favorable = $result['count'];

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0 AND(comment_rank = 2 OR comment_rank = 3)" .
                ' ORDER BY comment_id DESC';
        $result = $this->row($sql);
        $medium = $result['count'];

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$id' AND comment_type = '$type' AND status = 1 AND parent_id = 0 AND comment_rank = 1 " .
                ' ORDER BY comment_id DESC';
        $result = $this->row($sql);
        $bad = $result['count'];

        $info['favorable_count'] = $favorable; //好评数量
        $info['medium_count'] = $medium; //中评数量
        $info['bad_count'] = $bad; //差评数量
        if ($info['count'] > 0) {
            $info['favorable'] = 0;
            if ($favorable) {
                $info['favorable'] = round(($favorable / $info['count']) * 100);  //好评率
            }
            $info['medium'] = 0;
            if ($medium) {
                $info['medium'] = round(($medium / $info['count']) * 100); //中评
            }
            $info['bad'] = 0;
            if ($bad) {
                $info['bad'] = round(($bad / $info['count']) * 100); //差评
            }
        } else {
            $info['favorable'] = 100;
            $info['medium'] = 100;
            $info['bad'] = 100;
        }
        return $info;
    }

    /**
     * 获取商品所有评论数量 by yuanzb
     * @param type $goods_id
     * @param type $type
     * @return type
     */
    function get_goods_comment($goods_id, $type) {

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$goods_id' AND comment_type = '$type' AND status = 1 AND parent_id = 0" .
                ' ORDER BY comment_id DESC';
        $result = $this->row($sql);
        return $result['count'];
    }

    /**
     * 获取商品好评百分比 by yuanzb
     * @param type $goods_id
     * @param type $type
     * @return type
     */
    function favorable_comment($goods_id, $type) {

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$goods_id' AND comment_type = '$type' AND status = 1 AND parent_id = 0" .
                ' ORDER BY comment_id DESC';
        $result = $this->row($sql);
        $count = $result['count'];

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre .
                "comment WHERE id_value = '$goods_id' AND comment_type = '$type' AND (comment_rank= 5 OR comment_rank = 4) AND status = 1 AND parent_id = 0" .
                ' ORDER BY comment_id DESC';
        $goods_result = $this->row($sql);
        $good_count = $goods_result['count'];
        $round = 100;
        if ($count > 0) {
            $round = round(($good_count / $count) * 100);
        }
        return $round;
    }

    /**
     * @param $user_id  用户ID
     * @param int $type 类型 0未评论 ，1已评论
     * @return Ambigous|bool|unknown
     */
    function get_goods_list($user_id){
        $sql = "SELECT oi.order_id,oi.order_sn,oi.consignee,oi.add_time FROM " . $this->pre . "order_info AS oi LEFT JOIN ".$this->pre."order_goods AS og ON oi.order_id=og.order_id WHERE oi.user_id=".$user_id." AND oi.order_status in (1,5) AND oi.shipping_status=2 AND oi.pay_status=2 AND oi.supplier_id=0 GROUP BY oi.order_id ORDER BY add_time DESC";

        $res=$this->query($sql);
        foreach ($res as $key => $re) {
            $sql="SELECT og.*,g.goods_thumb FROM ".$this->pre ."order_goods AS og LEFT JOIN ".$this->pre."goods AS g ON og.goods_id=g.goods_id WHERE order_id=".$re['order_id']." AND og.is_comment = 0 AND og.user_id = ".$_SESSION['user_id'];
            
            $order_goods=$this->query($sql);
            $res[$key]['goods_info'] =$order_goods;
            $res[$key]['add_time']=date('m-d H:i:s',$res[$key]['add_time']);

            $sql="SELECT og.*,g.goods_thumb FROM ".$this->pre ."order_goods AS og LEFT JOIN ".$this->pre."goods AS g ON og.goods_id=g.goods_id WHERE order_id=".$re['order_id']." AND og.is_comment = 1 AND og.user_id = ".$_SESSION['user_id'];
            $goods_order=$this->query($sql);
            $res[$key]['info_goods'] =$goods_order;
        }
        // p(1);
        return $res;
    }
    /**
     *  查询评论商品数量
     *
     * @access  public
     * @param   int     $user_id        用户id
     * @param   int     $page_size      列表最大数量
     * @param   int     $start          列表起始页
     * @return  res
     */
    function comment_count($user_id) {
        
        // $where = "g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND " . "g.is_delete = 0 ";
       $sql = "SELECT COUNT(*) FROM " . $this->pre . "order_info WHERE user_id=".$user_id." AND order_status in (1,5) AND shipping_status=2 AND pay_status=2 AND supplier_id=0";
         $recount = $this->row($sql);
         // p($recount);
        // for($i=0;$i<=$recount['count'];$i++) 
        // {
        // $sql = 'SELECT og.goods_name FROM ' . $this->pre . "order_info AS oi LEFT JOIN ".$this->pre."order_goods AS og ON oi.order_id=og.order_id WHERE oi.user_id=".$_SESSION['user_id']." AND oi.order_status in (1,5) AND oi.shipping_status=2 AND oi.pay_status=2 AND oi.supplier_id=0";
        // // p($sql,0);
        // }
        
        // p($sql);

        // return $res['count'];
    }

    /**
     *  获取用户评论
     *
     * @access  public
     * @param   int     $user_id        用户id
     * @param   int     $page_size      列表最大数量
     * @param   int     $start          列表起始页
     * @return  comments
     */
    function get_comment_list($user_id, $page_size, $start)
    {
        $sql = "SELECT c.*, g.goods_name AS cmt_name, r.content AS reply_content, r.add_time AS reply_time ".
            " FROM " . $this->pre . "comment AS c ".
            " LEFT JOIN " . $this->pre . "comment AS r ".
            " ON r.parent_id = c.comment_id AND r.parent_id > 0 ".
            " LEFT JOIN " . $this->pre . "goods AS g ".
            " ON c.comment_type=0 AND g.goods_number != 0 AND c.id_value = g.goods_id ".
            " WHERE c.user_id='$user_id' ORDER BY add_time DESC LIMIT ".$start.",".$page_size;
        $res=$this->query($sql);

        $comments = array();
        $to_article = array();
        while ($row = $this->fetchRow($res))
        {
            $row['formated_add_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
            if ($row['reply_time'])
            {
                $row['formated_reply_time'] = local_date($GLOBALS['_CFG']['time_format'], $row['reply_time']);
            }
            if ($row['comment_type'] == 1)
            {
                $to_article[] = $row["id_value"];
            }
            $comments[] = $row;
        }

        if ($to_article)
        {
            $sql = "SELECT article_id , title FROM " . $GLOBALS['ecs']->table('article') . " WHERE " . db_create_in($to_article, 'article_id');
            $arr = $GLOBALS['db']->getAll($sql);
            $to_cmt_name = array();
            foreach ($arr as $row)
            {
                $to_cmt_name[$row['article_id']] = $row['title'];
            }

            foreach ($comments as $key=>$row)
            {
                if ($row['comment_type'] == 1)
                {
                    $comments[$key]['cmt_name'] = isset($to_cmt_name[$row['id_value']]) ? $to_cmt_name[$row['id_value']] : '';
                }
            }
        }

        return $comments;
    }
    
    
    function get_comment_goods($user_id){
        
        $sql="SELECT c.*,g.goods_thumb,g.shop_price,g.goods_name FROM ".$this->pre."comment AS c LEFT JOIN ".$this->pre."goods AS g ON c.id_value=g.goods_id WHERE comment_type=0 AND user_id=".$user_id." ORDER BY add_time DESC";
        $res=$this->query($sql);
        return $res;
    }

}
