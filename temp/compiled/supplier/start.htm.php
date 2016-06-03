<!-- $Id: start.htm 17216 2011-01-19 06:03:12Z liubo $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<!-- directory install start -->

<script type="Text/Javascript" language="JavaScript">
<!--
  //Ajax.call('cloud.php?is_ajax=1&act=cloud_remind','', cloud_api, 'GET', 'JSON');
    function cloud_api(result)
    {
      //alert(result.content);
      if(result.content=='0')
      {
        document.getElementById("cloud_list").style.display ='none';
      }
      else
       {
         document.getElementById("cloud_list").innerHTML =result.content;
      }
    } 
   function cloud_close(id)
    {
      Ajax.call('cloud.php?is_ajax=1&act=close_remind&remind_id='+id,'', cloud_api, 'GET', 'JSON');
    }
  //-->
 </script>

<ul id="lilist" style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
  <?php $_from = $this->_var['warning_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warning');if (count($_from)):
    foreach ($_from AS $this->_var['warning']):
?>
  <li class="Start315"><?php echo $this->_var['warning']; ?></li>
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">

</ul>
<!-- directory install end -->
<!-- start personal message -->
<?php if ($this->_var['admin_msg']): ?>
<div class="list-div" style="border: 1px solid #CC0000">
  <table cellspacing='1' cellpadding='3'>
    <tr>
      <th><?php echo $this->_var['lang']['pm_title']; ?></th>
      <th><?php echo $this->_var['lang']['pm_username']; ?></th>
      <th><?php echo $this->_var['lang']['pm_time']; ?></th>
    </tr>
    <?php $_from = $this->_var['admin_msg']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'msg');if (count($_from)):
    foreach ($_from AS $this->_var['msg']):
?>
      <tr align="center">
        <td align="left"><a href="message.php?act=view&id=<?php echo $this->_var['msg']['message_id']; ?>"><?php echo sub_str($this->_var['msg']['title'],60); ?></a></td>
        <td><?php echo $this->_var['msg']['user_name']; ?></td>
        <td><?php echo $this->_var['msg']['send_date']; ?></td>
      </tr>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </table>
  </div>
<br />
<?php endif; ?>
<!-- end personal message -->
<!-- start order statistics -->
<div class="list-div">
<table cellspacing='1' cellpadding='3'>
  <tr>
    <th class="group-title">店长公告：</th>
  </tr>
  <tr>
    <td width="100%" style="padding:20px 20px 30px 20px;"><?php echo $this->_var['supplier_notice']; ?></td>    
  </tr>

  <tr>
    <th class="group-title">通知文章：</th>
  </tr>
  <tr>
    <td width="100%" >
		<table cellpadding=1 cellspacing=1 width="100%" bgcolor="#f9fdff">
		<tr><th style="background:#f4f9fc;font-weight:bold;">文章标题</th><th style="background:#f4f9fc;font-weight:bold;">发布时间</th></tr>
		<?php $_from = $this->_var['supplier_article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sarticle');if (count($_from)):
    foreach ($_from AS $this->_var['sarticle']):
?>
		<tr><td><a href="<?php echo $this->_var['sarticle']['url']; ?>" ><?php echo $this->_var['sarticle']['title']; ?></a></td><td align=center> <?php echo $this->_var['sarticle']['formated_addtime']; ?></td></tr>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</table>
	</td>    
  </tr>

</table>
</div>
<!-- end order statistics -->
<br />


<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js')); ?>


<?php echo $this->fetch('pagefooter.htm'); ?>
