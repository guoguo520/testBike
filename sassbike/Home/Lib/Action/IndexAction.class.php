<?php
header("content-type:text/html;charset=utf-8");
class IndexAction extends Action {
     public function index(){
          import("ORG.Util.Page"); //导入分页类
          if(!$_SESSION['nickName']){
               $Model = D("Offer_recordView");
               $where1['Offer_record.offerState']=0;

               $count=$Model->where($where1)->count();

                //实例化分页类,传入总记录数和每页显示的记录数
               $Page=new Page($count,12);     
               $page=$Page->show();     //分页显示输出

               $bikeInfos=$Model->field('offerId,bikeId,userId,nickName,brand,bikeKinds,sexKinds,price,phone,location,offerTime,offerState,bikeImg,bikeExtension')->where($where1)->limit($Page->firstRow .','.$Page->listRows)->select();

               for($i=0;$i<$count;$i++){
                    if($bikeInfos[$i]['sexKinds']=='m'){
                         $bikeInfos[$i]['sexKinds']='男式';
                    }
                    elseif($bikeInfos[$i]['sexKinds']=='f'){
                         $bikeInfos[$i]['sexKinds']='女式';
                    }

                    if($bikeInfos[$i]['bikeKinds']=='bike'){
                         $bikeInfos[$i]['bikeKinds']='自行车';
                    }
                    elseif($bikeInfos[$i]['bikeKinds']=='elect'){
                         $bikeInfos[$i]['bikeKinds']='电动车';
                    }
               }
               // var_dump($bikeInfos);
               $this->assign('bikeInfo',$bikeInfos);    
               $this->assign('page',$page);  //赋值分页输出
          }
          else{
               $Model = D("Offer_recordView");
               $where1['Offer_record.offerState']=0;
               $where1['Offer_record.userId']=array('neq',$_SESSION['userId']);

               $count=$Model->where($where1)->count();

                //实例化分页类,传入总记录数和每页显示的记录数
               $Page=new Page($count,12);     
               $page=$Page->show();     //分页显示输出

               $bikeInfos=$Model->field('offerId,bikeId,userId,nickName,brand,bikeKinds,sexKinds,price,phone,location,offerTime,offerState,bikeImg,bikeExtension')->where($where1)->limit($Page->firstRow .','.$Page->listRows)->select();

               for($i=0;$i<$count;$i++){
                    if($bikeInfos[$i]['sexKinds']=='m'){
                         $bikeInfos[$i]['sexKinds']='男式';
                    }
                    elseif($bikeInfos[$i]['sexKinds']=='f'){
                         $bikeInfos[$i]['sexKinds']='女式';
                    }

                    if($bikeInfos[$i]['bikeKinds']=='bike'){
                         $bikeInfos[$i]['bikeKinds']='自行车';
                    }
                    elseif($bikeInfos[$i]['bikeKinds']=='elect'){
                         $bikeInfos[$i]['bikeKinds']='电动车';
                    }
               }

               $this->assign('bikeInfo',$bikeInfos);    
               $this->assign('page',$page);  //赋值分页输出
          }
            
          $this->display();             //输出模板   
     }

     public function do_search(){
          import("ORG.Util.Page"); //导入分页类

          $skey=$_POST['skey'];
          $Kinds=M('Kinds');

          $where["searchKey"]=array("like","%$skey%");
          $bike=$Kinds->where($where)->find();
          $prop=$bike['prop'];
          $propKey=$bike['propKey'];

          $Model = D("Offer_recordView");
          $where1['Offer_record.offerState']=0;

          if($propKey='bike' || $propKey=='elect'){
               $where["Bikelist.bikeKinds"]=array("like","%$propKey%");
          }elseif ($propKey='m' || $propKey=='f') {
               $where["Bikelist.sexKinds"]=array("like","%$propKey%");
          }

          $count=$Model->where($where1)->count();

           //实例化分页类,传入总记录数和每页显示的记录数
          $Page=new Page($count,12);     
          $page=$Page->show();     //分页显示输出

          $bikeInfos=$Model->field('offerId,bikeId,userId,nickName,brand,bikeKinds,sexKinds,price,phone,location,offerTime,offerState,bikeImg,bikeExtension')->where($where1)->limit($Page->firstRow .','.$Page->listRows)->select();

          for($i=0;$i<$count;$i++){
               if($bikeInfos[$i]['sexKinds']=='m'){
                    $bikeInfos[$i]['sexKinds']='男式';
               }
               elseif($bikeInfos[$i]['sexKinds']=='f'){
                    $bikeInfos[$i]['sexKinds']='女式';
               }

               if($bikeInfos[$i]['bikeKinds']=='bike'){
                    $bikeInfos[$i]['bikeKinds']='自行车';
               }
               elseif($bikeInfos[$i]['bikeKinds']=='elect'){
                    $bikeInfos[$i]['bikeKinds']='电动车';
               }
          }

         
          // var_dump($bikeInfos);
          $this->assign('bikeInfo',$bikeInfos);    
          $this->assign('page',$page);  //赋值分页输出

          $this->ajaxReturn(1,$propKey,1);
     }

     public function moreBikeInfo(){
          //头像
          $m=M('User');
          // $where['username']=$_SESSION['username'];
          // $userPhoto=$m->where($where)->getField('headPhoto',1);
          // $userExt=$m->where($where)->getField('extension',1);
          // $this->assign('userPhoto',$userPhoto);
          // $this->assign('userExt',$userExt);

          //车辆信息
          $offerId=$_GET['offerId']; 
          $offer=M('Offer_record');
          $offerInfo=$offer->where('offerId='.$offerId)->find();

          $bikeId=$offerInfo['bikeId'];
          $offerTime=$offerInfo['offerTime'];

          $bikelist=M(Bikelist);
          $where1['bikeId']=$bikeId;
          $data=$bikelist->where($where1)->select();
          $data[0]['offerTime']=$offerTime;
          $data[0]['offerId']=$offerId;
          if($data[0]['bikeKinds']=='bike'){
               $data[0]['bikeKinds']=0; //自行车
          }
          elseif ($data[0]['bikeKinds']=='elect') {
               $data[0]['bikeKinds']=1; //电动车
          }

          if($data[0]['sexKinds']=='m'){
               $data[0]['sexKinds']=0;       //男
          }
          elseif ($data[0]['sexKinds']=='f') {
               $data[0]['sexKinds']=1;       //女
          }
        
          $userWhere['userId']=$data[0]['userId'];
          $nickName=$m->where($userWhere)->getField('nickName',1);
          $data[0]['nickName']=$nickName;
            
          // var_dump($data);
          $this->assign('bikeInfo',$data);
          $this->display(); 
    }

    public function do_borrowBike(){
          if(!$_SESSION['nickName']){
               $this->redirect('__APP__/User/login');
          }
          else{
               $offerId=$_GET['offerId'];
               //修改供车记录表里的offerState为1
               $offer=M('Offer_record');
               $where['offerId']=$offerId;
               $bikeInfo=$offer->where($where)->find();

               //向借车记录表里插入一条记录
               $borrowRecord['bikeId']=$bikeInfo['bikeId'];
               $borrowRecord['userId']=$bikeInfo['userId'];
               $borrowRecord['borrowerId']=$_SESSION['userId'];
               $borrowRecord['offerId']=$bikeInfo['offerId'];
               $borrowRecord['borrowTime']=date('Y-m-d H-i-s');
               $borrowRecord['borrowState']=0;    //未还

               $borrow=M('Borrow_record');
               $borrow->add($borrowRecord);

               $bikeInfo['offerState']=1;    //已借,向供车表保存车辆状态
               $bikeInfo['borrowerId']=$_SESSION['userId'];
               $bikeInfo['borrowId']=$borrow->max('borrowId'); 
               $i=$offer->where($where)->save($bikeInfo); 
               
               $url=__URL__.'/borrowBike';
               $data['newUrl'] = $url;
               if($i){
                    $this->ajaxReturn($data,"借车成功！",1);
               }
               else{
                    $this->ajaxReturn(0,"借车失败！",0);
               }
               
          }
    }
}


