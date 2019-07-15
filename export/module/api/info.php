<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

/**
 * 模块信息
 */
return [
  'name' => 'api',
  'title' => '编辑',
  'identifier' => 'api.ming.module',
  'author' => 'cartman',
  'version' => '1.0.0',
  'description' => '编辑模块',
  'need_module' => [
    [
      'admin',
      'admin.dolphinphp.module',
      '1.0.0',
    ],
  ],
  'need_plugin' => [],
  'tables' => [
    'api_student',
  ],
  'database_prefix' => 'dp_',
  'config' => [
    [
      'text',
      'summary',
      '默认摘要字数',
      '发布文章时，如果没有填写摘要，则自动获取文档内容为摘要。如果此处不填写或填写0，则不提取摘要。',
      0,
    ],
    [
      'ckeditor',
      'contact',
      '联系方式',
      '',
      '<div class="font-s13 push"><strong>河源市卓锐科技有限公司</strong><br />
地址：河源市江东新区东环路汇通苑D3-H232<br />
电话：0762-8910006<br />
邮箱：admin@zrthink.com</div>',
    ],
    [
      'textarea',
      'meta_head',
      '顶部代码',
      '代码会放在 <code>&lt;/head&gt;</code> 标签以上',
      '',
    ],
    [
      'textarea',
      'meta_foot',
      '底部代码',
      '代码会放在 <code>&lt;/body&gt;</code> 标签以上',
      '',
    ],
    [
      'radio',
      'support_status',
      '在线客服',
      '',
      [
        '禁用',
        '启用',
      ],
      1,
    ],
    [
      'colorpicker',
      'support_color',
      '在线客服配色',
      '',
      'rgba(0,158,232,1]',
    ],
    [
      'image',
      'support_wx',
      '在线客服微信二维码',
      '在线客服微信二维码',
      '',
    ],
    [
      'ckeditor',
      'support_extra',
      '在线客服额外内容',
      '在线客服额外内容，可填写电话或其他说明',
      '',
    ],
  ],
];
