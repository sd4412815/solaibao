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
<a href="store_inout_goods.php?act=list&sid=0" <?php if ($this->_var['filter']['sid'] == '0'): ?>class="store_curr"<?php endif; ?>>全部</a>&nbsp;&nbsp;
<?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
<a href="store_inout_goods.php?act=list&sid=<?php echo $this->_var['store']['store_id']; ?>"  <?php if ($this->_var['filter']['sid'] == $this->_var['store']['store_id']): ?>class="store_curr"<?php endif; ?> ><?php echo $this->_var['store']['store_name']; ?></a>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td>
</tr>
<?php if ($this->_var['showck']): ?>
<tr><td width="80">请选择库房：</td>
<td id="store_sub">
<a href="store_inout_goods.php?act=list&sid=<?php echo $this->_var['filter']['sid']; ?>&ssid=0"  <?php if ($this->_var['filter']['ssid'] == '0'): ?>class="store_curr"<?php endif; ?> >全部</a>&nbsp;&nbsp;
<?php $_from = $this->_var['sub_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sub');if (count($_from)):
    foreach ($_from AS $this->_var['sub']):
?>
<a href="store_inout_goods.php?act=list&sid=<?php echo $this->_var['filter']['sid']; ?>&ssid=<?php echo $this->_var['sub']['store_id']; ?>" <?php if ($this->_var['filter']['ssid'] == $this->_var['sub']['store_id']): ?>class="store_curr"<?php endif; ?> ><?php echo $this->_var['sub']['store_name']; ?></a>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</td>
</tr>
<?php endif; ?>
</table>
</div>


<div class="form-div">
  <form action="javascript:searchGoods()" name="searchForm" >
    <img src="images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
    <select name="inout_mode" onchange="get_inout_type(this, 'inout_type_box')" >
    <option value="0">出库和入库</option>
	<option value="1">出库</option>
	<option value="2">入库</option>
    </select>
	<select name="inout_type" id="inout_type_box">
      <option value="0">出入库类型</option>
    </select>
	日期<input type="text" name="add_time1" id="add_time1" size=16 readonly="readonly"   /><input name="selbtn1" type="button" id="selbtn1" onclick="return showCalendar('add_time1', '%Y-%m-%d %H:%M', '24', false, 'selbtn1');" value="<?php echo $this->_var['lang']['btn_select']; ?>" class="button"/>至<input type="text" name="add_time2" id="add_time2"  size=16 readonly="readonly" /><input name="selbtn2" type="button" id="selbtn2" onclick="return showCalendar('add_time2', '%Y-%m-%d %H:%M', '24', false, 'selbtn2');" value="<?php echo $this->_var['lang']['btn_select']; ?>" class="button"/>
	<select name="brand">
	<option value="0">请选择品牌</option>
	<?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['brand']):
?>
	<option value="<?php echo $this->_var['brand']['brand_id']; ?>"><?php echo $this->_var['brand']['brand_name']; ?></option>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</select>
	商品货号<input type="text" name="goods_sn" id="goods_sn"  size=15 />
	商品名称<input type="text" name="goods_name" id="goods_name"  />
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
    <th>商品图片</th>
    <th>商品货号</th>
    <th>商品名称</th>
    <th>商品属性</th>
    <th>库房名称</th>
    <th>出入库类型</th>
	<th>出入库单号</th>
	<th>关联定单号</th>
	<th>提/交货人</th>
	<th>出入库日期</th>
	<th>出入库数</th>
	<th>库存数量</th>
  </tr>
  <?php $_from = $this->_var['inout_goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
  <tr>
    <td><?php echo $this->_var['list']['book_id']; ?></td>
	<td style="padding:5px;text-align:center;"><?php if ($this->_var['list']['goods_thumb']): ?><img src="../<?php echo $this->_var['list']['goods_thumb']; ?>" width=50 height=50><?php endif; ?></td>
    <td class="first-cell"><a href="../goods.php?id=<?php echo $this->_var['list']['goods_id']; ?>" target="_blank"><?php echo $this->_var['list']['goods_sn']; ?></a></td>
    <td align="left" style="width:23%;line-height:18px;"><?php echo $this->_var['list']['goods_name']; ?></td>
    <td align="center" style="line-height:18px;"><?php echo $this->_var['list']['goods_attr_name']; ?></td>
    <td align="center" style="line-height:18px;" ><?php echo $this->_var['list']['store_name']; ?></td>
    <td align="center" style="line-height:18px;"><?php echo $this->_var['list']['inout_type_name']; ?></td>
	<td style="line-height:18px;"><?php echo $this->_var['list']['inout_sn']; ?></td>	
	<td style="line-height:18px;"><?php echo $this->_var['list']['order_sn']; ?></td>
	<td style="line-height:18px;"><?php echo $this->_var['list']['takegoods_man']; ?></td>
	<td style="line-height:18px;"><?php echo $this->_var['list']['add_time']; ?></td>
	<td style="font-weight:bold;color:<?php if ($this->_var['list']['inout_mode'] == '1'): ?>#ff3300<?php else: ?>#0000ff<?php endif; ?>;"><?php if ($this->_var['list']['inout_mode'] == 1): ?>-<?php endif; ?><?php echo $this->_var['list']['number_shishou']; ?></td>
    <td align="center" ><?php echo $this->_var['list']['number_stock']; ?></td>
   </tr>
   <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="13"><?php echo $this->_var['lang']['no_article']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
  <tr>&nbsp;
    <td align="right" nowrap="true" colspan="13"><?php echo $this->fetch('page.htm'); ?></td>
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

 /* 搜索序时簿 */
 function searchGoods()
 {
    listTable.filter.inout_mode = parseInt(document.forms['searchForm'].elements['inout_mode'].value);
	listTable.filter.inout_type = parseInt(document.forms['searchForm'].elements['inout_type'].value);
	listTable.filter.goods_sn = Utils.trim(document.forms['searchForm'].elements['goods_sn'].value);
	listTable.filter.goods_name = Utils.trim(document.forms['searchForm'].elements['goods_name'].value);
	listTable.filter.add_time1 = Utils.trim(document.forms['searchForm'].elements['add_time1'].value);
	listTable.filter.add_time2 = Utils.trim(document.forms['searchForm'].elements['add_time2'].value);
    listTable.filter.brand = parseInt(document.forms['searchForm'].elements['brand'].value);
    listTable.filter.page = 1;
    listTable.loadList();
 }

 /* 下拉联动出入库类型 */
 function get_inout_type(obj, target)
 {
		var inout_mode = obj.options[obj.selectedIndex].value;
		Ajax.call('store_inout_goods.php?act=get_inout_type', "target="+ target+"&inout_mode=" + inout_mode , get_inouttype_ecshop120_response, "GET", "JSON");
  }
  function get_inouttype_ecshop120_response(result)
  {
		var sel = document.getElementById(result.target);
		sel.length = 0;
		var opt = document.createElement("OPTION");
		opt.value = '0';
		opt.text  = result.inout_mode;
		sel.options.add(opt);
		if (result.type_list)
		{
			for (i = 0; i < result.type_list.length; i ++ )
			{
				var opt = document.createElement("OPTION");
				opt.value = result.type_list[i].type_id;
				opt.text  = result.type_list[i].type_name;
				sel.options.add(opt);
			}
		}
		sel.selectedIndex = 0;
  }



 
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>
