<?php

/**
 * User: Yuan
 * Date: 16-5-9
 * Time: 上午2:21
 */
defined('IN_ECTOUCH') or die('Deny Access');
class NavigatorModel extends BaseModel
{
    /*------------------------------------------------------ */
    //-- 获得系统列表
    /*------------------------------------------------------ */
    function get_sysnav()
    {
        global $_LANG;
        $sysmain = array(
            array('团购中心','index.php?m=default&c=groupbuy'),
            array('拍卖活动','index.php?m=default&c=auction'),
//            array($_LANG['snatch'],'index.php?m=default&c=snatch'),//夺宝奇兵
//            array($_LANG['tag_cloud'],'tag_cloud.php'),//标签云
            array('用户中心','index.php?m=default&c=user&a=index'),
            array('批发商城', 'index.php?m=default&c=wholesale&a=index'),
            array('优惠活动', 'index.php?m=default&c=activity&a=index'),
            array('专题', 'index.php?m=default&c=topic&a=index'),
//            array('留言板', 'message.php'),
//            array('报价单', 'quotation.php'),
            array('积分商城', 'index.php?m=default&c=exchange&a=index'),

        );

        $sysmain[] = array('-','-');

        $catlist = array_merge(cat_list1(0, 0, false), array('-'), $this->article_cat_list(0, 0, false));
//
        foreach($catlist as $key => $val)
        {
            $val['view_name'] = $val['cat_name'];
            for($i=0;$i<$val['level'];$i++)
            {
                $val['view_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;' . $val['view_name'];
            }
            $val['url'] = str_replace( '&amp;', '&', $val['url']);
            $val['url'] = str_replace( '&', '&amp;', $val['url']);
            $sysmain[] = array($val['cat_name'], $val['url'], $val['view_name']);
        }
        return $sysmain;
    }

    /**
     * 获得指定分类下的子分类的数组
     *
     * @access  public
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @return  mix
     */
    function article_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
    {
        static $res = NULL;

        if ($res === NULL)
        {
            $data = read_static_cache('art_cat_pid_releate');
            if ($data === false)
            {
                $sql = "SELECT c.*, COUNT(s.cat_id) AS has_children, COUNT(a.article_id) AS aricle_num ".
                    ' FROM ' . $this->pre. "article_cat AS c".
                    " LEFT JOIN " . $this->pre . "article_cat AS s ON s.parent_id=c.cat_id".
                    " LEFT JOIN " . $this->pre . "article AS a ON a.cat_id=c.cat_id".
                    " GROUP BY c.cat_id ".
                    " ORDER BY parent_id, sort_order ASC";
                $res = $this->query($sql);
                write_static_cache('art_cat_pid_releate', $res);
            }
            else
            {
                $res = $data;
            }
        }

        if (empty($res) == true)
        {
            return $re_type ? '' : array();
        }

        $options = $this->article_cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

        /* 截取到指定的缩减级别 */
        if ($level > 0)
        {
            if ($cat_id == 0)
            {
                $end_level = $level;
            }
            else
            {
                $first_item = reset($options); // 获取第一个元素
                $end_level  = $first_item['level'] + $level;
            }

            /* 保留level小于end_level的部分 */
            foreach ($options AS $key => $val)
            {
                if ($val['level'] >= $end_level)
                {
                    unset($options[$key]);
                }
            }
        }

        $pre_key = 0;
        foreach ($options AS $key => $value)
        {
            $options[$key]['has_children'] = 1;
            if ($pre_key > 0)
            {
                if ($options[$pre_key]['cat_id'] == $options[$key]['parent_id'])
                {
                    $options[$pre_key]['has_children'] = 1;
                }
            }
            $pre_key = $key;
        }

        if ($re_type == true)
        {
            $select = '';
            foreach ($options AS $var)
            {
                $select .= '<option value="' . $var['cat_id'] . '" ';
                $select .= ' cat_type="' . $var['cat_type'] . '" ';
                $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
                $select .= '>';
                if ($var['level'] > 0)
                {
                    $select .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select .= htmlspecialchars(addslashes($var['cat_name'])) . '</option>';
            }

            return $select;
        }
        else
        {
            foreach ($options AS $key => $value)
            {
//                $options[$key]['url'] = build_uri('article_cat', array('acid' => $value['cat_id']), $value['cat_name']);
                $options[$key]['url'] ="index.php?m=default&c=article&a=info&aid=".$value['cat_id'];
            }
            return $options;
        }
    }


    /**
     * 过滤和排序所有文章分类，返回一个带有缩进级别的数组
     *
     * @access  private
     * @param   int     $cat_id     上级分类ID
     * @param   array   $arr        含有所有分类的数组
     * @param   int     $level      级别
     * @return  void
     */
    function article_cat_options($spec_cat_id, $arr)
    {
        static $cat_options = array();

        if (isset($cat_options[$spec_cat_id]))
        {
            return $cat_options[$spec_cat_id];
        }

        if (!isset($cat_options[0]))
        {
            $level = $last_cat_id = 0;
            $options = $cat_id_array = $level_array = array();
            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0)
                    {
                        if ($value['parent_id'] > 0)
                        {
                            break;
                        }

                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_cat_id  = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] == $last_cat_id)
                    {
                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($cat_id_array) != $last_cat_id)
                            {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    }
                    elseif ($value['parent_id'] > $last_cat_id)
                    {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1)
                {
                    $last_cat_id = array_pop($cat_id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_cat_id != end($cat_id_array))
                    {
                        $last_cat_id = end($cat_id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id]))
                {
                    $level = $level_array[$last_cat_id];
                }
                else
                {
                    $level = 0;
                }
            }
            $cat_options[0] = $options;
        }
        else
        {
            $options = $cat_options[0];
        }

        if (!$spec_cat_id)
        {
            return $options;
        }
        else
        {
            if (empty($options[$spec_cat_id]))
            {
                return array();
            }

            $spec_cat_id_level = $options[$spec_cat_id]['level'];

            foreach ($options AS $key => $value)
            {
                if ($key != $spec_cat_id)
                {
                    unset($options[$key]);
                }
                else
                {
                    break;
                }
            }

            $spec_cat_id_array = array();
            foreach ($options AS $key => $value)
            {
                if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                    ($spec_cat_id_level > $value['level']))
                {
                    break;
                }
                else
                {
                    $spec_cat_id_array[$key] = $value;
                }
            }
            $cat_options[$spec_cat_id] = $spec_cat_id_array;

            return $spec_cat_id_array;
        }
    }

}