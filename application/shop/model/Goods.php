<?php
namespace app\shop\model;

use think\Model as ThinkModel;

/**
 * 商品模型
 * @package app\shop\model
 */
class Goods extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_GOODS__';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    //发布时间转时间戳
    protected function setPublishTimeAttr($value){
        return strtotime($value);
    }

    //获取幻灯片图片地址
    protected function getGoodsPicsAttr($value){
        if($value){
            $value = explode(',',$value);
            return get_files_path($value);
        }else{
            return $value;
        }
    }

    //获取商品图片地址
    protected function getGoodsPicAttr($value){
        if($value){
            return get_file_path($value);
        }else{
            return $value;
        }
    }
}
