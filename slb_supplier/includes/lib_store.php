<?php
$storeTypeId = get_store_type();//获取仓库创建来源
if($storeTypeId < 0){
	$link[] = array('href' => 'store.php?act=add', 'text' => $_LANG['00_store_type']);
    sys_msg('请先做仓库类型选择', 0, $link);
}else{
	$isManageStore = is_manage_store($storeTypeId);//是否有权限操作
}
$_REQUEST['storetypeid'] = $storeTypeId;//方便在各位调用
$_REQUEST['ismanagestore'] = $isManageStore;//方便在各位调用
//$smarty->assign('storeTypeId',$storeTypeId);
//$smarty->assign('isManageStore',$isManageStore);

?>