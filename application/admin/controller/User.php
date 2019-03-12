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
			return $this->success('登录成功','admin/index/index');
		}else{
			return $this->error('登录失败');
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

	/**
	 * 渲染用户编辑页面
	 */
	public function userEdit(){
		// 获取当前要更新的用户的主键
		$user_id = Request::param('id');
		// 根据主键进行查询
		$userInfo = UserModel::where('id',$user_id)->find();
		// 设置编辑页面的模板变量
		$this->view->assign('title','用户编辑');
		$this->view->assign('userinfo',$userInfo);
		// 渲染模板
		return $this->view->fetch('useredit');
	}

	/**
	 * 保存用户编辑
	 */
	public function doEdit(){
		// 获取用户提交信息
		$data = Request::param();
		// 获取主键
		$id = $data['id'];
		// 用户密码加密再保存回去
		$data['passwd'] = md5($data['passwd']);
		// 删除主键id
		unset($data['id']);
		// 执行更新操作
		if(UserModel::where('id',$id)->data($data)->update()){
			return $this->success('更新成功','userList');
		}else{
			return $this->error('没有更新或者更新失败');
		}
	}

	/**
	 * 删除用户操作
	 */
	public function doDelete(){
		// 获取要删除的主键id
		$id = Request::param('id');
		// 执行删除操作
		if(UserModel::where('id',$id)->data('status','1')->update()){
			return $this->success('删除成功','userList');
		}else{
			return $this->error('删除失败');
		}
	}

	/*退出登录*/
	public function logout(){
		// 清楚session
		Session::clear();
		// 退出登录并跳转到登录页面
		return $this->success('退出成功','admin/user/login');
	}
}