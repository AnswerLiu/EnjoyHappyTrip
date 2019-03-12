<?php 
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Site as SiteModel;
use think\facade\Request;
use think\facade\Session;

class Site extends Base
{
	// 站点管理首页
	public function index(){
		// 获取站点信息
		$siteInfo = SiteModel::get(['status'=>1]);

		// 模板赋值
		$this->view->assign('siteInfo',$siteInfo);
		return $this->view->fetch('index');
	}

	// 执行站点信息保存操作
	public function save(){
		// 获取数据
		$data = Request::param();
		// 更新
		if(SiteModel::where('id',0)->data($data)->update()){
			return $this->success('更新成功','index');
		}else{
			return $this->error('更新失败');
		}
	}
}