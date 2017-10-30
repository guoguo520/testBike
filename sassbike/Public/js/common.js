//登录判断开始
// $(document).ready(function(){ 

//      // Getting the kittens cookie: 
//      var str = $.cookie("kittens"); 

//      // str now contains "Seven Kittens" 
//  });
//登录判断结束
//一、查看最新公告详情开始
// jQuery(document).ready(function($){
//     //首页查看公告详情开始
//     var $moreInfo=$('a.more-info');
//     var $content=$('.news-info');
//     $content.hide();
//     for(var i=0;i<$moreInfo.length;i++){
//         $moreInfo.eq(i).click(function(event){
//             event.preventDefault();
//             var $link=$(this);
//             $link.next().slideToggle('slow');
//             if($link.text()=='详情'){
//                 $link.text('关闭');
//             }
//             else{
//                 $link.text('详情');
//             }
//         })
//     }
//     //首页查看公告详情结束
// }),


jQuery.myExtend={
    server:function(id){  
        $id = id;
        $.get('Index/index', {'p':$id}, function(data){  
            //用get方法发送信息到Index中的index方法
            $("#ajaxServer").replaceWith("<div id='ajaxServer' class='news-panl'>"+data.content+"</div>"); 
        });
    },

    checkTel:function(tel,telHelp){   // 手机号验证封装函数
        $tel=tel;
        $telHelp=telHelp;
        if($tel.val()==''){
            $tel.removeClass('form-control-correct');
            $telHelp.removeClass('help-correct');

            $tel.addClass('form-control-wrong');
            $telHelp.html('手机号不能为空！').addClass('help-wrong');
            return false;
        }
        else{
            if(!($tel.val().match(/^1[3|4|5|8][0-9]\d{4,8}$/)) || $tel.val().length<11){
                $tel.removeClass('form-control-correct');
                $telHelp.removeClass('help-correct');

                $tel.addClass('form-control-wrong');
                if($tel.val().length<11){
                    $telHelp.html('手机号不够11位！').addClass('help-wrong');
                }
                else{
                    $telHelp.html('手机号格式错误！').addClass('help-wrong'); 
                } 
                return false;
            }
            else{
                $tel.removeClass('form-control-wrong');
                $telHelp.removeClass('help-wrong');

                $tel.addClass('form-control-correct');
                $telHelp.html('手机号格式正确！').addClass('help-correct');
            }
        }
    },

    checkNickName:function(nickName,nickNameHelp){   // 手机号验证封装函数
        $nickName=nickName;
        $nickNameHelp=nickNameHelp;
        if($nickName.val()=='' || $nickName.val().length>20){
            $nickName.removeClass('form-control-correct');
            $nickNameHelp.removeClass('help-correct');

            $nickName.addClass('form-control-wrong');
            $nickNameHelp.html('昵称必填且不能超过20个字符！').addClass('help-wrong');
            return false;
        }
        else{
            $nickName.removeClass('form-control-wrong');
            $nickNameHelp.removeClass('help-wrong');

            $nickName.addClass('form-control-correct');
            $nickNameHelp.html('昵称格式正确！').addClass('help-correct');
        }
    },

    checkPassword:function(psw,pswHelp){    // 密码验证封装函数
        $psw=psw;
        $pswHelp=pswHelp;

        if($psw.val()==''){
            $psw.removeClass('form-control-correct');
            $pswHelp.removeClass('help-correct');

            $psw.addClass('form-control-wrong');
            $pswHelp.html('密码不能为空！').addClass('help-wrong');
            return false;
        }
        else if($psw.val().length<6 || $psw.val().length>8){
            $psw.removeClass('form-control-correct');
            $pswHelp.removeClass('help-correct');

            $psw.addClass('form-control-wrong');
            $pswHelp.html('密码的位数应在6-8位之间！').addClass('help-wrong');
            return false;
        }
        else{
            $psw.removeClass('form-control-wrong');
            $pswHelp.removeClass('help-wrong');

            $psw.addClass('form-control-correct');
            $pswHelp.html('密码格式正确！').addClass('help-correct');
        }

    },

    checkCode:function(code,codeHelp){  //验证码验证封装函数
        $code=code;
        $codeHelp=codeHelp;

        if($code.val()==''){
            $code.removeClass('form-control-correct');
            $codeHelp.removeClass('help-correct');

            $code.addClass('form-control-wrong');
            $codeHelp.html('验证码不能为空！').addClass('help-wrong');
            return false;
        }
        else if($code.val().length<4){
            $code.removeClass('form-control-correct');
            $codeHelp.removeClass('help-correct');

            $code.addClass('form-control-wrong');
            $codeHelp.html('验证码不足4位！').addClass('help-wrong');
            return false;
        }
        else{
            $code.removeClass('form-control-wrong');
            $codeHelp.removeClass('help-wrong');
            $code.addClass('form-control-correct');
            $codeHelp.html('验证码输入正确').addClass('help-correct');
        }
    },

    checkAdminNum:function(adminInput,adminNumHelp){
        $adminInput=adminInput;
        $adminNumHelp=adminNumHelp;

        if($adminInput.val().length!==3){
            $adminInput.removeClass('form-control-correct');
            $adminNumHelp.removeClass('help-correct');

            $adminInput.addClass('form-control-wrong');
            $adminNumHelp.html('请输入3位管理员编号！').addClass('help-wrong');
            return false;
        }
        else{
            $adminInput.removeClass('form-control-wrong');
            $adminNumHelp.removeClass('help-wrong');

            $adminInput.addClass('form-control-correct');
            $adminNumHelp.html('管理员编号格式正确！').addClass('help-correct');
        }
    },

    checkDoublePassword:function(psw1,psw2,rPswHelp2){
        $psw1=psw1;
        $psw2=psw2;
        $rPswHelp2=rPswHelp2;
        if($psw1.val()=='' || $psw2.val()==''){
            $psw2.removeClass('form-control-correct');
            $rPswHelp2.removeClass('help-correct');

            $psw2.addClass('form-control-wrong');
            $rPswHelp2.html('密码不能为空！').addClass('help-wrong');
            return false;
        }
        else if($psw1.val()===$psw2.val()){
            $psw2.removeClass('form-control-wrong');
            $rPswHelp2.removeClass('help-wrong');

            $psw2.addClass('form-control-correct');
            $rPswHelp2.html('两次密码输入相同').addClass('help-correct');   
        }
        else{
            $psw2.removeClass('form-control-correct');
            $rPswHelp2.removeClass('help-correct');

            $psw2.addClass('form-control-wrong');
            $rPswHelp2.html('两次密码输入不同！').addClass('help-wrong');
            return false;
        }
    },

    checkSize:function(obj,objHelp,len){ //判断字符长度函数
        $obj=obj;
        $objHelp=objHelp;
        $len=len;
        if($obj.val()===''){
            $obj.removeClass('form-control-correct');
            $objHelp.removeClass('help-correct');

            $obj.addClass('form-control-wrong');
            $objHelp.html('该字段不能为空！').addClass('help-wrong');
            return false;
        }
        else if($obj.val().length>$len){
            $obj.removeClass('form-control-correct');
            $objHelp.removeClass('help-correct');

            $obj.addClass('form-control-wrong');
            $objHelp.html('输入应低于'+$len+'位数！').addClass('help-wrong');
            return false;
        }
        else{
            $obj.removeClass('form-control-wrong');
            $objHelp.removeClass('help-wrong');

            $obj.addClass('form-control-correct');
            $objHelp.html('输入格式正确！').addClass('help-correct');   
        }
    }

}

jQuery(document).ready(function($){
    //用户登录手机号验证
    if($('#tel').val()!=''){
         $.myExtend.checkTel($('#tel'),$('#telHelp'));
    }
    $('#tel').blur(function(){
        return $.myExtend.checkTel($(this),$('#telHelp'));
    })

    //用户登录密码验证
    if($('#psw').val()!=''){
        $.myExtend.checkPassword($('#psw'),$('#pswHelp'));
    }
    $('#psw').blur(function(){
        return $.myExtend.checkPassword($(this),$('#pswHelp'));
    })
    

    //用户登录验证码验证
    if($('#code').val()!=''){
        $.myExtend.checkCode($('#code'),$('#codeHelp'));
    }
    $('#code').blur(function(){
        return $.myExtend.checkCode($(this),$('#codeHelp'));
    })

    // 点击用户登录按钮
    $('#login').click(function(e){
        if($('#tel').val()==''||$('#psw').val()==''||$('#code').val()==''){
            alert('信息填写不完整！');
            return false;  
        }
    })

})

//注册页面
jQuery(document).ready(function($){
    // 用户注册手机号判断
    if($('#rTelInput').val()!=''){
        $.myExtend.checkTel($('#rTelInput'),$('#rTelHelp'));
    }
    $('#rTelInput').blur(function(){
        return $.myExtend.checkTel($(this),$('#rTelHelp'));
    })

    if($('#nickName').val()!=''){
        $.myExtend.checkNickName($('#nickName'),$('#nickNameHelp'));
    }
    $('#nickName').blur(function(){
        return $.myExtend.checkNickName($(this),$('#nickNameHelp'));
    })
    
    //用户注册密码判断
    if($('#passwordInput1').val()!=''){
        $.myExtend.checkPassword($('#passwordInput1'),$('#rPswHelp1'));
    }
    $('#passwordInput1').blur(function(){
        return $.myExtend.checkPassword($(this),$('#rPswHelp1'));
    })

    //用户注册确认密码判断
    if($('#passwordInput2').val()!='' && $('#passwordInput1').val()!=''){
        $.myExtend.checkDoublePassword($('#passwordInput1'),$('#passwordInput2'),$('#rPswHelp2'));
    }
    $('#passwordInput2').blur(function(){
        return $.myExtend.checkDoublePassword($('#passwordInput1'),$(this),$('#rPswHelp2'));
    })

});

//发布车辆时信息验证
jQuery(document).ready(function($){
    //对手机号的验证
    if($('#offerPhone').val()!=''){
        $.myExtend.checkTel($('#offerPhone'),$('#offerPhoneHelp'));
    }
    $('#offerPhone').blur(function(){
        return $.myExtend.checkTel($(this),$('#offerPhoneHelp'));
    })

    if($('#location').val()!=''){
        $.myExtend.checkSize($('#location'),$('#locationHelp'),25);
    }
    $('#location').blur(function(){
        return $.myExtend.checkSize($(this),$('#locationHelp'),25);
    })
   

});
    //3、检查手机号格式函数
    // function checkPhone(phone){
    //     var lastNode=phone.nextSibling;
    //     if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(phone.value))||(phone.value.length<11)){
    //         if(!(/^1[3|4|5|8][0-9]\d{4,8}$/.test(phone.value))&&(phone.value.length<11)){
    //             if(lastNode.nodeName=='SMALL'){
    //                 phone.parentNode.removeChild(lastNode);
    //             }
    //             var helpNode=createElement('small','手机号前七位错误！且不是完整的11位！','text-danger');
    //         }
    //         else if(phone.value.length<11){
    //             if(lastNode.nodeName=='SMALL'){
    //                 phone.parentNode.removeChild(lastNode);
    //             }
    //             var helpNode=createElement('small','不是完整的11位手机号！','text-danger');
    //         }
    //         else{
    //             if(lastNode.nodeName=='SMALL'){
    //                 phone.parentNode.removeChild(lastNode);
    //             }
    //             var helpNode=createElement('small','手机号前七位错误！','text-danger');
    //         }

    //         insertAfter(helpNode,phone);
    //         phone.focus();
    //         return false;
    //     }
    //     else{
    //         if(lastNode.nodeName=='SMALL'){
    //             phone.parentNode.removeChild(lastNode);
    //         }
    //         var helpNode=createElement('small','手机号格式正确！','text-success');
    //         insertAfter(helpNode,phone);
    //     }
    // }
    //原生js判断手机号格式是否正确结束

jQuery(document).ready(function($){

    //管理员编号验证
    if($('#adminInput').val()!=''){
        $.myExtend.checkAdminNum($('#adminInput'),$('#adminNumHelp'));
    }
    $('#adminInput').blur(function(){
        return $.myExtend.checkAdminNum($(this),$('#adminNumHelp'));
    })

    //管理员密码验证
    if($('#adminPsw').val()!=''){
        $.myExtend.checkPassword($('#adminPsw'),$('#adminPswHelp'));
    }
    $('#adminPsw').blur(function(){
        return $.myExtend.checkPassword($(this),$('#adminPswHelp'));
    })

    // 点击管理员登录按钮
    $('#adminLogin').click(function(e){
        if($('#adminInput').val()==''||$('#adminPsw').val()==''){
            alert('信息填写不完整！');
            return false;
        }
    })

    // 后台导航开始
    $(".nav-a").each(function(){
        $(this).click(function(){
            $(".nav-a").each(function(){
                $(this).removeClass("active");
            })
            if($(this).hasClass("active")){
                $(this).removeClass("active");
            }
            else{
                $(this).addClass("active");
            }
            
        })
    })
    $(".collapse").each(function(){
        $(".collapse").click(function(){
            if($(this).hasClass("show")){
                $(".collapse").each(function(){
                    $(this).prev().removeClass("active-title");
                })
                $(this).prev().addClass("active-title");
            }
        })
    })
    // 后台导航结束

     // 添加用户手机号判断
    if($('#addTelInput').val()!=''){
        $.myExtend.checkTel($(this),$('#addTelHelp'));
    }
    $('#addTelInput').blur(function(){
        return $.myExtend.checkTel($(this),$('#addTelHelp'));
    })

    if($('#addNickName').val()!=''){
        $.myExtend.checkNickName($(this),$('#addNickNameHelp'));
    }
    $('#addNickName').blur(function(){
        return $.myExtend.checkNickName($(this),$('#addNickNameHelp'));
    })
    
    //添加用户密码判断
    if($('#addpsw1').val()!=''){
        $.myExtend.checkPassword($('#addpsw1'),$('#addpswHelp1'));
    }
    $('#addpsw1').blur(function(){
        return $.myExtend.checkPassword($(this),$('#addpswHelp1'));
    })

    //添加用户确认密码判断
    if($('#addpsw2').val()!='' && $('#addpsw1').val()!=''){
        $.myExtend.checkDoublePassword($('#addpsw1'),$('#addpsw2'),$('#addpswHelp2'));
    }
    $('#addpsw2').blur(function(){
        return $.myExtend.checkDoublePassword($('#addpsw1'),$(this),$('#addpswHelp2'));
    })

    //判断公告标题字数
    $('#newsTitle').blur(function(){
        $.myExtend.checkSize($(this),$('#newsTitleHelp'),120);
    });
    $('#newsContent').blur(function(){
        $.myExtend.checkSize($(this),$('#newsContentHelp'),600);
    });
    $('#addNewsBtn').click(function(){
        if($('#newsTitle').val()==='' || $('#newsContent').val()===''){
            alert('信息填写不完整！');
            return false;
        }
    })
    
})

