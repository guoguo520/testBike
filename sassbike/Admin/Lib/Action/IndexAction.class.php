<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){

		$this->display();
    }

    public function do_adminLogin(){	//管理员登录
		$adminInput=$_POST['adminInput'];
		$adminPsw=md5($_POST['adminPsw']);

		$m=M('Admin');
		$where['adminName']=$adminInput;
		$where['adminPsw']=$adminPsw;

		$_SESSION['adminNum']=$adminInput;
		
		$i=$m->where($where)->count();
		if($i>0){
			$this->redirect('__APP__/User/userList');
		}
		else{
			$this->error('用户名或密码错误！');
		}
	}

	public function do_adminLoginOut(){
		if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
			$_SESSION['adminNum']=null;
			$this->redirect('__APP__/Index/index');
		}
	}
   
}

?>