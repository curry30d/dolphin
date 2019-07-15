<?php
/**
 * 模块信息
 */
return [
    // 模块名[必填]
    'name'        => 'api',
    // 模块标题[必填]
    'title'       => '编辑',
    // 模块唯一标识[必填]，格式：模块名.开发者标识.module
    'identifier'  => 'api.ming.module',
    // 开发者[必填]
    'author'      => 'cartman',
    // 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
    'version'     => '1.0.0',
    // 模块描述[必填] 
	'description' => '编辑模块',

    'need_module' => [
        ['admin', 'admin.dolphinphp.module', '1.0.0']
    ],

    'need_plugin' => [],

     'tables' => [
       'api_student',
    ],
    // 原始数据库表前缀
    // 用于在导入模块sql时，将原有的表前缀转换成系统的表前缀
    // 一般模块自带sql文件时才需要配置
    'database_prefix' => 'dp_',

    // 模块参数配置
    'config' => [
        ['text', 'summary', '默认摘要字数', '发布文章时，如果没有填写摘要，则自动获取文档内容为摘要。如果此处不填写或填写0，则不提取摘要。', 0],
        ['ckeditor', 'contact', '联系方式', '', '<div class="font-s13 push"><strong>河源市卓锐科技有限公司</strong><br />
地址：河源市江东新区东环路汇通苑D3-H232<br />
电话：0762-8910006<br />
邮箱：admin@zrthink.com</div>'],
        ['textarea', 'meta_head', '顶部代码', '代码会放在 <code>&lt;/head&gt;</code> 标签以上'],
        ['textarea', 'meta_foot', '底部代码', '代码会放在 <code>&lt;/body&gt;</code> 标签以上'],
        ['radio', 'support_status', '在线客服', '', ['禁用', '启用'], 1],
        ['colorpicker', 'support_color', '在线客服配色', '', 'rgba(0,158,232,1)'],
        ['image', 'support_wx', '在线客服微信二维码', '在线客服微信二维码'],
        ['ckeditor', 'support_extra', '在线客服额外内容', '在线客服额外内容，可填写电话或其他说明'],
    ],

];