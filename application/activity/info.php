<?php
/**
 * 模块信息
 */
return [
    // 模块名[必填]
    'name'        => 'activity',
    // 模块标题[必填]
    'title'       => '活动',
    // 模块唯一标识[必填]，格式：模块名.开发者标识.module
    'identifier'  => 'activity.ming.module',
    // 开发者[必填]
    'author'      => '戴志奇',
    // 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
    'version'     => '1.0.0',
    // 模块描述[必填] 
	'description' => '活动模块',

    'need_module' => [
        ['admin', 'admin.dolphinphp.module', '1.0.0']
    ],

    'need_plugin' => [],

     'tables' => [
       
    ],
    // 原始数据库表前缀
    // 用于在导入模块sql时，将原有的表前缀转换成系统的表前缀
    // 一般模块自带sql文件时才需要配置
    'database_prefix' => 'dp_',

    // 模块参数配置
    'config' => [
    ],
   

];