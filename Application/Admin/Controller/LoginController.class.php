<?php
namespace Admin\Controller;

use Think\Controller;
// use Think\Page;
// use Think\Image;
class LoginController extends Controller
{
	
	public function login()
	{
		//显示 表单 create.html
		$this->display();
	}


	public function dologin()
	{
	//接收数据 先打印
	// echo "<pre>";
	// var_dump($_POST);
	//var_dump($_get['xxoo']);
	$uname = $_POST['uname'];
	$upwd  = $_POST['upwd'];
	$code  = $_POST['code'];

	$verify = new \Think\Verify();
	
	if ( !$verify->check($code) ) {
		$this->error('验证码不对');
	}

	$user = M('bbs_user')->where("uname='$uname'")->find();
	//echo "<pre>";
	//var_dump($user);
	//var_dump( password_verify($upwd,$user['upwd']) );
	if ( $user && password_verify($upwd,$user['upwd']) ) {
		/*
			保存当前登陆成功的用户信息
		 */
		$_SESSION['userinfo'] = $user;
		$_SESSION['flag'] = true;

		$this->success('登陆成功','/index.php?m=admin&c=index&a=index');
	} else {
		$this->error( '账号或密码不对!' );
	}
	//和数据库相关 调用 add /delete/save/upload 时
	// try{
		// $row = M('bbs_part') -> add( $_POST );
		// } catch(\Exception $e) {
			// $this-> error( $e->getMessage() );
		// }
	}
	public function logout()
	{
	$_SESSION['userinfo'] = NULL;
	$_SESSION['flag'] = false;

	$this->success('成功退出...','/index.php?m=admin&c=login&a=login');
	}

	public function code()
	{

		$config = array(
		'fontSize' => 20, // 验证码字体大小
		'length'   => 3, // 验证码位数
		'useNoise' => false, // 关闭验证码杂点
		'useCurve' => false,  //是否使用混淆曲线 默认为true
		'imageW'   => 120, //设置为0为自动计算
		'imageH'   => 37.5, //设置为0为自动计算
		);
		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}
}