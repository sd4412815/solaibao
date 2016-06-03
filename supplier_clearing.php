<?php

/**
 * 购物流程
 */


@define('IN_ECS', true);
require(ROOT_PATH . 'includes/lib_order.php');
 $a=0;
 if($_GET['act'] == "supplier_clearing_goods" ){
    $sql = "SELECT supplier_id,user_id FROM " . $ecs->table('supplier') ." WHERE user_id = ".$_SESSION['user_id'];
    $result = $GLOBALS['db']->getRow($sql);
    if($_GET['suppId'] != $result['supplier_id'])
    {
        echo "<script>alert('您无权操作！');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    }else{

 	if ($_SESSION['user_id'] > 0)
    {
        /* 检查购物车中是否有商品 */
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
        " WHERE user_id = '" . $_SESSION['user_id'] . "' " .
        "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";
    $cart_is_goods=$db->getOne($sql);
    if ($cart_is_goods == 0)
    {
        show_message(@$_LANG['no_goods_in_cart'], '', '', 'warning');
    }

    /* 检查商品库存 */
    /* 如果使用库存，且下订单时减库存，则减少库存 */
    if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
    {
        $cart_goods_stock = get_cart_supplier_goods();
        $_cart_goods_stock = array();
        if(!empty($cart_goods_stock)){
            foreach ($cart_goods_stock['goods_list'] as $value)
            {
                @$_cart_goods_stock[$value['rec_id']] = $value['goods_number'];
            }
        }
       
        unset($cart_goods_stock, $_cart_goods_stock);
    }



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
    // if (!check_consignee_info($consignee, $flow_type))
    // {
    //      如果不完整则转向到收货人信息填写界面 
    //     ecs_header("Location: flow.php?step=consignee\n");
    //     exit;
    // }

    $_POST['how_oos'] = isset($_POST['how_oos']) ? intval($_POST['how_oos']) : 0;
    $_POST['card_message'] = isset($_POST['card_message']) ? compile_str($_POST['card_message']) : '';
    $_POST['inv_type'] = !empty($_POST['inv_type']) ? compile_str($_POST['inv_type']) : '';
    $_POST['inv_payee'] = isset($_POST['inv_payee']) ? compile_str($_POST['inv_payee']) : '';
    $_POST['inv_content'] = isset($_POST['inv_content']) ? compile_str($_POST['inv_content']) : '';
    $_POST['postscript'] = isset($_POST['postscript']) ? compile_str($_POST['postscript']) : '';



    $order = array(
        'shipping_id'     => intval(@$_POST['shipping']),
        'pay_id'          => intval(@$_POST['payment']),
        'pack_id'         => isset($_POST['pack']) ? intval($_POST['pack']) : 0,
        'card_id'         => isset($_POST['card']) ? intval($_POST['card']) : 0,
        'card_message'    => trim($_POST['card_message']),
        'surplus'         => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
        'integral'        => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
       
        'bonus_id'        => isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
        'need_inv'        => empty($_POST['need_inv']) ? 0 : 1,
        'inv_type'        => $_POST['inv_type'],
        'inv_payee'       => trim($_POST['inv_payee']),
        'inv_content'     => $_POST['inv_content'],
        'postscript'      => trim($_POST['postscript']),
        'how_oos'         => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
        'need_insure'     => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
        'user_id'         => $_SESSION['user_id'],
        'add_time'        => gmtime(),
        'order_status'    => OS_UNCONFIRMED,
        'shipping_status' => SS_UNSHIPPED,
        'pay_status'      => PS_UNPAYED,
        'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district'])),
        'real_name'       => @$_POST['real_name'],
        );



    @$cl = supplier_cart_goods($flow_type,$cart_id);
    $tel = $_GET['zhi'];
    $id_card= $_GET['shen'];
    $name=$_GET['xm'];
    $user_info=user_info($tel);
    $rec_id_arr=array();
    if (@$_SESSION['rec_id']){
        $rec_id_arr=explode(',',$_SESSION['rec_id']);
        foreach ($rec_id_arr as $key => $value) {
            $sql = "SELECT * FROM ". $ecs->table('cart') ." WHERE rec_id = ".$value;
            $row = $GLOBALS['db']->getRow($sql);
            $total['goods_price'] = round($row['goods_price']*$row['goods_number']);//单件
            @$total['goods_prices'] += round($row['goods_price']*$row['goods_number']);//总价
            $total['tax_fee'] = $row['goods_price']*$row['goods_number']*$row['cart_tax']/100;//单件税额
            @$total['tax_fees'] += $row['goods_price']*$row['goods_number']*$row['cart_tax']/100;//共计税额
            // $total['goods_sprintf'] =sprintf('%.2f',$total['goods_tax']);
            $total['goods_amount'] = round($total['goods_prices']+$total['tax_fees']);//单件总计多少钱
            // $total['allprice'] += round($total['goods_prices']+$total['tax_fees']);//所有商品的钱
            $total['goods_id'] = $row['goods_id'];
            $total['goods_name'] = $row['goods_name'];
            $total['goods_sn'] = $row['goods_sn'];
            $total['cart_tax'] = $row['cart_tax'];
            $total['goods_number'] = $row['goods_number'];
            $total['market_price'] = $row['market_price'];
            $total['goods_price'] = $row['goods_price'];

        }

    }

    $order['order_sn']              =get_order_sn();
    $order['user_id']               =@$goods_info['user_id'];
    $order['order_status']          =5;
    $order['shipping_status']       =2;
    $order['pay_status']            =2;
    $order['consignee']             =$name;
    $order['country']               =1;
    $order['province']              =1;
    $order['city']                  =1;
    $order['district']              =1;
    $order['address']               ='上门自提';
    $order['shipping_id']           =1;
    $order['shipping_name']         ='上门自提';
    $order['pay_id']                =0;
    $order['pay_name']              ='现金结算';
    $order['mobile']                =$tel;
    $order['pay_id']                =0;
    $order['how_oos']               ='等待所有商品备齐后再发';


    $order['goods_amount']          =@$total['goods_amount'];  //总价
    $order['shipping_fee']          =0;                       //配送费用
    $order['insure_fee']            =0;                       //保价费用
    $order['tax_fee']               =@$res['cart_tax'];        //税率
    $order['pay_fee']               =0;                       //支付费用
    $order['pack_fee']              =0;                       //包装费用
    $order['card_fee']              =0;                       //贺卡费用
    $order['money_paid']            =@$total['goods_amount'];  //已付款金额
    $order['order_amount']          =@$total['goods_amount'];  //应付款金额
    $order['tax_fee']               =@$total['tax_fees'];     //税额
    $order['discount']              =0;  //折扣


    $order['surplus']               =0;
    $order['integral']              =0;
    $order['integral_money']        =0;
    $order['bonus']                 =0;
    $order['real_name']             =$name;
    $order['id_card']               =$id_card;
    $order['from_ad']               =0;
    $order['referer']               ='店长';
    $order['add_time']              =time();
    $order['confirm_time']          =time();
    $order['pay_time']              =time();
    $order['pack_id']               =0;
    $order['card_id']               =0;
    $order['bonus_id']              =0;
    $order['extension_id']          =0;
    $order['agency_id']             =0;
    $order['is_separate']           =1;
    $order['parent_id']             =0;
    $order['mobile_pay']            =0;
    $order['supplier_id']           =$_SESSION['suppId'];
    $order['froms']                 ='pc';
    $order['mobile_order']          =0;

    $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order,'INSERT');

    $order_id=$db->insert_id();

            foreach (@$rec_id_arr as $key => $value) {
                $sql = "SELECT * FROM ". $ecs->table('cart') ." WHERE rec_id = ".$value;
                $row = $GLOBALS['db']->getRow($sql);
                $goods_info['order_id']                     = $order_id;
                $goods_info['goods_id']                     = $row['goods_id'];
                $goods_info['goods_name']                   = $row['goods_name'];
                $goods_info['goods_sn']                     = $row['goods_sn'];
                $goods_info['goods_tax']                    = $row['cart_tax'];
                $goods_info['tax_fee']                      = $row['goods_price']*$row['goods_number']*($row['cart_tax']/100);
                $goods_info['product_id']                   = 0;
                $goods_info['goods_number']                 = $row['goods_number'];
                $goods_info['market_price']                 = $row['market_price'];
                $goods_info['goods_price']                  = $row['goods_price'];
                $goods_info['send_number']                  = 1;
                $goods_info['is_real']                      = 1;
                $goods_info['parent_id']                    = 0;
                $goods_info['is_gift']                      = 0;
                $goods_info['goods_attr_id']                = 0;
                
                $res = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_goods'), $goods_info,'INSERT');
            }
            if (@$res){
                $sql = "SELECT rec_id FROM " . $ecs->table('cart') ." WHERE  user_id = " .$_SESSION['user_id']. " AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";
            $cart_id =  $GLOBALS['db']->getRow($sql);
            $sql0 = "SELECT goods_id FROM ". $ecs->table('cart') ."WHERE rec_id = ". $cart_id['rec_id'];
            $goodsid = $GLOBALS['db']->getRow($sql0);
            $sql1 = "SELECT goods_number FROM ". $ecs->table('goods') . " WHERE goods_id =" .$goodsid['goods_id'];
            $number =  $GLOBALS['db']->getRow($sql1);
            $num = ($number['goods_number']-$_GET['num']);

                if(!empty($cart_id)){
                    //修改库存
                $sql = "UPDATE ". $ecs->table('goods') ."SET goods_number = ".$num. " WHERE goods_id = ".$goodsid['goods_id'];
                $GLOBALS['db']->query($sql);
                    //删除购物车内商品
                $sql = "DELETE FROM ". $GLOBALS['ecs']->table('cart') ." WHERE user_id = " .$_SESSION['user_id'];
 
                $arra[]= $GLOBALS['db']->query($sql);

                echo "<script>alert('感谢您到本店购物！欢迎下次光临！');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
                }

            }

        }else{
            echo "<script>alert('您的信息还未填写完整，点击确定，去填写！');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
     }   
  }
