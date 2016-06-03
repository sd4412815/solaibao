<?php



@define('IN_ECS', true);
require(ROOT_PATH . 'includes/lib_order.php');
$smarty->assign('categories', get_categories_tree2()); // 分类树
$record_count= get_cagtegory_goods_count();
assign_template_supplier();//自定义导航栏
$attr_arg = array();
$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
$default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');
$sort = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC'))) ? trim($_REQUEST['order']) : $default_sort_order_method;
$page = isset($_REQUEST['page'])   && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
$brand = isset($_REQUEST['brand']) && intval($_REQUEST['brand']) > 0 ? intval($_REQUEST['brand']) : 0;
$price_max = isset($_REQUEST['price_max']) && intval($_REQUEST['price_max']) > 0 ? intval($_REQUEST['price_max']) : 0;
$price_min = isset($_REQUEST['price_min']) && intval($_REQUEST['price_min']) > 0 ? intval($_REQUEST['price_min']) : 0;
$filter_attr_str = isset($_REQUEST['filter_attr']) ? htmlspecialchars(trim($_REQUEST['filter_attr'])) : '0';
$filter_attr_str = trim(urldecode($filter_attr_str));
$filter_attr_str = preg_match('/^[\d\.]+$/',$filter_attr_str) ? $filter_attr_str : '';
$filter_attr = empty($filter_attr_str) ? '' : explode('.', $filter_attr_str);
// $size       = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 5;
$size       = 8;
$ceilsize = ceil($record_count/$size);
$max_page = ($record_count> 0) ? ceil($record_count / $size) : 1;
if ($page > $max_page)
    {
        $page = $max_page;
    }
$pager = get_pager('supplier.php', array('go' => "more&suppId=" . $_SESSION['suppId'] . "&id=".$_REQUEST['id']."&sort=".$sort."&order=".$order), $record_count, $page, 8);


$pager['search'] = array(
        'keywords'   => @stripslashes(urlencode($_REQUEST['keywords'])),
        'category'   => @$category,
        'brand'      => @$_REQUEST['brand'],
        'sort'       => $sort,
        'order'      => $order,
        'min_price'  => @$_REQUEST['min_price'],
        'max_price'  => @$_REQUEST['max_price'],
        'action'     => @$action,
        'intro'      => empty($intromode) ? '' : trim($intromode),
        'goods_type' => @$_REQUEST['goods_type'],
        'sc_ds'      => @$_REQUEST['sc_ds'],
        'outstock'   => @$_REQUEST['outstock']
    );
$pager['search'] = array_merge($pager['search'], $attr_arg);
$smarty->assign('pager',$pager);

        if($_REQUEST['id']==10444){
            
            $count = get_cagtegory_goods_count();
            $search_new = get_cagtegory_goods_list($pager,$page,$size);
            $smarty->assign('search_new',  $search_new);
            $smarty->assign('record_count',  $count);
        }
        if($_REQUEST['id']==10445){
            $count = get_cagtegory_goods_count();
            $search_hot = get_cagtegory_goods_list($pager,$page,$size);
            $smarty->assign('search_hot',  $search_hot);
            $smarty->assign('record_count',  $count);
        }
        if($_REQUEST['id']==10446){
            $count = get_cagtegory_goods_count();
            $search_best = get_cagtegory_goods_list($pager,$page,$size);
            $smarty->assign('search_best',  $search_best);
            $smarty->assign('record_count',  $count);
        }
        $smarty->assign('ceilsize',$ceilsize);
        $smarty->display('supplier_search.dwt');


/**
 * 获得分类下的商品总数
 *
 * @access  public
 * @param   string     $cat_id
 * @return  integer
 */
function get_cagtegory_goods_count()
{
    if($_REQUEST['id']==10444){
       $where = "is_new = 1 AND";
    }elseif($_REQUEST['id']==10445){
       $where = "is_hot = 1 AND";
    }elseif($_REQUEST['id']==10446){
       $where = "is_best = 1 AND";
    }
 
    /* 返回商品总数 */
    $sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('category') . ' AS sgc LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
                'ON sgc.cat_id = g.cat_id WHERE  is_on_sale = 1 AND goods_number > 0 AND is_delete = 0 AND '. $where .' g.supplier_id = '.$_SESSION['suppId'];
             // p($sql);  
               // p($GLOBALS['db']->getOne($sql));
    return $GLOBALS['db']->getOne($sql); 
   
}
/**
 * 获得分类下的商品信息
 *
 * @access  public
 * @param   string     $cat_id
 * @return  integer
 */
function get_cagtegory_goods_list($pager,$page,$size)
{
    if($_REQUEST['id']==10444){
       $where = "is_new = 1 AND";
    }elseif($_REQUEST['id']==10445){
       $where = "is_hot = 1 AND";
    }elseif($_REQUEST['id']==10446){
       $where = "is_best = 1 AND";
    }
 
    /* 返回商品信息 */
    $sql = " SELECT g.shop_price,g.goods_thumb,g.goods_name,g.market_price,g.goods_id,sgc.parent_id FROM ".  $GLOBALS['ecs']->table('goods') . "AS g LEFT JOIN ". $GLOBALS['ecs']->table('category') ." AS sgc ON g.cat_id = sgc.cat_id WHERE ".$where. " supplier_id = ".$_SESSION['suppId']." AND is_on_sale = 1 AND is_delete = 0 AND goods_number > 0 ORDER BY ".$pager['search']['sort']." ".$pager['search']['order']." LIMIT ".$size*($page-1)."," . $size;
    return $GLOBALS['db']->getAll($sql);
   
}
