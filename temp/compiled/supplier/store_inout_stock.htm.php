<!-- $Id: article_list.htm 16783 2009-11-09 09:59:06Z liuhui $ -->

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
	//Ajax.call('store_inout_in.php?is_ajax=1&act=search_store_sub', 'parent_id='+pid, get_store_subResponse, 'GET', 'JSON');
 }
 function get_store_subResponse(result)
 {
	
 }
</script>
<div class="form-div">
<table cellpadding=1 cellspacing=5 width="100%">
<tr><td width="80">请选择仓库：</td>
<td id="store_main" align=left >
<a href="store_inout_stock.php?act=list&sid=0" <?php if ($this->_var['filter']['sid'] == '0'): ?>class="store_curr"<?php endif; ?>>全部</a>&nbsp;&nbsp;
<?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
<a href="store_inout_stock.php?act=list&sid=<?php echo $this->_var['store']['store_id']; ?>"  <?php if ($this->_var['filter']['sid'] == $this->_var['store']['store_id']): ?>class="store_curr"<?php endif; ?> ><?php echo $this->_var['store']['store_name']; ?></a>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td>
</tr>
<?php if ($this->_var['showck']): ?>
<tr><td width="80">请选择库房：</td>
<td id="store_sub">
<a href="store_inout_stock.php?act=list&sid=<?php echo $this->_var['filter']['sid']; ?>&ssid=0"  <?php if ($this->_var['filter']['ssid'] == '0'): ?>class="store_curr"<?php endif; ?> >全部</a>&nbsp;&nbsp;
<?php $_from = $this->_var['sub_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');if (count($_from)):
    foreach ($_from AS $this->_var['sub']):
?>
<a href="store_inout_stock.php?act=list&sid=<?php echo $this->_var['filter']['sid']; ?>&ssid=<?php echo $this->_var['sub']['store_id']; ?>" <?php if ($this->_var['filter']['ssid'] == $this->_var['sub']['store_id']): ?>class="store_curr"<?php endif; ?> ><?php echo $this->_var['sub']['store_name']; ?></a>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td>
</tr>
<?php endif; ?>
</table>
</div>


<div class="form-div">
  <form action="javascript:searchStock()" name="searchForm" >
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />  	
	商品货号<input type="text" name="goods_sn" id="goods_sn"  size=15 />
	商品名称<input type="text" name="goods_name" id="goods_name"  />
    <input type="submit" value="<?php echo $this->_var['lang']['button_search']; ?>" class="button" />
  </form>
</div>

<form method="POST" action="store_inout_stock.php" name="listForm">
<!-- start cat list -->
<div class="list-div" id="listDiv">
<?php endif; ?>

<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />商品ID</th>
    <th>商品图片</th>
    <th>商品货号</th>
    <th width="30%">商品名称</th>
    <th>仓库</th>
    <th>属性</th>
    <th>库存</th>
  </tr>
  <?php $_from = $this->_var['stock_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td><input type="checkbox" name="checkboxes[]" value="<?php echo $this->_var['list']['goods_id']; ?>|<?php echo $this->_var['list']['store_id']; ?>" /><?php echo $this->_var['list']['goods_id']; ?></td>
    <td style="padding:5px;text-align:center;">
	<?php if ($this->_var['list']['goods_thumb']): ?><img src="../<?php echo $this->_var['list']['goods_thumb']; ?>" width=40 height=40><?php endif; ?>
	</td>
    <td align="center"><a href="../goods.php?id=<?php echo $this->_var['list']['goods_id']; ?>" target="_blank"><?php echo $this->_var['list']['goods_sn']; ?></a></td>
    <td align="center" style="line-height:18px;"><a href="../goods.php?id=<?php echo $this->_var['list']['goods_id']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></td>
    <td align="center" style="line-height:18px;"><?php echo $this->_var['list']['store_name']; ?></td>
    <td align="center"style="line-height:18px;">
	<?php $_from = $this->_var['list']['attr_stock']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');if (count($_from)):
    foreach ($_from AS $this->_var['attr']):
?>
	<?php echo $this->_var['attr']['goods_attr_name']; ?><br>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</td>
    <td align="center" style="line-height:18px;"><?php $_from = $this->_var['list']['attr_stock']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');if (count($_from)):
    foreach ($_from AS $this->_var['attr']):
?>
	<?php echo $this->_var['attr']['store_number']; ?><br>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?></td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_article']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>&nbsp;
    <td align="right" nowrap="true" colspan="8"><?php echo $this->fetch('page.htm'); ?></td>
  </tr>
</table>
<input type="hidden" name="act" value="export_goods" />

  <input type="button" value="导出库存商品信息" id="btnSubmit2" name="btnSubmit2" class="button" onclick="export_goods();" />(请尽量按仓库来导出)
  
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

 /* 搜索库存 */
 function searchStock()
 {
    listTable.filter.goods_sn = Utils.trim(document.forms['searchForm'].elements['goods_sn'].value);
    listTable.filter.goods_name = Utils.trim(document.forms['searchForm'].elements['goods_name'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }

 /* 代码增加_start   By  morestock_morecity  */
  function export_goods()
  {
        var frm=document.forms['listForm'];
		 frm.elements['act'].value ="export_goods";
	    frm.submit();
  }
  /* 代码增加_end   By  morestock_morecity  */

 
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
