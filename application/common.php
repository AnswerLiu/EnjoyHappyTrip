<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;

// 根据cate_id查询yc_article_cate表，获取栏目名称
if(!function_exists('getCateName')){
	function getCateName($cate_id){
		return Db::table('yc_article_category')->where(['id'=>$cate_id])->value('name');
	}
}
