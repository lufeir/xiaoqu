<?php
/**
 * Created by PhpStorm.
 * User: 李政宇
 * Date: 2017/7/7
 * Time: 17:49
 */

namespace Wechat\Controller;


use Think\Controller;

class NoticeController extends Controller
{
    public function notice(){
        $news=M('document');
        $list=$news->join('onethink_picture  on onethink_document.cover_id=onethink_picture.id')->field('onethink_document.*,onethink_picture.path')->select();
        $this->assign('list',$list);

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
        $this->assign('list',$list);
        $this->display('content');
    }
}