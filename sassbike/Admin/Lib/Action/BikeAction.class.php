<?php
// 本类由系统自动生成，仅供测试用途
class BikeAction extends Action {
    public function bikeList(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
            $user=M('User');

            import("ORG.Util.Page");    //导入分页类
            $m=M('Bikelist');
            $count=$m->count();
            $Page=new Page($count,6);   //实例化分页类
            $page=$Page->show();    //分页显示输出

            $bikeInfos=$m->order('bikeId')->limit($Page->firstRow .','.$Page->listRows)->select();

            $count1=count($bikeInfos);
            for ($i=0; $i < $count1; $i++) {
                if($bikeInfos[$i]['userId']==0){
                    $bikeInfos[$i]['nickName']='官方';
                }
                else{
                    $userId=$bikeInfos[$i]['userId']; 
                    $nickName=$user->where('userId='.$userId)->getField('nickName');
                    $bikeInfos[$i]['nickName']=$nickName;
                }
                
                
                if($bikeInfos[$i]['sexKinds']=='m'){
                    $bikeInfos[$i]['sexKinds']='男式';
                }elseif ($bikeInfos[$i]['sexKinds']=='f') {
                    $bikeInfos[$i]['sexKinds']='女式';
                }

                if($bikeInfos[$i]['bikeKinds']=='bike'){
                    $bikeInfos[$i]['bikeKinds']='自行车';
                }elseif($bikeInfos[$i]['bikeKinds']=='elect'){
                    $bikeInfos[$i]['bikeKinds']='电动车';
                }
            }
            $this->assign('bikeInfo',$bikeInfos);
            $this->assign('page',$page);    //赋值分页输出
            $this->display();
        }
        
    }

    public function addBike(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$this->display();
        }
    }

    public function alterBike(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$bikeId=$_GET['bikeId'];

        	$bike=M('Bikelist');
        	$bikeInfo=$bike->where('bikeId='.$bikeId)->find();
        	
        	$this->assign('bikeInfo',$bikeInfo);
        	$this->display();
        }
    }

    public function do_alterBike(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$bikeId=$_GET['bikeId'];
        	$userId=$_GET['userId'];

        	$bikeData['brand']=$_POST['bikeBrand'];
            $bikeData['phone']=$_POST['phone'];
            $bikeData['price']=$_POST['price'];
            $bikeData['location']=$_POST['location'];
          	$bikeData['howOld']=$_POST['howOld'];
          	$bikeData['bikeKinds']=$_POST['bikeKinds'];
          	$bikeData['sexKinds']=$_POST['sexKinds'];

          	//如果上传车辆照片开始
           if(!empty($_FILES['photo']['tmp_name'])){

                import('ORG.Net.UploadFile');
                $upload = new UploadFile();// 实例化上传类
                $upload->maxSize  = 3145728 ;// 设置附件上传大小

                // 设置附件上传类型
                $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');

                // 设置附件上传目录
                if($userId==0){
                	$upload->savePath =  './Uploads/bikeImgs/admin/';
                }else{
                	$upload->savePath =  './Uploads/bikeImgs/'.$userId.'/';
                }

                 //php基本语法部分开始，主要任务用于截取上传文件，文件名  
                $fileName=$_FILES["photo"]["name"];  //这样就能够取得上传的文件名 

                //通过对$fileName的处理，就能够取得上传的文件的后缀名 
                $fileExtensions=strrchr($fileName, '.');   

                //文件重命名“0管理员Id_上传时间”
                $serverFileName=$userId."_".time();

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


        	$bike=M('Bikelist');
        	$i=$bike->where('bikeId='.$bikeId)->save($bikeData);
        	
        	if($i){
        		$this->success('修改车辆信息成功！');
        	}else{
        		$this->error('修改车辆信息失败！');
        	}
        }
    }

    public function do_deleteBike(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$bikeId=$_GET['bikeId'];
        	$bike=M('Bikelist');
        	$i=$bike->where('bikeId='.$bikeId)->delete();
        	if($i){
        		$this->success('删除成功！');
        	}else{
        		$this->error('删除失败！');
        	}
        }
    }

    public function do_addBike(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$bikeData['userId']=$_SESSION['adminNum']-1;

          	$bikeData['brand']=$_POST['bikeBrand'];
            $bikeData['phone']=$_POST['phone'];
            $bikeData['price']=$_POST['price'];
            $bikeData['location']=$_POST['location'];
          	$bikeData['howOld']=$_POST['howOld'];
          	$bikeData['bikeKinds']=$_POST['bikeKinds'];
          	$bikeData['sexKinds']=$_POST['sexKinds'];

           //如果上传车辆照片开始
           if(!empty($_FILES['photo']['tmp_name'])){
                $id=$_SESSION['adminNum'];

                import('ORG.Net.UploadFile');
                $upload = new UploadFile();// 实例化上传类
                $upload->maxSize  = 3145728 ;// 设置附件上传大小

                // 设置附件上传类型
                $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');

                // 设置附件上传目录
                $upload->savePath =  './Uploads/bikeImgs/admin/';

                 //php基本语法部分开始，主要任务用于截取上传文件，文件名  
                $fileName=$_FILES["photo"]["name"];  //这样就能够取得上传的文件名 

                //通过对$fileName的处理，就能够取得上传的文件的后缀名 
                $fileExtensions=strrchr($fileName, '.');   

                //文件重命名“0管理员Id_上传时间”
                $serverFileName=$_SESSION['adminNum']."_".time();

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
            $offerData['userId']=$_SESSION['adminNum']-1;
            $offerData['offerTime']=date('Y-m-d H-i-s');
            //车辆状态：0：未借出，1：已借出，2：已还车，3：已停供
            $offerData['offerState']=0;   

           //将车辆信息存到Offer_record表中
           $i=$offer->add($offerData); 

          	if($i>0){
          		$this->success('添加成功！');
          	}
          	else{
          		$this->error('添加失败！');
          	}
        }
    }
   
}
?>