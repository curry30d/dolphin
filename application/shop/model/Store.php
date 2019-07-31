<?php
namespace app\shop\model;

use think\Model as ThinkModel;

/**
 * 门店模型
 * @package app\shop\model
 */
class Store extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_STORE__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

}
