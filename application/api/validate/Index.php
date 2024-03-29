<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\api\validate;

use think\Validate;

/**
 * 广告验证器
 * @package app\cms\validate
 * @author 蔡伟明 <314013107@qq.com>
 */
class Index extends Validate
{
    // 定义验证规则
    protected $rule = [
        'name|姓名'         => 'require',
        'sex|性别'          => 'require',
        'area|城市id'    => 'require'
        
    ];

    // 定义验证提示
    protected $message = [

        'name|姓名'        => '姓名不能为空',
        'sex|性别'          => 'require',
        'area|城市id'    => '必须选择城市'
    ];

    // 定义验证场景
    protected $scene = [
        'name' => ['name']
    ];
}
