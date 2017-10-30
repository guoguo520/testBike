<?php
// 本类由系统自动生成，仅供测试用途
class NewsAction extends Action {
    public function newsList(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
    		import("ORG.Util.Page");	//导入分页类
    		$m=M('News');
    		$count=$m->count();
    		$Page=new Page($count,6);	//实例化分页类
    		$page=$Page->show();	//分页显示输出

    		$newsInfos=$m->order('newsId')->limit($Page->firstRow .','.$Page->listRows)->select();
    		$count1=count($newsInfos);
    		
    		$this->assign('newsInfo',$newsInfos);
    		$this->assign('page',$page);	//赋值分页输出
    		$this->display();
        }
    }

    public function alterNews(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$newsId=$_GET['newsId'];
        	$news=M('News');
        	$newsInfo=$news->where('newsId='.$newsId)->find();
        	var_dump($newsInfo);
        	$this->assign('newsInfo',$newsInfo);
        	$this->display();
        }
    }

    public function do_alterNews(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$newsId=$_GET['newsId'];
        	$data['title']=$_POST['title'];
        	$data['content']=$_POST['content'];
        	$news=M('News');
        	$i=$news->where('newsId='.$newsId)->save($data);
        	if($i){
        		$this->success('修改成功！');
        	}else{
        		$this->error('修改失败！');
        	}
        }
    }

    public function do_deleteNews(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	$newsId=$_GET['newsId'];
        	$news=M('News');
        	$i=$news->where('newsId='.$newsId)->delete();
        	if($i){
        		$this->success('删除成功！');
        	}else{
        		$this->error('删除失败！');
        	}
        }
    }

    public function addNews(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
    	
    	   $this->display();
        }
    }

    public function do_addNews(){
        if(!$_SESSION['adminNum']){
            $this->redirect('__APP__/Index/index');
        }
        else{
        	import('ORG.Util.Date');// 导入日期类
    		$m=M('News');
    		$data['title']=$_POST['title'];
    		$data['content']=$_POST['content'];

    		$data['pubTime']= date('Y-m-d H:i:s');
    		$i=$m->add($data);
    		
    		if($i){
                $this->success('添加成功！');
            }
            else{
                $this->error('添加失败！');
            }
        }
    }


}
?>