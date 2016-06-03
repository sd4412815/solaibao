<?php

/**
 *  仓库库房切换程序
*/

define('IN_ECS', true);
define('INIT_NO_USERS', true);
define('INIT_NO_SMARTY', true);

require(dirname(__FILE__) . '/includes/init.php');
require(dirname(__FILE__) . '/includes/lib_store.php');
require(ROOT_PATH . 'includes/cls_json.php');

header('Content-type: text/html; charset=' . EC_CHARSET);

$parentid = !empty($_REQUEST['parentid']) ? intval($_REQUEST['parentid']) : 0;
if($parentid)
{
	$sql="select store_name, store_id from ". $ecs->table('store_main') ." where supplier_id=".$_REQUEST['storetypeid']." and parent_id='$parentid' order by store_id asc";
	$store_list=$db->getAll($sql);
	$arr['store_list'] = $store_list;
}
$arr['target']  = !empty($_REQUEST['target']) ? stripslashes(trim($_REQUEST['target'])) : '';
$arr['target']  = htmlspecialchars($arr['target']);

$json = new JSON;
echo $json->encode($arr);

?>