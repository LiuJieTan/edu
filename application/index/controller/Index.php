<?php
namespace app\index\controller;
class Index extends Base
{
    public function index()
    {
        $this->isLogin(); //避免未登录通过网址获得该页面
        $this->view->assign('title','微央教学管理系统中心');
        return $this -> view -> fetch();
    }
}
