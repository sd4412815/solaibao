<?php

if (!defined('IN_ECS'))
{
	die('Hacking attempt');
}

//商品管理权限
$purview['02_goods_list_pass1']		= 'goods_list';//审核通过商品列表
$purview['02_goods_list_pass2']		= 'goods_list';//未审核商品
$purview['02_goods_list_pass3']		= 'goods_list';//审核未通过商品列表
$purview['01_goods_add']			= 'goods_manage';//添加新商品
$purview['04_category_list']		= 'category_list';//商品分类
$purview['05_comment_manage']		= 'comment_manage';//用户评论
$purview['05_shaidan_manage']		= 'comment_manage';//
$purview['11_goods_trash']			= array('goods_list', 'goods_trash');//商品回收站
$purview['04_goods_distribution']		= 'goods_list';//

/* 代码增加_start   By yuanzb*/
$purview['01_store_manage']  		= 'store_manage';
$purview['02_store_shipping_demo']  = 'store_shipping_demo';
$purview['03_store_inout_goods']    = 'store_inout_goods';
$purview['03_store_inout_out']      = 'inout_out_manage';
$purview['03_store_inout_in']       = 'inout_in_manage';
$purview['04_store_inout_stock']    = 'store_inout_stock';
$purview['15_store_out_type']       = 'store_inout_type';
$purview['15_store_in_type']        = 'store_inout_type';

/* 代码增加_end   By yuanzb */

//佣金管理
$purview['03_rebate_nopay']			= 'rebate_manage';//未处理佣金列表
$purview['03_rebate_pay']			= 'rebate_manage';//已完结佣金列表

//促销管理权限
$purview['04_bonustype_list']    	= 'bonus_manage';
$purview['08_group_buy']         	= 'group_by';
$purview['12_favourable']        	= 'favourable';


//订单管理
$purview['01_order_list']			= 'order_list';//订单列表
$purview['03_order_query']			= 'order_query';//订单查询
$purview['04_merge_order']			= 'merge_order';//合并订单
$purview['05_edit_order_print']		= 'edit_order_print';//订单打印
$purview['06_undispose_booking'] = 'booking';
$purview['09_delivery_order']		= 'delivery_order';//发货单列表
$purview['10_back_order']			= 'back_order';//退货单列表
$purview['12_order_excel']          = 'order_list';


//店铺系统设置
$purview['01_base']					= 'shop_base';//店铺基本设置
$purview['02_menu']					= 'shop_menu';//店铺导航栏
$purview['03_guanggao']				= 'shop_guanggao';//店铺主广告
$purview['04_article']				= 'shop_article';//店铺文章
$purview['05_header']				= 'shop_header';//店铺头部自定义
$purview['06_templates']			= 'shop_templates';//店铺模版选择
$purview['07_street']				= 'shop_base';//店铺街信息

//权限管理
//$purview['admin_logs']				= 'admin_logs';//管理员日志
$purview['admin_list']				= 'admin_list';//管理员列表
//$purview['admin_role']             = 'role_manage';//角色管理
?>