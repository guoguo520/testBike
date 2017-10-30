

<?php
	class Offer_recordViewModel extends ViewModel{
		public $viewFields=array(
			'Offer_record'=>array('offerId','bikeId','userId','borrowerId','borrowId','offerTime','offerState','stopOfferTime'),
			'User'=>array('nickName','phone'=>'userPhone','_on'=>'Offer_record.userId=User.userId'),
			'Bikelist'=>array('brand','howOld','bikeKinds','sexKinds','price','phone','location','bikeImg','bikeExtension','_on'=>'Bikelist.bikeId=Offer_record.bikeId'),
		);
	}
?>
