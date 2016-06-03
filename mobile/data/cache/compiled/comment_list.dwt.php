<?php echo $this->fetch('library/page_header.lbi'); ?>
<?php echo $this->fetch('library/search.lbi'); ?> 
<div class="con">
  <div style="height:4.3em;"></div>
  <header>
    <nav class="ect-nav ect-bg icon-write"> <?php echo $this->fetch('library/page_menu.lbi'); ?> </nav>
  </header>

<div class="row">




    <div >
        <ul>
            <li class="list-group-item box box-warning box-solid tab-content" style="margin: -1.1px; padding:0px;">
                   <div class="row">
                    <ul>
                        <?php $_from = $this->_var['comment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_list');if (count($_from)):
    foreach ($_from AS $this->_var['goods_list']):
?>

                        <li style="<?php if ($this->_var['goods_list'] > 1): ?>border-bottom: 1px solid #aaa;<?php else: ?>border:0px;<?php endif; ?> border-top:1px solid #fff; height:118px; width:640px;margin-left: 14px;">
                            <div class="col-sm-4" style="float:left;width:90px;height:80px; position: absolute;margin:7px;margin-top:15px;z-index: 11">
                                <a href="index.php?m=default&c=goods&a=index&id=<?php echo $this->_var['goods_list']['goods_id']; ?>">
                                    <img style="width: 80px;height: 80px; " src="<?php echo $this->_var['path']; ?>/<?php echo $this->_var['goods_list']['goods_thumb']; ?>" alt="">
                                </a>
                            </div>
                            <div class="col-sm-8" style="width:640px;height:110px;">
                                <dl>
                                    <a href="index.php?m=default&c=goods&a=index&id=<?php echo $this->_var['goods_list']['goods_id']; ?>">
                                        <dd style="width:20%;height:15%; position: absolute;margin-left:100px;margin-top:30px; "><?php if (strlen ( $this->_var['goods_list']['goods_name'] ) > 10): ?><?php echo sub_str($this->_var['goods_list']['goods_name'],10); ?><?php else: ?><?php echo $this->_var['goods_list']['goods_name']; ?><?php endif; ?></dd><br><br>
                                    </a>
                                        <dd style="width:20% ;height:15%;position: absolute;margin-left:100px;margin-top:30px; ">￥<?php echo $this->_var['goods_list']['shop_price']; ?>元.</dd>

                                </dl>
                                <dl>


                                    <dt style="margin-left:220px;"><?php if (! empty ( $this->_var['goods_list']['content'] )): ?>评论：<?php else: ?>&nbsp;<?php endif; ?><?php if (strlen ( $this->_var['goods_list']['content'] ) > 4): ?><?php echo sub_str($this->_var['goods_list']['content'],4); ?><?php else: ?><?php echo $this->_var['goods_list']['content']; ?><?php endif; ?></dt>

                                    <a href="index.php?m=default&c=goods&a=comment_list&id=<?php echo $this->_var['goods_list']['goods_id']; ?>" style="margin-left:240px;">点击查看详情</a>
                                </dl>
                                <p class="pull-right" >


                                </p>
                            </div>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
            </li>
        </ul>

    </div>


</div>
<style>
textarea
{
width:100%;
height:80%;
overflow-y:auto;
}

</style>
<form id='comment_a' name="theForm" action="<?php echo url('comment/insert_comment');?>" method="post">
    <div class='collapse' id="collapseExample" style='position:absolute;top: 0px;left: 0px;z-index: 1000; '>
        <div id='con'  class="well" style='height:370px;width:100%; display:none; border:3px solid #ccc; padding-top:-40px; z-index: 1000;'>
            <button type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="close" aria-label="Close"><span aria-hidden="true"> <i class="glyphicon glyphicon-remove"></i></span></button>
            <textarea name='content' id='' cols='44' placeholder='例如：非常棒,非常好,又或者是不买忍不住！'rows='10'></textarea>
            <div>
            <!-- <input type="text"> -->
            <input type="hidden" id="comment_c" name="goodsid" value="">
            <input type="hidden" id="comment_d" name="orderid" value="">
                <input style='font-size:22px;background:#e95466;margin-left:112px; border-radius:4px; padding:6px 10px;'value="提交评价" type="submit"> 
            </div>
        </div>
    </div>
</form>



<?php echo $this->fetch('library/page_footer.lbi'); ?>

<script type="text/javascript">
$('#close').click(function(){
        $('#con').css('display','none');
    });

function comment_y(goods_id,order_id){
    // alert(goods_id);
    // alert(order_id);
$('#con').css('display','block');
// var url='index.php?m=default&c=comment&a=insert_comment&id='+id;
// $('#comment_a').attr('action',url);
$('#comment_c').attr('value',goods_id);
$('#comment_d').attr('value',order_id);





//     var gid =id;
//   layer.open({
//     type: 1,
//     content: "<div id='con' style='height:370px;width:320px; border:3px solid #ccc; padding-top:-40px;'><a href='javascript:;' style='width:30px;height:30px;padding-left:10px; padding-top:-10px; float:right;border-radius:90%; background:#aaa;' onclick='comment_X();'>×</a><textarea name='' id='' cols='44' placeholder='例如：非常棒！'rows='10'></textarea><div><a href='index.php?m=default&c=comment&comment_list&id=' style='font-size:22px;background:#e95466;margin-left:112px; border-radius:4px; padding:6px 10px;'> <b style='height:100px;width:200px;font-weight:normal; color:#fff; '>提交评价</b></a></div></div>",
//     anim: 0,
//     fixed:true,
//     style: 'position:fixed; top:0;  left:0; width:100%; height:300px; padding-top:220px; border:none; '
// });  
}

function comment_X(){
 // layer.closeAll();


}
  
</script>
</body>
</html>