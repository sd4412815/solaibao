<?php echo $this->fetch('library/page_header.lbi'); ?>
<?php echo $this->fetch('library/search.lbi'); ?> 
<div class="con">
  <div style="height:4.3em;"></div>
  <header>
    <nav class="ect-nav ect-bg icon-write"> <?php echo $this->fetch('library/page_menu.lbi'); ?> </nav>
  </header>

<div>

<script src="js/jquery-1.12.3.min.js"></script>
<div class="AreaR">
  <div class="box">
    <div class="box_1">
      <div class="userCenterBox boxCenterList clearfix" style="_height:1%;">
        
      <div class="flow-consignee-list ect-bg-colorf">
      <div style=" width:100%; height:50%; border :1px solid #ddd; float: left; padding-top:25px;padding-left:25px;">
        <div style="width:60%; height:90%; float:left; margin-left:20px;">
     	    <img style=" float:left; width:60px; height:60px; margin-left:0px; margin-bottom:30px; " src="themes/default/images/shouyi.png"> 
          <div style="margin-left:80px;">我的积分：<h4 style="margin-top:5px;"><?php echo $this->_var['all']; ?>分</h4></div>
        </div>
     </div>
     <div style="clear:both"></div>
       <section> 
          <ul id="J_ItemList">
          <?php $_from = $this->_var['points']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
            <li class="ect-padding-tb checkout-add single_item " style="padding-left:20px">
              <p>时间：<?php echo $this->_var['item']['date']; ?></p>
              <p>来源：<?php echo $this->_var['item']['detail']; ?></p>
              <p>积分：<b class="ji_fen"><?php echo $this->_var['item']['pay_points']; ?></b></p>
            </li>  
          <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>     
           </ul> 
       </section>
      </div>
      </div>
    </div>
  </div>
  <script>
  	
		var as = document.getElementById("J_ItemList");
        var bs = as.getElementsByTagName("b");
        for ( var i = 0; i < bs.length; i++)
        {
            var obj = bs[i];
			var objj = obj.innerHTML;
            //var num = parseFloat (obj.firstChild.nodeValue);
            //obj.style.color = num > 0 ? "green" : "red";
			if(objj < 0){
				obj.parentNode.parentNode.style.backgroundColor = "pink"
			}
        }
		
  </script>
  
</div>
<?php echo $this->fetch('library/page.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
</body>
</html>