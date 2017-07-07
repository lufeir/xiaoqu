<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/6
 * Time: 14:06
 */

namespace Admin\Controller;




class CenterController extends AdminController
{
    //小区租售列表
    public function index(){
        //获取数据
       $sale=M('sale')->where(['status'=>1])->select();
       $this->assign('sale',$sale);
       $this->meta_title ='小区管理';
       $this->display();
    }

    //增加小区租售
    public function add(){
        if(IS_POST){
            $Sale=D('Sale');
            $data=$Sale->create();
            if($data){
                $data['start_time']=time();
                $data['end_time']=strtotime($data['end_time']);
                $data['status']=1;
                $id=$Sale->add($data);
                if($id){
                   $this->success('添加成功',U('index'));
                    //记录行为
                    action_log('update_sale', 'sale', $id, UID);
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($Sale->getError());
            }
        }else{
            $this->assign('info',null);
            $this->display('edit');
        }
    }

    //编辑小区租售
    public function edit($id=0){
        if(IS_POST){
            $Sale=D('Sale');
            $data=$Sale->create();
            if($data){
                //var_dump($Sale->save());exit;
                if($Sale->save()){
                    //记录操作
                    action_log('update_sale','sale',$id,UID);
                    $this->success('修改成功',U('index'));
                }else{
                    $this->error('修改失败');
                }
            }else{
                $this->error($Sale->getError());
            }
        }else{
            $info=M('Sale')->find($id);
            $this->assign('id',$id);
            $this->assign('info',$info);
            $this->display();
        }
    }

    //删除
    public function del(){
        $id=array_unique((array)I('get.id',0));
        if(empty($id)){
            $this->error('请选择你要删除的数据');
        }
        $map=array(array('id'=>array('in',$id)));
        if(M('Sale')->where($map)->delete()){
            action_log('update_sale','sale',$id,UID);
            $this->success('删除成功',U('index'));
        }else{
            $this->error('删除失败');
        }
    }



}