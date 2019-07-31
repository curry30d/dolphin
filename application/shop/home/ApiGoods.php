<?php
namespace app\shop\home;

use app\common\controller\Api;
use app\user\model\User as AdminModel;
use think\facade\Request;
use util\Tree;
use app\shop\model\Category as CategoryModel;
use app\shop\model\Goods as GoodsModel;
use app\basic\model\Area as AreaModel;
use think\Db;
/**
 * 商品接口
 * @package app\shop\home
 */
class ApiGoods extends Api {

    /**
     * 获取商品分类列表
     * @param int $pid 分类父id
     */
    public function goodscate($pid = 0){
        $map[] = ['status','=',1];
        //获取原始数据用db方法
        $cate_list = db('shop_goods_category')->order('sort desc')->select();

        $tree = new Tree(['title'=>'catename']);
        $result = $tree::toLayer($cate_list,$pid);
        if($result){
            $this->result($result,1,'成功','json');
        }else{
            $this->result('',0,'失败','json');
        }
    }

    /**
     * 获取商品列表
     */
    public function lists($status = 1,$type = null,$page = 1,$limit = 10){
        $map[] = ['status','=',$status];//是否上架

        if($type){
            $map[] = ['goods_type','=',$type];//商品分类
            //coding... 预留获取分类ID下所有子分类的商品
        }

        //地区
        $area = input('post.area');
        if($area){
            //地区ID
            $areaId = AreaModel::where('merger',$area)->value('id');
            $adminId = AdminModel::where('area',$areaId)->value('id');
            if($adminId){
                $map[] = ['aid','=',$adminId];
            }else{
                $this->result('',0,'该地区未开放','json');
            }
        }

        $goods_list = GoodsModel::where($map)->order('sort desc')->page($page,$limit)->select();

        if($goods_list->isEmpty()){
            $this->result('',0,'无数据','json');
        }else{
            $this->result($goods_list,1,'成功','json');
        }
    }


    /**
     * 商品详情
     * @param $gid  商品ID
     */
    public function info($gid){
        $goods_info = GoodsModel::get($gid);
        if($goods_info){
            $this->result($goods_info,1,'成功','json');
        }else{
            $this->result('',0,'无数据','json');

        }
    }

    public function search_goods(){
        $goods_name = input('post.goods_name');
        // $data = Db::name('shop_goods')
        //     ->where('name|short_name|title|keyword','like','%'.$goods_name.'%')
        //     ->where('create_time&update_time','>',0)
        //     ->select();
        $res = [];
        $data = GoodsModel::where('keyword','like','%'.$goods_name.'%')->where('status',1)->select()->toArray();
        foreach ($data as $key => $value) {
            //var_dump($value);
            foreach ($value as $k => $v) {
               

                if($k!='id'||$k!='status'||$k!='publish_type'||$k!='type'){
                    //var_dump($k!='id');
                    //echo $k."----";
                     $res[$key][$k] = $v;
                }
            }
        }
         $this->result($res,1,'成功','json');
    }
}
