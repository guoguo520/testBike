<?php
	class Borrow_recordViewModel extends ViewModel{
		public $viewFields=array(
			'Borrow_record'=>array('borrowId','bikeId','userId','borrowerId','offerId','borrowTime','borrowState','backTime','allTime','allPrice'),
			'User'=>array('nickName','phone'=>'userPhone','_on'=>'Borrow_record.userId=User.userId'),
			'Bikelist'=>array('brand','howOld','bikeKinds','sexKinds','price','phone','location','bikeImg','bikeExtension','_on'=>'Bikelist.bikeId=Borrow_record.bikeId'),
		);

	}
?>