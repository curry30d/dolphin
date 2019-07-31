<?php
namespace app\shop\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;

use app\shop\model\Order as OrderModel;

/**
 * 订单管理
 * @package app\shop\admin
 */
class Order extends Admin{

    public function index($group = '-1'){
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = OrderModel::where($map)->order($order)->paginate();

        //tab
        $list_tab = [
            '-1' => ['title' => '全部', 'url' => url('index', ['group' => '-1'])],
            '0' => ['title' => '待抢单', 'url' => url('index', ['group' => '0'])],
            '1' => ['title' => '待付款', 'url' => url('index', ['group' => '1'])],
            '2' => ['title' => '待发货', 'url' => url('index', ['group' => '2'])],
            '3' => ['title' => '配送中', 'url' => url('index', ['group' => '3'])],
            '4' => ['title' => '待评价', 'url' => url('index', ['group' => '4'])],
            '5' => ['title' => '完成', 'url' => url('index', ['group' => '5'])]
        ];

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addOrder('id,offer,address_id')
            ->setSearch(['id' => 'ID']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['order_id', '订单号'],
                ['uid','用户','callback',function($data){
                    return $data['username'].'('.$data['id'].')';
                }],
                ['offer','出价'],
                ['address_id','收货地址','callback',function($data){
                    return $data['area'][0].$data['area'][1].$data['area'][2];
                }],
                ['status','状态','status','',['待抢单','待付款','待发货','配送中','待评价','完成']],


                ['right_button', '操作', 'btn']
            ])
            ->setTabNav($list_tab,  $group)
            ->addTopButtons('enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('Link', 'title,url')
            ->fetch(); // 渲染模板
    }

    public function add()
    {
        return parent::add(); // TODO: Change the autogenerated stub
    }

    public function edit($id = '')
    {
        return parent::edit($id); // TODO: Change the autogenerated stub
    }

}

