<?php
namespace plugins\Sms;
use app\common\controller\Plugin;

class Sms extends Plugin
{
	
	    /**
     * @var array 插件信息
     */
    public $info = [
        // 插件名[必填]
        'name'        => 'Sms',
        // 插件标题[必填]
        'title'       => '短信信息',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'sms.ming.plugin',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-globe',
        // 插件描述[选填]
        'description' => '这是一个短信插件。',
        // 插件作者[必填]
        'author'      => '戴志奇',
        // 作者主页[选填]
        'author_url'  => '',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0',
        // 是否有后台管理功能
        'admin'       => '1',
    ];
    
    /**
     * @var string 原数据库表前缀
     */
    public $database_prefix = 'dp_';
    
    /**
     * @var array 管理界面字段信息
     */
    public $admin = [
    	'title'        => '', // 后台管理标题
        'table_name'   => '', // 数据库表名，如果没有用到数据库，则留空
        'order'        => 'said,name', // 需要排序功能的字段，多个字段用逗号隔开
        'filter'       => ['pay_type' => [
                                    ['alipay' => '支付宝', 'wxpay' => '微信', 'unionpay' => '银联']
                                         ],
                          'pay_status' => [
                                     ['未支付', '已支付']
                       ],
                     ],
                     // 需要筛选功能的字段，多个字段用逗号隔开
        'search_title' => '', // 搜索框提示文字,一般不用填写
        'search_field' => [ // 需要搜索的字段，如果需要搜索，则必填，否则不填
            'said' => '名言',
            'name' => '出处'
        ],
        
        // 后台列表字段
        'columns' => [
            ['id', 'ID'],
            ['said', '名言'],
            ['name', '出处'],
            ['status', '状态', 'switch'],
            ['right_button', '操作', 'btn'],
        ],
        
        // 右侧按钮
        'right_buttons' => [

         'edit',          // 使用系统自带的编辑按钮
         'enable',       // 使用系统自带的启用按钮
         'disable',      // 使用系统自带的禁用按钮
         'delete',    ],
        
        // 顶部栏按钮
        'top_buttons' => []
    ];
    
    /**
     * 安装方法必须实现
     */
    public function install(){
        return true;
    }

    /**
     * 卸载方法必须实现
     */
    public function uninstall(){
        return true;
    }
}