<!-- $Id: brand_list.htm 15898 2009-05-04 07:25:41Z liuhui $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>
<form method="post" action="" name="listForm">
<!-- start brand list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

  <table cellpadding="3" cellspacing="1">
    <tr>
	 <th><?php echo $this->_var['lang']['type_id']; ?></th>
      <th><?php if ($_REQUEST['in_out'] == 1): ?><?php echo $this->_var['lang']['type_name1']; ?><?php else: ?><?php echo $this->_var['lang']['type_name2']; ?><?php endif; ?></th>     
      <th><?php echo $this->_var['lang']['is_valid']; ?></th>
	  <?php if ($_REQUEST['ismanagestore']): ?>
      <th><?php echo $this->_var['lang']['handler']; ?></th>
	  <?php endif; ?>
    </tr>
    <?php $_from = $this->_var['type_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'type');if (count($_from)):
    foreach ($_from AS $this->_var['type']):
?>
    <tr>
      <td align="center"><?php echo $this->_var['type']['type_id']; ?>
      </td>
      <td><?php echo $this->_var['type']['type_name']; ?></td>
      <td align="center"><?php echo $this->_var['type']['is_valid_val']; ?></td>
	  <?php if ($_REQUEST['ismanagestore']): ?>
      <td align="center">
	    <?php if (! $this->_var['type']['is_noedit']): ?>
        <a href="store_inout_type.php?act=edit&id=<?php echo $this->_var['type']['type_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><?php echo $this->_var['lang']['edit']; ?></a> |
        <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['type']['type_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['edit']; ?>"><?php echo $this->_var['lang']['remove']; ?></a> 
		<?php endif; ?>
      </td>
	  <?php endif; ?>
    </tr>
    <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <tr>
      <td align="right" nowrap="true" colspan="6">
      <?php echo $this->fetch('page.htm'); ?>
      </td>
    </tr>
  </table>

<?php if ($this->_var['full_page']): ?>
<!-- end brand list -->
</div>
</form>

<script type="text/javascript" language="javascript">
  <!--
  listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
  listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

  
  onload = function()
  {
      // 开始检查订单
      startCheckOrder();
  }
  
  //-->
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>