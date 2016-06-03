<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');



if ($_REQUEST['act'] == 'add')
{
    admin_priv('store_manage');
    
    // $storeId = get_store_type();
    $storeId=0;
    if($storeId > -1){
    	$link[] = array('href' => 'store_manage.php?act=list', 'text' => $_LANG['01_store_manage']);
    	sys_msg($_LANG['haveselect'], 0, $link);
    }

    $selectArray = array(0=>'使用运营商仓库',$_SESSION['supplier_id']=>'创建属于自己的仓库');

    /* 赋值模板显示 */
    $smarty->assign('ur_here',      $_LANG['01_store_manage']);
    $smarty->assign('selectInfo',    $selectArray);

    assign_query_info();
    $smarty->display('store_info.htm');
}
elseif ($_REQUEST['act'] == 'insert'){
	admin_priv('store_manage');
	$typeid = isset($_POST['type_id']) ? intval($_POST['type_id']) : -1;
	if($typeid < 0){
		$link[] = array('href' => 'store_manage.php?act=list', 'text' => $_LANG['01_store_manage']);
    	sys_msg($_LANG['feifacaozuo'], 0, $link);
	}
	$store = array(
		'supplier_id' 	=> $_SESSION['supplier_id'],
		'type'			=> $typeid
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('store_type'), $store, 'INSERT');
	$error_no = $GLOBALS['db']->errno();
	if($error_no == 1062){
		$message = $_LANG['haveselect'];
	}else{
		$message = $_LANG['succesful'];
	}
	$link[] = array('href' => 'store_manage.php?act=list', 'text' => $_LANG['01_store_manage']);
    sys_msg($message, 0, $link);
}
?>