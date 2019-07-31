<?php
namespace app\shop\model;

use think\Model as ThinkModel;
use app\member\model\User as UserModel;
use app\member\model\Address as AddressModel;
use app\shop\model\OrderGoods as OrderGoodsModel;

/**
 * 选中商品模型
 * @package app\shop\model
 */
class Order extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_ORDERS__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 用户信息
     */
    public function getUidAttr($value)
    {
        return UserModel::field('id,username,nickname')->get($value);
    }

    /**
     * 获取地址信息
     */
    public function getAddressIdAttr($value)
    {
        if($value){
            return AddressModel::get($value);
        }else{
            return $value;
        }
    }

    public function getGoodsAttr($value,$data)
    {
        $goods = OrderGoodsModel::where('oid',$data['order_id'])->select();
        return $goods;
    }

}
