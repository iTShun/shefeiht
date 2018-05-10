<?php
error_reporting(0);
session_start();
require("../data/head.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link href="static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title><?=$cf['site_name']?></title>

</head>
<body>
<?php

function getCookieUsername()
{
  if(isset($_COOKIE['username']) && strlen($_COOKIE['username']))
  {
    return $_COOKIE['username'];
  }else
  {
    return "";
  }
}
function getCookiePassword()
{
  if(isset($_COOKIE['password']) && strlen($_COOKIE['password']))
  {
    return $_COOKIE['password'];
  }else
  {
    return "";
  }
}

$act = $_REQUEST["act"];
if ($act == "adminlogin")
{
    $username = trim($_POST["Username"]);
    $password = trim($_POST["Password"]);
    
    $b=$database->select("admin", "*", array("AND"=>array("OR"=>array("username"=>$username, "uname"=>$username), "password"=>md5($password))));
    
    if(!$b[0])
    {
        echo "<script>alert('帐户或密码错误,请重新输入!');location.href='index.php';</script>";
        exit();
    }
    else
    {

        if (isset($_POST['Checkbox']))
        {
          setcookie('username',$username,time()+3600*24*7,"/");
          setcookie('name',$b[0]['name'],time()+3600*24*7,"/");
          setcookie('password',$password,time()+3600*24*7,"/");
        }
        else
        {
          setcookie('username',$username,time()+3600*24*7,"/");
          setcookie('name',$b[0]['name'],time()+3600*24*7,"/");
          setCookie('password','',time()-10);
        }

        $database->update("admin", array("logins[+]"=>1), array("OR"=>array("username"=>$username, "uname"=>$username)));

        //if (is_array($b) && $b[0]['username'] == "admin" && $b[0]['id'] == 1)
         // echo "<script>location.href='main_.php';</script>";
        //else
          echo "<script>location.href='main.php';</script>";
        
        exit;
  	 }
} 

//退出后台************************************************************

if ($act=="logout")
{
session_unset();
echo "<script>location.href='index.php';</script>"; 
} 
?>
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
  <script language=javascript>
<!--
function CheckForm()
{
	if(document.Login.Username.value == "")
	{
		alert("请输入用户名！");
		document.Login.Username.focus();
		return false;
	}
	if(document.Login.Password.value == "")
	{
		alert("请输入密码！");
		document.Login.Password.focus();
		return false;
	}	
}
//-->
</script>
    <form name="Login" class="form form-horizontal" action="?act=adminlogin" method="post" onSubmit="return CheckForm();">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
         
          
          <input type="text" name="Username" id="item1" placeholder="账户" value="<?php echo getCookieUsername(); ?>" class="input-text size-L" autocomplete="off" >
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          
          <input type="password" name="Password" id="item2" placeholder="密码"  value="<?php echo getCookiePassword(); ?>" class="input-text size-L" autocomplete="off">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <Label><input type="checkbox" name="Checkbox"> <FONT color='RED'>记住密码</FONT></Label>
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
         
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
    </form>
  </div>
</div>
<div class="footer"><?=$cf['copyrighta']?></div>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script> 

</body>
</html>