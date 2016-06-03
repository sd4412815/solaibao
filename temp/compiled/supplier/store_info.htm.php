<!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="main-div">
<form method="post" action="store.php" name="theForm"  onsubmit="return validate()">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <td class="label"><?php echo $this->_var['lang']['shipping_select']; ?></td>
    <td>
	<!-- <select name="type_id" size=1> -->
	<!-- <option value="0">请选择</option> -->
	<?php $_from = $this->_var['selectInfo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'info');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['info']):
?>	
	<!-- <option value="<?php echo $this->_var['key']; ?>" ><?php echo $this->_var['info']; ?></option> -->
	<input type="radio" name="type_id" value="<?php echo $this->_var['key']; ?>" /><?php echo $this->_var['info']; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<!-- </select> --><?php echo $this->_var['lang']['require_field']; ?></td>
  </tr>
  <tr>
  <td></td>
  <td><?php echo $this->_var['lang']['shipping_select_desc']; ?></td>
  </tr>

  <tr>
    <td colspan="2" align="center"><br />
      <input type="submit" class="button" value="<?php echo $this->_var['lang']['button_submit']; ?>" />
      <input type="reset" class="button" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
      <input type="hidden" name="act" value="insert" />
    </td>
  </tr>
</table>
</form>
</div>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js')); ?>

<script language="JavaScript">
<!--
onload = function()
{
    // 开始检查订单
    startCheckOrder();
}
/**
 * 检查表单输入的数据
 */
function validate()
{
	var objects = document.forms["theForm"].elements['type_id'];
	var checked = false;

    for (var i = 0; i < objects.length; i ++ )
    {
      if (objects[i].type.toLowerCase() != "radio") continue;
      if (objects[i].checked)
      {
        checked = true;
        break;
      }
    }

    if ( ! checked){
		alert('您必须选择一个仓库类型！');
	}
	return checked;
    
}

//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>