<?php

/** 功能描述：用户评论控制器
 * ============================================================================
 * 文件名称：CommentControoller.class.php
 * ----------------------------------------------------------------------------
 */
/* 访问控制 */
defined('IN_ECTOUCH') or die('Deny Access');

class CommentController extends CommonController {

    private $cmt;
    private $act;
    protected $user_id;
    private $size = 10; // 每页数据
    private $page = 1; // 页数
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        $this->user_id = $_SESSION['user_id'];
        /* 只有在没有提交评论内容以及没有act的情况下才跳转 */
        $this->cmt = I('request.cmt');
        $this->act = I('request.act');
        if (!isset($this->cmt) && !isset($this->act)) {
            ecs_header("Location: ./\n");
        }
    }

    public function index() {

        $result = array('error' => 0, 'message' => '', 'content' => '');
        if (empty($this->act)) {

            $this->cmt = I('request.cmt', '', 'json_str_iconv');
            $result = array(
                'error' => 0,
                'message' => '',
                'content' => ''
            );
            if (empty($this->act)) {
                /*
                 * act 参数为空 默认为添加评论内容
                 */
                $json = new EcsJson;
                $cmt = $json->decode($this->cmt);

                $cmt->page = 1;
                $cmt->id = !empty($cmt->id) ? intval($cmt->id) : 0;
                $cmt->type = !empty($cmt->type) ? intval($cmt->type) : 0;

                if (empty($cmt) || !isset($cmt->type) || !isset($cmt->id)) {
                    $result ['error'] = 1;
                    $result ['message'] = L('invalid_comments');
                } elseif (!is_email($cmt->email)) {
                    $result ['error'] = 1;
                    $result ['message'] = L('error_email');
                } else {
                    if ((intval(C('captcha')) & CAPTCHA_COMMENT) && gd_version() > 0) {
                        /* 检查验证码 */
                        if ($_SESSION ['ectouch_verify'] !== strtoupper($cmt->captcha)) {
                            $result ['error'] = 1;
                            $result ['message'] = L('invalid_captcha');
                        } else {
                            $factor = intval(C('comment_factor'));
                            if ($cmt->type == 0 && $factor > 0) {
                                /* 只有商品才检查评论条件 */
                                switch ($factor) {
                                    case COMMENT_LOGIN :
                                        if ($_SESSION ['user_id'] == 0) {
                                            $result ['error'] = 1;
                                            $result ['message'] = L('comment_login');
                                        }
                                        break;

                                    case COMMENT_CUSTOM :

                                        if ($_SESSION ['user_id'] > 0) {
                                            $condition = "user_id = '" . $_SESSION ['user_id'] . "'" . " AND (order_status = '" . OS_CONFIRMED . "' or order_status = '" . OS_SPLITED . "') " . " AND (pay_status = '" . PS_PAYED . "' OR pay_status = '" . PS_PAYING . "') " . " AND (shipping_status = '" . SS_SHIPPED . "' OR shipping_status = '" . SS_RECEIVED . "') ";
                                            $tmp = $this->model->table('order_info')->field('order_id')->where($condition)->getOne();
                                            if (empty($tmp)) {
                                                $result ['error'] = 1;
                                                $result ['message'] = L('comment_custom');
                                            }
                                        } else {
                                            $result ['error'] = 1;
                                            $result ['message'] = L('comment_custom');
                                        }
                                        break;
                                    case COMMENT_BOUGHT :
                                        if ($_SESSION ['user_id'] > 0) {
                                            $sql = "SELECT o.order_id" . " FROM " . $this->model->pre . "order_info AS o, " . $this->model->pre . "order_goods AS og " . " WHERE o.order_id = og.order_id" . " AND o.user_id = '" . $_SESSION ['user_id'] . "'" . " AND og.goods_id = '" . $cmt->id . "'" . " AND (o.order_status = '" . OS_CONFIRMED . "' or o.order_status = '" . OS_SPLITED . "') " . " AND (o.pay_status = '" . PS_PAYED . "' OR o.pay_status = '" . PS_PAYING . "') " . " AND (o.shipping_status = '" . SS_SHIPPED . "' OR o.shipping_status = '" . SS_RECEIVED . "') " . " LIMIT 1";

                                            $res = $this->model->query($sql);
                                            $tmp = $res[0]['order_id'];
                                            if (empty($tmp)) {
                                                $result ['error'] = 1;
                                                $result ['message'] = L('comment_brought');
                                            }
                                        } else {
                                            $result ['error'] = 1;
                                            $result ['message'] = L('comment_brought');
                                        }
                                }
                            }

                            /* 无错误就保存留言 */
                            if (empty($result ['error'])) {
                                model('Comment')->add_comment($cmt);
                            }
                        }
                    } else {
                        /* 没有验证码时，用时间来限制机器人发帖或恶意发评论 */
                        if (!isset($_SESSION ['send_time'])) {
                            $_SESSION ['send_time'] = 0;
                        }

                        $cur_time = gmtime();
                        if (($cur_time - $_SESSION ['send_time']) < 30) { // 小于30秒禁止发评论
                            $result ['error'] = 1;
                            $result ['message'] = L('cmt_spam_warning');
                        } else {
                            $factor = intval(C('comment_factor'));
                            if ($cmt->type == 0 && $factor > 0) {
                                /* 只有商品才检查评论条件 */
                                switch ($factor) {
                                    case COMMENT_LOGIN :
                                        if ($_SESSION ['user_id'] == 0) {
                                            $result ['error'] = 1;
                                            $result ['message'] = L('comment_login');
                                        }
                                        break;

                                    case COMMENT_CUSTOM :
                                        if ($_SESSION ['user_id'] > 0) {
                                            $condition = "user_id = '" . $_SESSION ['user_id'] . "'" . " AND (o.order_status = '" . OS_CONFIRMED . "' or o.order_status = '" . OS_SPLITED . "') " . " AND (o.pay_status = '" . PS_PAYED . "' OR o.pay_status = '" . PS_PAYING . "') " . " AND (o.shipping_status = '" . SS_SHIPPED . "' OR o.shipping_status = '" . SS_RECEIVED . "') ";
                                            $tmp = $this->model->table('order_info')->field('order_id')->where($condition)->getOne();
                                            if (empty($tmp)) {
                                                $result ['error'] = 1;
                                                $result ['message'] = L('comment_custom');
                                            }
                                        } else {
                                            $result ['error'] = 1;
                                            $result ['message'] = L('comment_custom');
                                        }
                                        break;

                                    case COMMENT_BOUGHT :
                                        if ($_SESSION ['user_id'] > 0) {
                                            $sql = "SELECT o.order_id" . " FROM " . $this->model->pre . "order_info AS o, " . $this->model->pre . "order_goods AS og " . " WHERE o.order_id = og.order_id" . " AND o.user_id = '" . $_SESSION ['user_id'] . "'" . " AND og.goods_id = '" . $cmt->id . "'" . " AND (o.order_status = '" . OS_CONFIRMED . "' or o.order_status = '" . OS_SPLITED . "') " . " AND (o.pay_status = '" . PS_PAYED . "' OR o.pay_status = '" . PS_PAYING . "') " . " AND (o.shipping_status = '" . SS_SHIPPED . "' OR o.shipping_status = '" . SS_RECEIVED . "') " . " LIMIT 1";
                                            $res = $this->model->query($sql);
                                            $tmp = $res[0]['order_id'];
                                            if (empty($tmp)) {
                                                $result ['error'] = 1;
                                                $result ['message'] = L('comment_brought');
                                            }
                                        } else {
                                            $result ['error'] = 1;
                                            $result ['message'] = L('comment_brought');
                                        }
                                }
                            }
                            /* 无错误就保存留言 */
                            if (empty($result ['error'])) {
                                model('Comment')->add_comment($cmt);
                                $_SESSION ['send_time'] = $cur_time;
                            }
                        }
                    }
                }
            }
        } else {
            /*
             * act 参数不为空 默认为评论内容列表 根据 _GET 创建一个静态对象
             */
            $cmt = new stdClass ();
            $id = I('get.id');
            $type = I('get.type');
            $page = I('get.page');
            $cmt->id = !empty($id) ? intval($id) : 0;
            $cmt->type = !empty($type) ? intval($type) : 0;
            $rank = I('get.rank');
            $cmt->page = isset($page) && intval($page) > 0 ? intval($page) : 1;
        }

        if ($result ['error'] == 0) {
            //全部评价
            $comment = model('Comment')->assign_comment($cmt->id, $cmt->type, 0, $cmt->page);
            $this->assign('comment_list', $comment['comments']);
            $this->assign('pager', $comment['pager']);
            //好评           
            $comment_favorable = model('Comment')->assign_comment($cmt->id, $cmt->type, '1');
            $this->assign('comment_fav', $comment_favorable['comments']);
            $this->assign('pager_fav', $comment_favorable['pager']);
            //中评
            $comment_medium = model('Comment')->assign_comment($cmt->id, $cmt->type, '2');
            $this->assign('comment_med', $comment_medium['comments']);
            $this->assign('pager_med', $comment_medium['pager']);
            //差评
            $comment_bad = model('Comment')->assign_comment($cmt->id, $cmt->type, '3');
            $this->assign('comment_bad', $comment_bad['comments']);
            $this->assign('pager_poor', $comment_bad['pager']);
            if ($rank == 1) {
                $comment_favorable = model('Comment')->assign_comment($cmt->id, $cmt->type, '1', $cmt->page);
                $this->assign('comment_fav', $comment_favorable['comments']);
                $this->assign('pager_fav', $comment_favorable['pager']);
            }
            if ($rank == 2) {
                $comment_medium = model('Comment')->assign_comment($cmt->id, $cmt->type, '2', $cmt->page);
                $this->assign('comment_med', $comment_medium['comments']);
                $this->assign('pager_med', $comment_medium['pager']);
            }
            if ($rank == 3) {
                $comment_bad = model('Comment')->assign_comment($cmt->id, $cmt->type, '3', $cmt->page);
                $this->assign('comment_bad', $comment_bad['comments']);
                $this->assign('pager_bad', $comment_bad['pager']);
            } else {
                $comment = model('Comment')->assign_comment($cmt->id, $cmt->type, '0', $cmt->page);
                $this->assign('comment_list', $comment['comments']);
                $this->assign('pager', $comment['pager']);
            }
            $this->assign('rank', $rank);
            $this->assign('comments_info', model('Comment')->get_comment_info($cmt->id, $cmt->type));
            $this->assign('comment_type', $cmt->type);
            $this->assign('id', $cmt->id);
            $this->assign('username', $_SESSION['user_name']);
            $this->assign('email', $_SESSION['email']);
            /* 验证码相关设置 */
            if ((intval(C('captcha')) & CAPTCHA_COMMENT) && gd_version() > 0) {
                $this->assign('enabled_captcha', 1);
                $this->assign('rand', mt_rand());
            }
            //$result['rank'] = $rank;
            $result['message'] = C('comment_check') ? L('cmt_submit_wait') : L('cmt_submit_done');
            $result['content'] = ECTouch::$view->fetch("library/comments_list.lbi");
        }
        echo json_encode($result);
    }

    public function comment_list(){
        if($_SESSION['user_id']>0){
            $this->user_id=$_SESSION['user_id'];
            $comment=model('comment')->get_comment_goods($this->user_id,10,0);
        $goods_search = model('Goodslist')->goodslist_get_search();
        $this->assign('goods_search', $goods_search);
        $this->assign('comment_list',$comment);
        $this->assign('path',C('SHOP_URL'));
        $this->display('comment_list.dwt');
    }else{
            echo "<script type='javascript'> alert('请您登陆');</script>";
            $url = __HOST__ . $_SERVER['REQUEST_URI'];
            $this->redirect(url('user/login', array(
                'referer' => urlencode($url)
            )));
            exit();
    }
}
    public function insert_comment(){
        if($_POST['goodsid']){
        $sql = "SELECT goods_sn FROM "  . $this->model->pre ."goods WHERE goods_id =".$_POST['goodsid'];
        $res = model('comment')->row($sql); 
/*        $sql = "SELECT user_name FROM " . $this->model->pre ."users WHERE user_id = ".$_SESSION['user_id'];
        $result = model('comment')->row($sql); */
        }

        if($_SESSION['user_id'] > 0 && !empty($_SESSION['user_name'])){
        $sql = "INSERT INTO ". $this->model->pre ."comment (id_value,user_name,content,comment_rank,add_time,ip_address,status,user_id,goods_sn,order_id) VALUES(".$_POST['goodsid'].",\"".$_SESSION['user_name']."\",\"".$_POST['content']."\",5,".time().",\"".$_SERVER['SERVER_ADDR']."\",0,".$_SESSION['user_id'].",".$res['goods_sn'].",".$_POST['orderid'].")";
        $row = model('comment')->row($sql);
        $sql = "UPDATE ". $this->model->pre ."order_goods SET is_comment = 1 WHERE order_id = ".$_POST['orderid']." AND goods_id = ".$_POST['goodsid'];
        model('comment')->query($sql);
        $this->redirect(url('comment/comment_list'));
        }else{
            echo "<script type='javascript'> alert('请您登陆');</script>";
            $url = __HOST__ . $_SERVER['REQUEST_URI'];
            $this->redirect(url('user/login', array(
                'referer' => urlencode($url)
            )));
            exit();
        }
    }

    /**
     * @return mixed
     */
    public function comment()
    {
        $this->user_id=$_SESSION['user_id'];
        $gid=$_REQUEST['gid'];
        $oid=$_REQUEST['oid'];
        $goods_info = model('Goods')->goods_info($gid);

        /*$com_info=array();
        $com_info['comment_type']=0;
        $com_info['id_value']=$gid;
        $com_info['user_name']=0;
        $com_info['comment_type']=0;
        $com_info['comment_type']=0;*/

        $this->assign('order_id',$oid);
        $this->assign('goods_info',$goods_info);
        $this->assign('path',C('SHOP_URL'));
        
        $this->display('comment_info.dwt');

        
    }
 

}
