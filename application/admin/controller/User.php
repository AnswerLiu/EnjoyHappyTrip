<?php 
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;
use think\Db;

class User extends Base
{
	// 渲染登录界面
	public function login(){

		$this->view->assign('title','管理员登录');
		return $this->view->fetch('login');
	}

	// 登录校验
	public function checkLogin(){
		$data = Request::param();
		$map[] = ['email','=',$data['email']];
		$map[] = ['passwd','=',md5($data['passwd'])];

		$result = UserModel::where($map)->find();
		if($result){
			Session::set('admin_id',$result['id']);
			Session::set('admin_name',$result['name']);
			$this->success('登录成功','admin/index/index');
		}else{
			$this->error('登录失败');
		}
	}
}