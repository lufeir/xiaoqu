<?php
/**
 * Created by PhpStorm.
 * User: 李政宇
 * Date: 2017/7/7
 * Time: 15:32
 */

namespace Wechat\Controller;


use Think\Controller;

class SaleController extends Controller
{
   public function index(){
       $Sale=M('Sale');
       $map=array('status'=>1,'type'=>1);
       $list = $Sale->where($map)->select();
       $map1=array('status'=>1,'type'=>2);
       $list2 = $Sale->where($map1)->select();
       foreach ($list as &$list1){
           $list1['price_status']=$list1['price_status']==1?'元/月':'万元';
       }
       foreach ($list2 as &$list1){
           $list1['price_status']=$list1['price_status']==1?'元/月':'万元';
       }
       $this->assign('list',$list);
       $this->assign('list2',$list2);
       $this->display();
   }
   public function content(){
       $id=I('get.id');
       $Sale=M('Sale');
       $map=array('id'=>$id);
       $list=$Sale->where($map)->select();
       if($list==null){
           $this->error('商品不存在！');
       }
       foreach ($list as &$list1){
           $list1['price_status']=$list1['price_status']==1?'元/月':'万元';
       }

       $this->assign('list',$list);
       $this->display('content');
   }
}