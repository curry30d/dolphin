<?php
namespace app\shop\model;

use think\Model as ThinkModel;
use app\shop\model\Goods as GoodsModel;

/**
 * 购物车模型
 * @package app\shop\model
 */
class Car extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_GOODS_CAR__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /*
     * 获取商品详情
     */
    protected function getGidAttr($value){
        $result =  GoodsModel::get($value);
        return $result;
    }

}
