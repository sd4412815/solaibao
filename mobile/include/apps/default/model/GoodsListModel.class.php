<?php

/**
 * ============================================================================
 * 文件名称：UserModel.php
 * ----------------------------------------------------------------------------
 * 功能描述：用户模型
 * ============================================================================
 */
/* 访问控制 */
// YWT_增加代码
defined('IN_ECTOUCH') or die('Deny Access');

class GoodsListModel extends BaseModel {

    protected $table = 'users';
    private $cat_id = 0; // 分类id
    private $children = '';
    private $brand = 0; // 品牌
    private $type = ''; //商品类型
    private $price_min = 0; // 最低价格
    private $price_max = 0; // 最大价格
    private $ext = '';
    private $size = 10; // 每页数据
    private $page = 1; // 页数
    private $sort = 'last_update';
    private $order = 'DESC'; // 排序方式
    private $keywords = 0; // 搜索关键词
    private $filter_attr_str = 0;

    function get_new($start,$page,$limit="0,10"){

        $sql = "SELECT goods_id FROM " . $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $newlist = $this->query($sql);
        return $newlist;
    }
    function get_hot($start,$page,$limit = '0,10'){
        $sql = "SELECT goods_id FROM " . $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $hotlist = $this->query($sql);
        return $hotlist;
    }
    function get_best($start,$page,$limit = '0,10'){
        $sql = "SELECT goods_id FROM " . $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $salse_list = $this->query($sql);
        return $salse_list;                                             
    }   
    /**==========
     * |价格排序|
     * ==========
     */
    function desc_new($limit){
        $order = "DESC";
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 ORDER BY shop_price ".$order." LIMIT ".$limit;
        $descxiaoliang = $this->query($sql);

        return $descxiaoliang;
    }
    function asc_new($limit){
        $order = "ASC";
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 ORDER BY shop_price ".$order." LIMIT ".$limit;
        $ascxiaoliang = $this->query($sql);
        return $ascxiaoliang;
    }
    function desc_hot($limit){
        $order = "DESC";
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 ORDER BY shop_price ".$order." LIMIT ".$limit;
        $descxiaoliang = $this->query($sql);
        return $descxiaoliang;
    }
    function asc_hot($limit){
        $order = "ASC";
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 ORDER BY shop_price ".$order." LIMIT ".$limit;
        $ascxiaoliang = $this->query($sql);
        return $ascxiaoliang;
    }
    function desc_best($limit){
        $order = "DESC";
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 ORDER BY shop_price ".$order." LIMIT ".$limit;
        $descxiaoliang = $this->query($sql);
        return $descxiaoliang;
    }
    function asc_best($limit){
        $order = "ASC";
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 ORDER BY shop_price ".$order." LIMIT ".$limit;
        $ascxiaoliang = $this->query($sql);
        return $ascxiaoliang;
    }

    /**===========
     * |人气排序 |
     * ===========
     */
    
    function desc_new1($limit){
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $res = $this -> query($sql);
            $descxiaoliang = array();
            foreach ($res as $k => $val) {
                $sql1 = "SELECT count(*) FROM ". $this->pre ."collect_goods  WHERE goods_id =".$val['goods_id'];
                $descxiaoliang[]= $this -> query($sql1);
            }
            foreach ($descxiaoliang as $k => $v){
                $ss[] = $v[0]['count(*)'];
            }
            $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $row = $this -> query($sql);

           
            foreach ($row as $key => $value) {
                $descxiaoliang[$key]['goods_name'] = $value['goods_name'];
                $descxiaoliang[$key]['shop_price'] = $value['shop_price'];
                $descxiaoliang[$key]['market_price'] = $value['market_price'];
                $descxiaoliang[$key]['goods_thumb'] = $value['goods_thumb'];
                $descxiaoliang[$key]['count'] = $v[0]['count(*)'];
            }
            array_multisort($ss,SORT_DESC,$descxiaoliang);
            return $descxiaoliang;
    }
    function asc_new1($limit){
       $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $res = $this -> query($sql);
            $ascxiaoliang = array();
            foreach ($res as $k => $val) {
                $sql1 = "SELECT count(*),goods_id FROM ". $this->pre ."collect_goods  WHERE goods_id =".$val['goods_id'];
                $ascxiaoliang[]= $this -> query($sql1);
            }
            foreach ($ascxiaoliang as $k => $v){
                $ss[] = $v[0]['count(*)'];
            }
            $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $row = $this -> query($sql);
            foreach ($row as $key => $value) {
                $ascxiaoliang[$key]['goods_name'] = $value['goods_name'];
                $ascxiaoliang[$key]['shop_price'] = $value['shop_price'];
                $ascxiaoliang[$key]['market_price'] = $value['market_price'];
                $ascxiaoliang[$key]['goods_thumb'] = $value['goods_thumb'];
            }
            array_multisort($ss,SORT_ASC,$ascxiaoliang);
            return $ascxiaoliang;
    }
    function desc_hot1($limit){
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $res = $this -> query($sql);
            $descxiaoliang = array();
            foreach ($res as $k => $val) {
                $sql1 = "SELECT count(*) FROM ". $this->pre ."collect_goods  WHERE goods_id =".$val['goods_id'];
                $descxiaoliang[]= $this -> query($sql1);
            }
            foreach ($descxiaoliang as $k => $v){
                $ss[] = $v[0]['count(*)'];
            }
            $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $row = $this -> query($sql);
            // p($row);
            foreach ($row as $key => $value) {
                
                $descxiaoliang[$key]['goods_name'] = $value['goods_name'];
                $descxiaoliang[$key]['shop_price'] = $value['shop_price'];
                $descxiaoliang[$key]['market_price'] = $value['market_price'];
                $descxiaoliang[$key]['goods_id'] = $value['goods_id'];
                $descxiaoliang[$key]['goods_thumb'] = $value['goods_thumb'];
               
            }
            // p($descxiaoliang);
            array_multisort($ss,SORT_DESC,$descxiaoliang);
            return $descxiaoliang;
    }
    function asc_hot1($limit){
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $res = $this -> query($sql);
            $ascxiaoliang = array();
            foreach ($res as $k => $val) {
                $sql1 = "SELECT count(*),goods_id FROM ". $this->pre ."collect_goods  WHERE goods_id =".$val['goods_id'];
                $ascxiaoliang[]= $this -> query($sql1);
            }
            foreach ($ascxiaoliang as $k => $v){
                $ss[] = $v[0]['count(*)'];
            }
            $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $row = $this -> query($sql);
            foreach ($row as $key => $value) {
                $ascxiaoliang[$key]['goods_name'] = $value['goods_name'];
                $ascxiaoliang[$key]['shop_price'] = $value['shop_price'];
                $ascxiaoliang[$key]['market_price'] = $value['market_price'];
                $ascxiaoliang[$key]['goods_id'] = $value['goods_id'];
                $ascxiaoliang[$key]['goods_thumb'] = $value['goods_thumb'];
            }
            array_multisort($ss,SORT_ASC,$ascxiaoliang);
            return $ascxiaoliang;
    }
    function desc_best1($limit){
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $res = $this -> query($sql);
            $descxiaoliang = array();
            foreach ($res as $k => $val) {
                $sql1 = "SELECT count(*),goods_id FROM ". $this->pre ."collect_goods  WHERE goods_id =".$val['goods_id'];
                $descxiaoliang[]= $this -> query($sql1);
            }
            foreach ($descxiaoliang as $k => $v){
                $ss[] = $v[0]['count(*)'];
            }
            $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $row = $this -> query($sql);
            foreach ($row as $key => $value) {
                $descxiaoliang[$key]['goods_name'] = $value['goods_name'];
                $descxiaoliang[$key]['shop_price'] = $value['shop_price'];
                $descxiaoliang[$key]['market_price'] = $value['market_price'];
                $descxiaoliang[$key]['goods_thumb'] = $value['goods_thumb'];
            }
            array_multisort($ss,SORT_DESC,$descxiaoliang);
            return $descxiaoliang;
    }
    function asc_best1($limit){
        $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $res = $this -> query($sql);
            $ascxiaoliang = array();
            foreach ($res as $k => $val) {
                $sql1 = "SELECT count(*),goods_id FROM ". $this->pre ."collect_goods  WHERE goods_id =".$val['goods_id'];
                $ascxiaoliang[]= $this -> query($sql1);
            }
            foreach ($ascxiaoliang as $k => $v){
                $ss[] = $v[0]['count(*)'];
            }
            $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND goods_number != 0 AND is_on_sale = 1 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
            $row = $this -> query($sql);
            foreach ($row as $key => $value) {
                $ascxiaoliang[$key]['goods_name'] = $value['goods_name'];
                $ascxiaoliang[$key]['shop_price'] = $value['shop_price'];
                $ascxiaoliang[$key]['market_price'] = $value['market_price'];
                $ascxiaoliang[$key]['goods_thumb'] = $value['goods_thumb'];
            }
            array_multisort($ss,SORT_ASC,$ascxiaoliang);
            return $ascxiaoliang;
    }
    //销量排序
    //yang
    function new_asc_salse($goods_id,$limit){
        // $result = Array();
        //  $sql = "SELECT g.shop_price,g.goods_thumb,g.goods_name,g.market_price,g.goods_id FROM ". $this->pre ."goods g WHERE  g.is_new = 1 AND g.supplier_id = 0 AND g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND g.is_delete = 0  LIMIT ".$limit;
        //   $res = $this -> query($sql);
        // // p($res);
       
        
        // foreach ($res as $key => $row) {
        //   $sql = "SELECT COUNT(goods_number) count FROM ". $this->pre."order_goods WHERE goods_id =".$row['goods_id'];
        //   $res1 = $this -> row($sql);
        //   $res[$key]['count']=$res1['count'];
        //   // $sql = "SELECT sum(goods_number) AS count FROM ". $this->pre."order_goods WHERE goods_id =".$row['goods_id'];
        //   // $result[$key] = $this -> row($sql);

        // }
        
        // foreach ($res as $key => $value) {
        //     $res[$key]['count'] = $value['count'];
        //     $rea[$key]['goods_name'] = $value['goods_name'];
            
        // }
        //             // p($res);
        // array_multisort($res, SORT_DESC, $rea, SORT_ASC, $salse);
       // p($result);
        // p($salse);
        // return $result;
 

   }
    function new_desc_salse($limit){
       $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_new = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $res = $this -> query($sql);
       
       foreach($res as $k=>$v){
           
           $sql="SELECT goods_id,order_id,goods_number FROM " . $this->pre."order_goods WHERE goods_id=".$v['goods_id'];
           $row=$this->query($sql);

                foreach($row as $key=>$val){
               
                    $sql="SELECT shipping_status,order_id FROM " . $this->pre."order_info WHERE   order_id=".$val['order_id'];
                    $a=$this->query($sql);

                    $a[0]['number']=$val['goods_number'];
                    $a[0]['goods_id']=$val['goods_id'];

                    foreach($a as $k1=>$v1){
                        if($v1['shipping_status']=="2"){
                         $m=(int)$v1['number'];                     
                         $n+=$m;                       
                        }
                    }
                }

            $salse[$k]['salse'] = $n;
            $salse[$k]['goods_name'] = $v['goods_name'];
            $salse[$k]['shop_price'] = $v['shop_price'];
            $salse[$k]['market_price'] = $v['market_price'];
            $salse[$k]['goods_thumb'] = $v['goods_thumb'];
            $salse[$k]['goods_id']=$v['goods_id'];
             $n=0;
           
             
        }
        
        foreach ( $salse as $key1 => $val1 ){
             $num1[$key1] = $val1 ['goods_name'];
            $num2[$key1] = $val1 ['salse'];
        }
 
        array_multisort($num2, SORT_DESC, $num1, SORT_ASC, $salse);
        return $salse;
 

   }
    function hot_desc_salse($limit){
       $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $res = $this -> query($sql);

       foreach($res as $k=>$v){
           
           $sql="SELECT goods_id,order_id,goods_number FROM " . $this->pre."order_goods WHERE goods_id=".$v['goods_id'];
           $row=$this->query($sql);
                foreach($row as $key=>$val){
               
                    $sql="SELECT shipping_status,order_id FROM " . $this->pre."order_info WHERE   order_id=".$val['order_id'];
                    $a=$this->query($sql);

                    $a[0]['number']=$val['goods_number'];
                    $a[0]['goods_id']=$val['goods_id'];

                   
                    foreach($a as $k1=>$v1){

                        if($v1['shipping_status']=="2"){                        
                         $m=(int)$v1['number'];                     
                         $n+=$m;                       
                        }
                    }
                }
            
            $salse[$k]['salse'] = $n;
            $salse[$k]['goods_name'] = $v['goods_name'];
            $salse[$k]['shop_price'] = $v['shop_price'];
            $salse[$k]['market_price'] = $v['market_price'];
            $salse[$k]['goods_thumb'] = $v['goods_thumb'];
            $salse[$k]['goods_id']=$v['goods_id'];
             $n=0;
           
             
        }
        
        foreach ( $salse as $key1 => $val1 ){
             $num1[$key1] = $val1 ['goods_name'];
            $num2[$key1] = $val1 ['salse'];
        }
 
        array_multisort($num2, SORT_DESC, $num1, SORT_ASC, $salse);

        return $salse;

       
   }
    function hot_asc_salse($limit){
       $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_hot = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $res = $this -> query($sql);
       

       foreach($res as $k=>$v){
           
           $sql="SELECT goods_id,order_id,goods_number FROM " . $this->pre."order_goods WHERE goods_id=".$v['goods_id'];
           $row=$this->query($sql);

                foreach($row as $key=>$val){
               
                    $sql="SELECT shipping_status,order_id FROM " . $this->pre."order_info WHERE   order_id=".$val['order_id'];
                    $a=$this->query($sql);

                    $a[0]['number']=$val['goods_number'];
                    $a[0]['goods_id']=$val['goods_id'];
                   
                    foreach($a as $k1=>$v1){

                        if($v1['shipping_status']=="2"){                        
                         $m=(int)$v1['number'];                     
                         $n+=$m;                       
                        }
                    }
                }

            
            $salse[$k]['salse'] = $n;
            $salse[$k]['goods_name'] = $v['goods_name'];
            $salse[$k]['shop_price'] = $v['shop_price'];
            $salse[$k]['market_price'] = $v['market_price'];
            $salse[$k]['goods_thumb'] = $v['goods_thumb'];
            $salse[$k]['goods_id']=$v['goods_id'];
             $n=0;
           
             
        }
        
        
        foreach ($salse as $key1 => $val1 ){
             $num1[$key1] = $val1 ['goods_name'];
            $num2[$key1] = $val1 ['salse'];
        }
 
        array_multisort($num2, SORT_ASC, $num1, SORT_DESC, $salse);

        return $salse;

       
   }
    function best_desc_salse($limit){
       $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $res = $this -> query($sql);
       

       foreach($res as $k=>$v){
           
           $sql="SELECT goods_id,order_id,goods_number FROM " . $this->pre."order_goods WHERE goods_id=".$v['goods_id'];
           $row=$this->query($sql);

                foreach($row as $key=>$val){
               
                    $sql="SELECT shipping_status,order_id FROM " . $this->pre."order_info WHERE   order_id=".$val['order_id'];
                    $a=$this->query($sql);

                    $a[0]['number']=$val['goods_number'];
                    $a[0]['goods_id']=$val['goods_id'];              
                    foreach($a as $k1=>$v1){
                        if($v1['shipping_status']=="2"){                        
                         $m=(int)$v1['number'];
                         $n+=$m;      
                        }
                    }
                }
            
            $salse[$k]['salse'] = $n;
            $salse[$k]['goods_name'] = $v['goods_name'];
            $salse[$k]['shop_price'] = $v['shop_price'];
            $salse[$k]['market_price'] = $v['market_price'];
            $salse[$k]['goods_thumb'] = $v['goods_thumb'];
            $salse[$k]['goods_id']=$v['goods_id'];
             $n=0;
           
             
        }
        foreach ( $salse as $key1 => $val1 ){
             $num1[$key1] = $val1 ['goods_name'];
            $num2[$key1] = $val1 ['salse'];
        }
 
        array_multisort($num2, SORT_DESC, $num1, SORT_ASC, $salse);
        return $salse;

       
   }
    function best_asc_salse($limit){
       $sql = "SELECT shop_price,goods_thumb,goods_name,market_price,goods_id FROM ". $this->pre ."goods WHERE is_best = 1 AND supplier_id = 0 AND is_on_sale = 1 AND goods_number != 0 AND is_alone_sale = 1 AND " . "is_delete = 0 LIMIT ".$limit;
        $res = $this -> query($sql);

       foreach($res as $k=>$v){
           
           $sql="SELECT goods_id,order_id,goods_number FROM " . $this->pre."order_goods WHERE goods_id=".$v['goods_id'];
           $row=$this->query($sql);

                foreach($row as $key=>$val){
               
                    $sql="SELECT shipping_status,order_id FROM " . $this->pre."order_info WHERE   order_id=".$val['order_id'];
                    $a=$this->query($sql);

                    $a[0]['number']=$val['goods_number'];
                    $a[0]['goods_id']=$val['goods_id'];

                    foreach($a as $k1=>$v1){
                        if($v1['shipping_status']=="2"){                        
                         $m=(int)$v1['number'];                     
                         $n+=$m;                       
                        }
                    }
                }

            
            $salse[$k]['salse'] = $n;
            $salse[$k]['goods_name'] = $v['goods_name'];
            $salse[$k]['shop_price'] = $v['shop_price'];
            $salse[$k]['market_price'] = $v['market_price'];
            $salse[$k]['goods_thumb'] = $v['goods_thumb'];
            $salse[$k]['goods_id']=$v['goods_id'];
             $n=0;
           
             
        }
        
        foreach ( $salse as $key1 => $val1 ){
             $num1[$key1] = $val1 ['goods_name'];
            $num2[$key1] = $val1 ['salse'];
        }
 
        array_multisort($num2, SORT_ASC, $num1, SORT_DESC, $salse);
        return $salse;
 

   }
   //yang
    /**
 * 获得分类下的商品
 *
 * @access public
 * @param string $children            
 * @return array
 */
    function goods_list_get_goods() {
    $display = $GLOBALS['display'];
    $where = "g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND " . "g.is_delete = 0 ";
    if ($this->keywords != '') {
        $where .= " AND (( 1 " . $this->keywords . " ) ) ";
    } else {
        $where.=" AND ($this->children OR " . model('Goods')->get_extension_goods($this->children) . ') ';
    }
    if ($this->type) {
        switch ($this->type) {
            case 'best':
                $where .= ' AND g.is_best = 1';
                break;
            case 'new':
                $where .= ' AND g.is_new = 1';
                break;
            case 'hot':
                $where .= ' AND g.is_hot = 1';
                break;
            case 'promotion':
                $time = gmtime();
                $where .= " AND g.promote_price > 0 AND g.promote_start_date <= '$time' AND g.promote_end_date >= '$time'";
                break;
            default:
                $where .= '';
        }
    }
    if ($this->brand > 0) {
        $where .= "AND g.brand_id=$this->brand ";
    }
    if ($this->price_min > 0) {
        $where .= " AND g.shop_price >= $this->price_min ";
    }
    if ($this->price_max > 0) {
        $where .= " AND g.shop_price <= $this->price_max ";
    }

    $start = ($this->page - 1) * $this->size;
    $sort = $this->sort == 'sales_volume' ? 'xl.sales_volume' : $this->sort;
    /* 获得商品列表 */
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' . "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " . 'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img, xl.sales_volume ' . 'FROM ' . $this->model->pre . 'goods AS g ' . ' LEFT JOIN ' . $this->model->pre . 'touch_goods AS xl ' . ' ON g.goods_id=xl.goods_id ' . ' LEFT JOIN ' . $this->model->pre . 'member_price AS mp ' . "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " . "WHERE $where $this->ext ORDER BY $sort $this->order LIMIT $start , $this->size";
    $res = $this->model->query($sql);
    
    $arr = array();
    foreach ($res as $row) {
        // 销量统计
        $sales_volume = (int) $row['sales_volume'];
        if (mt_rand(0, 3) == 3){
            $sales_volume = model('GoodsBase')->get_sales_count($row['goods_id']);
            $sql = 'REPLACE INTO ' . $this->model->pre . 'touch_goods(`goods_id`, `sales_volume`) VALUES('. $row['goods_id'] .', '.$sales_volume.')';
            $this->model->query($sql);
        }
        if ($row['promote_price'] > 0) {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
        } else {
            $promote_price = 0;
        }
        /* 处理商品水印图片 */
        $watermark_img = '';

        if ($promote_price != 0) {
            $watermark_img = "watermark_promote_small";
        } elseif ($row['is_new'] != 0) {
            $watermark_img = "watermark_new_small";
        } elseif ($row['is_best'] != 0) {
            $watermark_img = "watermark_best_small";
        } elseif ($row['is_hot'] != 0) {
            $watermark_img = 'watermark_hot_small';
        }

        if ($watermark_img != '') {
            $arr[$row['goods_id']]['watermark_img'] = $watermark_img;
        }

        $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
        if ($display == 'grid') {
            $arr[$row['goods_id']]['goods_name'] = C('goods_name_length') > 0 ? sub_str($row['goods_name'], C('goods_name_length')) : $row['goods_name'];
        } else {
            $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
        }
        $arr[$row['goods_id']]['name'] = $row['goods_name'];
        $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
        $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'], $row['goods_name_style']);
        $arr[$row['goods_id']]['market_price'] = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price'] = price_format($row['shop_price']);
        $arr[$row['goods_id']]['type'] = $row['goods_type'];
        $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
        $arr[$row['goods_id']]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['url'] = url('goods/index', array(
            'id' => $row['goods_id']
        ));
        $arr[$row['goods_id']]['sales_count'] = $sales_volume;
        $arr[$row['goods_id']]['sc'] = model('GoodsBase')->get_goods_collect($row['goods_id']);
        $arr[$row['goods_id']]['mysc'] = 0;

        // 检查是否已经存在于用户的收藏夹
        if ($_SESSION['user_id']) {
            unset($where);
            // 用户自己有没有收藏过
            $where['goods_id'] = $row['goods_id'];
            $where['user_id'] = $_SESSION['user_id'];
            $rs = $this->model->table('collect_goods')
                    ->where($where)
                    ->count();
            $arr[$row['goods_id']]['mysc'] = $rs;
        }
        $arr[$row['goods_id']]['promotion'] = model('GoodsBase')->get_promotion_show($row['goods_id']);
        $arr[$row['goods_id']]['comment_count'] = model('Comment')->get_goods_comment($row['goods_id'], 0);  //商品总评论数量 
        $arr[$row['goods_id']]['favorable_count'] = model('Comment')->favorable_comment($row['goods_id'], 0);  //获得商品好评百分比
    }
    return $arr;
}
function goodslist_get_new_count() {
        
        $where = "g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND " . "g.is_delete = 0 ";

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre . 'goods AS g ' . ' LEFT JOIN ' . $this->pre . 'touch_goods AS xl ' . ' ON g.goods_id=xl.goods_id ' . ' LEFT JOIN ' . $this->pre . 'member_price AS mp ' . "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " . "WHERE $where $ext AND supplier_id = 0 AND is_new = 1";
        $res = $this->row($sql);
        return $res['count'];
    }
function goodslist_get_hot_count() {
        
        $where = "g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND " . "g.is_delete = 0 ";

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre . 'goods AS g ' . ' LEFT JOIN ' . $this->pre . 'touch_goods AS xl ' . ' ON g.goods_id=xl.goods_id ' . ' LEFT JOIN ' . $this->pre . 'member_price AS mp ' . "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " . "WHERE $where $ext AND supplier_id = 0 AND is_hot = 1";
        $res = $this->row($sql);
        return $res['count'];
    }
function goodslist_get_best_count() {
        
        $where = "g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND " . "g.is_delete = 0 ";

        $sql = 'SELECT COUNT(*) as count FROM ' . $this->pre . 'goods AS g ' . ' LEFT JOIN ' . $this->pre . 'touch_goods AS xl ' . ' ON g.goods_id=xl.goods_id ' . ' LEFT JOIN ' . $this->pre . 'member_price AS mp ' . "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " . "WHERE $where $ext AND supplier_id = 0 AND is_best = 1";
        $res = $this->row($sql);
        return $res['count'];
    }



        /**
     * 获得分类下的商品
     *
     * @access public
     * @param string $children            
     * @return array
     */
    function goodslist_get_search() {
        $display = $GLOBALS['display'];
        $where = "g.is_on_sale = 1 AND g.goods_number != 0 AND g.is_alone_sale = 1 AND " . "g.is_delete = 0 ";
        if ($this->keywords != '') {
            $where .= " AND (( 1 " . $this->keywords . " ) ) ";
        } else {
             $this->children = get_children($this->cat_id);
            $where.=" AND ($this->children OR " . model('Goods')->get_extension_goods($this->children) . ') ';
        }

        if ($this->type) {
            switch ($this->type) {
                case 'best':
                    $where .= ' AND g.is_best = 1';
                    break;
                case 'new':
                    $where .= ' AND g.is_new = 1';
                    break;
                case 'hot':
                    $where .= ' AND g.is_hot = 1';
                    break;
                case 'promotion':
                    $time = gmtime();
                    $where .= " AND g.promote_price > 0 AND g.promote_start_date <= '$time' AND g.promote_end_date >= '$time'";
                    break;
                default:
                    $where .= '';
            }
        }

        if ($this->brand > 0) {
            $where .= "AND g.brand_id=$this->brand ";
        }
        if ($this->price_min > 0) {
            $where .= " AND g.shop_price >= $this->price_min ";
        }
        if ($this->price_max > 0) {
            $where .= " AND g.shop_price <= $this->price_max ";
        }
        $where .= ' AND supplier_id = 0';

        $start = ($this->page - 1) * $this->size;
        $sort = $this->sort == 'sales_volume' ? 'xl.sales_volume' : $this->sort;
        /* 获得商品列表 */
        $sql = 'SELECT g.goods_id, g.goods_name, g.click_count, g.goods_name_style, g.market_price, g.shop_price AS org_price, ' . "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " . 'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img, xl.sales_volume ' . 'FROM ' . $this->model->pre . 'goods AS g ' . ' LEFT JOIN ' . $this->model->pre . 'touch_goods AS xl ' . ' ON g.goods_id=xl.goods_id ' . ' LEFT JOIN ' . $this->model->pre . 'member_price AS mp ' . "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " . "WHERE $where $this->ext ORDER BY click_count DESC LIMIT 0 , 8";
        $res = $this->model->query($sql);
        $arr = array();
        foreach ($res as $row) {
            // 销量统计
            $sales_volume = (int) $row['sales_volume'];
            if (mt_rand(0, 3) == 3){
                $sales_volume = model('GoodsBase')->get_sales_count($row['goods_id']);
                $sql = 'REPLACE INTO ' . $this->model->pre . 'touch_goods(`goods_id`, `sales_volume`) VALUES('. $row['goods_id'] .', '.$sales_volume.')';
                $this->model->query($sql);
            }
            if ($row['promote_price'] > 0) {
                $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            } else {
                $promote_price = 0;
            }
            /* 处理商品水印图片 */
            $watermark_img = '';

            if ($promote_price != 0) {
                $watermark_img = "watermark_promote_small";
            } elseif ($row['is_new'] != 0) {
                $watermark_img = "watermark_new_small";
            } elseif ($row['is_best'] != 0) {
                $watermark_img = "watermark_best_small";
            } elseif ($row['is_hot'] != 0) {
                $watermark_img = 'watermark_hot_small';
            }

            if ($watermark_img != '') {
                $arr[$row['goods_id']]['watermark_img'] = $watermark_img;
            }

            $arr[$row['goods_id']]['goods_id'] = $row['goods_id'];
            if ($display == 'grid') {
                $arr[$row['goods_id']]['goods_name'] = C('goods_name_length') > 0 ? sub_str($row['goods_name'], C('goods_name_length')) : $row['goods_name'];
            } else {

                $arr[$row['goods_id']]['goods_name'] = $row['goods_name'];
            }
            $arr[$row['goods_id']]['name'] = $row['goods_name'];
            $arr[$row['goods_id']]['goods_brief'] = $row['goods_brief'];
            $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'], $row['goods_name_style']);
            $arr[$row['goods_id']]['market_price'] = price_format($row['market_price']);
            $arr[$row['goods_id']]['shop_price'] = price_format($row['shop_price']);
            $arr[$row['goods_id']]['type'] = $row['goods_type'];
            $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
            $arr[$row['goods_id']]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
            $arr[$row['goods_id']]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
            $arr[$row['goods_id']]['url'] = url('goods/index', array('id' => $row['goods_id']));
            $arr[$row['goods_id']]['sales_count'] = $sales_volume;
            $arr[$row['goods_id']]['sc'] = model('GoodsBase')->get_goods_collect($row['goods_id']);
            $arr[$row['goods_id']]['mysc'] = 0;
            $arr[$row['goods_id']]['click_count'] = $row['click_count'];
            // 检查是否已经存在于用户的收藏夹
            if ($_SESSION['user_id']) {
                unset($where);
                // 用户自己有没有收藏过
                $where['goods_id'] = $row['goods_id'];
                $where['user_id'] = $_SESSION['user_id'];
                $rs = $this->model->table('collect_goods')
                        ->where($where)
                        ->count();
                $arr[$row['goods_id']]['mysc'] = $rs;

            }
            $arr[$row['goods_id']]['promotion'] = model('GoodsBase')->get_promotion_show($row['goods_id']);
            $arr[$row['goods_id']]['comment_count'] = model('Comment')->get_goods_comment($row['goods_id'], 0);  //商品总评论数量 
            $arr[$row['goods_id']]['favorable_count'] = model('Comment')->favorable_comment($row['goods_id'], 0);  //获得商品好评百分比
        }
        // p($arr);
        return $arr;
    }



}
// YWT_增加代码end




