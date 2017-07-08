<?php
/**
 * Created by PhpStorm.
 * User: 李政宇
 * Date: 2017/7/6
 * Time: 11:49
 */
namespace Admin\Controller;
class CenterController extends AdminController{
    public function index(){
        $User = M('Sale'); // 实例化User对象
        $count      = $User->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where('status=1')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list as &$list1){
            $list1['status']=$list1['status']==1?'启用':'禁用';
            $list1['type']=$list1['type']==1?'出租':'销售';
            $list1['price_status']=$list1['price_status']==1?'元/月':'万元';
        }
//        赋值分页输出
        $this->assign('list', $list);
        $this->assign('page',$show);
        $this->display();
    }
    public function add(){
          if(IS_POST){
              $Sale = D('Sale');
              $data=$Sale->create();
              if($data){
                 $data['end_time']=strtotime($data['end_time']);
                 $data['create_time']=time();
                  $id = $Sale->add($data);
                  if($id){
                      $this->success('新增成功', U('index'));
                      //记录行为
                      action_log('update_sale', 'sale', $id, UID);
                  } else {
                      $this->error('新增失败');
                  }
              } else {
                  $this->error($Sale->getError());
              }

          }else {
              $pid = I('get.pid', 0);
              $this->assign('pid', $pid);
              $this->assign('info',null);
              $this->meta_title = '添加租售';
              $this->display('edit');
          }
    }
    public function edit($id = 0){
        if(IS_POST){
            $Channel = D('Sale');
            $data = $Channel->create();
            if($data){
                $data['end_time']=strtotime($data['end_time']);
                if($Channel->save($data)){
                    //记录行为
                    action_log('update_sale', 'sale', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($Channel->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Sale')->find($id);
            $info['end_time']=date('Y-m-d', $info['end_time']);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = I('get.pid', 0);

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑租售';
            $this->display();
        }
    }
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Sale')->where($map)->delete()){
            //记录行为
            action_log('update_sale', 'sale', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map = array('id' => array('in', $id) );
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Sale', $map );
                break;
            case 'resumeuser':
                $this->resume('Sale',$map);
                break;
            case 'deleteuser':
                $this->delete('Sale', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

}