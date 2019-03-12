<?php
namespace app\index\controller;

use app\common\controller\Base;

class User extends Base
{
	/*渲染注册页面*/
	public function register(){
		// 检测是否允许注册
		$this->is_reg();

		return '放开了注册吧，小伙伴们！';
	}
}