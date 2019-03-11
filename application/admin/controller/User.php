<?php 
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;

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
			Session::set('admin_role',$result['role']);
			$this->success('登录成功','admin/index/index');
		}else{
			$this->error('登录失败');
		}
	}

	/**
	 * 用户列表
	 *  
	 */
	public function userList(){
		// 获取当前用户id和级别is_admin
		$data['admin_id'] = Session::get('admin_id');
		$data['admin_role'] = Session::get('admin_role');

		// 获取当前用户信息
		$userList = UserModel::where('id',$data['admin_id'])->find();

		// 如果是超级管理员获取全部信息
		if($data['admin_role'] == "超级管理员"){
			$userList = UserModel::select();
		}

		// 模板赋值
		$this->view->assign('title','用户管理');
		$this->view->assign('empty','<span>没有任何数据</span>');
		$this->view->assign('userList',$userList);

		// 渲染输出用户列表的模板
		return $this->view->fetch('userList');
	}

	/*退出登录*/
	public function logout(){
		// 清楚session
		Session::clear();
		// 退出登录并跳转到登录页面
		$this->success('退出成功','admin/user/login');
	}
}