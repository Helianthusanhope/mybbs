<?php
namespace Home\Controller;

use Think\Controller;
// use Think\Page;
// use Think\Image;
class IndexController extends Controller
{
	
	public function index()
	{
		//获取分区信息
	    $parts = M('bbs_part')->select();
	    //把 分区数组的下标 用 其中的pid 代替
	    $parts = array_column($parts,null,'pid');
	    //获取板块信息
	    $cates = M('bbs_cate')->select();

	 	//echo "<pre>";
		// print_r($cates);
	    //把 板块信息 追加到 分区信息 中
	    foreach ($cates as $cate) {
	    	$parts[ $cate['pid'] ]  ['sub'] [] = $cate;
	    }

		//接收数据 先打印
		// echo "<pre>";
		// print_r($parts); die;
		// var_dump($_get['xxoo']);
		// die;
		//和数据库相关 调用 add /delete/save/upload 时
		// try{
			// $row = M('bbs_part') -> add( $_POST );
		// } catch(\Exception $e) {
			// $this-> error( $e->getMessage() );
		// }
		$this->assign('parts',$parts);
		$this->display();
	}


}