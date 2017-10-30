<?php
	header("content-type:text/html;charset=utf-8");
	class PublicAction extends Action {
		//验证码
		function code(){
			import('ORG.Util.Image');
   	 		Image::buildImageVerify();
		}
	}

?>