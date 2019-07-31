<?php
namespace app\shop\home;

use app\common\controller\Api;
use app\user\model\User as AdminModel;
use think\facade\Request;
use app\shop\model\Category as CategoryModel;
use app\shop\model\Goods as GoodsModel;
use app\basic\model\Area as AreaModel;
use app\shop\model\Car as CarModel;

/**
 * 购物车接口
 * @package app\shop\home
 */
class ApiCar extends Api {

    /**
     * 加入购物车
     * @param $uid int  用户ID
     * @param $gid int  商品ID
     * @param $num int  改变数量
     * @param int $type int   默认1 为增加   0为减少
     */
    public function setGoods($uid,$gid,$num,$type = 1){
        $info = CarModel::where('uid',$uid)->where('gid',$gid)->find();
        //判断购物车是否有这商品
        if($info){
            $where[] = ['id','=',$info['id']];

            if($type == 1){
                if(CarModel::where($where)->setInc('num',$num)){
                    $this->result('',1,'成功','json');
                }else{
                    $this->result('',0,'失败','json');
                }
            }else{
                //加入减少数量小于购物车的数量
                if($num < $info['num']){
                    if(CarModel::where($where)->setDec('num',$num)){
                        $this->result('',1,'成功','json');
                    }else{
                        $this->result('',0,'失败','json');
                    }
                }else{
                    if(CarModel::destroy($info['id'])){
                        $this->result('',1,'成功','json');
                    }else{
                        $this->result('',0,'失败','json');
                    }
                }
            }

        }else{
            //增加
            if($type == 1){
                $data['uid'] = $uid;
                $data['gid'] = $gid;
                $data['num'] = $num;
                if(CarModel::create($data)){
                    $this->result('',1,'成功','json');
                }else{
                    $this->result('',0,'失败','json');
                }
            }else{
                $this->result('',0,'不存在','json');
            }
        }
    }

    /**
     * 获取购物车列表
     * @param $uid  会员id
     */
    public function carlist($uid){
        $result = CarModel::where('uid',$uid)->select();
        if($result){
            $this->result($result,1,'成功','json');
        }else{
            $this->result('',0,'无数据','json');
        }
    }
}