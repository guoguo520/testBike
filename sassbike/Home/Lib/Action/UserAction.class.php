<?php
	header("content-type:text/html;charset=utf-8");
	class UserAction extends Action {
		public function login(){
			$this->display();
		}

		public function do_login(){	//用户登录
			//获取用户名和密码等，与数据库中的比较，有就跳转到Index/index
			$tel=$_POST['tel'];
			$password=md5($_POST['password']);
			// var_dump($password);
			$code=$_POST['code'];
			if($_SESSION['verify']!==md5($code)){
				$this->error('验证码错误！');
			}

			$m=M('User');
			$where['username']=$tel;
			$where['password']=$password;

			$userInfo=$m->where($where)->find();
			$nickName=$userInfo['nickName'];
			$username=$userInfo['username'];
			$userId=$userInfo['userId'];

			$i=$m->where($where)->count();
			if($i>0){
				$_SESSION['nickName']=$nickName;
				$_SESSION['username']=$username;
				$_SESSION['userId']=$userId;

				$this->redirect('Index/index');
			}
			else{
				// $this->ajaxReturn(0,'用户名或密码错误！',0);
				$this->error('用户名或密码错误！');
			}
		}

		public function do_loginOut(){	//退出系统
			foreach ($_SESSION as $key => $value) {
				$_SESSION[$key]=null;
			}
			$this->redirect('__APP__/Index/index');

		}

		public function register(){	
			$this->display();

		}

		public function do_register(){	//用户注册
			import('ORG.Util.Date');// 导入日期类
			$m=M('User');
			$data['username']=$_POST['userName'];
			$data['nickName']=$_POST['nickName'];
			$data['password']=$_POST['userPassword'];


			$data['joinTime']= date('Y-m-d H-i-s');

			$data['phone']=$_POST['userName'];
			$i=$m->add($data);
			
			if($i){
                $this->ajaxReturn($i,"注册成功！",1);
            }
            else{
                $this->ajaxReturn(0,"注册失败！",0);
            }
		}

	    public function personInfo(){	//输出个人信息
	     	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		$m=M('User');
	     		$where['userId']=$_SESSION['userId'];
	     		$userInfo=$m->where($where)->find();

	     		
	     		$userId=$userInfo['userId'];
	     		$offer=M('Offer_record');
	     		//供车次数
	     		$offerCount=$offer->where('userId='.$userId)->count();
	     		$userInfo['offerCount']=$offerCount;

	     		$borrow=M('Borrow_record');
	     		//供车次数
	     		$borrowCount=$borrow->where('userId='.$userId)->count();
	     		$userInfo['borrowCount']=$borrowCount;

	     		// var_dump($userInfo);
	     		$this->assign('userInfo',$userInfo); //赋值数据集
	     		$this->display();			//输出模板
	     	}

	     	
	     }

	    public function do_saveInfo(){	//保存修改的个人信息
	    	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
		    	$id=$_SESSION['userId'];
		    	// 要修改的数据对象属性赋值
		    	$m=M('User');
				$data['nickName'] = $_POST['nickName'];
				$_SESSION['nickName']=$data['nickName'];	//修改session
				
				$data['sex'] = $_POST['sex'];
				if($data['sex']=='m'){
					$data['sex']=0;	//男
				}
				else if($data['sex']=='f'){
					$data['sex']=1;	//女
				}
				$data['phone']=$_POST['phone'];
				$where['userId']=$id;

				// 根据条件保存修改的数据
				$i=$m->where($where)->save($data); 
				if($i>0){
					$this->success('修改成功！');
				}
				else{
					$this->error('修改失败！');
				}
			}
	    }

	     //自定义删除指定目录下的所有文件及文件夹
	    
	     
	    Public function upload(){		//头像上传
	    	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
		    	$id=$_SESSION['userId'];

				import('ORG.Net.UploadFile');
				$upload = new UploadFile();// 实例化上传类
				$upload->maxSize  = 3145728 ;// 设置附件上传大小
				$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->savePath =  './Uploads/headPhotos/'.$id.'/';// 设置附件上传目录

				 //截取上传文件，文件名 ，这样就能够取得上传的文件名 
		        $fileName=$_FILES["photo"]["name"];	 

				//通过对$fileName的处理，就能够取得上传的文件的后缀名 
		        $fileExtensions=strrchr($fileName, '.');	 

		        //文件重命名“用户Id_上传时间”
		        $serverFileName=$_SESSION['userId']."_".time();
			    
			    //1、使用basename($uriString)我们可以得到一个包含扩展名的文件名，如果不需要扩展名，也可以使用basename($uriString, $extString)过滤扩展名，仅仅返回文件名。  
		        //2、date("Ymd")能够返回一个YYMMDD格式的字符串  
		        //3、mt_rand()能够返回一个随机数  
		        //将以上三个东西拼接起来  
		        //php基本语法部分结束  

		        $upload->saveRule=$serverFileName;//设置在服务器保存的文件名 

		        //上传前删除原来的头像
	            deldir($upload->savePath);

				if(!$upload->upload()) {	// 上传错误提示错误信息
					$this->error($upload->getErrorMsg());
				}else{
					$info =  $upload->getUploadFileInfo();
					
				}

				// 保存表单数据 包括附件数据
				$User = M("User"); // 实例化User对象
				
				$where['userId']=$id;
				$data = array(
					'headPhoto'=>$serverFileName,
					'extension'=>$fileExtensions
				);
				// 将用户头像保存到user表中
				$User-> where($where)->setField($data); 
				$this->success('数据保存成功！');
			}
		}

		//借车记录
	    public function borrowRecord(){	
	     	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		//头像
	     		$m=M('User');
	     		$where['userId']=$_SESSION['userId'];
	     		$userPhoto=$m->where($where)->getField('headPhoto',1);
	     		$userExt=$m->where($where)->getField('extension',1);
	     		$this->assign('userPhoto',$userPhoto);
	     		$this->assign('userExt',$userExt);

	     		import("ORG.Util.Page");	//导入分页类

	     		if($this->isGet()){
	     			$borrowState=$_GET['borrowState'];	//获取车辆状态
	     		}
	     		
	     		$borrow=D('Borrow_recordView');

				if($borrowState==1 || $borrowState==2){
	     			$where1['Borrow_record.borrowState']=$borrowState-1;
	     		}
	     		
	     		$where1['Borrow_record.borrowerId']=$_SESSION['userId'];
	     		$count=$borrow->where($where1)->count();

	     		//总记录数和每页显示的记录数
				$Page=new Page($count,12);	
				$page=$Page->show();	//分页显示输出

				$bikeInfos=$borrow->field('borrowId,bikeId,userId,borrowerId,offerId,borrowTime,borrowState,backTime,allTime,allPrice,nickName,brand,price,location,bikeImg,bikeExtension')->where($where1)->limit($Page->firstRow .','.$Page->listRows)->select();

				for($i=0;$i<$count;$i++){
					if($bikeInfos[$i]['bikeKinds']=='bike'){
		     			$bikeInfos[$i]['bikeKinds']='自行车';	//自行车
		     		}
		     		elseif ($bikeInfos[$i]['bikeKinds']=='elect') {
		     			$bikeInfos[$i]['bikeKinds']='电动车';	//电动车
		     		}

		     		if($bikeInfos[$i]['sexKinds']=='m'){
		     			$bikeInfos[$i]['sexKinds']='男式';		//男
		     		}
		     		elseif ($bikeInfos[$i]['sexKinds']=='f') {
		     			$bikeInfos[$i]['sexKinds']='女式';		//女
		     		}					
	     		}
	     		// var_dump($bikeInfos);
	     		// $this->ajaxReturn($bikeInfos,'查询成功！',1);

	     		$this->assign('bikeInfo',$bikeInfos);	     	
	     		$this->assign('page',$page);	//赋值分页输出
		     	$this->display();
		    }
	     }

	     public function moreBorrowInfo(){	//借车记录里的车辆详细信息
	     	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		//头像
	     		$m=M('User');
	     		$where['userId']=$_SESSION['userId'];
	     		$userPhoto=$m->where($where)->getField('headPhoto',1);
	     		$userExt=$m->where($where)->getField('extension',1);
	     		$this->assign('userPhoto',$userPhoto);
	     		$this->assign('userExt',$userExt);

	     		  //车辆信息
               	$borrowId=$_GET['borrowId']; 
               	$borrow=D('Borrow_recordView');
               	$where1['borrowId']=$borrowId;
               	$bikeInfo=$borrow->field('borrowId,bikeId,userId,borrowerId,offerId,borrowTime,borrowState,backTime,allTime,allPrice,nickName,brand,howOld,phone,sexKinds,bikeKinds,location,price,bikeImg,bikeExtension')->where($where1)->select();
               	
				if($bikeInfo[0]['bikeKinds']=='bike'){
	     			$bikeInfo[0]['bikeKinds']='自行车';	//自行车
	     		}
	     		elseif ($bikeInfo[0]['bikeKinds']=='elect') {
	     			$bikeInfo[0]['bikeKinds']='电动车';	//电动车
	     		}

	     		if($bikeInfo[0]['sexKinds']=='m'){
	     			$bikeInfo[0]['sexKinds']='男式';		//男
	     		}
	     		elseif ($bikeInfo[0]['sexKinds']=='f') {
	     			$bikeInfo[0]['sexKinds']='女式';		//女
	     		}

		     	// var_dump($bikeInfo);

				$this->assign('bikeInfo',$bikeInfo);
               	

	     		$this->display();
	     	}

	    }

	    public function do_backBike(){		//还车操作
	     	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		$borrowId=$_GET['borrowId'];
	     		$where['borrowId']=$borrowId;

	     		$borrow=M(Borrow_record);
	     		$borrowInfo=$borrow->where($where)->find();

	     		$bikeId=$borrowInfo['bikeId'];
	     		$borrowState=$borrowInfo['borrowState'];
	     		$borrowTime=$borrowInfo['borrowTime'];
	     		$offerId=$borrowInfo['offerId'];

	     		$offer=M('Offer_record');
	     		$offerwhere['offerId']=$offerId;
	     		$offerData['offerState']=2;	//改为已还车
	     		$offer->where($offerwhere)->save($offerData);//保存状态

	     		$backTime=date('Y-m-d H:i:s');
	     		$data['backTime']=$backTime;
	     		$data['borrowState']=1;	//状态改为已还车

	     		$backTime1=strtotime($backTime);
     			$borrowTime1=strtotime($borrowTime);
     			
				$allSecond=$backTime1-$borrowTime1;	//总秒数
				$day=floor($allSecond/86400);	//天数

				$allSecond1=$allSecond%86400;
				$hour=floor($allSecond1/3600);	//小时

				$allSecond2=$allSecond1%3600;
				$minute=floor($allSecond2/60); //分钟

				$second=$allSecond2%60;	//秒

				if ($day!=0) {
					$difTime=$day.'天'.$hour.'小时'.$minute.'分钟'.$second.'秒';
				}
				elseif($day==0 && $hour!=0){
					$difTime=$hour.'小时'.$minute.'分钟'.$second.'秒';
				}
				elseif ($day==0 && $hour==0) {
					$difTime=$minute.'分钟'.$second.'秒';
				}
				elseif ($day==0 && $hour==0 && $minute==0) {
					$difTime=$second.'秒';
				}
				
				$data['allTime']=$difTime;	//总时间

				//计算总钱数
				$bike=M('Bikelist');
				$bikeWhere['bikeId']=$bikeId;
				$price=$bike->where($bikeWhere)->getField('price');

				$allPrice=$day*24*$price+$hour*$price+ceil(($price/60)*$minute)+ceil(($price/3600)*$minute);

	     		$data['allPrice']=$allPrice;	//总钱数

	     		$i=$borrow->where($where)->save($data);
	     		
	     		$url=__URL__.'/borrowRecord';
               	$data['newUrl'] = $url;
                if($i){
                    $this->ajaxReturn($data,"还车成功！",1);
                }
                else{
                    $this->ajaxReturn(0,"还车失败！",0);
                }
				
	     	}
	     }

	   
	     //供车记录
	    public function offerRecord(){
	     	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		//头像
	     		$m=M('User');
	     		$where['userId']=$_SESSION['userId'];
	     		$userPhoto=$m->where($where)->getField('headPhoto',1);
	     		$userExt=$m->where($where)->getField('extension',1);
	     		$this->assign('userPhoto',$userPhoto);
	     		$this->assign('userExt',$userExt);

	     		if($this->isGet()){
	     			$offerState=$_GET['offerState'];	//获取车辆状态
	     		}

	     		import("ORG.Util.Page");	//导入分页类
	     		
	     		$Model = D("Offer_recordView");

	     		if($offerState==1 || $offerState==2 || $offerState==3 || $offerState==4){
	     			$where1['Offer_record.offerState']=$offerState-1;
	     		}

	     		$where1['Offer_record.userId']=$_SESSION['userId'];
	     		$count=$Model->where($where1)->count();
	     		
	     		//总记录数和每页显示的记录数
				$Page=new Page($count,12);	
				$page=$Page->show();	//分页显示输出

				$bikeInfos=$Model->field('offerId,bikeId,userId,brand,offerState,offerTime,backTime,stopOfferTime,bikeImg,bikeExtension')->where($where1)->limit($Page->firstRow .','.$Page->listRows)->select();

				// var_dump($bikeInfos);
	     		$this->assign('bikeInfo',$bikeInfos);	     	
	     		$this->assign('page',$page);	//赋值分页输出
		     	$this->display();			//输出模板
		    }
	    } 

	    public function moreOfferInfo(){
	    	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		//头像
	     		$m=M('User');
	     		$where['userId']=$_SESSION['userId'];
	     		$userPhoto=$m->where($where)->getField('headPhoto',1);
	     		$userExt=$m->where($where)->getField('extension',1);
	     		$this->assign('userPhoto',$userPhoto);
	     		$this->assign('userExt',$userExt);

	     		//车辆信息
	     		$offerId=$_GET['offerId'];	
	     		$offer=D('Offer_recordView');
	     		$whereId['offerId']=$offerId;
                $bikeInfos=$offer->field('offerId,bikeId,userId,borrowerId,borrowId,offerTime,offerState,stopOfferTime,nickName,usePhone,brand,howOld,bikeKinds,sexKinds,price,phone,location,bikeImg,bikeExtension')->where($whereId)->select();

                if($bikeInfos[0]['bikeKinds']=='bike'){
	     			$bikeInfos[0]['bikeKinds']=0;	//自行车
	     		}
	     		elseif ($bikeInfos[0]['bikeKinds']=='elect') {
	     			$bikeInfos[0]['bikeKinds']=1;	//电动车
	     		}

	     		if($bikeInfos[0]['sexKinds']=='m'){
	     			$bikeInfos[0]['sexKinds']=0;		//男
	     		}
	     		elseif ($bikeInfos[0]['sexKinds']=='f') {
	     			$bikeInfos[0]['sexKinds']=1;		//女
	     		}

                $bikeState=$bikeInfos[0]['offerState'];
                if($bikeState==1 || $bikeState==2){	//已借出或已还车
                	$borrowerId=$bikeInfos[0]['borrowerId'];
                	$borrowerPhone=$m->where('userId'.'='.$borrowerId)->getField('phone',1);
                	$borrowerNickName=$m->where('userId'.'='.$borrowerId)->getField('nickName',1);

                	$bikeInfos[0]['borrowerPhone']=$borrowerPhone;
                	$bikeInfos[0]['borrowerNickName']=$borrowerNickName;

                	$borrowId=$bikeInfos[0]['borrowId'];
                	$bWhere['borrowId']=$borrowId;
                	$borrow=M(Borrow_record);
                	$borrowInfo=$borrow->where($bWhere)->find();

                	$bikeInfos[0]['borrowTime']=$borrowInfo['borrowTime'];
                	$bikeInfos[0]['backTime']=$borrowInfo['backTime'];
                	$bikeInfos[0]['allTime']=$borrowInfo['allTime'];
                	$bikeInfos[0]['allPrice']=$borrowInfo['allPrice'];

                	
                }
                // var_dump($bikeInfos);

	     		$this->assign('bikeInfo',$bikeInfos);	
	     		$this->display();			//输出模板
	     	}
	    }

	    public function do_stopOffer(){	//停止供车操作
	    	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		$offerId=$_GET['offerId'];
	     		$offer=M('Offer_record');
	     		$where['offerId']=$offerId;
	     		$offerInfo=$offer->where($where)->find();

	     		$offerInfo['offerState']=3;	//修改为已停供
	     		$offerInfo['stopOfferTime']=date('Y-m-d H:i:s');

	     		$i=$offer->where($where)->save($offerInfo);
	     		if($i){
	     			$this->ajaxReturn($i,"停止供车成功！",1);
	     		}
	     		else{
	     			$this->ajaxReturn(0,"停止供车失败！",0);
	     		}

	     	}
	    }

	     public function do_doubleOffer(){	//再次供车操作
	     	if(!$_SESSION['userId']){
	     		$this->redirect('__APP__/Index/index');
	     	}
	     	else{
	     		$offerId=$_GET['offerId'];
	     		$offer=M('Offer_record');
	     		$where['offerId']=$offerId;
	     		$offerInfo=$offer->where($where)->find();

	     		if($offerInfo['borrowId']){
	     			$data['bikeId']=$offerInfo['bikeId'];
		     		$data['userId']=$offerInfo['userId'];
		     		$data['offerTime']=date('Y-m-d H:i:s');
		     		$data['offerState']=0;
		     		$i=$offer->add($data);
	     		}
	     		
	     		else{
	     			$offerInfo['offerState']=0;	//修改为已停供
		     		$offerInfo['stopOfferTime']=null;
		     		$offerInfo['offerTime']=date('Y-m-d H:i:s');
		     		$i=$offer->save($offerInfo);
	     		}
	     		
	     		if($i){
	     			$this->ajaxReturn($i,"再次供车成功！",1);
	     		}
	     		else{
	     			$this->ajaxReturn(0,"再次供车失败！",0);
	     		}

	     	}
	     }

	   
	}

?>