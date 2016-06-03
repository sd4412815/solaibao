<?php

/**
 * 商品仓库管理程序
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$exc_SLB = new exchange($ecs->table("goods_storeroom"), $db, 'store_id', 'store_name');

/*------------------------------------------------------ */
//-- 管理界面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    assign_query_info();

    $smarty->assign('ur_here',          '仓库管理');
    $smarty->assign('full_page',        1);

    $goods_storeroom_list = get_goods_storeroom();
    // die;
    $smarty->assign('goods_storeroom_list',   $goods_storeroom_list['type']);
    $smarty->assign('filter',		$goods_storeroom_list['filter']);
    $smarty->assign('record_count', $goods_storeroom_list['record_count']);
    $smarty->assign('page_count',   $goods_storeroom_list['page_count']);

    $smarty->assign('action_link',      array('text' => $_LANG['new_storeroom'], 'href' => 'goods_storeroom.php?act=add'));

    $smarty->display('goods_storeroom.htm');
}

/*------------------------------------------------------ */
//-- 获得列表
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    $goods_storeroom_list = get_goods_storeroom();

    $smarty->assign('goods_storeroom_list',   $goods_storeroom_list['type']);
    $smarty->assign('filter',       $goods_storeroom_list['filter']);
    $smarty->assign('record_count', $goods_storeroom_list['record_count']);
    $smarty->assign('page_count',   $goods_storeroom_list['page_count']);

    make_json_result($smarty->fetch('goods_storeroom.htm'), '',
        array('filter' => $goods_storeroom_list['filter'], 'page_count' => $goods_storeroom_list['page_count']));
}

/*------------------------------------------------------ */
//-- 修改仓库名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_storeroom_name')
{

}



/*------------------------------------------------------ */
//-- 添加仓库
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add')
{
    admin_priv('goods_storeroom');

    $smarty->assign('ur_here',     $_LANG['new_storeroom']);
    $smarty->assign('action_link', array('href'=>'goods_storeroom.php?act=list', 'text' => $_LANG['goods_storeroom_list']));
    $smarty->assign('action',      'add');
    $smarty->assign('form_act',    'insert');
    $smarty->assign('province_list', get_province_list());

    assign_query_info();
    $smarty->display('goods_storeroom_info.htm');
}

elseif ($_REQUEST['act'] == 'insert')
{
    $goods_store['store_name']   = $_POST['store_name'];
    $goods_store['store_desc'] = sub_str($_POST['store_desc'], 255);
    $goods_store['sort_order']    = intval($_POST['sort_order']);
	$goods_store['store_province']    = $_POST['province'] ? "#" . implode("#",$_POST['province'])."#"  : "";

	$is_only = $exc_SLB->is_only('store_name', $_POST['store_name']);
    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['repeat_storeroom_name'], stripslashes($_POST['store_name'])), 1);
    }

    if ($db->autoExecute($ecs->table('goods_storeroom'), $goods_store) !== false)
    {
        $links = array(array('href' => 'goods_storeroom.php?act=list', 'text' => $_LANG['back_list']));
        sys_msg($_LANG['add_storeroom_success'], 0, $links);
    }
    else
    {
        sys_msg($_LANG['add_storeroom_failed'], 1);
    }
}

/*------------------------------------------------------ */
//-- 编辑仓库信息
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    $goods_store_qq2211707 = get_storeroom_info(intval($_GET['store_id']));

    if (empty($goods_store_qq2211707))
    {
        sys_msg($_LANG['cannot_found_storeroom'], 1);
    }

    admin_priv('goods_storeroom');

    $smarty->assign('ur_here',     $_LANG['edit_storeroom']);
    $smarty->assign('action_link', array('href'=>'goods_storeroom.php?act=list', 'text' => $_LANG['goods_storeroom_list']));
    $smarty->assign('action',      'add');
    $smarty->assign('form_act',    'update');
    $smarty->assign('goods_store',  $goods_store_qq2211707);
	$smarty->assign('province_list', get_province_list($_GET['store_id']));

    assign_query_info();
    $smarty->display('goods_storeroom_info.htm');
}

elseif ($_REQUEST['act'] == 'update')
{
    $goods_store_SLB['store_name']   = $_POST['store_name'];
    $goods_store_SLB['store_desc'] = sub_str($_POST['store_desc'], 255);
	$goods_store_SLB['sort_order']    = intval($_POST['sort_order']);
	$goods_store_SLB['store_province']    =  $_POST['province'] ? "#" . implode("#",$_POST['province'])."#"  : "";
    $store_id                   = intval($_POST['store_id']);

	$is_only = $exc_SLB->is_only('store_name', $_POST['store_name'], $_POST['store_id']);
    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['repeat_storeroom_name'], stripslashes($_POST['store_name'])), 1);
    }

    if ($db->autoExecute($ecs->table('goods_storeroom'), $goods_store_SLB, 'UPDATE', "store_id='$store_id'") !== false)
    {


        $links = array(array('href' => 'goods_storeroom.php?act=list', 'text' => $_LANG['back_list']));
        sys_msg($_LANG['edit_storeroom_success'], 0, $links);
    }
    else
    {
        sys_msg($_LANG['edit_storeroom_failed'], 1);
    }
}
/*------------------------------------------------------ */
//-- 删除仓库
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('goods_type');

    $id = intval($_GET['id']);

    $name = $exc_SLB->get_name($id);
    $sql = "SELECT COUNT(goods_storeroom) FROM ".$GLOBALS['ecs']->table('goods')." WHERE goods_storeroom =".$id;
    $recount = $GLOBALS['db']->getOne($sql);
    if($recount == 0){
        if ($exc_SLB->drop($id))
        {
            admin_log(addslashes($name), 'remove', 'goods_storeroom');

            $url = 'goods_storeroom.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

            ecs_header("Location: $url\n");
            exit;
        }
        else
        {
            make_json_error($_LANG['remove_failed']);
        }
    }else{
        make_json_error('此仓库内有'.$recount.'件商品,请删除商品后,再移除该仓库');
    }
   
}

/**
 * 获得所有商品类型
 *
 * @access  public
 * @return  array
 */
function get_goods_storeroom()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 分页大小 */
        $filter = array();

        /* 记录总数以及页数 */
        $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('goods_storeroom');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 查询记录 */
        $sql = "SELECT * ".
               "FROM ". $GLOBALS['ecs']->table('goods_storeroom').
               ' LIMIT ' . $filter['start'] . ',' . $filter['page_size'];
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $all = $GLOBALS['db']->getAll($sql);

    foreach ($all AS $key=>$val)
    {
        @$all[$key]['attr_group'] = strtr($val['attr_group'], array("\r" => '', "\n" => ", "));
    }

    return array('type' => $all, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 获得指定的仓库的详情
 *
 * @param   integer     $store_id 分类ID
 *
 * @return  array
 */
function get_storeroom_info($store_id)
{
    $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('goods_storeroom'). " WHERE store_id='$store_id'";

    return $GLOBALS['db']->getRow($sql);
}

/*
* 省份列表
*/
function get_province_list($store_id='')
{
	if ($store_id)
	{
		$sql = "select store_province from ". $GLOBALS['ecs']->table('goods_storeroom') ." where store_id='$store_id' ";
		$store_province = $GLOBALS['db']->getOne($sql);
	}
	$province_list=array();
	$sql="select * from ". $GLOBALS['ecs']->table("region") ." where region_type=1";
	$res=$GLOBALS['db']->query($sql);
	while($row=$GLOBALS['db']->fetchRow($res))
	{
		$province_list[$row['region_id']]['name'] = $row['region_name'];
		if ( @strstr($store_province, "#".$row['region_id']."#") )
		{
			$province_list[$row['region_id']]['sel'] = 1;
		}
		else
		{
			$province_list[$row['region_id']]['sel'] = 0;
		}
	}
	return $province_list;
}

/**
 * 更新属性的分组
 *
 * @param   integer     $cat_id     商品类型ID
 * @param   integer     $old_group
 * @param   integer     $new_group
 *
 * @return  void
 */
function update_attribute_group($cat_id, $old_group, $new_group)
{
    $sql = "UPDATE " . $GLOBALS['ecs']->table('attribute') .
            " SET attr_group='$new_group' WHERE cat_id='$cat_id' AND attr_group='$old_group'";
    $GLOBALS['db']->query($sql);
}

?>
