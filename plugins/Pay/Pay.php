<?php
// +----------------------------------------------------------------------
// | 短信插件
// +----------------------------------------------------------------------
// | 版权所有 2017~2019 江西快鱼科技有限公司 [ http://www.p2cn.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://p2cn.com
// +----------------------------------------------------------------------
// | 创建者    allenlinc（allenlinc@gmail.com）
// +----------------------------------------------------------------------
// | 创建时间  2018/5/23 下午10:01
// +----------------------------------------------------------------------
namespace plugins\Pay;

use app\common\controller\Plugin;

/**
 * 支付插件
 * @package plugin\SmsJs
 * @author allenlinc <allenlinc@gmail.com>
 */
class Pay extends Plugin{
    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'Pay',
        // 插件标题[必填]
        'title'       => '支付插件',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'pay.kuaiyu.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-envelope-o',
        // 插件描述[选填]
        'description' => '支持支付宝支付，微信支付',
        // 插件作者[必填]
        'author'      => '快鱼科技',
        // 作者主页[选填]
        'author_url'  => 'http://www.p2cn.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0',
        // 是否有后台管理功能
        'admin'       => '0',
    ];



    /**
     * 安装方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 卸载方法必须实现
     * 一般只需返回true即可
     * 如果安装前有需要实现一些业务，可在此方法实现
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }
}
