<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/9
 * Time: 10:16
 */

namespace Wechat\Controller;


use Think\Controller;

class XQactiveController extends Controller
{

    public function index(){
        $page=0;
        $pagesize=2;
        $service=M('document');
        //在首页不现实过期的数据 状态改为0
        $data['status'] = 0;
        $service->data($data)->where('category_id=43 AND deadline<'.time())->save();



//


        $list=$service->where('status=1 AND category_id=41')->limit($page,$pagesize)->select();
        $this->assign('list',$list);
        $p=I('get.p');
        if($p) {

            $list = $service->where('status=1 AND category_id=43')->limit(($p - 1) * $pagesize, $pagesize)->select();

            foreach ($list as &$lt) {

                $picture = M('picture');
                $path_info = $picture->where('id=' . $lt['cover_id'])->find();

                $path = $path_info['path'];
                $lt['cover_id'] = $path;


            }
//

            if ($list) {
                $data = [
                    'status' => 1,
                    'data' => $list,
                ];
            } else {
                $data = ['status' => 0];

            }

            $this->ajaxReturn($data);
            $this->assign('list', $list);
        }
        $this->display();
    }


    public function detail($id=0){
        if($id){

            $infos=M('document');
            $info=$infos->find($id);
//            dump($infos->view);exit;
            $infos->view+=1;
//           var_dump($view);exit;
            $infos->where('id='.$id)->save();


            $doc_info=M('document_article')->find($id);


            $this->assign('info',$info);
            $this->assign('doc_info',$doc_info);
            $this->meta_title = '小区活动';
            $this->display();

        }

    }

    public function baoming(){

        if(!is_login()){

           $data=[
               'status'=>0,
                'info'=>'请登录后再报名！',
                'url'=>U('User/login')
           ];

        }else{

                $id=I('get.id');
                //获取用户名
            $username=session('user_auth')['username'];
                $active=M('active');
                //获取到活动表中数据
            $document=M('document')->where('id='.$id)->find();

                //判断active 表中是否存在该用户和该活动
            $abc=$active->where("active_id=$id AND username='$username'")->find();
               if($abc==null){


                   $infos=[
                       'username'=>$username,
                       'active_id'=>$document['id'],
                       'title'=>$document['title'],
                       'description'=>$document['description'],
                       'deadline'=>$document['deadline'],
                       'create_time'=>time(),
                       'status'=>1
                   ];


                   $active->data($infos)->add();

                   $data=[
                       'status'=>1,
                       'info'=>'报名成功！',
                       'url'=>U('Index/my')

                   ];

               }else{
                   $data=[
                       'status'=>2,
                       'info'=>'你已经申请过该活动，看看其他活动吧',


                   ];
               }



            }

//        }


        $this->ajaxReturn($data);

    }
}