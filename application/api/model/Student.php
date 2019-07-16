<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\api\model;

use think\Model as ThinkModel;
class Student extends ThinkModel
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'student';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

     public function getStudentTypeAttr($value, $data)
    {
        $type = [0 => '普通', 1 => 'VIP', 2 => '黄金', 3 => '白金', 4 => '钻石'];
        return $type[$value];
    }


    // 定义修改器
    public function setStartTimeAttr($value)
    {
        return $value != '' ? strtotime($value) : 0;
    }
    public function setEndTimeAttr($value)
    {
        return $value != '' ? strtotime($value) : 0;
    }
    public function getStartTimeAttr($value)
    {
        return $value != 0 ? date('Y-m-d', $value) : '';
    }
    public function getEndTimeAttr($value)
    {
        return $value != 0 ? date('Y-m-d', $value) : '';
    }
}
