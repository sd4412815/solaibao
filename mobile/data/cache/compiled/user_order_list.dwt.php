<?php echo $this->fetch('library/user_header.lbi'); ?>
<style>

	.act:active{text-decoration:underline; font-weight:bold;}
</style> 
<section class="content" style="padding: 0;">
<?php if ($this->_var['show_asynclist']): ?>
<div class="ect-pro-list user-order" style="border-bottom:none;">
    <ul id="J_ItemList">
       <li class="single_item"></li>
       <a href="javascript:;" style="text-align:center" class="get_more"></a>
    </ul>
</div>
<?php else: ?>

    <?php $_from = $this->_var['orders_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'orders');$this->_foreach['orders_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['orders_list']['total'] > 0):
    foreach ($_from AS $this->_var['orders']):
        $this->_foreach['orders_list']['iteration']++;
?>

        <div class="box box-danger box-solid" style="margin:0;padding:0;margin-bottom: 5px;margin-top: 5px; border:#ccc 1px solid;">
            <div class="box-header with-border" style="background-color:#ddd;">
                <a href="<?php echo url('user/order_detail', array('order_id'=>$this->_var['orders']['order_id']));?>" style="color:#666;" class="act">
                    <h4 style="font-size: 12px;" class="box-title">
                        <?php echo $this->_var['lang']['order_number']; ?>：<?php echo $this->_var['orders']['order_sn']; ?>
                    </h4>
                </a>
                <div class="box-tools pull-right" style="line-height: 41px; color:#666; font-size:14px;">
                    <?php echo $this->_var['orders']['order_status']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php echo url('user/order_detail', array('order_id'=>$this->_var['orders']['order_id']));?>" style="color:#666; font-size:14px;" class="act">
                        查看订单
                    </a>
                </div>
            </div>
            <div class="box-body">
                <ul class="nav nav-stacked">
                    <?php $_from = $this->_var['orders']['goods_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['orders'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['orders']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['orders']['iteration']++;
?>
                        <li class="row" style="margin: 0;padding: 15px 0 0 0;">
                            <img class="col-xs-4" style="width: 80px;" src="<?php echo $this->_var['path']; ?>/<?php echo $this->_var['goods']['goods_thumb']; ?>" alt="">
                            <p class="col-xs-8" style="width:40%;">
                                <a href="<?php echo url('goods/index',array('id'=>$this->_var['goods']['goods_id']));?>"><?php echo sub_str($this->_var['goods']['goods_name'],15); ?></a>
                            </p>
                            <?php if ($this->_var['orders']['order_status'] == '已完成'): ?>
                            <p class="col-xs-2"  style="margin-right: 20px; padding:0;">
                                <?php if ($this->_var['goods']['is_comment'] == 0): ?>
                                <a class="btn btn-app" href="<?php echo url('comment/comment',array('oid'=>$this->_var['orders']['order_id'],'gid'=>$this->_var['goods']['goods_id']));?>">
                                    <i class="fa fa-edit"></i>
                                    去评价
                                </a>
                                <?php else: ?>

                                    <i class="fa fa-check-circle-o"></i>
                                    已评价

                                <?php endif; ?>
                            </p>
                            <?php else: ?>
                            &nbsp;
                            <?php endif; ?>

                        </li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
        </div>

    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<!--	<div class="" style="border-bottom:none;">
		<ul id="J_ItemList">
		 &lt;!&ndash;<?php $_from = $this->_var['orders_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'orders');$this->_foreach['orders_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['orders_list']['total'] > 0):
    foreach ($_from AS $this->_var['orders']):
        $this->_foreach['orders_list']['iteration']++;
?>&ndash;&gt;
		 <a href="<?php echo url('user/order_detail', array('order_id'=>$this->_var['orders']['order_id']));?>">
			<li>
			<dl class="all-order">
			  <dt>
				<h4 class="title"><?php echo $this->_var['lang']['order_number']; ?>：<?php echo $this->_var['orders']['order_sn']; ?></h4>
			  </dt>
			  <dd><?php echo $this->_var['lang']['order_status']; ?>：<?php echo $this->_var['orders']['order_status']; ?><?php if ($this->_var['orders']['order_status'] == '已完成'): ?>去评价<?php else: ?>&nbsp;<?php endif; ?></dd>
			  <dd><?php echo $this->_var['lang']['order_total_fee']; ?>：<span class="ect-color"><?php echo $this->_var['orders']['total_fee']; ?></span></dd>
			  <dd><?php echo $this->_var['lang']['order_addtime']; ?>：<?php echo $this->_var['orders']['order_time']; ?></dd>
			</dl>
			<i class="pull-right fa fa-angle-right"></i> 
			</li>
		</a> 
		&lt;!&ndash;<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>&ndash;&gt;
		</ul>
	</div>-->
</section>
 <?php echo $this->fetch('library/page.lbi'); ?>
<?php endif; ?>
<!--</div>-->

<?php echo $this->fetch('library/search.lbi'); ?>
<?php echo $this->fetch('library/page_footer.lbi'); ?>
<script type="text/javascript">
<?php $_from = $this->_var['lang']['merge_order_js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
    var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
if(<?php echo $this->_var['show_asynclist']; ?>){
get_asynclist('index.php?m=default&c=user&a=async_order_list&pay=<?php echo $this->_var['pay']; ?>' , '__TPL__/images/loader.gif');
}
</script> 
</body></html>