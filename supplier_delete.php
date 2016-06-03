<?php

/**
 * 购物流程
 */

@define('IN_ECS', true);

require(ROOT_PATH . 'includes/lib_order.php');


 if($_GET['act'] == "supplier_delete_goods" ){
  

    if(!empty($_GET['rec_id'])){
   
    $sql = "DELETE FROM ".  $GLOBALS['ecs']->table('cart') ." WHERE rec_id = ".$_GET['rec_id'];
      
    $GLOBALS['db']->query($sql);
   
    echo "<script>alert('删除成功');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
	 
    }
}





