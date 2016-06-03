<?php

/**
 * 购物流程
 */

@define('IN_ECS', true);
// $_COOKIE['rec_id']=null;

require(ROOT_PATH . 'includes/lib_order.php');

assign_template_supplier();
/* 载入语言文件 */


// print_r(SESS_ID);
/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */


//echo "123";die;
/*------------------------------------------------------ */
//-- 添加商品到购物车
/*------------------------------------------------------ */
if ($_GET['go'] == 'flow')
{
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
    $sql = "SELECT rec_id FROM " . $ecs->table('cart') ." WHERE  user_id = " .$_SESSION['user_id']. " AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

    $cart_id =  $GLOBALS['db']->getRow($sql);
   
    $sql = "SELECT order_sn FROM " . $ecs->table('order_info') ." WHERE  user_id = " .$_SESSION['user_id'];

    $sn[] =  $GLOBALS['db']->getRow($sql);
   
    /* 取得购物类型 */
    

    /* 团购标志 */
    if ($flow_type == CART_GROUP_BUY_GOODS)
    {
        $smarty->assign('is_group_buy', 1);
    }
    /* 积分兑换商品 */
    elseif ($flow_type == CART_EXCHANGE_GOODS)
    {
        $smarty->assign('is_exchange_goods', 1);
    }
    else
    {
        //正常购物流程  清空其他购物流程情况
        $_SESSION['flow_order']['extension_code'] = '';
    }

    /* 检查购物车中是否有商品 */
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') ." WHERE  user_id = " .$_SESSION['user_id']. " AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

    $res =  $GLOBALS['db']->getRow($sql);
     // $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
     //    " WHERE session_id = '" . SESS_ID . "' " .
     //    "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

    /*if ($db->getOne($sql) == 0)
    {
        show_message($_LANG['no_goods_in_cart'], '', '', 'warning');
    }*/

    /*
     * 检查用户是否已经登录
     * 如果用户已经登录了则检查是否有默认的收货地址
     * 如果没有登录则跳转到登录和注册页面
     */
    if (empty($_SESSION['direct_shopping']) && $_SESSION['user_id'] == 0)
    {
        /* 用户没有登录且没有选定匿名购物，转向到登录页面 */
        ecs_header("Location: user.php");
        exit;
    }

    $consignee = get_consignee($_SESSION['user_id']);
    /* 检查收货人信息是否完整 */
//    if (!check_consignee_info($consignee, $flow_type))
//    {
//        /* 如果不完整则转向到收货人信息填写界面 */
//        ecs_header("Location: flow.php?step=consignee\n");
//       exit;
//    }

    $_SESSION['flow_consignee'] = $consignee;
    $smarty->assign('consignee', $consignee);

    $smarty->assign('order_sn', $sn);
    /* 对商品信息赋值 */

    $cart_goods = supplier_cart_goods($flow_type,$cart_id);
    // p($cart_goods);
        if($cart_goods){
            foreach ($cart_goods as $key => $value) {
                @$ass['0']['ass'] +=(float)$value['all'];
                $ass['0']['ass']=sprintf('%.2f',$ass['0']['ass']);
            }
            foreach ($cart_goods as $key => $value) {
                @$rec_id .= ','.$value['rec_id']; 

            }
        }
        
        $_SESSION['rec_id']=null;   
        @$rec_id = substr($rec_id,1);
        $_SESSION['rec_id']=$rec_id;

    @$smarty->assign('ass',$ass);
    // p($cart_goods);
    $smarty->assign('goods_list', $cart_goods);

        // 当前系统时间
    $smarty->display('supplier_flow.dwt');
}

