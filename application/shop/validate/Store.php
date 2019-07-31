<?php
namespace app\shop\validate;

use think\Validate;

/**
 * 广告分类验证器
 * @package app\cms\validate
 * @author 快鱼科技
 */
class Store extends Validate
{
    // 定义验证规则
    protected $rule = [
//        'catename|分类名称'  => 'require|length:1,30|unique:shop_goods_category'
    ];

    // 定义验证场景
    protected $scene = [
//        'catename' => ['name']
    ];
}
