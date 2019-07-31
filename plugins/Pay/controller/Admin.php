<?php
// +----------------------------------------------------------------------
// | 快鱼管理
// +----------------------------------------------------------------------
// | 版权所有 2017~2019 江西快鱼科技有限公司 [ http://www.p2cn.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://p2cn.com
// +----------------------------------------------------------------------
// | 创建者    allenlinc（allenlinc@gmail.com）
// +----------------------------------------------------------------------
// | 创建时间  2018/5/29 下午1:08
// +----------------------------------------------------------------------
namespace plugins\Pay\controller;
use app\common\controller\Common;
use think\Request;
use Payment\Common\PayException;
use Payment\Client\Charge;
use Payment\Config;
use Payment\Client\Notify;
use think\Log;
use Payment\Notify\AliNotify;

/**
 * 插件后台控制器
 * @package plugins\Pay\controller
 * 参考https://helei112g1.gitbooks.io/payment-sdk/content/chapter1/zhi-fu-bao.html文档
 */
class Admin extends Common
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * APP支付方法
     * @param $type string  类型  例: ALI_CHANNEL_APP
     * @param $order array  订单数据
     */
    public function PayApp($type,$order){
        switch ($type){
            case 'ALI_CHANNEL_APP'://支付宝APP支付
                self::aliPayApp($order);
                break;
            case 'WX_CHANNEL_APP'://微信APP支付
                self::wxPayApp($order);
                break;
            default:
              $this->result('',0,'参数错误','json');
        }
    }

    public function notify(){

      
        //开发模式异步通知日记分离，方便查看
//        if(config('develop_mode')){
//            Log::init([
//                'type'  =>  'File',
//                'path'  =>  LOG_PATH.'/pay/',
//            ]);
//        }
        // echo "hello world";
        // die;
        //获取XML信息
        //$type = null;
        //$postXml = file_get_contents("php://input");
        //var_dump($postXml);
//        Log::write($postXml,'debug');
        //判断是否是xml
        //if(xml_parse(xml_parser_create(),$postXml)){
            //$postXml = json_decode(json_encode(simplexml_load_string($postXml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        //}
        //echo $postXml;
        //die;
        $type=null;
        
        if (input('get.auth_app_id') == plugin_config('Pay.zfbAppid')) { //传过来的post数据appid与配置相同 则是支付宝支付
            $config = self::aliConfig();//获取支付宝支付配置
            $type = 'ali_charge';
           
        } //elseif ($postXml['mch_id'] == plugin_config('Pay.wxMchId')) {
            //$config = self::wxConfig();//获取微信支付配置
            //$type = 'wx_charge';
        //} 
            //else{
          //  return false;
        //}
       
    
           //Log::write('这是测试','debug');
        //运行方法
        //http://app18517.p2cn.com/index/plugin/execute/_plugin/Pay/_controller/Admin/_action/notify/model/模块名.htm ， 文件夹下创建 PayCall.php文件
        //获取模块参数
        // $params = $_SERVER["REQUEST_URI"];
        
        // $params = explode('/',$params)[11];
        // var_dump($params);
        // $params = str_replace('.html','',$params);
        // echo  $params;
        // die;
        $callbackstr = '\app\shop\event\PayCall';
        //var_dump($callbackstr);
        //echo $type;
        //ali_chargefail
        //echo Config::ALI_CHARGE;
        $callback = new $callbackstr();
        //$callback->notifyProcess();
        //var_dump($callback);
       
        
        $retData = Notify::getNotifyData($type, $config);
        //$retData['channel'] = $type;

        //var_dump($retData);
        //$res=$callback->notifyProcess($retData);
        //var_dump($res);
        $AliNotify = new AliNotify($config);
        //$AliNotify->getNotifyData($retData);
        //$retData = $AliNotify->getRetData($retData);
        //var_dump($retData);
        //echo $AliNotify->getTradeStatus( $retData['trade_status']);


        //var_dump($AliNotify->getNotifyData());
        var_dump($AliNotify->checkNotifyData($retData));
        //var_dump($retData);
        die;
        //var_dump($AliNotify->callback($callbackstr,$retData));
        //var_dump($AliNotify->handle($callback));
       
        //var_dump($retData);
        



       try {
    // 获取第三方的原始数据，未进行签名检查，根据自己需要决定是否需要该步骤
    //$retData = Notify::getNotifyData($type, $config);
       $ret = Notify::run($type, $config, $callback);// 处理回调，内部进行了签名检查 
        //$ret = $callback->notifyProcess($retData);// 处理回调，内部进行了签名检查
       } catch (PayException $e) {
    //echo $e->errorMessage();
         exit;
       }
 
         return $ret;


        //$ret = Notify::run($type, $config, $callback);// 处理回调，内部进行了签名检查  
        //$ret = Notify::run($config, $config, $callback);// 处理回调，内部进行了签名检查  
        //var_dump($config);
        //var_dump($ret);
        //echo "success";
        //echo $ret;

    }

    /**
     * 支付宝APP密钥
     * @param $order
     */
    private function aliPayApp($order){
        // 订单信息
        if(array_key_exists("orderno",$order)){
            $orderNo = $order['orderno'];
        }else{
            $orderNo = 'KY_'.time() . rand(1000, 9999);
        }
        //返回值
        $reParam = $order['model'];
        $payData = [
            'body'    => $order['body'],
            'subject'    => $order['subject'],
            'order_no'    => $orderNo,
            'timeout_express' => time() + plugin_config('Pay.zfbexpire'),// 表示必须 XXXs 内付款
            'amount'    => $order['amount'],// 单位为元 ,最小为0.01
            'return_param' => $reParam,
            'client_ip' => get_client_ip(0,true),// 客户地址
            'goods_type' => '1',// 0—虚拟类商品，1—实物类商品
            'store_id' => '',
        ];
        //通知
        if(array_key_exists('nurl',$order)){
            $nurl = $order['nurl'];
        }else{
            $nurl = null;
        }

        if(array_key_exists('rurl',$order)){
            $rurl = $order['rurl'];
        }else{
            $rurl = null;
        }

        try {
            $str = Charge::run(Config::ALI_CHANNEL_APP,self::aliConfig($nurl,$rurl),$payData);
            $this->result($str,1,'成功','json');
        } catch (PayException $e) {
            return 100;
            $this->result('',0,'失败','json');
        }
    }

    public function swxpay($order){
        $res=self::wxPayApp2($order);
        return $res;
    }


    /**
     * 微信APP密钥
     * @param $order
     */
    private function wxPayApp($order){
        $wxConfig = self::wxConfig();

        // 订单信息
        if(array_key_exists("orderno",$order)){
            $orderNo = $order['orderno'];
        }else{
            $orderNo =  'KY_'.time() . rand(1000, 9999);
        }

        //返回值
        $reParam = $order['model'];

        $payData = [
            'body'    => $order['body'],
            'subject'    => $order['subject'],
            'order_no'    => $orderNo,
            'timeout_express' => time() + plugin_config('Pay.wxexpire'),// 表示必须 xxxs 内付款
            'amount'    => $order['amount'],// 微信沙箱模式，需要金额固定为3.01
            'return_param' => $reParam,
            'client_ip' => get_client_ip(0,true),// 客户地址
        ];

        //通知
        if(array_key_exists('nurl',$order)){
            $nurl = $order['nurl'];
        }else{
            $nurl = null;
        }

        if(array_key_exists('rurl',$order)){
            $rurl = $order['rurl'];
        }else{
            $rurl = null;
        }

        try {
            $ret = Charge::run(Config::WX_CHANNEL_APP, $wxConfig, $payData);
            $this->success('成功','',$ret);
        } catch (PayException $e) {
            $this->error($e->errorMessage());
        }

    }



    private function wxPayApp2($order){
        $wxConfig = self::wxConfig();

        // 订单信息
        if(array_key_exists("orderno",$order)){
            $orderNo = $order['orderno'];
        }else{
            $orderNo =  'KY_'.time() . rand(1000, 9999);
        }

        //返回值
        $reParam = $order['return_param'];

        $payData = [
            'amount'    => $order['amount'],// 微信沙箱模式，需要金额固定为3.01
            'body'    => $order['body'],
            'client_ip' => get_client_ip(0,true),// 客户地址
            'order_no'    => $orderNo,
            'return_param' => $reParam,
            'subject'    => $order['subject'],
            'timeout_express' => time() + plugin_config('Pay.wxexpire'),// 表示必须 xxxs 内付款
        ];
        #var_dump($wxConfig);
        #var_dump($payData);die;

        //通知
        if(array_key_exists('nurl',$order)){
            $nurl = $order['nurl'];
        }else{
            $nurl = null;
        }

        if(array_key_exists('rurl',$order)){
            $rurl = $order['rurl'];
        }else{
            $rurl = null;
        }
        $ret = Charge::run(Config::WX_CHANNEL_APP, $wxConfig, $payData);
        return $ret;
    }


    /**
     * 支付宝配置
     * @param null $nurl  异步通知
     * @param null $rurl  同步通知  H5中使用
     * @return array
     */
    public function aliConfig($nurl = null,$rurl = null){
        //判断支付宝是否启用
        if(!plugin_config('Pay.zfbStatus')){
            $this->result('',0,'支付宝被关闭,请启用','json');
        }

        return [
            'use_sandbox'               => (bool)plugin_config('Pay.zfbSandbox'),// 是否使用沙盒模式
            'app_id'                    => plugin_config('Pay.zfbAppid'),
            'sign_type'                 => 'RSA2',// RSA  RSA2
            // ！！！注意：如果是文件方式，文件中只保留字符串，不要留下 -----BEGIN PUBLIC KEY----- 这种标记
            // 可以填写文件路径，或者密钥字符串  当前字符串是 rsa2 的支付宝公钥(开放平台获取)
            'ali_public_key'            => plugin_config('Pay.zfbRAS'),
            // ！！！注意：如果是文件方式，文件中只保留字符串，不要留下 -----BEGIN RSA PRIVATE KEY----- 这种标记
            // 可以填写文件路径，或者密钥字符串  我的沙箱模式，rsa与rsa2的私钥相同，为了方便测试
            'rsa_private_key'           => plugin_config('Pay.zfbpsn'),
            'limit_pay'                 => explode(',',plugin_config('Pay.zfbLimitpay')),// 用户不可用指定渠道支付当有多个渠道时用“,”分隔
            // 与业务相关参数
            'notify_url'                => is_null($nurl) ? plugin_config('Pay.zfbnotify') : $nurl,
            'return_url'                => is_null($rurl) ? plugin_config('Pay.zfbredirect') : $rurl,
            'return_raw'                => false,// 在处理回调时，是否直接返回原始数据，默认为 true
        ];
    }

    /**
     * 微信配置
     */
    public function wxConfig($nurl = null,$rurl = null){
        //判断微信是否启用
        if(!plugin_config('Pay.wxStatus')){
            $this->result('',0,'微信支付被关闭,请启用','json');
        }

        return [
            'app_id'            => plugin_config('Pay.wxAppId'),// 公众账号ID
            'app_cert_pem'      => MULU.get_file_path(plugin_config('Pay.wxAppCertPem')),
            'app_key_pem'       => MULU.get_file_path(plugin_config('Pay.wxAppKeyPem')),
            'fee_type'          => 'CNY',// 货币类型  当前仅支持该字段
            'limit_pay'         => explode(',',plugin_config('Pay.wxLimitPay')),// 指定不能使用信用卡支付   不传入，则均可使用
            'mch_id'            => plugin_config('Pay.wxMchId'),// 商户id
            'md5_key'           => plugin_config('Pay.wxMd5Key'),// md5 秘钥
            'notify_url'        => is_null($nurl) ? plugin_config('Pay.wxnotify') : $nurl,
            'redirect_url'      => is_null($rurl) ? plugin_config('Pay.wxredirect') : $rurl,// 如果是h5支付，可以设置该值，返回到指定页面
            'return_raw'        => false,// 在处理回调时，是否直接返回原始数据，默认为true
            'sign_type'         => 'MD5',// MD5  HMAC-SHA256
            'use_sandbox'       => (bool)plugin_config('Pay.wxSandbox')// 是否使用 微信支付仿真测试系统
        ];
    }

}