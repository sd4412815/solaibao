<!-- $Id: booking_info.htm 16854 2009-12-07 06:20:09Z sxc_shop $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<div class="list-div">
<table width="100%" cellpadding="3" cellspacing="1" >
  <tr>
    <th colspan="4"><?php echo $this->_var['lang']['booking']; ?></th>
  <tr>
  <tr>
    <td align="right" class="first-cell" ><?php echo $this->_var['lang']['user_name']; ?>：</td>
    <td><?php echo htmlspecialchars($this->_var['booking']['user_name']); ?></td>
    <td align="right" class="first-cell" ><?php echo $this->_var['lang']['booking_time']; ?>：</td>
    <td><?php echo $this->_var['booking']['booking_time']; ?></td>
  </tr>
  <tr>
    <td align="right" class="first-cell" ><?php echo $this->_var['lang']['goods_name']; ?>：</td>
    <td><a href="../goods.php?id=<?php echo $this->_var['booking']['goods_id']; ?>" target="_blank" title="<?php echo $this->_var['lang']['view']; ?>"><?php echo $this->_var['booking']['goods_name']; ?></a></td>
    <td align="right" class="first-cell" ><?php echo $this->_var['lang']['number']; ?>：</td>
    <td><?php echo $this->_var['booking']['goods_number']; ?></td>
  </tr>
  <tr>
    <td align="right" class="first-cell" valign="top"><?php echo $this->_var['lang']['desc']; ?>：</td>
    <td colspan="3"><?php echo nl2br(htmlspecialchars($this->_var['booking']['goods_desc'])); ?></td>
  </tr>
  <tr>
    <td align="right" class="first-cell" ><?php echo $this->_var['lang']['link_man']; ?>：</td>
    <td><?php echo $this->_var['booking']['link_man']; ?> <?php if ($this->_var['booking']['email']): ?><<?php echo htmlspecialchars($this->_var['booking']['email']); ?>><?php endif; ?> </td>
    <td align="right" class="first-cell" ><?php echo $this->_var['lang']['tel']; ?>：</td>
    <td><?php echo htmlspecialchars($this->_var['booking']['tel']); ?></td>
  </tr>
  <?php if ($this->_var['booking']['is_dispose']): ?>
  <tr>
    <th colspan="4" ><?php echo $this->_var['lang']['dispose_info']; ?></th>
  </tr>
  <tr>
    <td align="right" class="first-cell"><?php echo $this->_var['lang']['dispose_user']; ?>：</td><td><?php echo $this->_var['booking']['dispose_user']; ?></td>
    <td align="right" class="first-cell"><?php echo $this->_var['lang']['dispose_time']; ?>：</td><td><?php echo $this->_var['booking']['dispose_time']; ?></td>
  </tr>
  <tr>
    <td align="right" class="first-cell"><?php echo $this->_var['lang']['note']; ?>：</td><td colspan="3"><?php echo $this->_var['booking']['dispose_note']; ?></td>
  </tr>
  <?php endif; ?>
</table>
</div>
<br />

<?php if ($this->_var['send_fail']): ?>
<ul style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
<li style="border: 1px solid #CC0000; background: #FFFFCC; padding: 10px; margin-bottom: 5px;" ><?php echo $this->_var['lang']['mail_send_fail']; ?></li>
</ul>
<?php endif; ?>

<div class="form-div">
<form name='theForm' method="post" action="goods_booking.php" onsubmit="return validate()">
  <?php echo $this->_var['lang']['note']; ?>:&nbsp;&nbsp;<input type="text" name="dispose_note" size="55" value="<?php echo $this->_var['booking']['dispose_note']; ?>" />&nbsp;<input type ="hidden" name="act" value="update" /><input type="hidden" name="rec_id" value="<?php echo $this->_var['booking']['rec_id']; ?>" ><input name="send_email_notice" type="checkbox" value='1'/><?php echo $this->_var['lang']['send_email_notice']; ?> <input type="submit" value="<?php echo $this->_var['lang']['i_dispose']; ?>" class="button">
  <?php if ($this->_var['booking']['is_dispose']): ?><input type="submit" name="remail" value="<?php echo $this->_var['lang']['remail']; ?>" class="button"><?php endif; ?>

</form>
</div>

<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,validator.js')); ?>

<script language="JavaScript">
<!--
document.forms['theForm'].elements['dispose_note'].focus();

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
    validator = new Validator("theForm");
    validator.required("dispose_note", no_note);
    return validator.passed();
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>