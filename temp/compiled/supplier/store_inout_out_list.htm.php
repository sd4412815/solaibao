<!-- $Id: article_list.htm 16783 2009-11-09 09:59:06Z liuhui $ -->
<div id="bg" class="bg" style="display:none;"></div>
<?php echo $this->fetch('store_inout_notice.htm'); ?>

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>
<script type="text/javascript" src="../js/calendar.php?lang=<?php echo $this->_var['cfg_lang']; ?>"></script>
<link href="../js/calendar/calendar.css" rel="stylesheet" type="text/css" />
<style>
a.store_curr{color:#f30;}
</style>
<script type="text/javascript">
 function get_store_sub(obj, pid)
 {
	 var store_main=document.getElementById('store_main');
	 var store_main_list = store_main.getElementsByTagName('a');
	 for(i=0;i<store_main_list.length;i++)
	 {
		store_main_list[i].className='';
	 }
	 obj.className='store_curr';
	//Ajax.call('store_inout_out.php?is_ajax=1&act=search_store_sub', 'parent_id='+pid, get_store_subResponse, 'GET', 'JSON');
 }
 function get_store_subResponse(result)
 {
	
 }
</script>
<div class="form-div">
<table cellpadding=1 cellspacing=5 width="100%">
<tr><td width="80">请选择仓库：</td>
<td id="store_main" align=left >
<a href="store_inout_out.php?act=list&sid=0" <?php if ($this->_var['filter']['sid'] == '0'): ?>class="store_curr"<?php endif; ?>>全部</a>&nbsp;&nbsp;
<?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
<a href="store_inout_out.php?act=list&sid=<?php echo $this->_var['store']['store_id']; ?>" <?php if ($this->_var['filter']['sid'] == $this->_var['store']['store_id']): ?>class="store_curr"<?php endif; ?>><?php echo $this->_var['store']['store_name']; ?></a>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td>
</tr>
<?php if ($this->_var['showck']): ?>
<tr><td width="80">请选择库房：</td>
<td id="store_sub">
<a href="store_inout_out.php?act=list&sid=<?php echo $this->_var['filter']['sid']; ?>&ssid=0"  <?php if ($this->_var['filter']['ssid'] == '0'): ?>class="store_curr"<?php endif; ?>>全部</a>&nbsp;&nbsp;
<?php $_from = $this->_var['sub_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');if (count($_from)):
    foreach ($_from AS $this->_var['sub']):
?>
<a href="store_inout_out.php?act=list&sid=<?php echo $this->_var['filter']['sid']; ?>&ssid=<?php echo $this->_var['sub']['store_id']; ?>" <?php if ($this->_var['filter']['ssid'] == $this->_var['sub']['store_id']): ?>class="store_curr"<?php endif; ?> ><?php echo $this->_var['sub']['store_name']; ?></a>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td>
</tr>
<?php endif; ?>
</table>
</div>


<div class="form-div">
  <form action="javascript:searchList()" name="searchForm" >
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    <select name="inout_status" >
      <option value="0">出库单状态</option>
       <?php $_from = $this->_var['lang']['inout_status']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('status_key', 'status_val');if (count($_from)):
    foreach ($_from AS $this->_var['status_key'] => $this->_var['status_val']):
?>
	   <option value="<?php echo $this->_var['status_key']; ?>"><?php echo $this->_var['status_val']; ?></option>
	   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
	<select name="inout_type" >
      <option value="0">出库类型</option>
	  <?php $_from = $this->_var['inout_type_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'type_val');if (count($_from)):
    foreach ($_from AS $this->_var['type_val']):
?>
	   <option value="<?php echo $this->_var['type_val']['type_id']; ?>"><?php echo $this->_var['type_val']['type_name']; ?></option>
	   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </select>
	出库日期<input type="text" name="add_time1" id="add_time1" size=16 readonly="readonly"   /><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('add_time1', '%Y-%m-%d %H:%M', '24', false, 'selbtn1');" value="<?php echo $this->_var['lang']['btn_select']; ?>" class="button"/>至<input type="text" name="add_time2" id="add_time2"  size=16 /><input name="selbtn2" type="button" id="selbtn2" onclick="return showCalendar('add_time2', '%Y-%m-%d %H:%M', '24', false, 'selbtn2');" value="<?php echo $this->_var['lang']['btn_select']; ?>" class="button"/>
	出库单号<input type="text" name="inout_sn" id="inout_sn" />
	提货人<input type="text" name="takegoods_man" id="takegoods_man"  size=15 />
    <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<form method="POST" action="store_inout_list.php?act=batch_remove" name="listForm">
<!-- start cat list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php echo $this->_var['lang']['rec_id']; ?></th>
    <th><?php echo $this->_var['lang']['inout_sn_in']; ?></th>
    <th><?php echo $this->_var['lang']['store_name_in']; ?></th>
	<th>库房仓管</th>
    <th><?php echo $this->_var['lang']['inout_type_in']; ?></th>
    <th><?php echo $this->_var['lang']['order_sn']; ?></th>
	<th>提货人</th>
    <th>出库日期</th>
	<th>出库单状态</th>
    <th><?php echo $this->_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = $this->_var['inout_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td><?php echo $this->_var['list']['rec_id']; ?></td>
    <td class="first-cell"><?php echo htmlspecialchars($this->_var['list']['inout_sn']); ?></td>
    <td align="center" style="padding:5px;line-height:18px;"><?php echo $this->_var['list']['store_name']; ?></td>
	<td><?php echo $this->_var['list']['admin_name']; ?></td>
    <td align="center"><?php echo $this->_var['list']['inout_type_name']; ?></td>
    <td align="center"><?php echo $this->_var['list']['order_sn']; ?></td>
    <td align="center"><?php echo $this->_var['list']['takegoods_man']; ?></td>
	<td align="center"><?php echo $this->_var['list']['add_time']; ?></td>
	<td align="center"><?php echo $this->_var['list']['inout_status_name']; ?></td>
    <td align="center" style="padding:5px;line-height:18px;">
	   <a href="store_inout_out.php?act=view&id=<?php echo $this->_var['list']['rec_id']; ?>">查看</a>&nbsp;&nbsp;
	   <?php if ($this->_var['list']['inout_status'] == '3'): ?>
	   <!-- <a href="javascript:showDiv(<?php echo $this->_var['list']['rec_id']; ?>, '3', '审核备注');">审核备注</a>&nbsp;&nbsp; -->
	   <?php endif; ?>
      <?php if ($this->_var['list']['inout_status'] == '2'): ?>
	  <a href="store_inout_out.php?act=check&id=<?php echo $this->_var['list']['rec_id']; ?>">审核</a>&nbsp;&nbsp;
	  <a href="javascript:showDiv(<?php echo $this->_var['list']['rec_id']; ?>, '3', '审核备注');">快速审核</a>&nbsp;&nbsp;
	  <a href="javascript:showDiv(<?php echo $this->_var['list']['rec_id']; ?>, '1', '退回');">退回</a>&nbsp;&nbsp;
	  <?php endif; ?>
	  <?php if ($this->_var['list']['inout_status'] == '1'): ?>
	  <a href="store_inout_out.php?act=edit&id=<?php echo $this->_var['list']['rec_id']; ?>">编辑</a>&nbsp;&nbsp;
	  <a href="store_inout_out.php?act=remove&id=<?php echo $this->_var['list']['rec_id']; ?>" onclick="return confirm('你确认要删除吗？')" >删除</a>&nbsp;&nbsp;
	  <?php if ($this->_var['list']['tjsq']): ?><a href="javascript:showDiv(<?php echo $this->_var['list']['rec_id']; ?>, '2', '提交审核');">提交审核</a><?php endif; ?>
	  <?php endif; ?>
    </td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_article']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>&nbsp;
    <td align="right" nowrap="true" colspan="20"><?php echo $this->fetch('page.htm'); ?></td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>


</form>
<!-- end cat list -->
<script type="text/javascript" language="JavaScript">
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
	/**
   * @param: bool ext 其他条件：用于转移分类
   */
  function confirmSubmit(frm, ext)
  {
      if (frm.elements['type'].value == 'button_remove')
      {
          return confirm(drop_confirm);
      }
      else if (frm.elements['type'].value == 'not_on_sale')
      {
          return confirm(batch_no_on_sale);
      }
      else if (frm.elements['type'].value == 'move_to')
      {
          ext = (ext == undefined) ? true : ext;
          return ext && frm.elements['target_cat'].value != 0;
      }
      else if (frm.elements['type'].value == '')
      {
          return false;
      }
      else
      {
          return true;
      }
  }
	 function changeAction()
  {
		
      var frm = document.forms['listForm'];

      // 切换分类列表的显示
      frm.elements['target_cat'].style.display = frm.elements['type'].value == 'move_to' ? '' : 'none';

      if (!document.getElementById('btnSubmit').disabled &&
          confirmSubmit(frm, false))
      {
          frm.submit();
      }
  }

 /* 搜索入库单 */
 function searchList()
 {    
    listTable.filter.inout_status = parseInt(document.forms['searchForm'].elements['inout_status'].value);
	listTable.filter.inout_type = parseInt(document.forms['searchForm'].elements['inout_type'].value);
	listTable.filter.add_time1 = Utils.trim(document.forms['searchForm'].elements['add_time1'].value);
	listTable.filter.add_time2 = Utils.trim(document.forms['searchForm'].elements['add_time2'].value);
	listTable.filter.inout_sn = Utils.trim(document.forms['searchForm'].elements['inout_sn'].value);
	listTable.filter.takegoods_man = Utils.trim(document.forms['searchForm'].elements['takegoods_man'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }



 
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
