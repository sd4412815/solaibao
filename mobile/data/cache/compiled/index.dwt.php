<?php echo $this->fetch('library/page_header.lbi'); ?>
<style>
	.input-search input{height:45px; width:91%; float:left; border:none; padding: 0 0.6em; box-sizing: border-box; border-radius: 0; border: none; border-radius: 0.4em 0 0 0.4em;}
	.input-search button{height:45px;}		
</style>
<div class="con"> 
  <header class="ect-header ect-margin-tb ect-margin-lr"> <a href="<?php echo url('category/top_all');?>" class="pull-left ect-icon ect-icon1 ect-icon-cate1"></a>
   <form action="<?php echo url('category/index');?><?php if ($this->_var['id']): ?>&id=<?php echo $this->_var['id']; ?><?php endif; ?>"  method="post" id="searchForm" name="searchForm">
    <div class="ect-header-div">
      <!--<button class="btn btn-default ect-text-left ect-btn-search" onClick="javascript:openSearch();"><i class="fa fa-search"></i>&nbsp;<?php echo $this->_var['lang']['no_keywords']; ?></button>-->

        <input type="search" name="keywords" style="margin: 0; border:none; border-color:transparent; border-radius: 0.4em 0 0 0.4em; height:2.8em;" class="col-xs-10 ect-text-left input-search" placeholder="<?php echo $this->_var['lang']['no_keywords']; ?>" id="keywordBox">

        <button  class="btn col-xs-2" type="submit" style="padding:2% 4%;" onclick="return check('keywordBox')" ><i class="glyphicon glyphicon-search" style="font-size: 20px;"></i></button>

    </div>
    </form>
  </header>
  
  <div id="focus" class="focus ect-margin-tb">
    <div class="hd">
      <ul>
      </ul>
    </div>
    <div class="bd">
      <?php 
$k = array (
  'name' => 'ads',
  'id' => '1',
  'num' => '3',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
    </div>
  </div>
  
 
  
  <nav class="container-fluid">
    <ul class="row ect-row-nav">
      <?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
      <a href="<?php echo $this->_var['nav']['url']; ?>">
      <li class="col-sm-3 col-xs-3"><i><img src="<?php echo $this->_var['nav']['pic']; ?>" ></i>
        <p class="text-center"><?php echo $this->_var['nav']['name']; ?></p>
      </li>
      </a> 
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
  </nav>
  
  
  
<!--   <div class="row" style="margin-top:20px;margin-bottom:20px;">
       <p style="color:#E95466" class="text-center"><img src="themes/default/images/queen.png" width="25" height="17" alt=""/>&nbsp;人气新品</p>
  </div>
  <div>
    	  <div style=" float:left; width:50%">
        	<a href=""><img src="themes/default/images/new1.png" style="width:100%"></a>
        </div>
        <div style=" float:left; width:50%">
        	  <a href=""><img src="themes/default/images/new2.png" style="width:100%"></a>
            <a href=""><img src="themes/default/images/new3.png" style="width:50%; float:left"></a>
            <a href=""><img src="themes/default/images/new4.png" style="width:50%; float:left"></a>
        </div>
        <div style="clear:both"></div>
  </div> -->
    
   
<div class="container-fluid">  
    <div class="row" style="background-color:#FFF;border-bottom:1px solid #E95466;height:40px;">
       <div class="col-xs-3">
       </div>
       <div class="col-xs-6 text-center" style="padding-top:10px;">
           <img src="themes/default/images/star.png" width="15" height="15" alt=""/>&nbsp;
           <strong>新品推荐</strong>&nbsp;
           <img src="themes/default/images/star.png" width="15" height="15" alt=""/>
       </div>
       <div class="col-xs-3 text-right" style="padding-top:10px;">
            <a href="index.php?m=default&c=goodslist&a=index1&type=new">更多 </a>
       </div>
    </div>
  
    <div class="row">
        <?php $_from = $this->_var['new_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'new_data');if (count($_from)):
    foreach ($_from AS $this->_var['new_data']):
?>
        <div class="col-xs-6" style="padding:5px 6px 5px 6px;">
        <div style=" width:148px; height:290px;">
            <a href="<?php echo $this->_var['new_data']['url']; ?>" class="thumbnail">
                <img src="<?php if ($this->_var['new_data']['thumb'] == ''): ?>./images/no_picture.png<?php else: ?><?php echo $this->_var['new_data']['thumb']; ?><?php endif; ?>" class="img-responsive img-rounded" style="width:100%;height:150px;" alt="<?php echo $this->_var['new_data']['name']; ?>">
            </a>
           
            <a href="<?php echo $this->_var['new_data']['url']; ?>" style="font-size:12px;"><?php if (strlen ( $this->_var['new_data']['name'] ) > 20): ?><?php echo sub_str($this->_var['new_data']['name'],20); ?><?php else: ?><?php echo $this->_var['new_data']['name']; ?><?php endif; ?></a>
            <p><span style="color:#A9A9A9;font-size:11px;">原价:<s><?php echo $this->_var['new_data']['market_price']; ?></s></span><br>
               <span style="color:#e95466;font-size:12px;padding-top:2px;">
               <strong><?php echo $this->_var['new_data']['shop_price']; ?></strong>
               </span>
            </p>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    

     
     <div class="row" style="background-color:#FFF;border-bottom:1px solid #E95466;height:40px;">
       <div class="col-xs-3">
       </div>
       <div class="col-xs-6 text-center" style="padding-top:10px;">
           <img src="themes/default/images/star.png" width="15" height="15" alt=""/>&nbsp;
           <strong>热卖商品</strong>&nbsp;
           <img src="themes/default/images/star.png" width="15" height="15" alt=""/>
       </div>
       <div class="col-xs-3 text-right" style="padding-top:10px;">
            <a href="index.php?m=default&c=goodslist&a=index1&type=hot">更多 </a>
       </div>                   
     </div>
   
     <div class="row">
     <?php $_from = $this->_var['hot_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'hot_data');if (count($_from)):
    foreach ($_from AS $this->_var['hot_data']):
?>

         <div class="col-xs-6" style="padding:5px 6px 5px 6px">
         <div style=" width:148px; height:290px;">
             <a href="<?php echo $this->_var['hot_data']['url']; ?>" class="thumbnail">
                <img src="<?php if ($this->_var['hot_data']['thumb'] == ''): ?>images/no_picture.png<?php else: ?><?php echo $this->_var['hot_data']['thumb']; ?><?php endif; ?>" class="img-responsive img-rounded" style="width:100%;height:150px;" alt="<?php echo $this->_var['hot_data']['name']; ?>">
             </a>
             <div class="row">
                <p style="font-size:14px;margin-left:20px"><?php if (strlen ( $this->_var['hot_data']['name'] ) > 20): ?><?php echo sub_str($this->_var['hot_data']['name'],20); ?><?php else: ?><?php echo $this->_var['hot_data']['name']; ?><?php endif; ?></p>
                <p style="color:#A9A9A9;font-size:12px;margin-left:20px;;margin-right:50px;display:block;float:left;width:80%">
                    <span style="float:left">原价：<s><?php echo $this->_var['hot_data']['market_price']; ?></s></span><br>
                    <span style="color:#e95466;font-size:14px;display:block;"><strong><?php echo $this->_var['hot_data']['shop_price']; ?></strong></span>
                </p>
             </div>
             </div>
         </div>
     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
     </div>
    
    
    <div class="row" style="background-color:#FFF;border-bottom:1px solid #E95466;height:40px;">
       <div class="col-xs-3">
       </div>
       <div class="col-xs-6 text-center" style="padding-top:10px;">
           <img src="themes/default/images/star.png" width="15" height="15" alt=""/>&nbsp;
           <strong>精品推荐</strong>&nbsp;
           <img src="themes/default/images/star.png" width="15" height="15" alt=""/>
       </div>
       <div class="col-xs-3 text-right" style="padding-top:10px;">
            <a href="index.php?m=default&c=goodslist&a=index1&type=best">更多 </a>
       </div>                    
    </div>
    <div class="row">
        <?php $_from = $this->_var['best_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'best_data');if (count($_from)):
    foreach ($_from AS $this->_var['best_data']):
?>
        <div class="col-xs-6" style="padding:5px 6px 5px 6px">
        <div style=" width:148px; height:290px;">
            <a href="<?php echo $this->_var['best_data']['url']; ?>" class="thumbnail">
                <img src="<?php if ($this->_var['best_data']['thumb'] == ''): ?>images/no_picture.png<?php else: ?><?php echo $this->_var['best_data']['thumb']; ?><?php endif; ?>" class="img-responsive img-rounded" style="width:100%;height:150px;"alt="<?php echo $this->_var['best_data']['name']; ?>">
            </a>
           
            <a href="<?php echo $this->_var['best_data']['url']; ?>" style="font-size:12px;"><?php if (strlen ( $this->_var['best_data']['name'] ) > 20): ?><?php echo sub_str($this->_var['best_data']['name'],20); ?><?php else: ?><?php echo $this->_var['best_data']['name']; ?><?php endif; ?></a>
            <p>
            	<span style="color:#A9A9A9;font-size:11px;">原价:<s><?php echo $this->_var['best_data']['market_price']; ?></s></span><br>
               <span style="color:#e95466;font-size:12px;padding-top:2px;"><strong><?php echo $this->_var['best_data']['shop_price']; ?></strong>
               </span>
            </p>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    
    
    
</div>
   
  
  
 <footer>
    <nav class="ect-nav"><?php echo $this->fetch('library/page_menu.lbi'); ?></nav>
  </footer>
  <div style="padding-bottom:4.2em;"></div>
</div>
<?php echo $this->fetch('library/search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?> 
<script type="text/javascript">
get_asynclist("<?php echo url('index/ajax_goods', array('type'=>'best'));?>" , '__TPL__/images/loader.gif');
</script>
</body>
</html>