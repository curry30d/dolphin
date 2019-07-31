<?php
namespace app\shop\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\shop\model\Category as GoodsCategoryModel;


/**
 * 商品分类管理
 * @package app\shop\admin
 * @author allenlinc <allenlinc@gmail.com>
 */
class GoodsCategory extends Admin{

    /**
     * 商品分类列表
     */
    public function index(){
        // 查询
        $map = $this->getMap();

        //过滤管理员
        $parents = getAdminChilds(UID);
        $map[] = ['aid','in',$parents];

        // 排序
        $order = $this->getOrder('update_time desc');

        // 数据列表
        $data_list = GoodsCategoryModel::where($map)->order($order)->paginate();

        //分类列表
        $cate_list = GoodsCategoryModel::all();
        $cate_now_list = [];
        $cate_now_list[0] = '顶层';
        foreach($cate_list as $cate){
            $cate_now_list[$cate['id']] = $cate['catename'];
        }

        return ZBuilder::make('table')
            ->setSearch(['catename' => '分类名称']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['cateimg','图标','img_url'],
                ['catename', '分类名称', 'text.edit'],
                ['pid','上级分类','text'],
                ['sort','排序','text.edit'],
                ['create_time', '创建时间', 'datetime'],
                ['update_time', '更新时间', 'datetime'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
            ->setTableName('shop_goods_category')
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,catename,create_time,update_time,status,sort')
            ->setRowList($data_list) // 设置表格数据
            ->addValidate('GoodsCategory', 'catename')
            ->addFilter('pid',$cate_now_list)
            ->assign('empty_tips','没有任何数据')
            ->fetch(); // 渲染模板

    }

    /**
     * 添加商品分类
     */
    public function add()
    {
        //保存表单
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'GoodsCategory');
            if(true !== $result) $this->error($result);

            if ($result = GoodsCategoryModel::create($data)) {
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        //分类列表
        $cate_list = GoodsCategoryModel::all();
        $cate_now_list = [];
        $cate_now_list[0] = '顶层';
        foreach($cate_list as $cate){
            $cate_now_list[$cate['id']] = $cate['catename'];
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'aid',UID],
                ['select','pid','上级分类','',$cate_now_list,0],
                ['text','sort','排序','按数字大小排序分类前后',99],
                ['text', 'catename', '分类名称'],
                ['textarea','description','介绍'],
                ['image','cateimg','图标'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1]
            ])
            ->fetch();
    }

    public function edit($id = '')
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            // 验证
            $result = $this->validate($data, 'GoodsCategory');
            if(true !== $result) $this->error($result);

            if (GoodsCategoryModel::update($data)) {
                // 记录行为
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = GoodsCategoryModel::get($id);
        $info = $info->getData();

        //分类列表
        $cate_list = GoodsCategoryModel::all();
        $cate_now_list = [];
        $cate_now_list[0] = '顶层';
        foreach($cate_list as $cate){
            $cate_now_list[$cate['id']] = $cate['catename'];
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addFormItems([
                ['hidden', 'id'],
                ['select','pid','上级分类','',$cate_now_list],
                ['text','sort','排序','按数字大小排序分类前后',99],
                ['text', 'catename', '分类名称'],
                ['textarea','description','介绍'],
                ['image','cateimg','图标'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1]
            ])
            ->setFormdata($info)
            ->fetch();
    }
}
