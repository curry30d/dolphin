<?php
/**
 * 模块信息
 */
return [
    // 模块名[必填]
    'name'        => 'shop',
    // 模块标题[必填]
    'title'       => '商城',
    // 模块唯一标识[必填]，格式：模块名.开发者标识.module
    'identifier'  => 'shop.kuaiyu.module',
    // 开发者[必填]
    'author'      => '快鱼科技',
    // 版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
    'version'     => '1.0.0',
    // 模块描述[必填]
    'description' => '商城模块',
    //字体图标[必填]
    'icon'        => 'glyphicon glyphicon-shopping-cart',
    //依赖模块
    'need_module' => [
        ['member','member.kuaiyu.module','1.1.0','>=']
    ],
    // 模块参数配置
    'config' => [
        ['text', 'express_fee', '配送费', '设置快递配送费', 2]
    ],
    // 行为配置
    'action' => [
        [
            'module' => 'shop',
            'name' => 'category_add',
            'title' => '新增商品分类',
            'remark' => '新增商品分类',
            'rule' => '',
            'log' => '[user|get_nickname] 新增商品分类：[details]',
            'status' => 1,
        ],
    ],
];