<?php
namespace app\shop\home;
use app\common\controller\Api;
use app\user\model\User as AdminModel;
use think\facade\Request;
use app\shop\model\Category as CategoryModel;
use app\shop\model\Goods as GoodsModel;
use app\basic\model\Area as AreaModel;
use app\shop\model\Car as CarModel;
use app\shop\model\Order as OrderModel;
use app\shop\model\OrderGoods as OrderGoodsModel;

/**
 * 订单接口
 * @package app\shop\home
 */
class ApiOrder extends Api {


    /**
     * 下单
     * @param $uid
     * @param $carid
     */
    public function place($uid,$carid){
        $data['order_id'] = 'OD'.time().rand(10000,99999);
        $data['uid'] = $uid;
        $data['address_id'] = input('addressid');
        $data['position'] = input('position');
        $data['auto_out'] = input('autoout');
        $data['offer'] = input('offer');

        $orderInfo = OrderModel::create($data);
        if($orderInfo){
            $carlist = explode(',',$carid);
            foreach ($carlist as $car){
                $goodInfo = CarModel::get($car);
                $data = null;
                $data['oid'] = $orderInfo['order_id'];
                $data['gid'] = $goodInfo['gid']['id'];
                $data['num'] = $goodInfo['num'];
                $data['price'] = $goodInfo['gid']['goods_price'];

                OrderGoodsModel::create($data);
            }
            $this->result('',1,'成功','json');
        }else{
            $this->result('',0,'失败','json');
        }

    }

    /**
     * 订单信息
     * @param int $page
     * @param int $limit
     */
    public function orderlist($page = 1,$limit = 6){
        $where = null;
        $where[] = ['uid','=',input('uid')];

        if(input('status')){
            $where[] = ['status','=',input('status')];
        }

        $list = OrderModel::where($where)->page($page,$limit)->select();
        foreach ($list as $key => $value){
            $list[$key]['goods'] = $value->goods;
        }

        if(count($list)){
            $this->result($list,1,'成功','json');
        }else{
            $this->result('',0,'无数据','json');
        }
    }

    /**
     * 订单详情
     * @param $oid
     */
    public function orderinfo($oid){
        $info = OrderModel::where('id',$oid)->find();
        $info['goods'] = $info->goods;

        $this->result($info,1,'成功','json');
    }


    public function setOrderStatus(){

    }





}