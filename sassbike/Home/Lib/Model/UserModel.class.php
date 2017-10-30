<?php
	class UserModel extends Model{
		
		protected $_auto=array(
			array('joinTime',date, self::MODEL_INSERT,array('Y-m-d H-i-s'))
		);

		// protected function getTime(){
		// 	return time();
		// }
	}
?>