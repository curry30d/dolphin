<?php
namespace app\shop\model;

use think\Model as ThinkModel;
use app\shop\model\Goods as GoodsModel;

/**
 * 选中商品模型
 * @package app\shop\model
 */
class OrderGoods extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_ORDERS_GOODS__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 商品详情
     * @param $value
     * @return mixed
     */
    public function getGidAttr($value){
        return GoodsModel::get($value);
    }

}