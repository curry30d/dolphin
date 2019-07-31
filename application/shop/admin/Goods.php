<?php
namespace app\shop\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\shop\model\Category as GoodsCategoryModel;
use app\shop\model\Choose as ChooseModel;
use app\shop\model\Goods as GoodsModel;
use app\shop\model\Store as StoreModel;

/**
 * 商品管理
 * @package app\cms\home
 */
class Goods extends Admin{

    public function index(){
        // 查询
        $map = $this->getMap();

        $columns = [ // 批量添加数据列
            ['id', 'ID'],
            ['name', '商品名称', 'text.edit'],
            ['sort','排序','text.edit'],
            ['create_time', '创建时间', 'datetime'],
            ['update_time', '更新时间', 'datetime'],
            ['status', '状态', 'switch']
        ];

        //过滤管理员
        if(session('user_auth.role') == 4){
            $map[] = ['aid','=',session('user_auth.pid')];
            $map[] = ['status','=',1];

            //商品选中列表
            $goods_choose = db('shop_store_choosegoods')->where('aid',UID)->value('choose');

            $columns[] = ['choose','选中','callback',function($data,$goodss_choose){
                $goodsId = $data['id'];
                $isIn = in_array($goodsId,explode(',',$goodss_choose));
                if($isIn){
                    return "<i class='fa fa-check text-success'></i>";
                }else{
                    return "<i class='fa fa-ban text-danger'></i>";
                }
            },'__data__',$goods_choose];
        }else{
            $parents = getAdminChilds(UID);
            $map[] = ['aid','in',$parents];
            $columns[] = ['right_button', '操作', 'btn'];
        }

        //增加选中商品按钮
        $btn_add_choose = [
            'title' => '添加商品',
            'icon'  => 'fa fa-fw fa-plus-circle',
            'class' => 'btn btn-success ajax-post',
            'href'  => url('addchoose')
        ];
        //删除选中商品按钮
        $btn_del_choose = [
            'title' => '取消商品',
            'icon'  => 'fa fa-fw fa-minus-circle',
            'class' => 'btn btn-danger ajax-post',
            'href'  => url('delchoose')
        ];

        // 排序
        $order = $this->getOrder('update_time desc');

        // 数据列表
        $data_list = GoodsModel::where($map)->order($order)->paginate();

        return ZBuilder::make('table')
            ->setSearch(['name' => '名称']) // 设置搜索框
            ->addColumns($columns)
            ->setTableName('shop_goods')
            ->addTopButtons('add,enable,disable,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,name,create_time,update_time,status,sort')
            ->setRowList($data_list) // 设置表格数据
            ->addTopButton('addchoose',$btn_add_choose)
            ->addTopButton('delchoose',$btn_del_choose)
            ->assign('empty_tips','没有任何数据')
            ->fetch(); // 渲染模板
    }

    public function add()
    {
        //保存表单
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            //复选保存
            $data['goods_attr'] = implode(',',$data['goods_attr']);

            // 验证
            $result = $this->validate($data, 'GoodsCategoryModel');
            if(true !== $result) $this->error($result);

            if ($result = GoodsModel::create($data)) {
                $this->success('新增成功', 'index');
            } else {
                $this->error('新增失败');
            }
        }

        //商品分类列表
        $cate_list = GoodsCategoryModel::all();
        $cate_now_list = [];
        foreach($cate_list as $cate){
            $cate_now_list[$cate['id']] = $cate['catename'];
        }
        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addGroup(
                [
                    '基本信息' => [
                        ['hidden', 'aid',UID],
                        ['radio','status','上架','',['否','是'],1],
                        ['text','sort','排序','按数字大小排序分类前后',99],
                        ['text', 'name', '商品名称','必填'],
                        ['text','short_name','商品简称','简短场景使用，非必填'],
                        ['text','title','副标题','副标题长度请控制在100个字以内'],
                        ['tags','keyword','关键字','搜索查询产品关键字，能精确搜索到产品'],
                        ['text', 'weight', '单个商品重量','必填'],
                    ],
                    '商品信息' => [
                        ['select','goods_type','产品分类','',$cate_now_list,0],
                        ['datetime','publish_time','发布时间','可以控制产品这个时间之后可见',date('Y-m-d H:i:s')],
                        ['number','goods_price','商品价格','',0,0,100000,100],
                        ['number','info_stock','库存数','',0],
                    ],
                    '商品属性' => [
                        ['linkages','grow_area','产地','必填','basic_area',4,'','id,title,pid'],
                        ['tags','standards','规格'],
                        ['checkbox','goods_attr','商品属性','',['新品','热卖','包邮']],
                        ['text','goods_lvl','级别'],
                        ['text','storetype','存储方式'],
                        ['radio', 'type', '商品类型', '商品类型，商品保存后无法修改，请谨慎选择', ['实体商品', '虚拟商品','虚拟商品(卡密) ','批发商品','记次/时商品'], 0],
                    ],
                    '商品图片' => [
                        ['image','goods_pic','商品图片'],
                        ['images','goods_pics','商品幻灯片']
                    ],
                    '商品详情' => [
                        ['ueditor','info_desc','详情']
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

            //复选保存
            $data['goods_attr'] = implode(',',$data['goods_attr']);

            // 验证
            $result = $this->validate($data, 'Goods');
            if(true !== $result) $this->error($result);

            if (GoodsModel::update($data)) {
                // 记录行为
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }

        $info = GoodsModel::get($id);
        $info = $info->getData();

        //商品分类列表
        $cate_list = GoodsCategoryModel::all();
        $cate_now_list = [];
        foreach($cate_list as $cate){
            $cate_now_list[$cate['id']] = $cate['catename'];
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->setPageTips('如果出现无法添加的情况，可能由于浏览器将本页面当成了广告，请尝试关闭浏览器的广告过滤功能再试。', 'warning')
            ->addGroup(
                [
                    '基本信息' => [
                        ['hidden', 'id'],
                        ['radio','status','上架','',['否','是']],
                        ['text','sort','排序','按数字大小排序分类前后'],
                        ['text', 'name', '商品名称','必填'],
                        ['text','short_name','商品简称','简短场景使用，非必填'],
                        ['text','title','副标题','副标题长度请控制在100个字以内'],
                        ['tags','keyword','关键字','搜索查询产品关键字，能精确搜索到产品'],
                    ],
                    '商品信息' => [
                        ['select','goods_type','产品分类','',$cate_now_list],
                        ['datetime','publish_time','发布时间','可以控制产品这个时间之后可见',date('Y-m-d H:i:s')],
                        ['number','goods_price','商品价格','','',0,100000,100],
                        ['number','info_stock','库存数',''],
                    ],
                    '商品属性' => [
                        ['linkages','grow_area','产地','必填','basic_area',4,'','id,title,pid'],
                        ['tags','standards','规格'],
                        ['checkbox','goods_attr','商品属性','',['新品','热卖','包邮']],
                        ['text','goods_lvl','级别'],
                        ['text','storetype','存储方式'],
                        ['radio', 'type', '商品类型', '商品类型，商品保存后无法修改，请谨慎选择', ['实体商品', '虚拟商品','虚拟商品(卡密) ','批发商品','记次/时商品'], 0],
                    ],
                    '商品图片' => [
                        ['image','goods_pic','商品图片'],
                        ['images','goods_pics','商品幻灯片']
                    ],
                    '商品详情' => [
                        ['ueditor','info_desc','详情']
                    ]
                ]
            )
            ->setFormdata($info)
            ->fetch();
    }

    /**
     * 增加选择商品
     * @param $ids array  选中的商品ID
     */
    public function addchoose($ids){

        $choose_list = ChooseModel::where('aid',UID)->find();

        if($choose_list){
            $choose_array = explode(',',$choose_list['choose']);

            $choose = array_merge($ids,$choose_array);
            $choose = array_unique($choose);
            $choose = implode(',',$choose);

            $data['choose'] = $choose;
            ChooseModel::where('aid',UID)->update($data);

        }else{
            $data['aid'] = UID;
            $data['choose'] = implode(',',$ids);
            ChooseModel::create($data);
        }
        $this->result('',1,'保存成功','json');
    }

    /**
     * 删除选择商品
     * @param $ids
     */
    public function delchoose($ids){
        $choose_list = ChooseModel::where('aid',UID)->find();

        if($choose_list){
            $choose_array = explode(',',$choose_list['choose']);

            $choose_array = array_diff($choose_array,$ids);
            $choose = implode(',',$choose_array);

            $data['choose'] = $choose;
            ChooseModel::where('aid',UID)->update($data);
            $this->result('',1,'保存成功','json');

        }else{
            $this->result('',0,'你还未添加商品','json');
        }
    }

}

