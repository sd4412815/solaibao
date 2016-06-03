<?php

/**
 * 店铺分类文件
*/


/* 获得请求的分类 ID */
// if (isset($_REQUEST['id']))
// {
//    $cat_id = intval($_REQUEST['id']);
// }
// else
// {
    /* 如果分类ID为0，则返回首页 */
//    ecs_header("Location: ./\n");

//    exit;
// }


/* 初始化分页信息 */
// $cat_id = isset($_REQUEST['id'])   && intval($_REQUEST['id'])  > 0 ? intval($_REQUEST['id'])  : 0;
//  // var_dump($cat_id);
// $keywords = isset($_REQUEST['keywords']) && !empty($_REQUEST['keywords']) ? htmlspecialchars($_REQUEST['keywords']) : '';
// $keywords = ($keywords == '请输入你要查找的商品') ? '' : $keywords;
//  var_dump($keywords);
// $price = isset($_REQUEST['price']) ? intval($_REQUEST['price']) : 0;
// $page = isset($_REQUEST['page'])   && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
// $size = isset($_CFG['page_size'])  && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;
// $sort  = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? 'g.'.trim($_REQUEST['sort'])  : 'g.goods_id';
// $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : 'DESC';
// /*------------------------------------------------------ */
// //-- PROCESSOR
// /*------------------------------------------------------ */

// /* 页面的缓存ID */
// $cache_id = sprintf('%X', crc32($cat_id . '-' . $display . '-' . $sort  .'-' . $order  .'-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' .
//     $_CFG['lang'] .'-'. $brand. '-' . $price_max . '-' .$price_min . '-' . $filter_attr_str.'-'.$_GET['suppId']));
// // var_dump($cache_id);
// if (!$smarty->is_cached('search.dwt', $cache_id))
// {
//     assign_template();
//     assign_template_supplier();
//     $position = assign_ur_here();
    
   
//     $smarty->assign('page_title',      $position['title']);    // 页面标题
//     //$smarty->assign('ur_here',         $ur_here);  // 当前位置

//     $smarty->assign('categories',      get_categories_tree_supplier()); // 分类树
    
//     $s_value = get_search_price($price);
//     // var_dump($s_value);
    
//     $children = get_cattype_supplier($cat_id,$keywords);
//     if($children === false){
//     	ecs_header("Location: supplier.php?suppId=".$_GET['suppId']);
//     	exit;
//     }
//     $count = get_cagtegory_goods_count($children,$keywords,$s_value);
//     var_dump($count);
//     $max_page = ($count> 0) ? ceil($count / $size) : 1;
// 	if ($page > $max_page)
//     {
//         $page = $max_page;
//     }
//     $goodslist = category_get_goods($children, $size, $page,$keywords,$s_value,$sort, $order);
//     var_dump($goodslist);
//     if($display == 'grid')
//     {
//         if(count($goodslist) % 2 != 0)
//         {
//             $goodslist[] = array();
//         }
//     }
//     assign_pager('supplier',            $cat_id, $count, $size, $sort, $order, $page, $keywords."&price=".$price, '', '', '', $display, ''); // 分页
//     $smarty->assign('goods_list',       $goodslist);
//     assign_dynamic('category');
// }
// $smarty->display('search.dwt', $cache_id);

// /*------------------------------------------------------ */
// //-- PRIVATE FUNCTION
// /*------------------------------------------------------ */

// /**
//  * 获得指定商品属性所属的分类的ID
//  *
//  * @access  public
//  * @param   integer     $cat        (1=>'精品推荐',2=>'新品上市',3=>'热卖商品')
//  * @param	string		$keywords    关键字
//  * @return  string
//  */
// function get_cattype_supplier($cat = 0,$keywords='')
// {
// 	if(empty($keywords)){
// 		$where = "supplier_id=".$_GET['suppId'];
// 		if($cat > 0){
// 			$where .= " AND recommend_type=".$cat;
// 		}
// 		$sql = "select cat_id  from ". $GLOBALS['ecs']->table('supplier_cat_recommend') ." where ".$where;
// 		$res = $GLOBALS['db']->getAll($sql);
// 		$arr = array();
// 		if(count($res)>0){
// 			foreach($res as $k => $v){
// 				$arr[$v['cat_id']] = $v['cat_id'];
// 			}
// 		}
// 		if(empty($arr)){
// 			return false;
// 		}
// 	    return 'sgc.cat_id ' . db_create_in(array_keys($arr));
// 	}else{
// 		return "g.goods_name like '%".$keywords."%'";
// 	}
// }

// /**
//  * 获得分类下的商品
//  *
//  * @access  public
//  * @param   string  $children
//  * @param   array      $price_search 搜索价格区间中的商品
//  * @return  array
//  */
// function category_get_goods($children, $size, $page,$keywords='',$price_search='',$sort, $order)
// {
//     $display = $GLOBALS['display'];
    
// 	$price_str = '';
// 	if(!empty($price_search)){
// 		if($price_search['min'] > 0){
// 			$price_str .= " AND g.shop_price > ".$price_search['min']." ";
// 		}
// 		if($price_search['max'] > 0){
// 			$price_str .= " AND g.shop_price <= ".$price_search['max']." ";
// 		}
// 	}
    
//     $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND ".
//             "g.is_delete = 0 ".$price_str." AND ($children)";


//     /* 获得商品列表 */
//     if(empty($keywords)){
//     	$where .= " AND sgc.supplier_id=".$_GET['suppId'];
//     	$sql = 'SELECT DISTINCT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .
//                 "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " .
//                 'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ' .
//     		'FROM ' . $GLOBALS['ecs']->table('supplier_goods_cat') . ' AS sgc ' .
//             'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
//     			"ON sgc.goods_id = g.goods_id " .
//             'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
//                 "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
//             "WHERE $where ORDER BY  $sort $order";
//     }else{
//     	$where .= " AND g.supplier_id=".$_GET['suppId'];
//     	$sql = 'SELECT DISTINCT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .
//                 "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " .
//                 'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ' .
//     		'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
//             'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
//                 "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
//             "WHERE $where ORDER BY  $sort $order";
//     }
    

//     $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

//     $arr = array();
//     while ($row = $GLOBALS['db']->fetchRow($res))
//     {
//         if ($row['promote_price'] > 0)
//         {
//             $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
//         }
//         else
//         {
//             $promote_price = 0;
//         }

//         /* 处理商品水印图片 */
//         $watermark_img = '';

//         if ($promote_price != 0)
//         {
//             $watermark_img = "watermark_promote_small";
//         }
//         elseif ($row['is_new'] != 0)
//         {
//             $watermark_img = "watermark_new_small";
//         }
//         elseif ($row['is_best'] != 0)
//         {
//             $watermark_img = "watermark_best_small";
//         }
//         elseif ($row['is_hot'] != 0)
//         {
//             $watermark_img = 'watermark_hot_small';
//         }

//         if ($watermark_img != '')
//         {
//             $arr[$row['goods_id']]['watermark_img'] =  $watermark_img;
//         }

//         $arr[$row['goods_id']]['goods_id']         = $row['goods_id'];
//         if($display == 'grid')
//         {
//             $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
//         }
//         else
//         {
//             $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];
//         }
//         $arr[$row['goods_id']]['name']             = $row['goods_name'];
//         $arr[$row['goods_id']]['goods_brief']      = $row['goods_brief'];
//         $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'],$row['goods_name_style']);
//         $arr[$row['goods_id']]['market_price']     = price_format($row['market_price']);
//         $arr[$row['goods_id']]['shop_price']       = price_format($row['shop_price']);
//         $arr[$row['goods_id']]['type']             = $row['goods_type'];
//         $arr[$row['goods_id']]['promote_price']    = ($promote_price > 0) ? price_format($promote_price) : '';
//         $arr[$row['goods_id']]['goods_thumb']      = get_image_path($row['goods_id'], $row['goods_thumb'], true);
//         $arr[$row['goods_id']]['goods_img']        = get_image_path($row['goods_id'], $row['goods_img']);
//         $arr[$row['goods_id']]['url']              = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
//     }

//     return $arr;
// }

// /**
//  * 获得分类下的商品总数
//  *
//  * @access  public
//  * @param   string     $children  
//  * @param   string		$keywords   商品名称查找
//  * @param   array      $price_search 搜索价格区间中的商品
//  * @return  integer
//  */
// function get_cagtegory_goods_count($children,$keywords='',$price_search='')
// {
//    $price_str = '';
// 	if(!empty($price_search)){
// 		if($price_search['min'] > 0){
// 			$price_str .= " AND g.shop_price > ".$price_search['min']." ";
// 		}
// 		if($price_search['max'] > 0){
// 			$price_str .= " AND g.shop_price <= ".$price_search['max']." ";
// 		}
// 	}

//     /* 返回商品总数 */
//     if(empty($keywords)){
//     	 $where  = "sgc.supplier_id=".$_GET['suppId']." AND $children";
//     	$sql = 'SELECT count(DISTINCT g.goods_id) FROM ' . $GLOBALS['ecs']->table('supplier_goods_cat') . ' AS sgc LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
//     			'ON sgc.goods_id = g.goods_id AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 '.$price_str.' WHERE '.$where;
//     }else{
//     	$where  = "g.supplier_id=".$_GET['suppId']." AND $children";
//     	$sql = 'SELECT count(DISTINCT g.goods_id) FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
//     			'WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 '. $price_str .' AND '.$where;
//     }
//     return $GLOBALS['db']->getOne($sql);
// }

// /**
//  * 获取搜索中用户选择的价格区间
//  * @param int  $price  代表价格区间的值
//  */
// function get_search_price($price=0){
// 	global $index_price;
// 	$num = count($index_price);
// 	$info = explode('-',$index_price[$price]);
// 	$ret = array('min'=>0,'max'=>0);
// 	if($price < --$num){
// 		//搜索中价格第一个条件
// 		if(count($info) == 1){
// 			$ret['min'] = intval($info[0]);
// 			$ret['max'] = 0;
// 		}else{
// 			$ret['min'] = intval($info[0]);
// 			$ret['max'] = intval($info[1]);
// 		}
// 	}elseif($price == --$num){
// 		//搜索中价格最后一个条件
// 		if(count($info) == 1){
// 			$ret['min'] = 0;
// 			$ret['max'] = intval($info[1]);
// 		}else{
// 			$ret['min'] = intval($info[0]);
// 			$ret['max'] = intval($info[1]);
// 		}
// 	}else{
// 		//搜索中价格中间条件
// 		$ret['min'] = intval($info[0]);
// 		$ret['max'] = intval($info[1]);
// 	}
// 	return $ret;
// }

    assign_template_supplier();//自定义导航栏
    $_REQUEST['keywords']   = !empty($_REQUEST['keywords'])   ? htmlspecialchars(trim($_REQUEST['keywords']))     : '';
    $_REQUEST['brand']      = !empty($_REQUEST['brand'])      ? intval($_REQUEST['brand'])      : 0;
    $_REQUEST['category']   = !empty($_REQUEST['category'])   ? intval($_REQUEST['category'])   : 0;
    $_REQUEST['min_price']  = !empty($_REQUEST['min_price'])  ? intval($_REQUEST['min_price'])  : 0;
    $_REQUEST['max_price']  = !empty($_REQUEST['max_price'])  ? intval($_REQUEST['max_price'])  : 0;
    $_REQUEST['goods_type'] = !empty($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0;
    $_REQUEST['sc_ds']      = !empty($_REQUEST['sc_ds']) ? intval($_REQUEST['sc_ds']) : 0;
    $_REQUEST['outstock']   = !empty($_REQUEST['outstock']) ? 1 : 0;
    @$children = get_children_supplier($cat_id);
    $sql = "SELECT goods_name,shop_price,goods_thumb FROM ". $ecs->table('goods') ." WHERE goods_name like '%".$_REQUEST['keywords']."%' ORDER BY shop_price DESC";
    $keywords = $db->getAll($sql);
    $smarty->assign('keywords',             $keywords);
    $action = '';
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'form')
    {
        /* 要显示高级搜索栏 */
        $adv_value['keywords']  = htmlspecialchars(stripcslashes($_REQUEST['keywords']));
        $adv_value['brand']     = $_REQUEST['brand'];
        $adv_value['min_price'] = $_REQUEST['min_price'];
        $adv_value['max_price'] = $_REQUEST['max_price'];
        $adv_value['category']  = $_REQUEST['category'];

        $attributes = get_seachable_attributes($_REQUEST['goods_type']);

        /* 将提交数据重新赋值 */
        foreach ($attributes['attr'] AS $key => $val)
        {
            if (!empty($_REQUEST['attr'][$val['id']]))
            {
                if ($val['type'] == 2)
                {
                    $attributes['attr'][$key]['value']['from'] = !empty($_REQUEST['attr'][$val['id']]['from']) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['from']))) : '';
                    $attributes['attr'][$key]['value']['to']   = !empty($_REQUEST['attr'][$val['id']]['to'])   ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]['to'])))   : '';
                }
                else
                {
                    $attributes['attr'][$key]['value'] = !empty($_REQUEST['attr'][$val['id']]) ? htmlspecialchars(stripcslashes(trim($_REQUEST['attr'][$val['id']]))) : '';
                }
            }
        }
        // p($attributes);
        if ($_REQUEST['sc_ds'])
        {
            $smarty->assign('scck',            'checked');
        }
        $smarty->assign('adv_val',             $adv_value);
        $smarty->assign('goods_type_list',     $attributes['cate']);
        $smarty->assign('goods_attributes',    $attributes['attr']);
        $smarty->assign('goods_type_selected', $_REQUEST['goods_type']);
        $smarty->assign('cat_list',            cat_list(0, $adv_value['category'], true, 2, false));
        $smarty->assign('brand_list',          get_brand_list());
        $smarty->assign('action',              'form');
        $smarty->assign('use_storage',          $_CFG['use_storage']);

        $action = 'form';
    }
    /* 初始化搜索条件 */
    $keywords  = '';
    $tag_where = '';
    if (!empty($_REQUEST['keywords']))
    {
        $arr = array();
        if (stristr($_REQUEST['keywords'], ' AND ') !== false)
        {
            /* 检查关键字中是否有AND，如果存在就是并 */
            $arr        = explode('AND', $_REQUEST['keywords']);
            $operator   = " AND ";
        }
        elseif (stristr($_REQUEST['keywords'], ' OR ') !== false)
        {
            /* 检查关键字中是否有OR，如果存在就是或 */
            $arr        = explode('OR', $_REQUEST['keywords']);
            $operator   = " OR ";
        }
        elseif (stristr($_REQUEST['keywords'], ' + ') !== false)
        {
            /* 检查关键字中是否有加号，如果存在就是或 */
            $arr        = explode('+', $_REQUEST['keywords']);
            $operator   = " OR ";
        }
        else
        {
            /* 检查关键字中是否有空格，如果存在就是并 */
            $arr        = explode(' ', $_REQUEST['keywords']);
            $operator   = " AND ";
        }

        $keywords = 'AND (';
        $goods_ids = array();
        foreach ($arr AS $key => $val)
        {
            if ($key > 0 && $key < count($arr) && count($arr) > 1)
            {
                $keywords .= $operator;
            }
            $val        = mysql_like_quote(trim($val));
            $sc_dsad    = $_REQUEST['sc_ds'] ? " OR goods_desc LIKE '%$val%'" : '';
            $keywords  .= "(goods_name LIKE '%$val%' OR goods_sn LIKE '%$val%' OR keywords LIKE '%$val%' $sc_dsad)";

            $sql = 'SELECT DISTINCT goods_id FROM ' . $ecs->table('tag') . " WHERE tag_words LIKE '%$val%' ";
            $res = $db->query($sql);
            while ($row = $db->FetchRow($res))
            {
                $goods_ids[] = $row['goods_id'];
            }

            $db->autoReplace($ecs->table('keywords'), array('date' => local_date('Y-m-d'),
                'searchengine' => 'ecshop', 'keyword' => addslashes(str_replace('%', '', $val)), 'count' => 1), array('count' => 1));
        }
        $keywords .= ')';

        $goods_ids = array_unique($goods_ids);
        $tag_where = implode(',', $goods_ids);
        if (!empty($tag_where))
        {
            $tag_where = 'OR g.goods_id ' . db_create_in($tag_where);
        }
    }

    $category   = !empty($_REQUEST['category']) ? intval($_REQUEST['category'])        : 0;
    $categories = ($category > 0)               ? ' AND ' . get_children($category)    : '';
    $brand      = $_REQUEST['brand']            ? " AND brand_id = '$_REQUEST[brand]'" : '';
    $outstock   = !empty($_REQUEST['outstock']) ? " AND g.goods_number > 0 "           : '';

    $min_price  = $_REQUEST['min_price'] != 0                               ? " AND g.shop_price >= '$_REQUEST[min_price]'" : '';
    $max_price  = $_REQUEST['max_price'] != 0 || $_REQUEST['min_price'] < 0 ? " AND g.shop_price <= '$_REQUEST[max_price]'" : '';

    /* 排序、显示方式以及类型 */
    $default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
    $default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
    $default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');

    $sort = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
    $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC'))) ? trim($_REQUEST['order']) : $default_sort_order_method;
    $display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_SESSION['display_search']) ? $_SESSION['display_search'] : $default_display_type);

    $_SESSION['display_search'] = $display;

    $page       = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
    $size       = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;

    $intromode = '';    //方式，用于决定搜索结果页标题图片

    if (!empty($_REQUEST['intro']))
    {
        switch ($_REQUEST['intro'])
        {
            case 'best':
                $intro   = ' AND g.is_best = 1';
                $intromode = 'best';
                $ur_here = $_LANG['best_goods'];
                break;
            case 'new':
                $intro   = ' AND g.is_new = 1';
                $intromode ='new';
                $ur_here = $_LANG['new_goods'];
                break;
            case 'hot':
                $intro   = ' AND g.is_hot = 1';
                $intromode = 'hot';
                $ur_here = $_LANG['hot_goods'];
                break;
            case 'promotion':
                $time    = gmtime();
                $intro   = " AND g.promote_price > 0 AND g.promote_start_date <= '$time' AND g.promote_end_date >= '$time'";
                $intromode = 'promotion';
                $ur_here = $_LANG['promotion_goods'];
                break;
            default:
                $intro   = '';
        }
    }
    else
    {
        $intro = '';
    }

    if (empty($ur_here))
    {
        $ur_here = $_LANG['search_goods'];
    }

    /*------------------------------------------------------ */
    //-- 属性检索
    /*------------------------------------------------------ */
    $attr_in  = '';
    $attr_num = 0;
    $attr_url = '';
    $attr_arg = array();

    if (!empty($_REQUEST['attr']))
    {
        $sql = "SELECT goods_id, COUNT(*) AS num FROM " . $ecs->table("goods_attr") . " WHERE 0 ";
        foreach ($_REQUEST['attr'] AS $key => $val)
        {
            if (is_not_null($val) && is_numeric($key))
            {
                $attr_num++;
                $sql .= " OR (1 ";

                if (is_array($val))
                {
                    $sql .= " AND attr_id = '$key'";

                    if (!empty($val['from']))
                    {
                        $sql .= is_numeric($val['from']) ? " AND attr_value >= " . floatval($val['from'])  : " AND attr_value >= '$val[from]'";
                        $attr_arg["attr[$key][from]"] = $val['from'];
                        $attr_url .= "&amp;attr[$key][from]=$val[from]";
                    }

                    if (!empty($val['to']))
                    {
                        $sql .= is_numeric($val['to']) ? " AND attr_value <= " . floatval($val['to']) : " AND attr_value <= '$val[to]'";
                        $attr_arg["attr[$key][to]"] = $val['to'];
                        $attr_url .= "&amp;attr[$key][to]=$val[to]";
                    }
                }
                else
                {
                    /* 处理选购中心过来的链接 */
                    $sql .= isset($_REQUEST['pickout']) ? " AND attr_id = '$key' AND attr_value = '" . $val . "' " : " AND attr_id = '$key' AND attr_value LIKE '%" . mysql_like_quote($val) . "%' ";
                    $attr_url .= "&amp;attr[$key]=$val";
                    $attr_arg["attr[$key]"] = $val;
                }

                $sql .= ')';
            }
        }

        /* 如果检索条件都是无效的，就不用检索 */
        if ($attr_num > 0)
        {
            $sql .= " GROUP BY goods_id HAVING num = '$attr_num'";

            $row = $db->getCol($sql);
            if (count($row))
            {
                $attr_in = " AND " . db_create_in($row, 'g.goods_id');
            }
            else
            {
                $attr_in = " AND 0 ";
            }
        }
    }
    elseif (isset($_REQUEST['pickout']))
    {
        /* 从选购中心进入的链接 */
        $sql = "SELECT DISTINCT(goods_id) FROM " . $ecs->table('goods_attr');
        $col = $db->getCol($sql);
        //如果商店没有设置商品属性,那么此检索条件是无效的
        if (!empty($col))
        {
            $attr_in = " AND " . db_create_in($col, 'g.goods_id');
        }
    }

    /* 获得符合条件的商品总数 */
    $sql   = "SELECT COUNT(*) FROM " .$ecs->table('goods'). " AS g ".
        "WHERE g.is_delete = 0 AND g.is_on_sale = 1 AND g.goods_number > 0 AND g.is_alone_sale = 1 $attr_in AND supplier_id=".$_REQUEST['suppId'].
        " AND (( 1 " . $categories . $keywords . $brand . $min_price . $max_price . $intro . $outstock ." ) ".$tag_where." )";
       
    $count = $db->getOne($sql);
    if($count=="0"){
        $null="没有搜到相关商品";
      
        $search['null']=$null;

        $smarty->assign('search', $search);  
    }
    
    $max_page = ($count> 0) ? ceil($count / $size) : 1;
    if ($page > $max_page)
    {
        $page = $max_page;
    }

    /* 查询商品 */
    $sql = "SELECT g.goods_id, g.goods_name, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ".
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                "g.promote_price, g.promote_start_date, g.promote_end_date, g.goods_thumb, g.goods_img, g.goods_brief, g.goods_type ".
            "FROM " .$ecs->table('goods'). " AS g ".
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            "WHERE g.is_delete = 0 AND g.is_on_sale = 1 AND g.goods_number > 0 AND g.is_alone_sale = 1 $attr_in  AND supplier_id=".$_REQUEST['suppId'].
                " AND (( 1 " . $categories . $keywords . $brand . $min_price . $max_price . $intro . $outstock . " ) ".$tag_where." ) " .
            "ORDER BY $sort $order";
    $res = $db->SelectLimit($sql, $size, ($page - 1) * $size);

    $arr = array();
    while ($row = $db->FetchRow($res))
    {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
        }
        else
        {
            $promote_price = 0;
        }

        /* 处理商品水印图片 */
        /* 处理商品水印图片 */
        $watermark_img = '';

        if ($promote_price != 0)
        {
            $watermark_img = "watermark_promote_small";
        }
        elseif ($row['is_new'] != 0)
        {
            $watermark_img = "watermark_new_small";
        }
        elseif ($row['is_best'] != 0)
        {
            $watermark_img = "watermark_best_small";
        }
        elseif ($row['is_hot'] != 0)
        {
            $watermark_img = 'watermark_hot_small';
        }

        if ($watermark_img != '')
        {
            $arr[$row['goods_id']]['watermark_img'] =  $watermark_img;
        }

        $arr[$row['goods_id']]['goods_id']      = $row['goods_id'];
        if($display == 'grid')
        {
            $arr[$row['goods_id']]['goods_name']    = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        }
        else
        {
            $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
        }
        $arr[$row['goods_id']]['type']          = $row['goods_type'];
        $arr[$row['goods_id']]['market_price']  = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']    = price_format($row['shop_price']);
        $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
        $arr[$row['goods_id']]['goods_brief']   = $row['goods_brief'];
        $arr[$row['goods_id']]['goods_thumb']   = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']     = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['url']           = "supplier.php?go=goods&suppId=".$_REQUEST['suppId']."&id=".$row['goods_id'];
    }

    if($display == 'grid')
    {
        if(count($arr) % 2 != 0)
        {
            $arr[] = array();
        }
    }

    $smarty->assign('goods_list', $arr);

    $smarty->assign('category',   $category);
    $smarty->assign('keywords',   htmlspecialchars(stripslashes($_REQUEST['keywords'])));
    $smarty->assign('search_keywords',   stripslashes(htmlspecialchars_decode($_REQUEST['keywords'])));
    $smarty->assign('brand',      $_REQUEST['brand']);
    $smarty->assign('min_price',  $min_price);
    $smarty->assign('max_price',  $max_price);
    $smarty->assign('outstock',  $_REQUEST['outstock']);

    /* 分页 */
    $url_format = "search.php?category=$category&amp;keywords=" . urlencode(stripslashes($_REQUEST['keywords'])) . "&amp;brand=" . $_REQUEST['brand']."&amp;action=".$action."&amp;goods_type=" . $_REQUEST['goods_type'] . "&amp;sc_ds=" . $_REQUEST['sc_ds'];
    if (!empty($intromode))
    {
        $url_format .= "&amp;intro=" . $intromode;
    }
    if (isset($_REQUEST['pickout']))
    {
        $url_format .= '&amp;pickout=1';
    }
    $url_format .= "&amp;min_price=" . $_REQUEST['min_price'] ."&amp;max_price=" . $_REQUEST['max_price'] . "&amp;sort=$sort";

    $url_format .= "$attr_url&amp;order=$order&amp;page=";
    $search = 'search&suppId='.$_SESSION['suppId'];
    $pager['search'] = array(
        'go'         => $search,
        'keywords'   => stripslashes(urlencode($_REQUEST['keywords'])),
        'sort'       => $sort,
        'order'      => $order
    );
    $pager['search'] = array_merge($pager['search'], $attr_arg);
    $pager = get_pager('supplier.php', $pager['search'], $count, $page, $size);
    // p($pager);
    $pager['display'] = $display;

    $smarty->assign('url_format', $url_format);
    $smarty->assign('pager', $pager);

    assign_template();
    assign_dynamic('search');
    $position = assign_ur_here(0, $ur_here . ($_REQUEST['keywords'] ? '_' . $_REQUEST['keywords'] : ''));
    $smarty->assign('page_title', $position['title']);    // 页面标题
    $smarty->assign('ur_here',    $position['ur_here']);  // 当前位置
    $smarty->assign('intromode',      $intromode);
    $smarty->assign('categories', get_categories_tree2()); // 分类树
    $smarty->assign('helps',       get_shop_help());      // 网店帮助
    $smarty->assign('top_goods',  get_top10());           // 销售排行
    $smarty->assign('promotion_info', get_promotion_info());
 //    $sql = "SELECT parent_id FROM ". $GLOBALS['ecs']->table('category') ." WHERE cat_id = ".$_SESSION['user_id'];
    
   
 //    $parent_id = $GLOBALS['db']->getAll($sql);
 //     p($parent_id);
 //     if ($parent_id == 683)
 //        {
 //            $where = 683;
 //        }
 // elseif ($parent_id == 690)
 //        {
 //            $where = 690;
 //        }
 // elseif ($parent_id == 700)
 //        {
 //            $where = 700;
 //        }
 // elseif ($parent_id == 706)
 //        {
 //            $where = 706;
 //        }
 // elseif ($parent_id == 713)
 //        {
 //           $where = 713;
 //        }


 //    $sql="SELECT g.goods_id,g.goods_thumb,g.goods_name,g.shop_price,g.market_price,g.cat_id,c.cat_name FROM ". $GLOBALS['ecs']->table('goods') ." AS g LEFT JOIN ". $GLOBALS['ecs']->table('category') ." AS c ON g.cat_id = c.cat_id WHERE c.parent_id = ". $where ." AND g.supplier_id = ".$_SESSION['suppId']." ORDER BY g.shop_price ";

 //    $res = $GLOBALS['db']->getAll($sql);
 //    foreach ($res as $key => $value) {
 //       $res[$key]=$value;
 //    }
 //    // p($res);
 
 //    $smarty->assign('goods_list0',$res);
    $smarty->display('search.dwt');
   
    /*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */
/**
 *
 *
 * @access public
 * @param
 *
 * @return void
 */
function is_not_null($value)
{
    if (is_array($value))
    {
        return (!empty($value['from'])) || (!empty($value['to']));
    }
    else
    {
        return !empty($value);
    }
}

/**
 * 获得可以检索的属性
 *
 * @access  public
 * @params  integer $cat_id
 * @return  void
 */
function get_seachable_attributes($cat_id = 0)
{
    $attributes = array(
        'cate' => array(),
        'attr' => array()
    );

    /* 获得可用的商品类型 */
    $sql = "SELECT t.cat_id, cat_name FROM " .$GLOBALS['ecs']->table('goods_type'). " AS t, ".
           $GLOBALS['ecs']->table('attribute') ." AS a".
           " WHERE t.cat_id = a.cat_id AND t.enabled = 1 AND a.attr_index > 0 ";
    $cat = $GLOBALS['db']->getAll($sql);

    /* 获取可以检索的属性 */
    if (!empty($cat))
    {
        foreach ($cat AS $val)
        {
            $attributes['cate'][$val['cat_id']] = $val['cat_name'];
        }
        $where = $cat_id > 0 ? ' AND a.cat_id = ' . $cat_id : " AND a.cat_id = " . $cat[0]['cat_id'];

        $sql = 'SELECT attr_id, attr_name, attr_input_type, attr_type, attr_values, attr_index, sort_order ' .
               ' FROM ' . $GLOBALS['ecs']->table('attribute') . ' AS a ' .
               ' WHERE a.attr_index > 0 ' .$where.
               ' ORDER BY cat_id, sort_order ASC';
        $res = $GLOBALS['db']->query($sql);

        while ($row = $GLOBALS['db']->FetchRow($res))
        {
            if ($row['attr_index'] == 1 && $row['attr_input_type'] == 1)
            {
                $row['attr_values'] = str_replace("\r", '', $row['attr_values']);
                $options = explode("\n", $row['attr_values']);

                $attr_value = array();
                foreach ($options AS $opt)
                {
                    $attr_value[$opt] = $opt;
                }
                $attributes['attr'][] = array(
                    'id'      => $row['attr_id'],
                    'attr'    => $row['attr_name'],
                    'options' => $attr_value,
                    'type'    => 3
                );
            }
            else
            {
                $attributes['attr'][] = array(
                    'id'   => $row['attr_id'],
                    'attr' => $row['attr_name'],
                    'type' => $row['attr_index']
                );
            }
        }
    }

    return $attributes;
}
?>
