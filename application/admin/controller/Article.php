<?php 
namespace app\admin\controller;
use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArticleModel;
use app\admin\common\model\Cate;
use think\facade\Request;
use think\facade\Session;

class Article extends Base
{
	/*文章管理首页*/
	public function index(){
		// 检查用户是否登录
		$this->isLogin();

		// 登录成功后跳转到文章管理界面
		return $this->redirect('articleList');
	}

	/*文章列表*/
	public function articleList(){
		// 检查用户是否登录
		$this->isLogin();

		// 获取全部文章
		$articleList = ArticleModel::paginate(2);

		// 设置模板变量
		$this->view->assign('title','文章管理');
		$this->view->assign('empty','<span style="color:red">没有文章</span>');
		$this->view->assign('articleList',$articleList);

		return $this->view->fetch('articlelist');
	}

	/*渲染编辑文章页面*/
	public function articleEdit(){
		// 获取文章的id
		$id = Request::param('id');
		// 根据主键查询更新的文章信息
		$articleInfo = ArticleModel::where('id',$id)->find();
		// 获取文章分类信息
		$cateList = Cate::all();
		// 设置模板变量
		$this->view->assign('title','编辑文章');
		$this->view->assign('articleInfo',$articleInfo);
		$this->view->assign('cateList',$cateList);

		return $this->view->fetch('articleedit');
	}

	/*执行文章更新操作*/
	public function doEdit(){
		// 获取用户提交信息
		$data = Request::param();
		$data['upd_time'] = date('Y-m-d H:i:s',time());
		// 获取上传的图片信息
		$file = Request::file('title_img');
		
		// 图片信息验证与上传到服务器指定目录
		$info = $file -> validate([
			'size'=> 500000000000,  //文件大小
			'ext'=>'jpeg,jpg,png,gif'  //文件扩展名
		])->move('uploads/'); //移动到public/uploads目录下面
		if($info){
			$data['title_img'] = $info->getSaveName();
		}else{
			$this->error($file->getError());
		}
		
		// 取出更新主键
		$id = $data['id'];
		// 删除主键id
		unset($data['id']);
		// 执行更新操作
		if(ArticleModel::where('id',$id)->data($data)->update()){
			return $this->success('更新成功','articleList');
		}else{
			return $this->error('没有更新或者更新失败');
		}
	}

	/*执行文章删除操作*/
	public function doDelete(){
		// 获取要删除的主键id
		$id = Request::param('id');
		// 执行删除操作
		if(ArticleModel::where('id',$id)->data('status','0')->update()){
			return $this->success('删除成功','articleList');
		}else{
			return $this->error('删除失败');
		}
	}

	/*渲染添加文章页面*/
	public function articleAdd(){
		$this->isLogin();
		$this->view->assign('title','添加文章');
		return $this->view->fetch('articleadd');
	}

	/*执行添加操作*/
	public function doAdd(){
		// 获取要添加的文章信息
		$data = Request::param();
		$data['create_time'] = date('Y-m-d H:i:s',time());
		$data['upd_time'] = date('Y-m-d H:i:s',time());
		// 执行添加操作并判断是否成功
		if(ArticleModel::create($data)){
			return $this->success('添加成功','articlelist');
		}else{
			return $this->error('添加失败');
		}
	}
}