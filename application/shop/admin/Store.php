<?php
namespace app\shop\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\shop\model\Store as StoreModel;
use app\user\model\Role as RoleModel;
use app\user\model\User as AdminModel;

/**
 * 门店管理
 * @package app\shop\admin
 */
class Store extends Admin{

    public function index(){
        // 查询
        $map = $this->getMap();

        //过滤管理员
        $parents = getAdminChilds(UID);
        $map[] = ['aid','in',$parents];

        // 排序
        $order = $this->getOrder('update_time desc');


        // 数据列表
        $data_list = StoreModel::where($map)->order($order)->paginate();

        return ZBuilder::make('table')
            ->setSearch(['catename' => '分类名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['sort','排序','text.edit'],
                ['title','门店名称','text'],
                ['create_time', '创建时间', 'datetime'],
                ['update_time', '更新时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->setTableName('shop_store')
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('create_time,update_time,status,sort')
            ->setRowList($data_list) // 设置表格数据
            ->assign('empty_tips','没有任何数据')
            ->fetch(); // 渲染模板
    }

    public function add()
    {
        //保存表单
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            //zone坐标分割
            if($data['zone']){
                $zone = explode(',',$data['zone']);
                $data['location_x'] = $zone[0];
                $data['location_y'] = $zone[1];
            }

            // 验证
            $result = $this->validate($data, 'Store');
            if(true !== $result) $this->error($result);

            if ($result = StoreModel::create($data)) {
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addGroup(
                [
                    '基本' => [
                        ['hidden', 'aid',UID],
                        ['text','sort','排序','按数字大小排序分类前后',99],
                        ['text', 'title', '门店名称','必填'],
                        ['linkages','area','地区','必填','basic_area',4,'','id,title,pid'],
                        ['text','address','地址',''],
                        ['text','tel','联系电话'],
                        ['textarea','description','介绍'],
                        ['image','logo','门店LOGO'],
                        ['radio', 'status', '立即启用', '', ['否', '是'], 1]
                    ],
                    '营业' => [
                        ['text','delivery_time','营业时间'],
                        ['radio','delivery_type','营业类型','',['全部不支持','支持自提','支持核销','支持自提和核销'],0]
                    ],
                    '位置' => [
                        ['bmap','zone','位置',module_config('basic.basic_bmap_sn')]
                    ],
                    '其他' => [
                        ['text','bossname','联系人姓名'],
                        ['text','bosstel','联系人电话'],
                        ['image','voucher','营业执照'],
                    ]
                ]
            )
            ->fetch();
    }

    public function edit($id = '')
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            //zone坐标分割
            if($data['zone']){
                $zone = explode(',',$data['zone']);
                $data['location_x'] = $zone[0];
                $data['location_y'] = $zone[1];
            }

            unset($data['zone']);
            unset($data['zone_address']);

            // 验证
            $result = $this->validate($data, 'Store');
            if(true !== $result) $this->error($result);

            if (StoreModel::update($data)) {
                // 记录行为
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = StoreModel::get($id);
        $info = $info->getData();
        $info['zone'] = $info['location_x'].','.$info['location_y'];
        $info['zone_address'] = '';


        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addGroup(
                [
                    '基本' => [
                        ['hidden','id'],
                        ['text','sort','排序','按数字大小排序分类前后',99],
                        ['text', 'title', '门店名称','必填'],
                        ['linkages','area','地区','必填','basic_area',4,'','id,title,pid'],
                        ['text','address','地址',''],
                        ['text','tel','联系电话'],
                        ['textarea','description','介绍'],
                        ['image','logo','门店LOGO'],
                        ['radio', 'status', '立即启用', '', ['否', '是'], 1]
                    ],
                    '营业' => [
                        ['text','delivery_time','营业时间'],
                        ['radio','delivery_type','营业类型','',['全部不支持','支持自提','支持核销','支持自提和核销'],0]
                    ],
                    '位置' => [
                        ['bmap','zone','位置',module_config('basic.basic_bmap_sn')]
                    ],
                    '其他' => [
                        ['text','bossname','联系人姓名'],
                        ['text','bosstel','联系人电话'],
                        ['image','voucher','营业执照'],
                    ]
                ]
            )
            ->setFormdata($info)
            ->fetch();
    }

}

