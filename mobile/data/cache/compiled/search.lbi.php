<div class="search" style="display:none;">
  <div class="ect-bg">
    <header class="ect-header ect-margin-tb ect-margin-lr text-center"><span><?php echo $this->_var['lang']['search']; ?></span><a href="javascript:;" onClick="closeSearch();"><i class="icon-close pull-right"></i></a></header>
  </div>
  <div class="ect-padding-lr" style="margin-bottom:30px;">
  <!-- <div style="margin-top:20px;">
      <img src="themes/default/images/log.png" alt="" style="width:100%; height:50%;margin-bottom:20px;">
  </div> -->
     <form action="<?php echo url('category/index');?><?php if ($this->_var['id']): ?>&id=<?php echo $this->_var['id']; ?><?php endif; ?>"  method="post" id="searchForm" name="searchForm">
      <div class="input-search"> <span>
        <input name="keywords" type="search" placeholder="<?php echo $this->_var['lang']['no_keywords']; ?>" id="keywordBox">
        </span>
        <button type="submit" value="<?php echo $this->_var['lang']['search']; ?>"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </form>
    <?php if ($this->_var['hot_search_keywords']): ?>
    <div class="hot-search">
      <p>
      <h4 class="title"><b><?php echo $this->_var['lang']['hot_search']; ?>：</b></h4>
      </p>
      <?php $_from = $this->_var['hot_search_keywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ky');if (count($_from)):
    foreach ($_from AS $this->_var['ky']):
?> 
      <a href="<?php echo url('category/index', array('keywords'=>$this->_var[ky]));?><?php if ($this->_var['id']): ?>&id=<?php echo $this->_var['id']; ?><?php endif; ?>"><?php echo $this->_var['ky']; ?></a> 
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    </div>
    <?php endif; ?> 
  </div>
  
  <div>
  <div style="margin-bottom:30px; text-align:center;"> ----------------</i> <p class="glyphicon glyphicon-hand-right"> 热 搜 商 品 </p> &nbsp;&nbsp;<p class="glyphicon glyphicon-hand-left"> </p> ----------------</i></div>
  
</div>
<div style="width:100%; padding-left:4%; padding-right:4%;">
 <?php $_from = $this->_var['goods_search']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['goods']):
?>

    <div style="width:46%; float:left; text-align:center; background:#fff; margin:2%; padding-bottom:5px;" >
      <div>
        <a href="<?php echo $this->_var['goods']['url']; ?>">
          <img src="<?php if ($this->_var['goods']['goods_thumb'] == ''): ?>../images/no_picture.png<?php else: ?><?php echo $this->_var['goods']['goods_thumb']; ?><?php endif; ?>" alt="商品图片" style="width:20vw; height:20vw;">
        </a>
      </div>
      <dl>
        <div style="width:100%; padding-left:20%;">
          <dd style="text-align:left; font-size:12px;"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php if (strlen ( $this->_var['goods']['goods_name'] ) > 8): ?><?php echo sub_str($this->_var['goods']['goods_name'],8); ?><?php else: ?><?php echo $this->_var['goods']['goods_name']; ?><?php endif; ?></a></dd>
          <dd style="text-align:left; font-size:12px;"><?php echo $this->_var['goods']['shop_price']; ?></dd>
          <dd style="text-align:left; font-size:12px;">受喜爱度：<?php echo $this->_var['goods']['click_count']; ?></dd>
        </div>
      </dl>
    </div>
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <div style="clear:both;"></div>
</div>
</div>
