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

	/*渲染编辑分类页面*/
	public function cateEdit(){
		// 获取分类的id
		$id = Request::param('id');
		// 根据主键查询更新的分类信息
		$cateInfo = CateModel::where('id',$id)->find();
		// 设置模板变量
		$this->view->assign('title','编辑分类');
		$this->view->assign('cateInfo',$cateInfo);

		return $this->view->fetch('cateedit');
	}

	/*执行更新操作*/
	public function doEdit(){
		// 获取用户提交信息
		$data = Request::param();
		$data['upd_time'] = date('Y-m-d H:i:s',time());
		// 取出更新主键
		$id = $data['id'];
		// 删除主键id
		unset($data['id']);
		// 执行更新操作
		if(CateModel::where('id',$id)->data($data)->update()){
			return $this->success('更新成功','cateList');
		}else{
			return $this->error('没有更新或者更新失败');
		}
	}

	/*执行删除操作*/
	public function doDelete(){
		// 获取要删除的主键id
		$id = Request::param('id');
		// 执行删除操作
		if(CateModel::where('id',$id)->data('status','0')->update()){
			return $this->success('删除成功','cateList');
		}else{
			return $this->error('删除失败');
		}
	}

	/*渲染添加分类页面*/
	public function cateAdd(){
		$this->isLogin();
		$this->view->assign('title','添加分类');
		return $this->view->fetch('cateadd');
	}

	/*执行添加操作*/
	public function doAdd(){
		// 获取要添加的分类信息
		$data = Request::param();
		$data['create_time'] = date('Y-m-d H:i:s',time());
		$data['upd_time'] = date('Y-m-d H:i:s',time());
		// 执行添加操作并判断是否成功
		if(CateModel::create($data)){
			return $this->success('添加成功','catelist');
		}else{
			return $this->error('添加失败');
		}
	}
}