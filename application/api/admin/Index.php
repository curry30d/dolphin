<?php
namespace app\api\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder; // 引入ZBuilder
use think\Db;
use think\Request;
use app\api\model\Student;
use app\api\model\Area;
use think\facade\Validate;
/**
 * api 后台模块
 */
class Index extends Admin
{
	public function index(){
		//return ZBuilder::make('table')->fetch();
 // 获取查询条件

        $map = $this->getMap();
		$data_list = Db::name('student')->where($map)->select();
		
        $data_list= Student::where($map)->order("id desc")->select()->toarray();
        
 // 使用ZBuilder快速创建数据表格
 return ZBuilder::make('table')
     ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
     ->setSearch(['id' => 'ID', 'name' => '用户名', 'city' => '地区']) // 设置搜索参数
     ->addColumns([ // 批量添加列
         ['id', 'ID'],
         ['name', '用户名'],
         ['sex', '性别'],
         ['city', '地区'],
         ['right_button', '操作', 'btn'],
     ])
     ->addRightButtons('edit,delete') // 批量添加右侧按钮
     ->setRowList($data_list) // 设置表格数据
     ->fetch(); // 渲染页面
		
	}

	public function add(){


		// 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            
            $result = $this->validate($data, 'Index');
            if (true !== $result) $this->error($result);
            $res=Area::where('id',$data['area'])->select()->toarray();
            
            //DB::table('dp_area')->where('id',$data['area'])->select();
            $arr[]=$res[0]['name'];
            while($res[0]['pid']){
        	   $res=DB::table('dp_area')->where('id',$res[0]['pid'])->select();
        	   $arr[]=$res[0]['name'];
            }
            $str=null;
            for ($i=count($arr);$i--;$i>0){
        	   $str.=$arr[$i];
            }
            if($data['sex']=='man'){
        	   $sex=1;
            }elseif($data['sex']=='female'){
        	   $sex=2;
            }else{
        	   $sex=0;
            }

            $result = Student::create(['name'=>$data['name'],'sex'=>$sex,'city'=>$str,'city_id'=>$data['area']]);
            //$stu=new Student();
           //$result=$stu->allowField(true)->save(['name'=>$data['name'],'sex'=>$sex,'city'=>$str,'city_id'=>$data['area']]);
           
            if ($result) {
                // 记录行为
                $action=action_log('student_add', 'student', $result->id, UID, $data['area']);
                
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

		return ZBuilder::make('form')
		->addText('name', '姓名')
		->addRadio('sex', '性别', '', ['man' => '男', 'female' => '女', 'umkonw' => '未知'], 'man')
		->addLinkages('area', '选择所在地区', '', 'area', 3)
	    ->setUrl('/dolphin_test/public/admin.php/api/index/add')
         ->fetch();
	}


    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');
         

        // 保存数据
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $result = $this->validate($data, 'Index');
            if (true !== $result) $this->error($result);
            // 禁止修改超级管理员的角色和状态
            if ($data['id'] == 1 && $data['role'] != 1) {
                $this->error('禁止修改超级管理员角色');
            }

            // 禁止修改超级管理员的状态
            if ($data['id'] == 1 && $data['status'] != 1) {
                $this->error('禁止修改超级管理员状态');
            }

            $res=Area::where('id',$data['area'])->select()->toarray();
            $arr[]=$res[0]['name'];
            while($res[0]['pid']){
        	   $res=Area::where('id',$res[0]['pid'])->select()->toarray();
        	   $arr[]=$res[0]['name'];
            }
            $str=null;
            for ($i=count($arr);$i--;$i>0){
        	   $str.=$arr[$i];
            }

           $result=Student::where('id',$data['id'])->update(['id'=>$data['id'],'name'=>$data['name'],'sex'=>$data['sex'],'city'=>$str,'city_id'=>$data['area']]);
           
           //var_dump($data['name'],$str,$data['area'],$id,$data['sex']);
           var_dump($result);
            if ($result) {
                // 记录行为
                action_log('student_edit', 'student', $data['id'], UID, $data['area']);
                //action_log('link_add', 'cms_link', $result['id'], UID, $data['area']);
                $this->success('修改成功', 'index');
            } else {
                $this->error('修改失败');
            }
           
        }
       
        // // 获取数据
        // $info = UserModel::where('id', $id)->field('password', true)->find();

        // // 角色列表
        // if (session('user_auth.role') != 1) {
        //     $role_list = RoleModel::getTree(null, false, session('user_auth.role'));
        // } else {
        //     $role_list = RoleModel::getTree(null, false);
        // }

        // 使用ZBuilder快速创建表单

  //       return ZBuilder::make('form')
  //       ->setPageTitle('编辑') // 设置页面标题
		// ->addText('name', '姓名')
		// ->addRadio('sex', '性别', '', ['man' => '男', 'female' => '女', 'umkonw' => '未知'], 'man')
		// ->addLinkages('area', '选择所在地区', '', 'area', 3)
	 //     ->setUrl('/dolphin_test/public/admin.php/api/index/add')
  //        ->fetch();

        $data_list = Student::where('id',$id)->select()->toarray();
        
        return ZBuilder::make('form')
            ->setPageTitle('编辑') // 设置页面标题
            ->addFormItems([ // 批量添加表单项
                ['hidden', 'id'],
                ['text', 'name', '姓名', '可以是中文'],
                ['radio', 'sex', '性别', '', ['未知','男', '女']]
            ])
            ->addLinkages('area', '选择所在地区', '', 'area', 3,$data_list[0]['city_id'])
              ->setFormData($data_list[0]) // 设置表单数据
            // ->setFormData($info) // 设置表单数据
            ->fetch();
    }

    public function delete($ids = [], $table = '')
    {
        //if ($ids === null) $this->error('参数错误');

        //$ids    = is_array($ids) ? '' : $ids;
        $document_title = Db::name('student')->where('id', 'in', $ids)->column('name');
        
        $res=Db::name("student")->delete($ids);
        if($res){
             // 删除并记录日志
            action_log('student_delete', 'student', $ids, UID, implode('、', $document_title));
        	$this->success('删除成功');
        }else{
        	 $this->error('删除失败');
        }
        // 移动文档到回收站
        // if (false === Db::name("student")->where('id', 'in', $ids)->setField('trash', 1)) {
        //     $this->error('删除失败');
        // }

        // 删除并记录日志
        //action_log('_trash', $table, $document_id, UID, implode('、', $document_title));
        //$this->success('删除成功');
    }
}