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

use think\facade\Hook;

/**
 * 用户公开控制器，不经过权限认证
 * @package app\activity\admin
 */
class Login extends Common
{
    /**
     * 用户登录
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function login()
    {  
        if ($this->request->isPost()) {
            // 获取post数据
               $data = $this->request->post();
               //验证数据
               $result = $this->validate($data, 'User.login');
               if(true!=$result){
                  return  $this->error($result);
               }

               $user=UserModel::where('mobile',$data['mobile'])->find();
               if($user==null){
                  $res=UserModel::create($data);
                  action_log('activity_login', 'admin_activity', $res->id, $res->id);
                  return $this->success('新增成功', 'index');
               }else{
                   return  $this->error('已经存在用户，请勿在邀请');
               }

        }else{
             $this->error('数据不能为空');
        }
    }

    
}