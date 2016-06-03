<?php

/**
 * 购物流程
 */

define('IN_ECS', true);

require(ROOT_PATH . 'includes/lib_order.php');

if ($_GET['act'] = 'clear_supplier_cart'){

     /*$sql = "SELECT rec_id FROM " . $GLOBALS['ecs']->table('cart') ." WHERE  user_id = " .$_SESSION['user_id']. " AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

    $cart_id =  $GLOBALS['db']->getRow($sql);
*/    // v($_SESSION['user_id']);
    if($_SESSION['user_id'] > 0){

    $sql = "DELETE c.* FROM ". $GLOBALS['ecs']->table('cart') ."AS c LEFT JOIN". $GLOBALS['ecs']->table('goods') ." AS g ON c.goods_id = g.goods_id WHERE c.user_id = ". $_SESSION['user_id'] ." AND g.supplier_id = " . $_SESSION['suppId'];
           
    $GLOBALS['db']->getRow($sql);
    }else{
        header("Location:user.php");
    }
    // $res=supplier_delete_goods($cart_id);
}



