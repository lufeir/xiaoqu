<?php
/**
 * Created by PhpStorm.
 * User: 李政宇
 * Date: 2017/7/8
 * Time: 11:26
 */

namespace Wechat\Controller;


use Think\Controller;

class ServiceController extends Controller
{
   public function index(){
       $start=0;
       $pagesze=1;
       $news=M('document');
       $p=I('get.p');
       $map=array('onethink_document.category_id'=>42,'onethink_document.status'=>1);
       $list=$news->join('onethink_picture  on onethink_document.cover_id=onethink_picture.id')->where($map)->field('onethink_document.*,onethink_picture.path')->limit($start,$pagesze)->select();
       $this->assign('list',$list);
       if($p){
           $list=$news->join('onethink_picture  on onethink_document.cover_id=onethink_picture.id')->where($map)->field('onethink_document.*,onethink_picture.path')->limit($p,$pagesze)->select();
           if($list){
               $data=['status'=>1,'data'=>$list];
           }else{
               $data=['status'=>0];
           }
           $this->ajaxReturn($data);
       }
       $this->display('index');
   }
    public function content(){
        $id=I('get.id');
        $news=M('Document');
        $map=array('a.id'=>$id);
        $list=$news->alias('a')->join(array('onethink_document_article b on a.id=b.id','onethink_member c on a.uid=c.uid'))->where($map)->field('a.*,b.content,c.nickname')->select();
        if($list==null){
            $this->error('消息已失效！');
        }
        M('Document')->where('id='.$id)->setInc('view');
        $this->assign('list',$list);
        $this->display('content');
    }
}