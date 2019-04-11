<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Image;
class CateController extends CommonController
{
   //添加板块
   public function create()
   {
		//获取所有分区
		$parts = M('bbs_part')->select();
		//查出所有用户
		$users = M('bbs_user')->where( "auth<3" )->select();
		//遍历用户
		//选择谁是版主
		$this->assign('users',$users);
		$this->assign('parts',$parts);
		$this->display();  //View/Cate/create.html
   }

   //接收表单数据,保存到数据库
   public function save()
   {
   		// $row = M('bbs_cate') -> add( $_POST );
   		try{
   			$row = M('bbs_cate') -> add( $_POST );
   		} catch( \Exception $e) {
   			$this-> error( $e->getMessage() );
   		}
		
	   
		if ($row){
			$this -> success('添加板块成功!');
		} else {
			$this -> error('添加板块失败!');
		}
   }

   public function index()   
   {
		//获取数据				
			
		$cates = M('bbs_cate')->select();

		//获取分区信息
		$parts = M('bbs_part')->select();
		//通过函数从 $part 数组中获取想要的元素 ([pid => '分区名称', ...])
		$parts = array_column($parts, 'pname', 'pid');

		// $parts = M('bbs_part')->getField('pid,pname');

		//获取用户信息
		$users = M('bbs_user')->select();
		//通过函数从 $user 数组中获取想要的元素 ([uid => '用户名称', ...])
		$users = array_column($users, 'uname', 'uid');
		
		// $users = M('bbs_user')->getField('uid,uname');

		//显示数据
		$this -> assign('cates',$cates);
		$this -> assign('parts',$parts);
		$this -> assign('users',$users);
		$this -> display(); 
   }
   
	public function del()
	{

		$cid = $_GET['cid']; // 接收要删除的ID
		// 进行删除操作
		$row = M('bbs_cate')->delete( $cid );
		
		if ($row) {
			$this->success('删除板块成功!');
		} else {
			$this->error('删除板块失败!');
		}
	}

	public function edit()
	{
		//获取数据
		$cid = $_GET['cid'];
		$cate = M('bbs_cate')->find();

		//获取分区信息
		$parts = M('bbs_part')->select();

		//获取用户信息
		$users = M('bbs_user')->where( "auth<3" )->select();

		//显示数据
		$this -> assign('cate',$cate);
		$this -> assign('parts',$parts);
		$this -> assign('users',$users);
		$this -> display(); 

		/*$cid = $_GET['cid'];
		$cate = M('bbs_cate') -> find( $cid );

		$this -> assign('cate', $cate);
		$this -> display();*/
	}

	public function update()
	{
		$cid  = $_GET['cid'];
		$row = M('bbs_cate')->where("cid=$cid") -> save( $_POST );
	   
		if ($row){
			$this -> success('修改分区成功!');
		} else {
			$this -> error('修改分区失败!');
		}
	}

} 