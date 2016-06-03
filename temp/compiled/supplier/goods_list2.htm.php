<!-- $Id: goods_list.htm 17126 2010-04-23 10:30:26Z liuhui $ -->

<?php if ($this->_var['full_page']): ?>
<?php echo $this->fetch('pageheader.htm'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,listtable.js')); ?>
<script type="text/javascript">
    //添加代码 全选 by yuanzb
    function selectAll_yuan(obj, chk)
    {
        if (chk == null)
        {
            chk = 'checkboxes';
        }

        var elems = obj.form.getElementsByTagName("INPUT");

        for (var i=0; i < elems.length; i++)
        {
            if (elems[i].name == chk || elems[i].name == chk + "[]")
            {
                elems[i].checked = obj.checked;
            }
        }

        check_box();
    }

    function check_box() {
        var ele = document.getElementsByName("checkboxes[]");
        var btn = document.getElementById("yuan2");
        var chem = false;
        for(var j=0;j <ele.length;j++){
            if(ele[j].checked == true){
               chem = true;
                break;
            }
        }
        if(chem == true){
            btn.disabled = false;
            btn.className = "button";
        }else{
            btn.disabled = true;
            btn.className = " ";
        }
    }
    //添加代码 全选 by yuanzb
</script>
<!-- 商品搜索 -->
<?php echo $this->fetch('goods_search2.htm'); ?>
<!-- 商品列表 -->
<!-- <form method="post" action="" name="listForm" onsubmit="return confirmSubmit(this)"> -->
<form method="post" action="goods.php?act=batch_copy2myshop&page=<?php echo $this->_var['filter']['page']; ?>" name="listForm">
    <!-- start goods list -->
    <div class="list-div" id="listDiv">
<?php endif; ?>
    <table cellpadding="3" cellspacing="1">
        <tr>
            <th>
              <input onclick='selectAll_yuan(this, "checkboxes")' type="checkbox" />
              <a href="javascript:listTable.sort('goods_id'); "><?php echo $this->_var['lang']['record_id']; ?></a><?php echo $this->_var['sort_goods_id']; ?>
            </th>
            <th><a href="javascript:listTable.sort('goods_name'); "><?php echo $this->_var['lang']['goods_name']; ?></a><?php echo $this->_var['sort_goods_name']; ?></th>
            <th><a href="javascript:listTable.sort('goods_sn'); "><?php echo $this->_var['lang']['goods_sn']; ?></a><?php echo $this->_var['sort_goods_sn']; ?></th>
            <th><a href="javascript:listTable.sort('shop_price'); "><?php echo $this->_var['lang']['shop_price']; ?></a><?php echo $this->_var['sort_shop_price']; ?></th>

            <?php if ($this->_var['use_storage']): ?>
            <th><a href="javascript:listTable.sort('goods_number'); "><?php echo $this->_var['lang']['goods_number']; ?></a><?php echo $this->_var['sort_goods_number']; ?></th>
            <?php endif; ?>
            <!--<th>标签</th> -->
            <th width="120"><?php echo $this->_var['lang']['handler']; ?></th>
        <tr>
      <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
        <tr>
            <td><input type="checkbox" name="checkboxes[]" value="<?php echo $this->_var['goods']['goods_id']; ?>" onchange="check_box()" /><?php echo $this->_var['goods']['goods_id']; ?></td>
            <td class="first-cell" style="<?php if ($this->_var['goods']['is_promote']): ?>color:red;<?php endif; ?>"><span><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></span></td>
            <td><span ><?php echo $this->_var['goods']['goods_sn']; ?></span></td>
            <td align="right"><span><?php echo $this->_var['goods']['shop_price']; ?>

            </span></td>
            <?php if ($this->_var['use_storage']): ?>
            <td align="right"><!-- <span onclick="listTable.edit(this, 'edit_goods_number', <?php echo $this->_var['goods']['goods_id']; ?>)"> --><?php echo $this->_var['goods']['goods_number']; ?><!-- </span> --></td>
            <?php endif; ?>
            <td align="center">

                <a href="<?php if ($this->_var['goods']['is_shangjia'] == 1): ?>#<?php else: ?>goods.php?act=copy2myshop&id=<?php echo $this->_var['goods']['goods_id']; ?><?php endif; ?>">
                    <img src="<?php if ($this->_var['goods']['is_shangjia'] == 1): ?>images/yishangjia.png<?php else: ?>images/shangjia.png<?php endif; ?>" alt="" width="80" style="margin-top: 10px;" >
                </a>
            </td>
        </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</table>
<!-- end goods list -->

<!-- 分页 -->
<table id="page-table" cellspacing="0">
  <tr>
    <td align="right" nowrap="true">


        <?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js')); ?>
        <div id="turn-page">
          <?php echo $this->_var['lang']['total_records']; ?> <span id="totalRecords"><?php echo $this->_var['record_count']; ?></span>
          <?php echo $this->_var['lang']['total_pages']; ?> <span id="totalPages"><?php echo $this->_var['page_count']; ?></span>
          <?php echo $this->_var['lang']['page_current']; ?> <span id="pageCurrent"><?php echo $this->_var['filter']['page']; ?></span>
          <?php echo $this->_var['lang']['page_size']; ?> <input type='text' size='3' id='pageSize' value="<?php echo $this->_var['filter']['page_size']; ?>" onkeypress="return listTable.changePageSize(event)" />
          <span id="page-link">
            <a href="javascript:gotoPageFirst()"><?php echo $this->_var['lang']['page_first']; ?></a>
            <a href="javascript:gotoPagePrev()"><?php echo $this->_var['lang']['page_prev']; ?></a>
            <a href="javascript:gotoPageNext()"><?php echo $this->_var['lang']['page_next']; ?></a>
            <a href="javascript:gotoPageLast()"><?php echo $this->_var['lang']['page_last']; ?></a>
           <!--  <select id="gotoPage" onchange="listTable.gotoPage(this.value)">
              
            </select> -->
          </span>
        </div>


    </td>
  </tr>
</table>

<?php if ($this->_var['full_page']): ?>
</div>

    <div>
        <input style="margin-left: 15px;" onclick='selectAll_yuan(this, "checkboxes")' type="checkbox" />
        <a onclick="selectAll_yuan(this, 'checkboxes')">全选</a>
        <input type="submit" value="批量一键上架" class="" id="yuan2" disabled="true" />

        
        <!--<input type="button" value="导出商品信息" id="btnSubmit2" name="btnSubmit2" class="button" onclick="export_goods();" />-->
        
    </div>
</form>
<script language="JavaScript">
      <!--
      var total_pages = <?php echo $this->_var['filter']['page_count']; ?>;
      var page        = <?php echo $this->_var['filter']['page']; ?>;
      var page_size   = <?php echo $this->_var['filter']['page_size']; ?>;
      var url         = 'goods.php?act=distribution&page='

      
      onload = function()
      {
        var lst = document.getElementById('gotoPage');

        for (i = 1; i <= total_pages; i++)
        {
          var opt = new Option(i, i);
          lst.options.add(opt);

          if (i == page)
          {
            opt.selected = true;
          }
        }
      }

      document.getElementById("pageSize").onkeypress = function(e)
      {
          var evt = Utils.fixEvent(e);
          if (evt.keyCode == 13)
          {
              document.forms['listForm'].submit();
              return false;
          };
      }

      /**
       * 前往第一页
       */
      function gotoPageFirst()
      {
        document.forms['listForm'].elements[page].value = 1;
        var va =document.forms['listForm'].elements[page].value;
        var sa = url + va;
        document.forms['listForm'].setAttribute("action",sa);
        document.forms['listForm'].submit();
      }

      /**
       * 跳转到下一页
       */
        
      function gotoPageNext()
      {
		  

        if (page < total_pages)
        { 
          document.forms['listForm'].elements[page].value = page + 1;
          var va =document.forms['listForm'].elements[page].value;
          var sa = url + va;
		      document.forms['listForm'].setAttribute("action",sa);
          document.forms['listForm'].submit();
        }
      }

      /**
       * 跳转到上一页
       */
      function gotoPagePrev()
      {
        if (page > 1)
        {
          document.forms['listForm'].elements[page].value = page - 1;
          var va =document.forms['listForm'].elements[page].value;
          var sa = url + va;
          document.forms['listForm'].setAttribute("action",sa);
          document.forms['listForm'].submit();
        }
      }

      /**
       * 跳转到最后一页
       */
      function gotoPageLast()
      {
        if (page < total_pages)
        {
          document.forms['listForm'].elements[page].value = total_pages;
          var va =document.forms['listForm'].elements[page].value;
          var sa = url + va;
          document.forms['listForm'].setAttribute("action",sa);
          document.forms['listForm'].submit();
        }
      }
      
      //-->
      </script>
<script type="text/javascript">
//  listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
//  listTable.pageCount = <?php echo $this->_var['page_count']; ?>;
//
//  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
//  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
//  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
//
//  
//  onload = function()
//  {
//    startCheckOrder(); // 开始检查订单
//    document.forms['listForm'].reset();
//  }

  /**
   * @param: bool ext 其他条件：用于转移分类
   */
/*  function confirmSubmit(frm, ext)
  {
      console.log(frm);
      if (frm.elements['type'].value == 'trash')
      {
          return confirm(batch_trash_confirm);
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
			
			<?php if ($this->_var['suppliers_list'] > 0): ?>
      frm.elements['suppliers_id'].style.display = frm.elements['type'].value == 'suppliers_move_to' ? '' : 'none';
			<?php endif; ?>

      if (!document.getElementById('btnSubmit').disabled &&
          confirmSubmit(frm, false))
      {
          frm.submit();
      }
  }

  /!* 代码增加_start   By  yuanzb  *!/
  function export_goods()
  {
        var frm=document.forms['listForm'];
		 frm.elements['act'].value ="export_goods";
	    frm.submit();
  }
  /!* 代码增加_end   By  yuanzb  *!/
*/
</script>
<?php echo $this->fetch('pagefooter.htm'); ?>
<?php endif; ?>