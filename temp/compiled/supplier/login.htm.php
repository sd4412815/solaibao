<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $this->_var['lang']['cp_home']; ?><?php if ($this->_var['ur_here']): ?> - <?php echo $this->_var['ur_here']; ?><?php endif; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="styles/general.css" rel="stylesheet" type="text/css" />
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
        body {
            color: white;
        }
    </style>
    
    <?php echo $this->smarty_insert_scripts(array('files'=>'../js/jquery-1.9.1.min.js,../js/jquery.json.js')); ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,validator.js')); ?>
    <script language="JavaScript">
        <!--
        // 这里把JS用到的所有语言都赋值到这里
        <?php $_from = $this->_var['lang']['js_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
        var <?php echo $this->_var['key']; ?> = "<?php echo $this->_var['item']; ?>";
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        
        if (window.parent != window)
        {
            window.top.location.href = location.href;
        }
        
        //-->
    </script>
    <style type="text/css">
        /* css 重置 */

        * {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        html,body{height:100%; padding:0;}
        body {
            background: #fff;
            font: normal 12px/22px 宋体;
            position:relative;
            background:#2f4158 url(images/login_bg02.jpg) 50% 50%; background-size: cover;
            background-repeat: no-repeat;
        }
        img {
            border: 0;
        }
        a {
            text-decoration: none;
            color: #333;
        }
        /* 本例子css */
        .slideBox {
            width: 521px;
            height: 70px;
            overflow: hidden;
            margin:0px auto;
            margin-top:20px;
            position: relative; background:url(images/qq.png) no-repeat;
        }
        .slideBox p{position:absolute; right:12px; top:5px; height:55px; line-height:55px; color:#eb8918; width:150px; text-align:left; font-size:20px;}

        .login_box{position:absolute; left:0; top:50%; width:100%;}
        .login_logo{text-align:center; padding-bottom:30px;}
        .login_con{width:422px; margin:-200px auto 0;}
        <?php if ($this->_var['gd_version'] > 0): ?>
        .login_con{margin:-230px auto 0;}
        <?php endif; ?>
        .login_h,.login_c,.login_f{background:url(images/login_bj.png) no-repeat;}
        .login_h,.login_f{height:5px; overflow:hidden;}
        .login_c{background-position:-422px 0; background-repeat:repeat-y; padding:0 51px;}
        .login_input,input.login_input[type='text']{border:1px solid #b1acaa; background:rgba(255,255,255,0.3) !important; filter: Alpha(opacity=30); background:#fff; border:1px solid #afa7a6; width:284px; height:32px; line-height:18px; height:18px; margin:0; outline:0; padding:8px 0 8px 34px; color:#333; font-family:Microsoft Yahei,'微软雅黑';}
        .login_c .label{position:relative; display:block;}
        .login_c .label i{position:absolute; top:11px; left:13px;}
        .login_c .button{background:#4d90fe url(images/login_sub.png) right 0 no-repeat; padding:0 38px 0 14px; color:#fff; height:34px; line-height:34px; border:0; outline:0; margin:0;}
        .login_c .button:hover{background:#326dfe url(images/login_sub.png) right -34px no-repeat;}
        .jizhu_label{position:relative; float:left; color:#7a7a7a; line-height:19px; font-family:Microsoft Yahei,'微软雅黑'; overflow:hidden;}
        .jizhu_label span{width:19px; height:19px; background:url(images/login_checkbox.png) no-repeat; float:left; margin-right:5px; float:left;}
        .jizhu_label span.checkbox{background-position:-19px 0;}
        .jizhu_label input{margin-left:-50px; float:left;}
        .jizhu_label label{float:left; line-height:19px;}
        .gb_version{position:absolute; top:0; right:0; width:120px; height:36px;}
        .login_f{background-position:-844px 0;}
    </style>
</head>
<body>
<div class="login_box">
    <div class="login_con">
        <div class="login_logo"><img src="images/login_logo.png" /></div>
        <div class="login_h"></div>
        <div class="login_c">
            <form method="post" action="privilege.php" name='theForm' onsubmit="return validate()">
                <table cellspacing="0" cellpadding="0" border="0" align="center" width="320">
                    <tr>
                        <td height="90" align="center" valign="middle" style="font-size:25px; color:#333; font-family:'宋体';"><span style="color:red;">搜来宝</span><span style="font-size:14px";>店长管理</span></td>
                    </tr>
                    <tr>
                        <td align="center" >
                            <table>
                                <tr>
                                    <td colspan="2">
                                        <label class="label">
                                            <i><img src="images/login_icon01.png" /></i>
                                            <input type="text" name="username" class="login_input" placeholder="<?php echo $this->_var['lang']['label_username']; ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top:18px;">
                                        <label class="label">
                                            <i><img src="images/login_icon02.png" /></i>
                                            <input type="password" name="password" class="login_input" placeholder="<?php echo $this->_var['lang']['label_password']; ?>"/>
                                        </label>
                                    </td>
                                </tr>
                                <?php if ($this->_var['gd_version'] > 0): ?>
                                <tr>
                                    <td colspan="2" style="padding-top:18px;">
                                        <label class="label">
                                            <i><img src="images/login_icon03.png" /></i>
                                            <input type="text" name="captcha" class="login_input" placeholder="<?php echo $this->_var['lang']['label_captcha']; ?>"/>
                                            <img src="index.php?act=captcha&<?php echo $this->_var['random']; ?>" alt="CAPTCHA" onclick= this.src="index.php?act=captcha&"+Math.random() title="<?php echo $this->_var['lang']['click_for_another']; ?>" class="gb_version" />
                                        </label>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td height="34" colspan="1" align="left" style="padding-top:18px;">
                                        <div class="jizhu_label">
                                            <input type="checkbox" value="1" name="remember" id="jizhu_label"/>
                                            <label for="jizhu_label"><span></span><?php echo $this->_var['lang']['remember']; ?></label>
                                        </div>
                                    </td>
                                    <td height="34" align="right" style="padding-top:18px;"><input type="submit" value="<?php echo $this->_var['lang']['signin_now']; ?>" class="button"/></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="35">&nbsp;</td>
                    </tr>
                </table>
                <input type="hidden" name="act" value="signin" />
            </form>
        </div>
        <div class="login_f"></div>
    </div>
</div>
<script language="JavaScript">
    <!--
    document.forms['theForm'].elements['username'].focus();
    
    /**
     * 检查表单输入的内容
     */
    function validate()
    {
        var validator = new Validator('theForm');
        validator.required('username', user_name_empty);
        //validator.required('password', password_empty);
        if (document.forms['theForm'].elements['captcha'])
        {
            validator.required('captcha', captcha_empty);
        }
        return validator.passed();
    }
    
    //-->
    $(function(){
        function label(){
            var jizhu_label=$('.jizhu_label')
            if(jizhu_label.find('input').is(':checked')){
                jizhu_label.find('span').addClass('checkbox');
            }else{
                jizhu_label.find('span').removeClass('checkbox');
            }
        }
        label();
        $('.jizhu_label').click(label)
    })
</script>
</body>