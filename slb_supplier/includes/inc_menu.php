<?php

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$modules['02_cat_and_goods']['01_goods_add']			  = 'goods.php?act=add';          // 添加商品
$modules['02_cat_and_goods']['02_goods_list_pass1']       = 'goods.php?act=list&supplier_status=1';         // 商品列表
//$modules['02_cat_and_goods']['01_goods_list_pass2']       = 'goods.php?act=list&supplier_status=0';         // 商品列表
//$modules['02_cat_and_goods']['01_goods_list_pass3']       = 'goods.php?act=list&supplier_status=-1';         // 商品列表
$modules['02_cat_and_goods']['05_comment_manage']		  = 'comment_manage.php?act=list'; //评论
//$modules['02_cat_and_goods']['05_shaidan_manage']   = 'shaidan.php?act=list';
$modules['02_cat_and_goods']['11_goods_trash']			  = 'goods.php?act=trash';        // 商品回收站
//$modules['02_cat_and_goods']['04_category_list']		  = 'category.php?act=list';    //商品分类
$modules['02_cat_and_goods']['04_goods_distribution']			  = 'goods.php?act=distribution';        // 可分销商品


//$modules['02_rebate_manage']['03_rebate_nopay']       = 'supplier_rebate.php?act=list&is_pay_ok=0';
//$modules['02_rebate_manage']['03_rebate_pay']       = 'supplier_rebate.php?act=list&is_pay_ok=1';

/* 代码增加_start  By   yuanzb */
//$modules['02_store_and_goods']['01_store_manage']       = 'store_manage.php?act=list';         // 仓库设置
//$modules['02_store_and_goods']['02_store_shipping_demo']       = 'store_shipping_demo.php?act=list';         // 运费模板
//$modules['02_store_and_goods']['03_store_inout_goods']       = 'store_inout_goods.php?act=list';         //出入库序时簿
//$modules['02_store_and_goods']['03_store_inout_out']       = 'store_inout_out.php?act=list';         //出库管理
//$modules['02_store_and_goods']['03_store_inout_in']       = 'store_inout_in.php?act=list';			 //入库管理
//$modules['02_store_and_goods']['04_store_inout_stock']       = 'store_inout_stock.php?act=list';			 //即时库存查询
//$modules['02_store_and_goods']['15_store_out_type']       = 'store_inout_type.php?act=list&in_out=1';         // 出库类型设置
//$modules['02_store_and_goods']['15_store_in_type']       = 'store_inout_type.php?act=list&in_out=2';             // 入库类型设置
/* 代码增加_end  By yuanzb */

//$modules['03_promotion']['04_bonustype_list']       = 'bonus.php?act=list';
//$modules['03_promotion']['08_group_buy']            = 'group_buy.php?act=list';
//$modules['03_promotion']['12_favourable']           = 'favourable.php?act=list';

$modules['04_order']['01_order_list']               = 'order.php?act=list';
//$modules['04_order']['03_order_query']              = 'order.php?act=order_query';
//$modules['04_order']['04_merge_order']              = 'order.php?act=merge';
//$modules['04_order']['05_edit_order_print']         = 'order.php?act=templates';
//$modules['04_order']['06_undispose_booking']        = 'goods_booking.php?act=list_all';
//$modules['04_order']['09_delivery_order']           = 'order.php?act=delivery_list';
//$modules['04_order']['10_back_order']               = 'order.php?act=back_list';
//$modules['04_order']['10_back_order']               = 'back.php?act=back_list';  //代码修改 By yuanzb
//$modules['04_order']['12_order_excel']              = 'excel.php?act=order_excel';


$modules['05_dianpu_manage']['01_base']               	= 	'shop_config.php?act=list_edit';
$modules['05_dianpu_manage']['02_menu']               	= 	'navigator.php?act=list';
$modules['05_dianpu_manage']['03_guanggao']             = 	'flashplay.php?act=list';
//$modules['05_dianpu_manage']['04_article']              = 	'article.php?act=list';
$modules['05_dianpu_manage']['05_header']               = 	'shop_header.php?act=list_edit';
//$modules['05_dianpu_manage']['06_templates']            = 	'template.php?act=list';
//$modules['05_dianpu_manage']['07_street']				= 	'street.php?act=info';


//$modules['10_priv_admin']['admin_logs']             = 'admin_logs.php?act=list';//管理员日志
$modules['10_priv_admin']['admin_list']             = 'privilege.php?act=list';
//$modules['10_priv_admin']['admin_role']             = 'role.php?act=list';//角色管理


?>
