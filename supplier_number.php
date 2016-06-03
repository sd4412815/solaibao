<?php

/**
 * 购物流程
 */

@define('IN_ECS', true);

require(ROOT_PATH . 'includes/lib_order.php');
$flow_type = 0;
if($_REQUEST['act'] == 'getPrice' ){
	 $sql = "SELECT goods_tax,shop_price FROM ".$GLOBALS['ecs']->table('goods')." WHERE goods_id = ". $_GET['goodsid'] . " AND supplier_id= ".$_SESSION['suppId'];

      $arr = $GLOBALS['db']->getAll($sql);
	foreach ($arr as $key => $value) {
         $arr[$key]['tax']= (float)$value['shop_price']*(float)$_GET['num']*(float)$value['goods_tax']/100;//总税金

         $arr[$key]['all'] = (float)$value['shop_price']*(float)$_GET['num'];
         $str = $arr[$key]['all'];
         // p($str); 
      }
      echo json_encode($str);
   
}

// if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'price')
// {
//     include('includes/cls_json.php');

//     $json   = new JSON;
//     $res    = array('err_msg' => '', 'result' => '', 'qty' => 1);

//     $attr_id    = isset($_REQUEST['attr']) ? explode(',', $_REQUEST['attr']) : array();
//     $number     = (isset($_REQUEST['number'])) ? intval($_REQUEST['number']) : 1;

//     if ($goods_id == 0)
//     {
//         $res['err_msg'] = $_LANG['err_change_attr'];
//         $res['err_no']  = 1;
//     }
//     else
//     {
//         if ($number == 0)
//         {
//             $res['qty'] = $number = 1;
//         }
//         else
//         {
//             $res['qty'] = $number;
//         }

//         $shop_price  = get_final_price($goods_id, $number, true, $attr_id);
//         $res['result'] = price_format($shop_price * $number);
//     }

//     die($json->encode($res));
// }