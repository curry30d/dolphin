<?php
namespace app\shop\event;
use Payment\Notify\PayNotifyInterface;
use Payment\Config;
use think\Log;
use think\Db;
/** 
* 接口回调事件总入口 
* 客户端需要继承该接口，并实现这个方法，在其中实现对应的业务逻辑 
* Class TestNotify 
*/
class PayCall implements PayNotifyInterface{    
    /**     * 回调进程     */
    public function notifyProcess(array $data)    {
            //http://域名/index/plugin/execute/_plugin/Pay/_controller/Admin/_action/notify/model/模块名.html
            // $log = new Log();
            // $log->write(json_encode($data),'debug');
            // //记录日志 在runtime/log/pay/下看记录
            // $channel = $data['channel'];
            // if ($channel === Config::ALI_CHARGE) {
            //      // 支付宝支付            
            //     $log->write('我是支付宝支付','debug');        
            // } elseif ($channel === Config::WX_CHARGE) {
            //      // 微信支付            
            //      $log->write('我是微信支付','debug');        
            // } else {            
            //      // 其它类型的通知            
            //      $log->write('未知事件','debug');
            // } 

       
              //DB::name('member_users')->where('mobile',18379411407)->update('money',0.01);
              //return json_encode($data);
            //return json_decode($data);
            //echo "1111111111111111";    
                // 执行业务逻辑，成功后返回true
           
           //var_dump($data);
            //echo "hello world";
            /// "success";
            //die;
            //$data['subject'];
            //var_dump($data['trade_status']);
            
            if( $data['trade_status'] == 'TRADE_SUCCESS'){
                $data['total_amount'];
                if($data['passback_params']=='shop_charge'){
                     $res = Db::name('member_users')->where('id',$data['subject'])->setInc('money',$data['total_amount']);
                     $is_charge = DB::name('member_users')->where('id',$data['subject'])->value('is_charge');

                     if( $data['total_amount']>=100 && $is_charge == 0){
                        $res = db('member_users')->where('id',$data['subject'])->update(['is_charge'=>1]);
                     }
                }else{
                     $res = db('shop_orders')->where('uid',$data['subject'])->update(['sttus'=>2]);

                }
                //$money = DB::name('member_users')->where('id',$data['subject'])->value('money');
//                $money+=$data['total_amount'];
                //var_dump($money);
                   //$res = Db::name('member_users')->where('id',$data['subject'])->setInc('money',$data['total_amount']);
                   if($res){
                    return 'success';
                  }else{
                    return 'fail';
                    //return $this->result('',0,'支付失败','json');
                  }
            }else{
                return 'fail';
               //return $this->result('',0,'支付失败','json');
            }   
                //return true;    
      }
 }