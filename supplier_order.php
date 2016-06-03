<?php

/**
 * 购物流程
 */

@define('IN_ECS', true);

require(ROOT_PATH . 'includes/lib_order.php');



if ($_REQUEST['act'] == 'getUserInfo' ) {

    $sql="SELECT ua.id_card,us.user_name FROM ". $ecs->table('users') ."AS us LEFT JOIN".$ecs->table("user_address")."AS ua ON ua.address_id = us.address_id WHERE mobile_phone = ".$_POST['tel'];
    $id_card = $GLOBALS['db']->getRow($sql);
		$str[]=$id_card['id_card'];
		$str[]=$id_card['user_name'];
		echo json_encode($str);
		// return $str;

}