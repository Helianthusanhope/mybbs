<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Image;
class PartController extends CommonController
{
	//显示表单
	public function create()
	{
		//显示 表单 create.html
		$this->display();
	}

	//接收表单数据,保存到数据库
	public function save()
	{

		// $row = M('bbs_part') -> add( $_POST );
		try{
			$row = M('bbs_part') -> add( $_POST );
		} catch(\Exception $e) {
			$this-> error( $e->getMessage() );
		}

		if ($row){
	  	$this -> success('添加分区成功!');
		} else {
	  	$this -> error('添加分区失败!');
		}
	}

	public function index()   
	{
	//获取数据

	$parts = M('bbs_part')->select();
		//显示数据
		$this -> assign('parts',$parts);

		$this -> display(); 
	}

	public function del()
	{
		$pid = $_GET['pid']; // 接收要删除的ID
		// 进行删除操作
		$row = M('bbs_part')->delete( $pid );
		
		if ($row) {
			$this->success('删除分区成功!');
		} else {
			$this->error('删除分区失败!');
		}
	}

	public function edit()
	{

		$pid = $_GET['pid'];
		$part = M('bbs_part') -> find( $pid );

		$this -> assign('part', $part);
		$this -> display();
	}

	public function update()
	{
		$pid  = $_GET['pid'];
	   	$row = M('bbs_part')->where("pid=$pid") -> save( $_POST );
	   
	   	if ($row){
	      	$this -> success('修改分区成功!');
	   	} else {
	      	$this -> error('修改分区失败!');
	   	}
	}
} 