<?php

/**
 * User: Yuan
 * Date: 2016-05-17
 * Time: 17:17
 */
defined('IN_ECTOUCH') or die('Deny Access');

class ErrorController extends CommonController
{
    public function index(){
        $now_error=self::getErrorInfo(2,10);
        $not_error=self::getErrorInfo(1);
        $error=self::getErrorInfo2(8);

        $user=M()->table('error_user','qw')->select();
        $this->assign('users',$user);
        $this->assign('now_error',$now_error);
        $this->assign('not_error',$not_error);
        $this->assign('error',$error);


        $this->display('error.dwt');
    }


    public function info()
    {
        $id=$_GET['id'];
        $sql="SELECT el.* ,eu.name AS user FROM error_log AS el LEFT JOIN error_user AS eu ON el.user_id=eu.id Where el.id=".$id;
        $res=M()->query($sql);

        $this->assign('error_info',$res[0]);
        $this->display('error_info.dwt');
    }

    public function add()
    {
        if(IS_POST){
            $error_info['title']=trim($_POST['title']);
            $error_info['soft']=(int)trim($_POST['soft']);
            $error_info['froms']=trim($_POST['froms']);
            $error_info['info']=trim($_POST['info']);
            $error_info['add_time']=date('Y-m-d H:i:s',time());
            $error_info['last_time']=date('Y-m-d H:i:s',time());

            $sql="INSERT INTO error_log (title,info,add_time,last_time,soft,froms) VALUES ('".$error_info['title']."','".$error_info['info']."','".$error_info['add_time']."','".$error_info['last_time']."','".$error_info['soft']."','".$error_info['froms']."')";
            $res=M()->query($sql);
            if($res){
                echo '添加成功';
            }
        header('Location:index.php?m=default&c=error&a=index');
        }
        $this->assign('sub','添加');
        $this->display('error_add.dwt');
    }
    

    public function del()
    {
        $id=$_POST['id'];
        $sql="DELETE FROM error_log WHERE id=".$id;
        $res=M()->query($sql);
    }
    

    public function edit()
    {
        $id=$_GET['id'];
        $sql="SELECT * FROM error_log WHERE id=".$id;
        $info=model('users')->row($sql);
        $this->assign('error',$info);
        $this->assign('sub','修改');
        $this->display('error_add.dwt');
    }


    public function update()
    {
        if(IS_GET){
            $id=$_GET['id'];
            $status=$_GET['status'];
            $user=$_GET['user'];
        }elseif(IS_POST){
            $id=$_POST['id'];
            $status=$_POST['status'];
            $user=$_POST['user'];
        }

        $set='';
        if($status){
            $set.='status='.$status;
        }
        if($user){
            $set.=',user_id='.$user;
        }
//        status=".$status.", user_id=".$user."

        $sql="UPDATE error_log SET ".$set.", last_time=now() WHERE id=".$id;

        M()->query($sql);
    }

    /**
     * @param null $status 0 未修改，2正在修改，8已完成
     * @return mixed
     */
    public function getErrorInfo($status=null,$limit=1000)
    {
        if($status){
            $where=' AND el.status='.$status;
        }
        $sql="SELECT el.* ,eu.name AS user FROM error_log AS el LEFT JOIN error_user AS eu ON el.user_id=eu.id Where el.id >0".$where." ORDER BY el.soft DESC ,el.last_time DESC LIMIT $limit";
        $res=M()->query($sql);

        return $res;
    }

    /**
     * @param null $status 0 未修改，2正在修改，8已完成
     * @return mixed
     */
    public function getErrorInfo2($status=null,$limit=1000)
    {
        if($status){
            $where=' AND el.status='.$status;
        }
        $sql="SELECT el.* ,eu.name AS user FROM error_log AS el LEFT JOIN error_user AS eu ON el.user_id=eu.id Where el.id >0".$where." ORDER BY el.last_time DESC LIMIT $limit";
        $res=M()->query($sql);

        return $res;
    }

}