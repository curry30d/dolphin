<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\activity\home;

use app\common\controller\Common;
use app\activity\model\User as UserModel;
use app\activity\model\Sign;

use think\Db;
//use app\admin\model\Module as ModuleModel;
//use think\facade\Hook;

/**
 * 用户公开控制器，不经过权限认证
 * @package app\activity\admin
 */
class Signed extends Common
{
    //我的列表
    public function index()
    {      //$user_id=session('uid');
            // $user=UserModel::get(1);
            // var_dump($user->user_id);
            // $user=new UserModel();
            // $data=$user->where('user_id','>','0')->select();
            // var_dump($data[0]['user_id']);

            // die;
            $now_time=strtotime (date("y-m-d",time()));
            $user_id=1;
            $data = db('activity_sign_rain_coin as sign')
            ->where('sign.update_time',">=",$now_time)
            ->where('user.user_id',$user_id)
            //查找的字段
            ->field('user.avatar,user.user_name,sign.money')
            //连表2,on后面的条件，第三参数可不写，left和right为左连接，右连接
            ->join('activity_user user ','user.user_id = sign.user_id','left')
            ->select();
            //var_dump($data);
           //$data=$sign->where('user_id',$user_id)->where('update_time',">=",$now_time)->select()->toarray();        
            return $data;
       
       
    }
    //签到功能
    public function sign(){
        
        $gap_time=1;
        $rain_coin=2.9;

        //$user_id=session('uid');
        $user_id=1;
       
        $user= new UserModel();
        $data=$user->find($user_id);
        //$data=$data->sign();
        $sign=new Sign();
        
        $now_time=strtotime (date("y-m-d",time()));
        $list=$sign->where('user_id',$user_id)->where('update_time',">=",$now_time)->select()->toarray();

        //新的一天还未签到，插入一条数据
        if($list!=1){
           $rain_coin=[
               'user_id'=>$user_id,
               'rain_coin'=> $rain_coin,
               'sign_in_number'=>1,
            ];

            action_log('activity_sign', 'admin_activity', $user_id, $user_id);
            $res=Sign::where('user_id',$user_id)->update($rain_coin);
            return $this->error('签到成功');
        }

        $dif_time=time()-$list[0]['update_time'];

        //判断签到的时间是否大于半个小时或者是隔天
        if($dif_time>$gap_time||date("Y-m-d",$list[0]['update_time'])!=date("Y-m-d",time())){
            $rain_coin=[
               'user_id'=>$user_id,
               'rain_coin'=> $rain_coin+$list[0]['rain_coin'],
               'sign_in_number'=>1+$list[0]['sign_in_number'],
            ];
            
            action_log('activity_sign', 'admin_activity', $user_id, $user_id);
            $res=Sign::where('user_id',$user_id)->update($rain_coin);
            //var_dump($res);
            return $this->error('签到成功');
        }else{
            return $this->error('签到失败，还未到时间');
        }
        //根据上一次签到的时间判断是不是当前日期
        //if($data->last_sign_time){}               
    }


    //奖励的列表
    public function reward_list(){
            $now_time=strtotime (date("y-m-d",time()));
            $last_time=strtotime (date("y-m-d",time()-60*60*24));
            $data = db('activity_sign_rain_coin as sign')
            //->where('sign.update_time',"<=",$now_time)
            ->where('sign.update_time',">=",$last_time)
            //查找的字段
            ->field('user.avatar,user.user_name,sign.money')
            //连表2,on后面的条件，第三参数可不写，left和right为左连接，右连接
            ->join('activity_user user ','user.user_id = sign.user_id','left')
            ->order('sign.money')
            ->select();
            return $data;
    }

    //获取的金钱
    public function get_money(){
        //开启事务
        Db::startTrans();
         try{
        //设置分发的红包   
        $money=100;
        $now_time=strtotime(date("Y-m-d",time()));
        $last_time=strtotime (date("y-m-d",time()-60*60*24));
        $stop_time=$now_time*60*60*24;
        $sign=new Sign();

        $user=new User();
        $sum;
        //查询前十名
        $data=$sign
        ->where('sign.update_time',"<",$now_time)
        ->where('sign.update_time',">=",$last_time)
        ->order('rain_coin')
        ->limit(10);
        //计算比例
        foreach ($data as $key => $value) {
            $sum+=$value->money;
        }
        $result=null;
        foreach ($data as $k => $v) {
             $result=$sign->where('id',$v->id)->update(['money'=>rand($money*$v->money/$sum,2)]);
             //$user->where('user_id',$v->user_id)->update()
            
        }
        $data=$sign->where("rain_coin")->order('rain_coin')->limit(10);
        $res;
        foreach ($data as $key => $value) {
            
            $list=$user->where('user_id',$v->user_id);
            foreach ($user as $k => $v) {
                if($v->user_id==$v->user_id){
                    $res=$user->where('user_id',$user_id)->update(['money'=>$value->money+$v->money]);
                }
            }
        }
       if(!$result==0&&$res!=0){
                action_log('activity_sign_money', 'admin_activity');
                Db::commit();

             }
        }catch(\Exception $e){
            action_log('activity_sign_money_failed', 'admin_activity');
            Db::rollback();
           $this->error('修改失败，请重试');
        }
    }
    
}