<?php
namespace app\activity\model;

use think\Model;

/**
 * 日志模型
 * @package app\admin\model
 */
class Sign extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'activity_sign_rain_coin';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
}