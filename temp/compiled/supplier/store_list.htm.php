<!-- $Id: area_list.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>

<?php if ($this->_var['parent_id'] == 0): ?>
<div class="form-div">
<form method="post" action="store_manage.php" name="soForm" >
<?php echo $this->_var['lang']['label_search']; ?>
<input type="text" name="keyword" maxlength="150" size="40" value="<?php echo $_REQUEST['keyword']; ?>" />
<input type="hidden" name="act" value="list" />
<input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" />
</form>
</div>
<?php endif; ?>


<?php if ($_REQUEST['ismanagestore']): ?>
<div class="form-div">
<form method="post" action="store_manage.php" name="theForm" onsubmit="return add_store()">
<?php if ($this->_var['parent_id']): ?><?php echo $this->_var['lang']['add_store_sub']; ?><?php else: ?><?php echo $this->_var['lang']['add_store_main']; ?><?php endif; ?>
<input type="text" name="store_name" maxlength="150" size="40" />
<input type="hidden" name="parent_id" value="<?php echo $this->_var['parent_id']; ?>" />
<input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />
</form>
</div>
<?php endif; ?>

<!-- start category list -->
<div class="list-div">
<table cellspacing='1' cellpadding='3' id='listTable'>
  <tr>
    <th><?php echo $this->_var['lang']['store_main_list']; ?></th>
  </tr>
</table>
</div>
<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='listTable'>
  <tr>
    <?php $_from = $this->_var['store_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['store_name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['store_name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['store_name']['iteration']++;
?>
      <?php if ($this->_foreach['store_name']['iteration'] > 1 && ( $this->_foreach['store_name']['iteration'] - 1 ) % 3 == 0): ?>
      </tr><tr>
      <?php endif; ?>
      <td class="first-cell" align="left">
	  <table width="100%" cellpadding=0 cellspacing=0 border=0>
	  <tr><td width="30%" align=right style="padding-right:30px;font-weight:bold;">
	  <?php if ($_REQUEST['ismanagestore']): ?>
       <span onclick="listTable.edit(this, 'edit_store_name', '<?php echo $this->_var['list']['store_id']; ?>'); return false;"><?php echo htmlspecialchars($this->_var['list']['store_name']); ?></span>
	   <?php else: ?>
	   <span><?php echo htmlspecialchars($this->_var['list']['store_name']); ?></span>
	   <?php endif; ?>
	   </td>
	   <td width="70%">
	   <?php if ($_REQUEST['ismanagestore']): ?>
       <span class="link-span">
       <a href="store_manage.php?act=shipping_area&id=<?php echo $this->_var['list']['store_id']; ?>" title="<?php echo $this->_var['lang']['manage_area']; ?>"><?php echo $this->_var['lang']['manage_area']; ?></a>&nbsp;&nbsp;
	   <a href="store_manage.php?act=list_sub&pid=<?php echo $this->_var['list']['store_id']; ?>" title="<?php echo $this->_var['lang']['manage_area']; ?>"><?php echo $this->_var['lang']['manage_sub']; ?></a>&nbsp;&nbsp;
	   <a href="store_manage.php?act=store_set_adminer&id=<?php echo $this->_var['list']['store_id']; ?>" title="<?php echo $this->_var['lang']['manage_area']; ?>"><?php echo $this->_var['lang']['manage_zhuguan']; ?></a>&nbsp;&nbsp;
       <a href="store_manage.php?act=store_remove&id=<?php echo $this->_var['list']['store_id']; ?>" onclick="return confirm('确认要删除吗')"  title="<?php echo $this->_var['lang']['drop']; ?>"><?php echo $this->_var['lang']['drop']; ?></a>
       </span>
	   <?php endif; ?>
	   </td>
	   </tr></table>
      </td>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>


<script language="JavaScript">
<!--

onload = function() {
  // 开始检查订单
  startCheckOrder();
}

/**
 * 新建区域
 */
function add_store()
{
    var store_name = Utils.trim(document.forms['theForm'].elements['store_name'].value);
    var parent_id   = Utils.trim(document.forms['theForm'].elements['parent_id'].value);

    if (store_name.length == 0)
    {
        alert(store_name_empty);
    }
    else
    {
      Ajax.call('store_manage.php?is_ajax=1&act=add_store',
        'parent_id=' + parent_id + '&store_name=' + store_name,
        listTable.listCallback, 'POST', 'JSON');
    }

    return false;
}

//-->
</script>


<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>