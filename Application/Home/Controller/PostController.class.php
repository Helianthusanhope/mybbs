<?php
namespace Home\Controller;

use Think\Controller;

class PostController extends Controller
{
    public function create()
    {
    	//可能会收到 一个版块ID
    	$cid = empty( $_GET['cid'] ) ? 0 : $_GET['cid'];
    	
    	if (empty($_SESSION['flag'])) {
    		$this->error('请先登录...','/');
    	}

    	//获取板块信息
    	$cates = M('bbs_cate')->getField('cid,cname');

    	$this->assign('cid',$cid);
    	$this->assign('cates',$cates);
    	
    	$this->display(); //View/Post/Create.html
    }

    public function save()
    {
    	$data = $_POST;

    	//发帖人
    	$data['uid'] = $_SESSION['userInfo']['uid'];
    	$data['updated_at'] = $data['created_at'] = time() ;

    	$row = M('bbs_post') -> add( $data );
            
        if ($row){
            $this -> success('帖子创建成功!');
         } else {
               $this-> error( '帖子发表失败!' );
         }
    	// echo '<pre>';
    	// print_r($_POST);

    }

    //帖子列表
    public function index()
    {
    	//要显示哪个板块下的帖子
    	$cid = empty( $_GET['cid'] ) ? 1 : $_GET['cid'];
    	//获取数据
    	$posts = M('bbs_post')->order("updated_at desc")->select();

    	//获取所有用户信息
    	$users = M('bbs_user')->getField('uid,uname');
    	//遍历显示
    	
    	$this->assign('posts',$posts);
    	$this->assign('users',$users );
    	$this->display();



    	// echo '<pre>';
    	// print_r($posts);
    }
}