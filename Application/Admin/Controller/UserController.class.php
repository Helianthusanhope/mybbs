<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;
use Think\Image;
class UserController extends CommonController
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
        $data = $_POST;
        $data['create_at'] = time();

        //echo '<pre>';
        //print_r($data); die;

        if( empty($data['upwd'] ) || empty( $data['reupwd']) ){
        $this -> error('密码不能为空');
        }

        if( $data['upwd'] !== $data['reupwd'] ){
          $this -> error('两次密码不一致!');
        }
        //加密密码
        $data['upwd'] = password_hash( $data['upwd'],PASSWORD_DEFAULT);

        //文件上传处理
        $data['uface'] = $this->doUp();
        //生成缩略图
        $this->doSm();
        //添加到数据库
        //$row = M('bbs_user') -> add( $data );
        try{
            $row = M('bbs_user') -> add( $data );
         } catch(\Exception $e) {
               $this-> error( $e->getMessage() );
         }

        if ($row){
            $this -> success('添加用户成功!');
        } else {
            $this -> error('添加用户失败!');
        }
    }

    public function index()   
    {
        //判断有没有性别条件
            //定义一个空数组
        $condition = [];

        if( !empty($_GET['sex']) ){
            $condition['sex'] = [ 'eq', "{$_GET['sex']}" ]; //select * from bbs_user where sez="w";
        }
        
        //判断有没有姓名条件
        if( !empty($_GET['uname']) ){
            $condition['uname'] = [ 'like',"%{$_GET['uname']}%"];  
            // select * from where uname like "%a%";
        }
        //实例化 表 对象
        $User  = M('bbs_user') ;
        //得到满足条件的总记录数
        $cnt   = $User-> where( $condition ) -> count();
        //实例化分页类 传入总记录数和每页显示的记录数(5);
        $Page = new Page($cnt, 3);
        //得到分页显示 html 代码
        $html_page = $Page->show();
            //获取数据
        $users =$User->where($condition)
                     ->limit($Page->firstRow, $Page->listRows)
                     ->select();             
        //显示数据
        $this -> assign('users',$users);
        $this -> assign('html_page',$html_page);
         //在view/user/index.php中可以使用 $users
        $this -> display(); 
    }

    public function del()
    {
        $uid = $_GET['uid']; // 接收要删除的用户ID
        // 进行删除操作
        $row = M('bbs_user')->delete( $uid );
        
        if ($row) {
            $this->success('删除用户成功!');
        } else {
            $this->error('删除用户失败!');
        }
    }

    public function edit()
    {

        $uid = $_GET['uid'];
        $user = M('bbs_user') -> find( $uid );
        $this -> assign('user', $user);
        $this -> display();
    }

    public function update()
    {
        $uid  = $_GET['uid'];
        $data = $_POST;
        //文件上传处理
      
        if ($_FILE['uface']['error'] !== 4 ) {
         $data['uface'] = $this->doUp(); //处理上传文件
         $this->doSm(); //生成缩略图
         //unlink( $user->uface );
        }
          //添加到数据库
        $User = M('bbs_user');
        $row = $User->where("uid=$uid") -> save( $data );
        if ($row !== 0 ) {
            $this->success('用户信息修改成功!','./index.php?m=admin&c=user&a=index');
        } else {
            $this->error('用户信息修改失败!');
        }
    }

    //处理上传文件
    private function doUp()
    {
        $config = array(
            'maxSize' => 3145728,
            'rootPath' => './',
            'savePath' => 'Public/Uploads/',
            'saveName' => array('uniqid',''),
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => true,
            'subName' => array('date','Ymd'),
            );
        $up = new \Think\Upload($config);// 实例化上传类

        $info = $up->upload();
        if( !$info ){
           $this->error( $up->getError ) ;
        }
        return $this ->filename = $info['uface']['savepath'].$info['uface']['savename'];
    }

    private function doSm()
    {
        //生成缩略图
        $image = new Image(Image::IMAGE_GD,$this->filename);
        //打开 $filename 文件 后续进行操作
        $image->thumb(150,150)->save( './'.getSm($this->filename));
    }
} 