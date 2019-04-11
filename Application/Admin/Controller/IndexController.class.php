<?php
namespace Admin\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
    	//显示 html页面,默认index.htm
        $this -> display();
    }
}