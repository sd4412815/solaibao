<?php

/**
 * �˷�
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if(!empty($_REQUEST['act']) && $_REQUEST['act'] == 'get_shipping_info'){
    $shipping_id=$_GET['id'];
    $sql="SELECT shipping_desc FROM".$ecs->table('shipping') . "WHERE shipping_id = ".$shipping_id." AND enabled = 1";
    echo $GLOBALS['db']->getOne($sql);
}

/* ���ͷ��� */
elseif(!empty($_REQUEST['act']) && $_REQUEST['act'] = 'shipping_fee'){
    $shipping_cod_fee = NULL;
    $order['shipping_id']=$_GET['id'];

        $region['country']  = $_GET['country'];
        $region['province'] = $_GET['province'];
        $region['city']     = $_GET['city'];
        $region['district'] = $_GET['district'];
        $shipping_info = shipping_area_info($order['shipping_id'], $region);

        if (!empty($shipping_info))
        {
            // �鿴���ﳵ���Ƿ�ȫΪ���˷���Ʒ����������˷Ѹ�Ϊ��
            $sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart') . " WHERE  `session_id` = '" . SESS_ID. "' AND `extension_code` != 'package_buy' AND `is_shipping` = 0";
            $shipping_count = $GLOBALS['db']->getOne($sql);

            @$total['shipping_fee'] = ($shipping_count == 0 AND $weight_price['free_shipping'] == 1) ?0 :  shipping_fee($shipping_info['shipping_code'],$shipping_info['configure'], $weight_price['weight'], $total['goods_price'], $weight_price['number']);
            if (!empty($order['need_insure']) && $shipping_info['insure'] > 0)
            {
                $total['shipping_insure'] = shipping_insure_fee($shipping_info['shipping_code'],
                    $total['goods_price'], $shipping_info['insure']);
            }
            else
            {
                $total['shipping_insure'] = 0;
            }

            if ($shipping_info['support_cod'])
            {
                $shipping_cod_fee = $shipping_info['pay_fee'];
            }
        }

    print_r($total['shipping_fee']);


}







/**
 * ȡ��ĳ���ͷ�ʽ��Ӧ��ĳ�ջ���ַ��������Ϣ
 * @param   int     $shipping_id        ���ͷ�ʽid
 * @param   array   $region_id_list     �ջ��˵���id����
 * @return  array   ����������Ϣ��config ��Ӧ�ŷ����л��� configure��
 */
function shipping_area_info($shipping_id, $region_id_list)
{
    $sql = 'SELECT s.shipping_code, s.shipping_name, ' .
        's.shipping_desc, s.insure, s.support_cod, a.configure ' .
        'FROM ' . $GLOBALS['ecs']->table('shipping') . ' AS s, ' .
        $GLOBALS['ecs']->table('shipping_area') . ' AS a, ' .
        $GLOBALS['ecs']->table('area_region') . ' AS r ' .
        "WHERE s.shipping_id = '$shipping_id' " .
        'AND r.region_id ' . db_create_in($region_id_list) .
        ' AND r.shipping_area_id = a.shipping_area_id AND a.shipping_id = s.shipping_id AND s.enabled = 1';
    $row = $GLOBALS['db']->getRow($sql);

    if (!empty($row))
    {
        $shipping_config = unserialize_config($row['configure']);
        if (isset($shipping_config['pay_fee']))
        {
            if (strpos($shipping_config['pay_fee'], '%') !== false)
            {
                $row['pay_fee'] = floatval($shipping_config['pay_fee']) . '%';
            }
            else
            {
                $row['pay_fee'] = floatval($shipping_config['pay_fee']);
            }
        }
        else
        {
            $row['pay_fee'] = 0.00;
        }
    }

    return $row;
}

/**
 * �������л���֧�������͵����ò���
 * ����һ����nameΪ����������
 *
 * @access  public
 * @param   string       $cfg
 * @return  void
 */
function unserialize_config($cfg)
{
    if (is_string($cfg) && ($arr = unserialize($cfg)) !== false)
    {
        $config = array();

        foreach ($arr AS $key => $val)
        {
            $config[$val['name']] = $val['value'];
        }

        return $config;
    }
    else
    {
        return false;
    }
}

/**
 * �����˷�
 * @param   string  $shipping_code      ���ͷ�ʽ����
 * @param   mix     $shipping_config    ���ͷ�ʽ������Ϣ
 * @param   float   $goods_weight       ��Ʒ����
 * @param   float   $goods_amount       ��Ʒ���
 * @param   float   $goods_number       ��Ʒ����
 * @return  float   �˷�
 */
function shipping_fee($shipping_code, $shipping_config, $goods_weight, $goods_amount, $goods_number='')
{
    if (!is_array($shipping_config))
    {
        $shipping_config = unserialize($shipping_config);
    }

    $filename = ROOT_PATH . 'includes/modules/shipping/' . $shipping_code . '.php';
    if (file_exists($filename))
    {
        include_once($filename);

        $obj = new $shipping_code($shipping_config);

        return $obj->calculate($goods_weight, $goods_amount, $goods_number);
    }
    else
    {
        return 0;
    }
}