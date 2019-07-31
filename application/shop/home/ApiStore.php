<?php
namespace app\shop\home;

use app\common\controller\Api;
use think\facade\Request;
//use util\Tree;
use app\shop\model\Category as CategoryModel;
use app\shop\model\Goods as GoodsModel;
use app\shop\model\Store as StoreModel;
use think\Db;
/**
 * 商品接口
 * @package app\shop\home
 */
class ApiStore extends Api {

    /**
     * 获取商店分类列表
     * @return mixed
     */
    public function store_list(){
    	if($this->request->isPost()){
    		$params = $this->request->post();
            //var_dump($params);
            $city = $params['city'] ;
            //选定客户所在的区
            $county = $params['county'];
            $county_pid = Db::name("basic_area")->where('title',$city)->value('id');
            $data = Db::name("basic_area")->where('title',$county)
            ->where('pid',$county_pid)
            ->select();
            //var_dump($data[0]['id']);
            //最近5公里的商家
            $distance = 5;
            //获取用户的经纬度
            $longitude = $params['longitude'];
            $latitude = $params['latitude'];
            //编写sql计算用户与商户的经纬距离的语句
            $dis = "sqrt( ( ((".$longitude."-location_x)*PI()*12656*cos(((".$latitude."+location_y)/2)*PI()/180)/180) * ((".$longitude."-location_x)*PI()*12656*cos (((".$latitude."+location_y)/2)*PI()/180)/180) ) + ( ((".$latitude."-location_y)*PI()*12656/180) * ((".$latitude."-location_y)*PI()*12656/180) ) )/2";

           // 加入排序，从最近到最近排序。
            $sql = "select id,title,logo,".$dis." as dis from ky_shop_store where ".$dis."<".$distance ." and area=".$data[0]['id']." ORDER BY dis ASC ";
          
            //获取商家图片地址
            $res = Db::query($sql);
            foreach ($res as $key => $value) {
                foreach ($value as $k => $v) {
                    if($k=='logo'){
                        $res[$key][$k] = get_file_path($v);
                    }
                    
                }
            }
            //消掉键名
            $res = json_encode($res);
            $res = json_decode($res);
            $this->result($res,1,'最近商家','json');
           
    	}else{
    		$this->result('',0,'请登录','json');
    	}

    	
    }
    /**
     * 获取商品列表
     *@return mixed
     */
    public function store_introduction(){
    	if($this->request->isPost()){
    		$params = $this->request->post();
    		$data = StoreModel::where('id',$params['store_id'])->select();
           
            $res=[];
    		foreach ($data as $key => $value) {
                $res['logo']          = get_file_path($value->logo);
    			$res['title']         = $value->title;
    			$res['delivery_time'] = $value->delivery_time;
    			$res['address']       = $value->address;
    			$res['delivery_type'] = $value->delivery_type;
    			$res['tel']           = $value->tel;
    			$res['description']   = $value->description;
                $res['voucher']       = get_file_path($value->voucher);
    		}
    		$this->result($res,1,'商家详情','json');
    	}else{
    		$this->result('',0,'请登录','json');
    	}
    }
   


    
   
}

