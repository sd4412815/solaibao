<?php echo $this->fetch('library/user_header.lbi'); ?>

 <div style=" width:100%; height:50%; border :1px solid #ddd; float: left; padding-top:25px;padding-left:25px;">
        <div style="width:60%; height:90%; float:left; margin-left:20px;">
          <img style=" float:left; width:60px; height:60px; margin-left:0px; margin-bottom:30px; " src="themes/default/images/shouyi.png"> 
          <div style="margin-left:80px;"><?php echo $this->_var['lang']['current_surplus']; ?><h4 style="margin-top:5px;"><?php echo $this->_var['surplus_amount']; ?></h4></div>
        </div>
  </div>

  <ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="<?php echo url('User/account_detail');?>" ><?php echo $this->_var['lang']['add_surplus_log']; ?></a></li>
    <li><a href="<?php echo url('User/account_log');?>" ><?php echo $this->_var['lang']['view_application']; ?></a></li>
  	<li><a href="<?php echo url('User/account_deposit');?>" ><?php echo $this->_var['lang']['surplus_type_0']; ?></a></li>
  	<li><a href="<?php echo url('User/account_raply');?>" ><?php echo $this->_var['lang']['surplus_type_1']; ?></a></li>
  </ul>
  
 <div class="user-account-detail">
  	<ul class=" ect-bg-colorf">
<?php $_from = $this->_var['account_log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
    	<li>
        	<p class="title"><span class="pull-left"><?php echo $this->_var['item']['change_time']; ?></span> <span class="pull-right"><?php echo $this->_var['item']['amount']; ?></span></p>
          <p class="content"><span class="remark pull-left"><?php echo $this->_var['item']['short_change_desc']; ?></span> <span class="pull-right text-right type"><?php echo $this->_var['item']['type']; ?></span></p>
        </li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
   <!--  <p class="pull-right count" style="background-color: bisque;"><?php echo $this->_var['lang']['current_surplus']; ?><b class="ect-colory"><?php echo $this->_var['surplus_amount']; ?></b></p> -->
  </div>
   <?php echo $this->fetch('library/page.lbi'); ?>
</div>
<?php echo $this->fetch('library/search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>