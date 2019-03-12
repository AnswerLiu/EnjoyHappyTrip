<?php 
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Cate as CateModel;
use think\facade\Request;
use think\facade\Session;

class Cate extends Base
{
	/*分类管理首页*/
	public function index(){
		// 检查用户是否登录
		$this->isLogin();

		// 登录成功后跳转到分类管理界面
		return $this->redirect('cateList');
	}

	/*分类列表*/
	public function cateList(){
		// 检查用户是否登录
		$this->isLogin();

		// 获取所有分类
		$cateList = CateModel::all();

		// 设置模板变量
		$this->view->assign('title','分类管理');
		$this->view->assign('empty','<span style="color:red">没有分类</span>');
		$this->view->assign('cateList',$cateList);

		return $this->view->fetch('catelist');
	}
}