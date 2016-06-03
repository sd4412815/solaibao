<?php

/**
 *  管理中心店铺管理
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/supplier.php');
$smarty->assign('lang', $_LANG);


/*------------------------------------------------------ */
//-- 店铺列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
     /* 检查权限 */
     admin_priv('supplier_manage');

    /* 查询 */
    $result = suppliers_list();

    /* 模板赋值 */
	  $ur_here_lang =@ $_REQUEST['status'] =='1' ? $_LANG['supplier_list'] : $_LANG['supplier_reg_list'];
    
    $smarty->assign('ur_here', $ur_here_lang); // 当前导航

    $smarty->assign('full_page',        1); // 翻页参数

    $smarty->assign('supplier_list',    $result['result']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);
    $smarty->assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');

    /* 显示模板 */
    assign_query_info();
    $smarty->display('supplier_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('supplier_manage');

    $result = suppliers_list();
    // p($result);
    $smarty->assign('supplier_list',    $result['result']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('supplier_list.htm'), '',array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}


/*------------------------------------------------------ */
//-- 查看、编辑供货商
/*------------------------------------------------------ */
elseif ($_REQUEST['act']== 'edit')
{
    /* 检查权限 */
    admin_priv('supplier_manage');
    $suppliers = array();

     /* 取得供货商信息 */
     $id = $_REQUEST['id'];
	   @$status = intval($_REQUEST['status']);
     $sql = "SELECT * FROM " . $ecs->table('supplier') . " WHERE supplier_id = '$id'";
     $supplier = $db->getRow($sql);
     if (count($supplier) <= 0)
     {
          sys_msg('该供应商不存在！');
     }
     
	/* 省市县 */
	$supplier_country = $supplier['country'] ?  $supplier['country'] : $_CFG['shop_country'];
	$smarty->assign('country_list',       get_regions());	
	$smarty->assign('province_list', get_regions(1, $supplier_country));
	$smarty->assign('city_list', get_regions(2, $supplier['province']));
	$smarty->assign('district_list', get_regions(3, $supplier['city']));
	$smarty->assign('supplier_country', $supplier_country);
	 /* 店铺等级 */
	$sql="select rank_name from ". $ecs->table('supplier_rank') ." where rank_id = ".$supplier['rank_id'];
	$rank_name=$db->getOne($sql);
	$supplier['rank_name'] = $rank_name;
	 $sql="select rank_id,rank_name from ". $ecs->table('supplier_rank') ." order by sort_order DESC";
	$supplier_rank=$db->getAll($sql);

	$smarty->assign('supplier_rank', $supplier_rank);
	
	/* 店铺类型 */
	 /*$sql="select str_name from ". $ecs->table('street_category') ." where str_id = ".$supplier['type_id'];
	$type_name=$db->getOne($sql);
	$supplier['type_name'] = $type_name;*/

     $smarty->assign('ur_here', $_LANG['edit_supplier']);
	   $lang_supplier_list = $status=='1' ? $_LANG['supplier_list'] :  $_LANG['supplier_reg_list'];
     $smarty->assign('action_link', array('href' => 'supplier.php?act=list', 'text' =>$lang_supplier_list ));

     $smarty->assign('form_action', 'update');
     $smarty->assign('supplier', $supplier);

     assign_query_info();

     $smarty->display('supplier_info.htm');
}

/*------------------------------------------------------ */
//-- 提交添加、编辑供货商
/*------------------------------------------------------ */
elseif ($_REQUEST['act']=='update')
{
    /* 检查权限 */
    admin_priv('supplier_manage');   
    
    //审核通过，必须要填写的项目
    // if(intval($_POST['status']) == 1){
    // 	if(intval($_POST['supplier_rebate_paytime'])<=0){
    // 		 sys_msg('结算类型必须选择！');
    // 	}
    // }

   /* 提交值 */
   $supplier_id =  intval($_POST['id']);
   $status_url = intval($_POST['status_url']);
   $supplier = array(
							//'rank_id'   => intval($_POST['rank_id']),
                            //'country'   => intval($_POST['country']),
							//'province'   => intval($_POST['province']),
							//'city'   => intval($_POST['city']),
							//'district'   => intval($_POST['district']),
							//'address'   => trim($_POST['address']),
                            //'tel'   => trim($_POST['tel']),
							//'email'   => trim($_POST['email']),
							//'contact_back'   => trim($_POST['contact_back']),
							//'contact_shop'   => trim($_POST['contact_shop']),
							//'contact_yunying'   => trim($_POST['contact_yunying']),
							//'contact_shouhou'   => trim($_POST['contact_shouhou']),
							//'contact_caiwu'   => trim($_POST['contact_caiwu']),
							//'contact_jishu'   => trim($_POST['contact_jishu']),
							'system_fee'   => trim(@$_POST['system_fee']),
							'supplier_bond'   => trim(@$_POST['supplier_bond']),
							'supplier_rebate'   => trim(@$_POST['supplier_rebate']),
							'supplier_rebate_paytime'   => intval(@$_POST['supplier_rebate_paytime']),
							'supplier_remark'   => trim(@$_POST['supplier_remark']),
							'status'   => intval(@$_POST['status'])
                           );
    if($supplier['status']==1){
        create_shop_settiongs($supplier_id);
    }
  /* 代码增加_start  By  yuanzb */
  /* 取得供货商信息 */
  //$sql = "SELECT * FROM " . $ecs->table('supplier') . " WHERE supplier_id = '" . $supplier_id ."' ";
  $sql = "select s.supplier_id,s.add_time,u.* from " . $ecs->table('supplier') . " as s left join ". $ecs->table('users') .
  		 " as u on s.user_id=u.user_id where s.supplier_id=".$supplier_id;
  $supplier_old = $db->getRow($sql);
  if (empty($supplier_old['supplier_id']))
  {
        sys_msg('该供货商信息不存在！');
  }
  if(empty($supplier_old['add_time']) && $supplier['status'] == 1){
  	//审核通过时就是店铺创建成功的时间
  	$supplier['add_time'] = time();

  }
  
  //更新相关店铺的管理员状态
  $sql = "select * from ". $ecs->table('supplier_admin_user') ." where supplier_id=".$supplier_old['supplier_id'];
  $info = $db->getAll($sql);
  if(count($info)>0){
  	$sql = "UPDATE ". $ecs->table('supplier_admin_user') ." SET user_name = '".$supplier_old['user_name']."',password = '".$supplier_old['password']."',email='".$supplier_old['email']."',ec_salt='".$supplier_old['ec_salt']."', checked = ".intval($_POST['status'])." WHERE supplier_id=".$supplier_old['supplier_id'];
  	$db->query($sql);
   
  }else{
	  	$insql = "INSERT INTO `slb_supplier_admin_user` (`uid`, `user_name`, `email`, `password`, `ec_salt`, `add_time`, `last_login`, `last_ip`, `action_list`, `nav_list`, `lang_type`, `agency_id`, `supplier_id`, `todolist`, `role_id`, `checked`) ".
					"VALUES(".$supplier_old['user_id'].", '".$supplier_old['user_name']."', '".$supplier_old['email']."', '".$supplier_old['password']."', '".$supplier_old['ec_salt']."', ".$supplier_old['last_login'].", ".$supplier_old['last_login'].", '".$supplier_old['last_ip']."', 'all', '', '', 0, ".$supplier_old['supplier_id'].", NULL, NULL, ".intval($_POST['status']).")";
	  	$db->query($insql);
  }
 /* 代码增加_end  By  yuanzb */

/* 保存供货商信息 */
$db->autoExecute($ecs->table('supplier'), $supplier, 'UPDATE', "supplier_id = '" . $supplier_id . "'");

if ($_POST['status']!='1')
{
	$sql="update ". $ecs->table('goods') ." set is_on_sale=0 where supplier_id='$supplier_id' ";
	$db->query($sql);
}

 /* 清除缓存 */
clear_cache_files();

/* 提示信息 */
$links[] = array('href' => ($status_url >0 ? 'supplier.php?act=list&status=1' : 'supplier.php?act=list'), 'text' => ($status_url >0 ? $_LANG['back_supplier_list'] : $_LANG['back_supplier_reg']));
sys_msg($_LANG['edit_supplier_ok'], 0, $links);    

}

/**
 *  获取供应商列表信息
 *
 * @access  public
 * @param
 *
 * @return void
 */
function suppliers_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

        /* 过滤信息 */
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'supplier_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['status'] = empty($_REQUEST['status']) ? '0' : intval($_REQUEST['status']);

        $where = 'WHERE applynum = 3 ';
        $where .= $filter['status'] ? " AND s.status = '". $filter['status']. "' " : " AND s.status in('0','-1') ";
        if(!empty($_REQUEST['brand_name'])){
            @$brand_name=' AND supplier_name LIKE \'%'.$_REQUEST['brand_name'].'%\'';
            $where.=$brand_name;
        }
        /* 分页大小 */
        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
        {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        }
        elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
        {
            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
        }
        else
        {
            $filter['page_size'] = 15;
        }

        /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('supplier') ." as s ". $where;
        $filter['record_count']   = $GLOBALS['db']->getOne($sql);
        $filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */
        $sql = "SELECT supplier_id,u.user_name, rank_id,add_time, supplier_name, tel, system_fee, supplier_bond, supplier_rebate, supplier_remark,  ".
			      "s.status ".
                "FROM " . $GLOBALS['ecs']->table("supplier") . " as s left join " . $GLOBALS['ecs']->table("users") . " as u on s.user_id = u.user_id 
                $where
                ORDER BY add_time DESC ," . $filter['sort_by'] . " " . $filter['sort_order']. "
                LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ", " . $filter['page_size'] . " ";

        set_filter($filter, $sql);

    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    
	$rankname_list =array();
	$sql2 = "select * from ". $GLOBALS['ecs']->table("supplier_rank") ;
	$res2 = $GLOBALS['db']->query($sql2);
	while ($row2=$GLOBALS['db']->fetchRow($res2))
	{
		$rankname_list[$row2['rank_id']] = $row2['rank_name'];
	}

	$list=array();
	$res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
	{
		$row['rank_name'] = $rankname_list[$row['rank_id']];
		$row['status_name'] = $row['status']=='1' ? '通过' : ($row['status']=='0' ? "未审核" : "未通过");
		$open = $GLOBALS['db']->getRow("select value from ".$GLOBALS['ecs']->table("supplier_shop_config")." where supplier_id=".$row['supplier_id']." and code='shop_closed'");
		if($open && $open['value'] == 0){
			$row['open'] = 1;
		}else{
			$row['open'] = 0;
		}
		$list[]=$row;
	}

    $arr = array('result' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
}
?>