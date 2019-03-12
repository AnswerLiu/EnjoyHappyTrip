<?php
namespace app\common\controller;

use think\Controller;
use think\Facade\Session;
use app\admin\common\model\Site;
use think\Facade\Request;

class Base extends Controller
{
	/**
	 * 初始化方法
	 * 在所有方法之前调用
	 * 常用来创建常量，公共方法等
	 */
	protected function initialize(){
		// 检测网站是否关闭
		$this->is_open();
	}

	// 检测站点是否关闭
	public function is_open(){
		// 获取当前站点状态
		$isOpen = Site::where('status',1)->value('is_open');
		// 判断站点是否关闭，如果站点已经关闭，只允许关闭前台，后台不能关
		if($isOpen == 0 && Request::module() == 'index'){
			// 关掉网站
			$info = <<< 'INFO'
<body style="background-color:#333">
<h1 style="color:#eee;text-align:center;margin:200px;">站点维护中……</h1>
</body>
INFO;
			exit($info);
		}
	}
}