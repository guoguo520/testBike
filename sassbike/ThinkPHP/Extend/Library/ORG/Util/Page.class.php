<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    
    // 分页栏每页显示的页数
    public $rollPage = 2;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 10;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =    array(
		'header' => '共 %totalRow% 条记录',  
        'prev'   => '<<',  
        'next'   => '>>',  
        'first'  => '首页',  
        'last'   => '末页',  
        'theme'  => '%upPage%  %linkPage% %downPage%', 
        // 'theme'  => '%first% %upPage%  %linkPage% %downPage% %end%',   
	
	// 'header'=>'条记录',
	// 'prev'=>'上一页',
	// 'next'=>'下一页',
	// 'first'=>'第一页',
	// 'last'=>'最后一页',
	// 'theme'=>'%totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%'
	);
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;

         // 获取分页变量名，如果未定义则定义默认分页变量名
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;

        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);  //获取变量的整数值
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数

        // 获取总栏数， 总栏数除以每页显示的栏数等于总栏数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);

        // 获取当前页数，如果 URL 当前页数参数不为空则转换整型并赋值给当前页数，否则赋值为 1
        $this->nowPage      =   !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;

        if($this->nowPage<1){
            $this->nowPage  =   1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }

        // 假设当前页数为 2，每页显示 5 条数据，当前页面就是从第 (5*(2-1)=5) 条记录开始读取数据,
        // 根据 limit 函数定义，索引从零开始，也就是实际的值是记录集的第六条数据
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }


    /**
     * 自定义导航显示
     * @access public
     * @param String $name 待替换的参数名称
     * @param String $value 替换的参数值
     * isset() 返回  bool 值
     * 若变量不存在则返回 FALSE 
     * 若变量存在且其值为NULL，也返回 FALSE 
     * 若变量存在且值不为NULL，则返回 TURE 
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage; // 默认分页变量名

        // 假设 40 条数据，每页显示 5 条，导航每页显示 4 栏，当前为第 3 页，也就是 ceil(3/4)=1
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);   // 当前分页栏

        // 分析分页参数
        //获取控制器名和方法名，并判断是否url不区分大小写
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)){
                $parameter      =   $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var =  !empty($_POST)?$_POST:$_GET;
                if(empty($var)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $var;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U('',$parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a></li>";
        }else{
            $upPage     =   '';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a></li>";
        }else{
            $downPage   =   '';
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst   =   '';
            $prePage    =   '';
        }else{
            $preRow     =   $this->nowPage-$this->rollPage;
            $prePage    =   "<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a></li>";
            $theFirst   =   "<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a></li>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage   =   '';
            $theEnd     =   '';
        }else{
            $nextRow    =   $this->nowPage+$this->rollPage;
            $theEndRow  =   $this->totalPages;
            $nextPage   =   "<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a></li>";
            $theEnd     =   "<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a></li>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page       =   ($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<li class='page-item'><a class='page-link' href='".str_replace('__PAGE__',$page,$url)."'>&nbsp;".$page."&nbsp;</a></li>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<li class='page-item active'><a class='page-link' href='#'>".$page."</a></li>";
                }
            }
        }
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

}