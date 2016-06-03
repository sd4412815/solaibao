<?php

/**
 *  专题前台123
 * ---------------------------------------------
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$_SESSION['send_code']='solaibao.com';
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

if (empty($_SESSION['user_id'])){
	$back_act = "index.php";
	if (!empty($_SERVER['QUERY_STRING']))
    {
      $back_act = 'apply.php?' . strip_tags($_SERVER['QUERY_STRING']);
    }
    show_message('请先登陆！', array('返回上一页','点击去登陆'), array($back_act, 'user.php'), 'info');
}

$sql = "SELECT supplier_id FROM ". $ecs->table('supplier') ."WHERE user_id = ".$_SESSION['user_id'];
$is_supplier = $db->getOne($sql);
if($is_supplier){
	show_message('您已经是店长了，请不要重复申请！', '返回', 'index.php', 'wrong');
}
$userid = $_SESSION['user_id'];

$shownum = (isset($_REQUEST['shownum'])) ? intval($_REQUEST['shownum']) : 0;

$upload_size_limit = $_CFG['upload_size_limit'] == '-1' ? ini_get('upload_max_filesize') : $_CFG['upload_size_limit'];

if(!empty($_POST)){
	unset($apply,$save);

	$save['user_id']			=$userid;
	$save['company_name']       = isset($_POST['company_name']) ? trim(addslashes(htmlspecialchars($_POST['company_name']))) : '';
	$save['supplier_name']	 	=$save['company_name'];
	$save['country'] 			= isset($_POST['country']) ? intval($_POST['country']) : 1;
	$save['province'] 			= isset($_POST['province']) ? intval($_POST['province']) : 1;
	$save['city']			 	= isset($_POST['city']) ? intval($_POST['city']) : 1;
	$save['district'] 			= isset($_POST['district']) ? intval($_POST['district']) : 1;
	$save['address'] 			= isset($_POST['address']) ? trim(addslashes(htmlspecialchars($_POST['address']))) : '';
	$save['tel'] 				= isset($_POST['tel']) ? trim(addslashes(htmlspecialchars($_POST['tel']))) : '';
	$save['company_type'] 		= isset($_POST['company_type']) ? trim($_POST['company_type']) : '';
	$save['contacts_name'] 		= isset($_POST['contacts_name']) ? trim(addslashes(htmlspecialchars($_POST['contacts_name']))) : '';
	$save['contacts_phone'] 	= $save['tel'];
	$save['rank_id'] = '6';
	$save['applynum'] = 3;//公司信息认证一
//	    $save['business_licence_number'] = isset($_POST['business_licence_number']) ? trim(addslashes(htmlspecialchars($_POST['business_licence_number']))) : '';
//	    $save['business_sphere'] = isset($_POST['business_sphere']) ? trim(addslashes(htmlspecialchars($_POST['business_sphere']))) : '';
//	    $save['organization_code'] = isset($_POST['organization_code']) ? trim(addslashes(htmlspecialchars($_POST['organization_code']))) : '';

	
	$save_agency['id'] = $save['user_id'];
	$save_agency['agency_name'] = $save['supplier_name'];
	$save_agency['agency_desc'] = $save['address'];
	//必填项验证
	/*$save1 = array_filter($save);
	if(count($save1)!=count($save)){
		show_message('请认真填写必填申请资料！', '返回', 'apply.php', 'wrong');
	}*/


	//指派给某个办事处
		// if($db->autoExecute($ecs->table('agency'), $save_agency, 'INSERT') !== false){
			if ($db->autoExecute($ecs->table('supplier'), $save, 'INSERT') !== false){

			 show_message('申请成功，请等待审核！', '返回', 'index.php', 'wrong');
			exit;
		 	}else{
			show_message('操作失败！', '返回', 'apply.php', 'wrong');
		 	}
		// }else{
		// 	show_message('操作失败咯！', '返回', 'apply.php', 'wrong');
		// }
}
if (@!$smarty->is_cached($templates, $cache_id))
{ 

    /* 模板赋值 */
    assign_template();
    $position = assign_ur_here();
	$smarty->assign('province_list',   get_regions(1,1) );

    $smarty->assign('page_title',       $position['title']);       // 页面标题
//    @$smarty->assign('ur_here',          $position['ur_here'] . '> ' . $topic['title']);     // 当前位置
    
}
$smarty->assign('piclimit',$upload_size_limit);
$smarty->assign('userid',intval($_SESSION['user_id']));
$smarty->display('apply.dwt');

?>
