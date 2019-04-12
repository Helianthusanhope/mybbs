<?php
namespace Home\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function signUp()
    {
    	$this->display(); //View/Login/signup.html
    }

    public function register()
    {
    	//判断两个密码不能为空
		if (empty($_POST['upwd']) || empty($_POST['reupwd'])) {
			$this -> error('密码或确认密码不能为空!');
		}
		//判断两个密码是否一致
		if ($_POST['upwd']!==$_POST['reupwd']) {
			$this -> error('两次密码不一致!');
		}

    	$data = $_POST;
    	$data['create_at'] = time();
    	$data['auth'] = 3;

		$upwd = password_hash($_POST['upwd'],PASSWORD_DEFAULT);

        $row = M('bbs_user') -> add( $data );
            
        if ($row){
            $this -> success('注册成功!','/');
         } else {
               $this-> error( '注册失败!' );//->getMessage() );
         }

         
    }

    public function dologin()
    {
    	$uname = $_POST['uname'];
    	$upwd = $_POST['upwd'];

    	$user = M('bbs_user')->where("uname='$uname'")->find();

    	if($user && password_verify($upwd,$user['upwd'])){
    		$_SESSION['userInfo'] = $user;
    		$_SESSION['flag'] = true;

    		$this->success('登陆成功','/');
    	} else {
    		$this->error('账户或密码错误!');
    	}
    }

    //退出
    public function logout()
    {
    	$_SESSION['flag'] = false;
    	$this -> success('正在退出...','/');
    }
}