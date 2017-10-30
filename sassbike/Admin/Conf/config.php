<?php
return array(
	//'配置项'=>'配置值'
	'URL_PATHINFO_DEPR'=>'/',	//修改地址栏分隔符
	
	//修改变量定界符将{}改为<{}>
	TMPL_L_DELIM=>'<{',	//修改变量左定界符
	TMPL_R_DELIM=>'}>',	//修改变量右定界符
	
	'DB_PREFIX'=>'bike_',
	'DB_DSN'=>'mysql://root:123456@localhost:3306/sharebike', //使用DSN方式配置数据库信息,优先使用
	// 'SHOW_PAGE_TRACE'=>true 	//开启页面的trace
);
?>