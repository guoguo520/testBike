
<?php
// 本类由系统自动生成，仅供测试用途
class UserAction extends Action {
	public function userList(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
    		import("ORG.Util.Page");	//导入分页类
    		$m=M('User');
    		$count=$m->count();
    		$Page=new Page($count,6);	//实例化分页类
    		$page=$Page->show();	//分页显示输出

    		$userInfos=$m->order('userId')->limit($Page->firstRow .','.$Page->listRows)->select();
    		$count1=count($userInfos);
    		for ($i=0; $i < $count1; $i++) { 
    			if($userInfos[$i][sex]==0){
    				$userInfos[$i][sex]='男';
    			}elseif ($userInfos[$i][sex]==1) {
    				$userInfos[$i][sex]='女';
    			}
    		}
    		$this->assign('userInfo',$userInfos);
    		$this->assign('page',$page);	//赋值分页输出
    		$this->display();
        }
	}

    public function addUser(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
    	   $this->display();
        }
    }

    public function do_addUser(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	import('ORG.Util.Date');// 导入日期类
    		$m=M('User');
    		$data['username']=$_POST['userName'];
    		$data['nickName']=$_POST['nickName'];
    		$data['password']=$_POST['userPassword'];


    		$data['joinTime']= date('Y-m-d H:i:s');

    		$data['phone']=$_POST['userName'];
    		$i=$m->add($data);
    		
    		if($i){
                $this->ajaxReturn($i,"添加成功！",1);
            }
            else{
                $this->ajaxReturn(0,"添加失败！",0);
            }
        }
    }

    public function alterUser(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
         	$userId=$_GET['userId'];
         	$user=M('User');
         	$userInfo=$user->where('userId='.$userId)->find();

         	$this->assign('userInfo',$userInfo);
         	$this->display();
        }
    }

     public function do_alterUser(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
         	$username=$_POST['username'];
         	$data['nickName']=$_POST['nickName'];
         	$data['sex']=$_POST['sex'];
         	$data['phone']=$_POST['phone'];
         	if($data['sex']=='m'){
         		$data['sex']=0;
         	}elseif ($data['sex']=='f') {
         		$data['sex']=1;
         	}
         	$user=M('User');
         	$i=$user->where('username='.$username)->save($data);
         	if($i){
         		$this->success('修改成功！');
         	}else{
         		$this->error('修改失败！');
         	}
        }
     }

     public function do_deleteUser(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
         	$userId=$_GET['userId'];
         	$user=M('User');
         	$i=$user->where('userId='.$userId)->delete();
         	var_dump($i);
         	if($i){
         		$this->success('删除成功！');
         	}else{
         		$this->error('删除失败！');
         	}
        }
     }

}

?>