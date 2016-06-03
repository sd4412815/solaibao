<?php

/**
 * 购物流程
 */

@define('IN_ECS', true);

require(ROOT_PATH . 'includes/lib_order.php');
//$flow_type = 0;
/*$sql = "SELECT rec_id FROM " . $ecs->table('cart') ." WHERE  user_id = " .$_SESSION['user_id']. " AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

$cart_id =  $GLOBALS['db']->getRow($sql);*/
if ($_GET['act'] == 'getorderprice'){

	$array = explode('.',$_GET['goods_number']);
    $num=$array[1];
    $array1 = array($array[0] => $array[1]);
    $_GET['goods_number'] = $array1;
       $aa = flow_update_cart($_GET['goods_number']);
       $sql = "SELECT goods_id FROM " . $ecs->table('cart') ."WHERE rec_id = " . $_GET['rec_id'];
       $res = $GLOBALS['db']->getRow($sql);

       $sql = "SELECT goods_number FROM ". $ecs->table('goods') ."WHERE goods_id = " . $res['goods_id'];
       $res = $GLOBALS['db']->getRow($sql);
       return $res;}
//        if($num > $res['goods_number']){
// }

            // p($res['goods_number']);
            // echo "<script type='text/javascript'> alert('该商品库存不足!'); </script>";
       
       // p($aa);
    // ecs_header("Location: supplier.php?go=flow\n"); 
    // show_message($_LANG['update_cart_notice'], $_LANG['back_to_cart'], 'supplier_flow.php');
    // exit;




 //    $rec_id=$_GET['recid'];
	// $sql = "SELECT rec_id,cart_tax,goods_price FROM ".$GLOBALS['ecs']->table('cart')." WHERE rec_id = ". $rec_id;

 //    $arr = $GLOBALS['db']->getRow($sql);
 //    $sua = flow_update_cart($_GET['num']);
 //    p($sua);
 // 	foreach ($arr as $key => $value) {
 //         $arr[$key]['tax']= (float)$value['goods_price']*(float)$_GET['num']*(float)$value['cart_tax']/100;//总税金
 //         $arr[$key]['all'] = (float)$value['goods_price']*(float)$_GET['num']+$arr[$key]['tax'];
 //         $sub[] = flow_update_cart($arr);
 //         $sub[] = $arr[$key]['tax'];
 //         $sub[] = $arr[$key]['all'];
 //         p($arr);
 //      }
 //    echo json_encode($sua);
   
// }




/**
 * 更新购物车中的商品数量
 *
 * @access  public
 * @param   array   $arr
 * @return  void
 */
function flow_update_cart($arr)
{
    /* 处理 */
    foreach ($arr AS $key => $val)
    {
        $val = intval(make_semiangle($val));
        if ($val <= 0 || !is_numeric($key))
        {
            continue;
        }

        //查询：
        $sql = "SELECT `goods_id`, `goods_attr_id`, `product_id`, `extension_code` FROM" .$GLOBALS['ecs']->table('cart').
            " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
        $goods = $GLOBALS['db']->getRow($sql);

        $sql = "SELECT g.goods_name, g.goods_number ".
            "FROM " .$GLOBALS['ecs']->table('goods'). " AS g, ".
            $GLOBALS['ecs']->table('cart'). " AS c " .
            "WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
        $row = $GLOBALS['db']->getRow($sql);

        //查询：系统启用了库存，检查输入的商品数量是否有效
        if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy')
        {
            if ($row['goods_number'] < $val)
            {
                show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
                    $row['goods_number'], $row['goods_number']));
                // print_r(1);
                exit;
            }
            /* 是货品 */
            $goods['product_id'] = trim($goods['product_id']);
            if (!empty($goods['product_id']))
            {
                $sql = "SELECT product_number FROM " .$GLOBALS['ecs']->table('products'). " WHERE goods_id = '" . $goods['goods_id'] . "' AND product_id = '" . $goods['product_id'] . "'";

                $product_number = $GLOBALS['db']->getOne($sql);
                if ($product_number < $val)
                {
                    show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
                        $product_number['product_number'], $product_number['product_number']));
                    exit;
                }
            }
        }
        elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy')
        {
            if (judge_package_stock($goods['goods_id'], $val))
            {
                show_message($GLOBALS['_LANG']['package_stock_insufficiency']);
                exit;
            }
        }

        /* 查询：检查该项是否为基本件 以及是否存在配件 */
        /* 此处配件是指添加商品时附加的并且是设置了优惠价格的配件 此类配件都有parent_id goods_number为1 */
        $sql = "SELECT b.goods_number, b.rec_id
                FROM " .$GLOBALS['ecs']->table('cart') . " a, " .$GLOBALS['ecs']->table('cart') . " b
                WHERE a.rec_id = '$key'
                AND a.session_id = '" . SESS_ID . "'
                AND a.extension_code <> 'package_buy'
                AND b.parent_id = a.goods_id
                AND b.session_id = '" . SESS_ID . "'";

        $offers_accessories_res = $GLOBALS['db']->query($sql);

        //订货数量大于0
        if ($val > 0)
        {
            /* 判断是否为超出数量的优惠价格的配件 删除*/
            $row_num = 1;
            while ($offers_accessories_row = $GLOBALS['db']->fetchRow($offers_accessories_res))
            {
                if ($row_num > $val)
                {
                    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
                        " WHERE session_id = '" . SESS_ID . "' " .
                        "AND rec_id = '" . $offers_accessories_row['rec_id'] ."' LIMIT 1";
                    $GLOBALS['db']->query($sql);
                }

                $row_num ++;
            }

            /* 处理超值礼包 */
            if ($goods['extension_code'] == 'package_buy')
            {
                //更新购物车中的商品数量
                $sql = "UPDATE " .$GLOBALS['ecs']->table('cart').
                    " SET goods_number = '$val' WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
            }
            /* 处理普通商品或非优惠的配件 */
            else
            {
                $attr_id    = empty($goods['goods_attr_id']) ? array() : explode(',', $goods['goods_attr_id']);
                $goods_price = get_final_price($goods['goods_id'], $val, true, $attr_id);

                //更新购物车中的商品数量
                $sql = "UPDATE " .$GLOBALS['ecs']->table('cart').
                    " SET goods_number = '$val', goods_price = '$goods_price' WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
            }
        }
        //订货数量等于0
        else
        {
            /* 如果是基本件并且有优惠价格的配件则删除优惠价格的配件 */
            while ($offers_accessories_row = $GLOBALS['db']->fetchRow($offers_accessories_res))
            {
                $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
                    " WHERE session_id = '" . SESS_ID . "' " .
                    "AND rec_id = '" . $offers_accessories_row['rec_id'] ."' LIMIT 1";
                $GLOBALS['db']->query($sql);
            }

            $sql = "DELETE FROM " .$GLOBALS['ecs']->table('cart').
                " WHERE rec_id='$key' AND session_id='" .SESS_ID. "'";
        }

        $GLOBALS['db']->query($sql);
    }

    /* 删除所有赠品 */
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') . " WHERE session_id = '" .SESS_ID. "' AND is_gift <> 0";
    $GLOBALS['db']->query($sql);
}
