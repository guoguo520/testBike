<?php
header("content-type:text/html;charset=utf-8");
class BikeAction extends Action {
     public function offerBike(){
          if(!$_SESSION['nickName']){
               $this->redirect('__APP__/Index/index');
          }
          else{
              //头像
               $m=M('User');
               $where['username']=$_SESSION['username'];
               $userPhoto=$m->where($where)->getField('headPhoto',1);
               $userExt=$m->where($where)->getField('extension',1);
               $this->assign('userPhoto',$userPhoto);
               $this->assign('userExt',$userExt);
               $this->display();             //输出模板
          }
          // var_dump($userInfo);
     }

     public function do_offerBike(){	//供车处理
          if(!$_SESSION['nickName']){
               $this->redirect('__APP__/Index/index');
          }
          else{
          	$bikeData['userId']=$_SESSION['userId'];
          	$bikeData['brand']=$_POST['bikeBrand'];
               $bikeData['phone']=$_POST['phone'];
               $bikeData['price']=$_POST['price'];
               $bikeData['location']=$_POST['location'];
          	$bikeData['howOld']=$_POST['howOld'];
          	$bikeData['bikeKinds']=$_POST['bikeKinds'];
          	$bikeData['sexKinds']=$_POST['sexKinds'];

               //如果上传车辆照片开始
               if(!empty($_FILES['photo']['tmp_name'])){
                    $id=$_SESSION['userId'];

                    import('ORG.Net.UploadFile');
                    $upload = new UploadFile();// 实例化上传类
                    $upload->maxSize  = 3145728 ;// 设置附件上传大小

                    // 设置附件上传类型
                    $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');

                    // 设置附件上传目录
                    $upload->savePath =  './Uploads/bikeImgs/'.$id.'/';

                     //php基本语法部分开始，主要任务用于截取上传文件，文件名  
                    $fileName=$_FILES["photo"]["name"];  //这样就能够取得上传的文件名 

                    //通过对$fileName的处理，就能够取得上传的文件的后缀名 
                    $fileExtensions=strrchr($fileName, '.');   

                    //文件重命名“用户Id_上传时间”
                    $serverFileName=$_SESSION['userId']."_".time();

                    //设置在服务器保存的文件名 
                    $upload->saveRule=$serverFileName;

                    if(!$upload->upload()) {      // 上传错误提示错误信息
                         $this->error($upload->getErrorMsg());
                    }else{
                         $info =  $upload->getUploadFileInfo();
                         
                    }
                    //车辆照片上传结束

                    $bikeData['bikeImg']=$serverFileName; 
                    $bikeData['bikeExtension']=$fileExtensions;
               }

               // 保存表单数据 包括附件数据
          	$bikelist=M('Bikelist');
               //将车辆信息存到bikelist表中
          	$bikelist->add($bikeData); 

               $bikeId=$bikelist->max('bikeId');
               $offer=M('Offer_record');
               $offerData['bikeId']=$bikeId;
               $offerData['userId']=$_SESSION['userId'];
               $offerData['offerTime']=date('Y-m-d H-i-s');
               //车辆状态：0：未借出，1：已借出，2：已还车，3：已停供
               $offerData['offerState']=0;   

               //将车辆信息存到Offer_record表中
               $i=$offer->add($offerData); 

          	if($i>0){
          		$this->success('供车成功！');
          	}
          	else{
          		$this->error('供车失败！');
          	}
          }
     	
     }

}

?>