<?php
namespace app\shop\model;

use think\Model as ThinkModel;

/**
 * 选中商品模型
 * @package app\shop\model
 */
class Choose extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_STORE_CHOOSEGOODS__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

}
