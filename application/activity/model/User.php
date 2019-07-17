<?php
namespace app\activity\model;

use think\Model;

/**
 * 日志模型
 * @package app\admin\model
 */
class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'activity_user';
    protected $pk='user_id';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function sign(){
    	$this->hasMany('Sign','user_id',"user_id");
    }
}