<?php
namespace app\shop\model;

use think\Model as ThinkModel;

/**
 * 商品分类模型
 * @package app\shop\model
 */
class Category extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = '__SHOP_GOODS_CATEGORY__';


    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取图片路径
     */
    protected function getCateimgAttr($value){
        return get_file_path($value);
    }

    /**
     * 获取上级名称
     */
    protected function getPidAttr($value){
        if($value == 0){
            return '顶级';
        }else{
            $resuslt = $this::where('id',$value)->value('catename');
            return $resuslt;
        }

    }



}
