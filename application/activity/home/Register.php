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
use think\Db;
//use app\admin\model\Module as ModuleModel;
//use think\facade\Hook;

/**
 * 用户公开控制器，不经过权限认证
 * @package app\activity\admin
 */
class Register extends Common
{
    /**
     * 用户登录
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function register()
    {  

        if ($this->request->isPost()) {
            // 获取post数据
               $data = $this->request->post();
               //验证数据
            
               $result = $this->validate($data, 'User.register');
               if(true!=$result){
                  return  $this->error($result);
               }
               $user=UserModel::where('mobile',$data['mobile'])->find();
               
               if($user==null){

                  $data['password']=md5($data['password']);
                  $res=Db('admin_module')->where('name','activity')->select();
                  $result=json_decode($res[0]['config']);
                  //读取福利的设置值
                  $data['welfare']= $result->welfare;
                  $res=UserModel::create($data);
                  session('uid',$res->id);
                  action_log('activity_register', 'admin_activity', $res->id, $res->id);
                  return $this->success('注册成功');
               }else{
                   if($user->password===$data['password']){
                       action_log('activity_login', 'admin_activity', $user->user_id, $user->user_id);
                       return  $this->success('已经存在用户，登录成功');
                   }
                   return  $this->error('已经存在用户，密码或用户名错误');
               }

        }else{
             $this->error('数据不能为空');
        }
    }

    
}