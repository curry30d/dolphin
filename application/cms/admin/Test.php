<?php
namespace app\cms\admin;

use app\admin\controller\Admin;
use think\Db;
use app\common\builder\ZBuilder;
/**
 * 仪表盘控制器
 * @package app\cms\admin
 */
class Test extends Admin
{
    /**
     * 首页
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function index()
    {
       

    return ZBuilder::make('form')->setPageTitle('添加')->fetch();
    }
    public function add(){


    }
}