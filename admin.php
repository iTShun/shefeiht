<?php

include 'head.php';

$act = $_GET["act"];

if($act == "")

{

?>

<SCRIPT language="javascript">

function CheckAll(form)

  {

  for (var i=0;i<form.elements.length;i++)

    {

    var e = form.elements[i];

    if (e.Name != "chkAll"&&e.disabled==false)

       e.checked = form.chkAll.checked;

    }

  }

function CheckAll2(form)

  {

  for (var i=0;i<form.elements.length;i++)

    {

    var e = form.elements[i];

    if (e.Name != "chkAll2"&&e.disabled==false)

       e.checked = form.chkAll2.checked;

    }

  }   

function ConfirmDel()

{

	if(document.myform.Action.value=="delete")

	{

		document.myform.action="?act=delart";

		if(confirm("确定要删除选中的记录吗？本操作不可恢复！"))

		    return true;

		else

			return false;

	}
	
	else if(document.myform.Action.value=="export_code"){

	  document.myform.action="?act=export_code";

	

	}
	else if(document.myform.Action.value=="exportall_code"){

	  document.myform.action="?act=exportall_code";

	

	}

}

</SCRIPT>


<?php

}

////系统相关设置

if($act == "config"){  

?>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?act=config">系统设置</a> <span class="c-gray en">&gt;</span> 基本信息配置 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_config">    

		<table cellpadding="3" cellspacing="1" class="table_98">

          <tr>

            <td colspan="3" align="center" bgcolor="#CCCCCC">配置信息</td></tr>

          <tr >

            <td width="10%"> 系统名称：</td>

            <td width="40%" ><input style="width:300px" class="input-text" name="cf[site_name]" type="text" id="cf[site_name]" size="50" value="<?php echo $cf['site_name']?>"></td>

			<td width="50%" > </td>

          </tr>

          <tr >

            <td>系统网址：</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[site_url]" value="<?php echo $cf['site_url']?>" size="50"></td>

			<td >请输入完整的网站域名 例：http://abc.com.cn/cx/</td>

          </tr>
          
            <tr >

            <td> 底部版权：</td>

            <td><textarea name="cf[copyrighta]" cols="65" rows="5"><?php echo $cf['copyrighta']?></textarea></td>

			<td > </td>

          </tr>	  

		
		   <tr >

            <td>默认每页显示数量</td>

            <td><input style="width:300px" class="input-text" type="text" name="cf[list_num]" id="list_num" value="<?=$cf['list_num']?>" /></td>

			<td></td>

          </tr>

		  <tr>

		   <td width="10%">系统时区：</td>

			<td><select name="cf[timezone]">

					<option value="-12" <?php if($cf['timezone']=='-12') echo "selected='selected'";?>>(GMT -12:00) Eniwetok, Kwajalein</option>

					<option value="-11" <?php if($cf['timezone']=='-11') echo "selected='selected'";?>>(GMT -11:00) Midway Island, Samoa</option>

					<option value="-10" <?php if($cf['timezone']=='-10') echo "selected='selected'";?>>(GMT -10:00) Hawaii</option>

					<option value="-9" <?php if($cf['timezone']=='-9') echo "selected='selected'";?>>(GMT -09:00) Alaska</option>

					<option value="-8" <?php if($cf['timezone']=='-8') echo "selected='selected'";?>>(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana</option>

					<option value="-7" <?php if($cf['timezone']=='-7') echo "selected='selected'";?>>(GMT -07:00) Mountain Time (US &amp; Canada), Arizona</option>

					<option value="-6" <?php if($cf['timezone']=='-6') echo "selected='selected'";?>>(GMT -06:00) Central Time (US &amp; Canada), Mexico City</option>

					<option value="-5" <?php if($cf['timezone']=='-6') echo "selected='selected'";?>>(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>

					<option value="-4" <?php if($cf['timezone']=='-4') echo "selected='selected'";?>>(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz</option>

					<option value="-3.5" <?php if($cf['timezone']=='-3.5') echo "selected='selected'";?>>(GMT -03:30) Newfoundland</option>

					<option value="-3" <?php if($cf['timezone']=='-3') echo "selected='selected'";?>>(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is</option>

					<option value="-2" <?php if($cf['timezone']=='-2') echo "selected='selected'";?>>(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena</option>

					<option value="-1" <?php if($cf['timezone']=='-1') echo "selected='selected'";?>>(GMT -01:00) Azores, Cape Verde Islands</option>

					<option value="0" <?php if($cf['timezone']=='0') echo "selected='selected'";?>>(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>

					<option value="1" <?php if($cf['timezone']=='1') echo "selected='selected'";?>>(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>

					<option value="2" <?php if($cf['timezone']=='2') echo "selected='selected'";?>>(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa</option>

					<option value="3" <?php if($cf['timezone']=='3') echo "selected='selected'";?>>(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi</option>

					<option value="3.5" <?php if($cf['timezone']=='3.5') echo "selected='selected'";?>>(GMT +03:30) Tehran</option>

					<option value="4" <?php if($cf['timezone']=='4') echo "selected='selected'";?>>(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi</option>

					<option value="4.5" <?php if($cf['timezone']=='4.5') echo "selected='selected'";?>>(GMT +04:30) Kabul</option>

					<option value="5" <?php if($cf['timezone']=='5') echo "selected='selected'";?>>(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>

					<option value="5.5" <?php if($cf['timezone']=='5.5') echo "selected='selected'";?>>(GMT +05:30) Bombay, Calcutta, Madras, New Delhi</option>

					<option value="5.75" <?php if($cf['timezone']=='5.75') echo "selected='selected'";?>>(GMT +05:45) Katmandu</option>

					<option value="6" <?php if($cf['timezone']=='6') echo "selected='selected'";?>>(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk</option>

					<option value="6.5" <?php if($cf['timezone']=='6.5') echo "selected='selected'";?>>(GMT +06:30) Rangoon</option>

					<option value="7" <?php if($cf['timezone']=='7') echo "selected='selected'";?>>(GMT +07:00) Bangkok, Hanoi, Jakarta</option>

					<option value="8" <?php if($cf['timezone']=='8') echo "selected='selected'";?>>(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei</option>

					<option value="9" <?php if($cf['timezone']=='9') echo "selected='selected'";?>>(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>

					<option value="9.5" <?php if($cf['timezone']=='9.5') echo "selected='selected'";?>>(GMT +09:30) Adelaide, Darwin</option>

					<option value="10" <?php if($cf['timezone']=='10') echo "selected='selected'";?>>(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok</option>

					<option value="11" <?php if($cf['timezone']=='11') echo "selected='selected'";?>>(GMT +11:00) Magadan, New Caledonia, Solomon Islands</option>

					<option value="12" <?php if($cf['timezone']=='12') echo "selected='selected'";?>>(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island</option>

					<option value="13" <?php if($cf['timezone']=='13') echo "selected='selected'";?>>(GMT +13:00) Nukualofa</option>

				  </select>			 </td>

			<td></td>

		  </tr>

		  <tr>

		   <td>系统时间格式：</td>

		   <td><input style="width:300px" class="input-text" name="cf[time_format]" type="text" size="12" value="<?php echo $cf['time_format'];?>"></td>

		   <td>服务器时间：<?=date($cf['time_format'],time());?><br /> 程序时间:<?=$GLOBALS['tgs']['cur_time'];?></td>

		  </tr>

		  <tr >

            <td> 资源类型：</td>

            <td><textarea name="cf[resource_type]" cols="65" rows="5"><?php echo $cf['resource_type']?></textarea></td>

			<td > </td>

          </tr>	  

          <tr >

            <td> 资源状态：</td>

            <td><textarea name="cf[resource_status]" cols="65" rows="5"><?php echo $cf['resource_status']?></textarea></td>

			<td > </td>

          </tr>

          <tr >

            <td> 资源渠道：</td>

            <td><textarea name="cf[resource_qudao]" cols="65" rows="5"><?php echo $cf['resource_qudao']?></textarea></td>

			<td > </td>

          </tr>

          <tr >
            
            <td>&nbsp;</td>
            
            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 保 存 " ></td>
            
            <td></td>
            
          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
</div>
    


<?php

}

//////////////////////////////////////////

if($act == "save_config"){

	global $database;

    $arr = array();

    $b = $database->select("config", "*", array("parentid"=>1));

    for ($i=0; $i < count($b); $i++) { 
    	$arr[$b[$i]['code']] = $b[$i]['code_value'];
    }

	 foreach ($_POST['cf'] AS $key => $val)

    {

        if($arr[$key] != $val)

        { 

		  ///变量格式化

		  if($key=='notices' or $key=='notice_1' or $key == 'notice_2' or $key=='notice_3' or $key=='agents' or $key=='agent_1' or $key=='agent_2' or $key=='agent_3'){

              $val = strreplace($val);

		  }

		  if($key=='site_close_reason'){

              $val = strreplace($val);

		  }
		  
		  $database->update("config", array("code_value"=>trim($val)), array("code"=>$key));

		}

	}

	 echo "<script>window.location.href='?act=config'</script>";

	   exit; 

}

////人员设置

if($act == "superadmin"){

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?act=superadmin">人员管理</a> <span class="c-gray en">&gt;</span> 人员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">

	<?php 
      //添加人员判断
		echo get_privilege_code("添加人员");
    ?>

    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="7%">id</td>

          <td width="10%">帐户</td>

          <td width="10%">便捷帐户</td>

          <td width="10%">姓名</td>

          <td width="20%">部门</td>

          <td width="20%">组别</td>

          <td width="20%">操作</td>          

		</tr>

		<?php

		 global $database;

		 $admins = $database->select("admin", "*", array("id[!]"=>0));
		
		 for ($i=0; $i < count($admins); $i++) { 
	        	$arr = $admins[$i];
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_superadmin&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["username"];?></a></td>

          <td><a href="?act=edit_superadmin&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["uname"];?></a></td>

          <td><?php echo $arr["name"];?></td>

          <td><?php echo $arr["section"];?></td>

          <td><?php echo $arr["group"];?></td>

          <td>
          <a title="编辑" href="?act=edit_superadmin&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; <a title="删除" href="?act=delete_superadmin&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
          
         </td>

        </tr>

		<?php

		}

		?>

		</table>

    

	</td>

  </tr>

</table>
</div></div>

<?php

}

////添加人员

if ($act == "add") {
	
	global $database;
	
	$sections = $database->select("section", "*", array("id[!]"=>0));
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?act=superadmin">人员管理</a> <span class="c-gray en">&gt;</span> 添加人员 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">

		<table align="center" cellpadding="0" cellspacing="0" class="table_98" id="id_add_superadmin">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_superadmin">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">添加员工帐户</td></tr>

          <tr >

            <td width="20%"> 帐户：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="username" type="text" id="username" size="20" value=""> *</td>

          </tr>

          <tr >

            <td width="20%"> 便捷帐户：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="uname" type="text" id="uname" size="20" value=""></td>

          </tr>

          <tr >

            <td>密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="password" value="" /> *(密码长度不能少于4位)</td>

          </tr>

		  <tr >

            <td>确认密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="repassword" value="" /> *</td>

          </tr>

          
          <tr >

            <td width="20%"> 姓名：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" size="20" value=""> *</td>

          </tr>

          <tr >

            <td width="20%"> 部门：</td>

            
            <td width="80%" >
            	<span style="width:300px" class="select-box"> <select name="section" class="select">
				<?php
					for ($i=0; $i < count($sections); $i++) { 
		            	$arr = $sections[$i];
					  echo '<option value="'.$arr['id'].'">'.$arr["name"].'</option>';
					}
				?>
            </select></span>
			 *</td>
            

          </tr>

          <tr >

            <td width="20%"> 组别：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="group" type="text" size="20" value=""> *</td>

          </tr>


          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确定添加 " ></td>

          </tr>

        </table>

      

	  </form>

	  

	  </td>

  </tr>

</table>
    </div></div>
    


<?php

}



//////////////////////////////////////////

if($act == "save_add_superadmin"){

       $username   = trim($_POST["username"]);

       $uname   = trim($_POST["uname"]);

	   $password   = trim($_POST["password"]);

	   $repassword = trim($_POST["repassword"]);

	   $name   = trim($_POST["name"]);

	   $section   = trim($_POST["section"]);

	   $group   = trim($_POST["group"]);

	   if($username==""){

	      echo "<script>alert('员工帐户不能为空');window.location.href='?act=superadmin'</script>";

		   exit;

	   }	  

		   if(strlen($password)<4){

			   echo "<script>alert('密码长度不能小于4位');window.location.href='?act=superadmin'</script>";

			   exit;

		   }

		   if($password != $repassword)

		   {

			   echo "<script>alert('两次输入的密码不一致');window.location.href='?act=superadmin'</script>";

			   exit;

		   }

		   if($name==""){

	      echo "<script>alert('员工姓名不能为空');window.location.href='?act=superadmin'</script>";

		   exit;

	   }

	   if($section==""){

	      echo "<script>alert('员工部门不能为空');window.location.href='?act=superadmin'</script>";

		   exit;

	   }

	   if($group==""){

	      echo "<script>alert('员工组别不能为空');window.location.href='?act=superadmin'</script>";

		   exit;

	   }

	   $b=$database->select("admin", "*", array("OR"=>array("username"=>$username, "uname"=>$username)));

	   if (!$b[0] && is_object($database->insert("admin", array("username"=>$username, "uname"=>$uname, "password"=>md5($password), "name"=>$name, "section"=>$section, "group"=>$group, "logins"=>0))))
	      echo "<script>alert('账户添加成功');</script>";
	    else
	      echo "<script>alert('账户添加失败');</script>";
	   
	   echo "<script>window.location.href='?act=superadmin'</script>";

	   exit; 

}



////编辑人员

if($act == "edit_superadmin"){ 

 global $database;

  	$id  = $_GET['id'];

  	$b = $database->select("admin", "*", array("id"=>$id));
  	if (!is_array($b))
    	exit;

    $arr = $b[0];
    $sections = $database->select("section", "*", array("id[!]"=>0));
    $cursection = $database->select("section", "*", array("id"=>$arr["section"]));
    if (is_array($cursection))
    	$cursection = $cursection[0];
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?act=superadmin">人员管理</a> <span class="c-gray en">&gt;</span> 人员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_superadmin">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑员工帐户</td></tr>

          <tr >

            <td width="20%"> 帐户：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="username" type="text" id="username" size="20" value="<?=$arr["username"]?>"></td>

          </tr>

          <tr >

            <td width="20%"> 便捷帐户：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="uname" type="text" id="uname" size="20" value=""></td>

          </tr>

          <tr >

            <td>密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="password" value="" />(如不修改密码则无需添写,密码长度不能少于4位)</td>

          </tr>

		  <tr >

            <td>确认密码：</td>

            <td><input style="width:300px" class="input-text" type="password" name="repassword" value="" /></td>

          </tr>

          <tr >

            <td width="20%"> 姓名：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" size="20" value=""></td>

          </tr>

          <tr >

            <td width="20%"> 部门：</td>

            
            <td width="80%" >
            	<select name="section" form="form1">
            		<option value="<?=$cursection["id"]?>"> <?=$cursection["name"]?></option>
            		<?php
		            	for ($i=0; $i < count($sections); $i++) { 
		            		$tarr = $sections[$i];
		            		if ($cursection["id"] != $tarr["id"]) {
		            ?>

		            <option value="<?=$tarr["id"]?>"> <?=$tarr["name"]?></option>

		            <?php

		            	}
		            }

		            ?>
            	</select>
			</td>
            

          </tr>

          <tr >

            <td width="20%"> 组别：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="group" type="text" size="20" value=""></td>

          </tr>

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
    </div></div>



<?php

}



////保存编辑的人员帐户//////////////////////////////////////

if($act == "save_edit_superadmin"){

		global $database;

       $id         = $_POST['id'];

	   $username   = trim($_POST["username"]);

       $uname   = trim($_POST["uname"]);

	   $password   = trim($_POST["password"]);

	   $repassword = trim($_POST["repassword"]);

	   $name   = trim($_POST["name"]);

	   $section   = trim($_POST["section"]);

	   $group   = trim($_POST["group"]);

	   $arr = array();

	   if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }

	  if ($username != "")
    	$arr["username"] = $username;

	    if ($uname != "")
	    	$arr["uname"] = $uname;

	    if ($password != "") {

	    	if(strlen($password)<4){

			   echo "<script>alert('密码长度不能小于4位');window.location.href='?act=superadmin'</script>";

			   exit;

		   }

		   if($password != $repassword)

		   {

			   echo "<script>alert('两次输入的密码不一致');window.location.href='?act=superadmin'</script>";

			   exit;

		   }

	    	$arr["password"] = md5($password);
	    }

	    if ($name != "")
	    	$arr["name"] = $name;

	    if ($section != "")
	    	$arr["section"] = $section;

	    if ($group != "")
	    	$arr["group"] = $group;


	    if (is_object($database->update("admin", $arr, array("id"=>$id))))
		    echo "<script>alert('账户更新成功');</script>";
		  else
		    echo "<script>alert('账户更新失败');</script>";

	   echo "<script>window.location.href='?act=superadmin'</script>";

	   exit; 



}



////删除人员帐户//////////////////////////////////////

if($act == "delete_superadmin"){

		global $database;

      $id         = $_GET['id'];

	   

	  if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }


	  if (is_object($database->delete("admin", array("id"=>$id))))
	    echo "<script>alert('账号删除成功');</script>";
	  else
	    echo "<script>alert('账号删除失败');</script>";

	  echo "<script>window.location.href='?act=superadmin'</script>";

	  exit; 



}




////等级设置

if($act == "dengji"){

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>





<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="10%">等级名称</td>
          <td width="10%">级别</td>
          <td width="10%">审核权限</td>
          <td width="10%">升级审核</td>
          <td width="10%">编辑权限</td>
          <td width="10%">删除权限</td>

          <td width="20%">操作</td>          

		</tr>
       
        <?php

		 $sqldj = "select djname from tgs_dengji where djname<>'' order by jibie DESC";

		 $resdj = mysql_query($sqldj);
		 
         
		 
		 while($arr = mysql_fetch_array($resdj)){	
		$djname1 .= $arr['djname'].',';
		 }
		$djname2 =$djname1;
		$djname = substr($djname2,0,strlen($djname2)-1); 
		//echo $djname;
		
		?>
        
        
        

		<?php

		 $sql = "select * from tgs_dengji where djname<>'' order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_dengji&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["djname"];?></a></td>
          <td><?php echo $arr["jibie"];?></td>
          <td>
		  
		  <?php 
		  
		   if($arr["checkper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
               <td> <?php 
		  
		   if($arr["sjcheckper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
          <td> <?php 
		  
		   if($arr["editper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
          <td><?php 
		  
		   if($arr["delper"]==1) 
		   {echo "有";}
           else   {echo "无";}
		   ?></td>
          <td>
            <a title="编辑" href="?act=edit_dengji&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; &nbsp;&nbsp; <a title="删除" href="?act=delete_dengji&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
            
          </td>

        </tr>
        
        

		<?php

		}
		
		

	
		?>
 <tr >

          <td colspan="7"> <a href="dengji.php?dldj=<?=$djname?>" class="btn btn-danger radius"><font color="#ffffff">同步等级设置</font> </a> 菜单修改或添加完成后，请点击同步等级设置按钮，完成代理等级同步 </td>

          </tr>
		</table>

    

	</td>

  </tr>

</table>

<br />

<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_dengji">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">增加等级</td></tr>

          <tr >

            <td width="20%"> 等级名称：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="djname" type="text" id="djname" size="20" value=""></td>

          </tr>

          <tr >

            <td>级别：</td>

            <td><input style="width:300px" class="input-text" name="jibie" type="text" id="jibie" size="20" value="">(请输入1-10之间的数字，数字越大，等级越高)</td>

          </tr>

		  <tr >

            <td>审核权限：</td>

            <td><input name="checkper" type="radio" value="1"/>有
           
           <input name="checkper" type="radio" value="0" checked="checked"/>无</td>

          </tr>
          
            <tr >

            <td>审核权限：</td>

            <td><input name="sjcheckper" type="radio" value="1"/>有
           
           <input name="sjcheckper" type="radio" value="0" checked="checked"/>无</td>

          </tr>
          
            <tr >

            <td>编辑权限：</td>

            <td><input name="editper" type="radio" value="1"/>有
           
           <input name="editper" type="radio" value="0" checked="checked"/>无</td>

          </tr>
           <tr >

            <td>删除权限：</td>

            <td><input name="delper" type="radio" value="1"/>有
           
           <input name="delper" type="radio" value="0" checked="checked"/>无</td>

          </tr>

          

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确定添加 " ></td>

          </tr>

        </table>

      

	  </form>

	  

	  </td>

  </tr>

</table>
    </div></div>
    






<?php

}

//////////////////////////////////////////

if($act == "save_add_dengji"){



       $djname   = trim($_POST["djname"]);
	   $jibie   = trim($_POST["jibie"]);
	   $checkper   = trim($_POST["checkper"]);
	   $sjcheckper   = trim($_POST["sjcheckper"]);
	   $editper   = trim($_POST["editper"]);
	   $delper   = trim($_POST["delper"]);

	  

	   $a          = 0;



	    

		   


	   $sql="insert into tgs_dengji set djname='".$djname."',delper='".$delper."', jibie='".$jibie."',checkper='".$checkper."',sjcheckper='".$sjcheckper."',editper='".$editper."'";

	   mysql_query($sql) or die("err:".$sql);

	   

       echo "<script>alert('添加等级成功');</script>";

	   echo "<script>window.location.href='?act=dengji'</script>";

	   exit; 

}





////编辑等级权限

if($act == "edit_dengji"){ 

 $id  = $_GET['id'];

 $sql = "select * from tgs_dengji where id=".$id." limit 1";

 $res = mysql_query($sql);

 $arr = mysql_fetch_array($res);

 $jibie  = $arr['jibie'];
 $djname  = $arr['djname'];
 $checkper  = $arr['checkper'];
 $sjcheckper  = $arr['sjcheckper'];
 $editper  = $arr['editper'];
 $delper  = $arr['delper'];
 

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">管理员管理</a> <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_dengji">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑等级权限</td></tr>

          <tr >

            <td width="20%"> 等级名称：</td>

            <td width="80%" ><input name="djname" type="text" class="input-text" id="djname" style="width:300px" value="<?php echo $djname?>" size="20"></td>

          </tr>
       <tr >

            <td width="20%"> 别级：</td>

            <td width="80%" ><input name="jibie" type="text" class="input-text" id="jibie" style="width:300px" value="<?php echo $jibie?>" size="20">请输入1-10之间的数字，数字越大，等级越高</td>

          </tr>
          <tr >

            <td>审核权限：</td>

            <td>  <input name="checkper" type="radio" value="1" 
		   <?php 
		   if($checkper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="checkper" type="radio" value="0"  <?php 
		   if($checkper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>

 <tr >

            <td>升级审核权限：</td>

            <td>  <input name="sjcheckper" type="radio" value="1" 
		   <?php 
		   if($sjcheckper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="sjcheckper" type="radio" value="0"  <?php 
		   if($sjcheckper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>
		  <tr >

            <td>编辑权限：</td>

            <td>  <input name="editper" type="radio" value="1" 
             <?php 
		   if($editper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="editper" type="radio" value="0"  <?php 
		   if($editper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>

            <tr >

            <td>删除权限：</td>

            <td>  <input name="delper" type="radio" value="1" 
             <?php 
		   if($delper==1) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?>
          
		     />有
           
           <input name="delper" type="radio" value="0"  <?php 
		   if($delper==0) 
		   {echo "checked=checked;";}
           else   {echo "";}
		   ?> />无</td>

          </tr>

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
    </div></div>



<?php

}



////保存编辑的等级权限信息//////////////////////////////////////

if($act == "save_edit_dengji"){



       $id         = $_POST['id'];

	   $djname   = trim($_POST["djname"]);
	   $jibie   = trim($_POST["jibie"]);

	   $checkper   = trim($_POST["checkper"]);
	   $sjcheckper   = trim($_POST["sjcheckper"]);

	   $editper = trim($_POST["editper"]);
	   
	   $delper = trim($_POST["delper"]);

	   $a          = 0;

	   if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=dengji'</script>";

			   exit;

	  }

	  

	   
		



		   $sql="update tgs_dengji set djname='".$djname."',jibie='".$jibie."',checkper='".$checkper."',sjcheckper='".$sjcheckper."',delper='".$delper."',editper='".$editper."' where id=".$id." limit 1";

	       mysql_query($sql) or die("err:".$sql);

		   $a= 1;

	


	   if($a == 1){

         echo "<script>alert('更新等级权限成功');</script>";

	   }else{

	     echo "<script>alert('更新等级权限失败!!');</script>";

	   }

	   echo "<script>window.location.href='?act=dengji'</script>";



	   exit; 



}

////删除等级//////////////////////////////////////

if($act == "delete_dengji"){



      $id         = $_GET['id'];

	   

	  if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }



	  

	  $sql="delete from tgs_dengji where id=".$id." limit 1";

	  mysql_query($sql) or die("err:".$sql);

		 

	   

      echo "<script>alert('等级删除成功！');</script>";

	  echo "<script>window.location.href='?act=dengji'</script>";

	  exit; 



}

////产品设置

if($act == "product"){

?>




<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">产品管理</a> <span class="c-gray en">&gt;</span> 产品设置 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>





<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="10%">产品名称</td>
          <td width="10%">顺序</td>
          <td width="10%">图片</td>

          <td width="20%">操作</td>          

		</tr>
       
        <?php

		 $sqldj = "select proname from tgs_product order by jibie DESC";

		 $resdj = mysql_query($sqldj);
		 
         
		 
		 while($arr = mysql_fetch_array($resdj)){	
		$proname .= $arr['proname'].' ';
		 }
		$proname =$proname;
		
		?>
        
        
        

		<?php

		 $sql = "select * from tgs_product order by jibie DESC";

		 $res = mysql_query($sql);
		 
        
		 while($arr = mysql_fetch_array($res)){		
       
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_product&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["proname"];?></a></td>
          <td><?php echo $arr["jibie"];?></td>
          
            <td><img src="<?php echo $arr["proimg"];?>" width="200" height="100" alt=""/></td>
            
          <td>
            <a title="编辑" href="?act=edit_product&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; &nbsp;&nbsp; <a title="删除" href="?act=delete_product&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
            
          </td>

        </tr>
        
        

		<?php

		}
		
		

	
		?>

		</table>

    

	</td>

  </tr>

</table>

<br />

<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_product">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">增加产品</td></tr>

          <tr >

            <td width="20%"> 产品名称：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="proname" type="text" id="proname" size="20" value=""></td>

          </tr>

          <tr >

            <td>顺序：</td>

            <td><input style="width:300px" class="input-text" name="jibie" type="text" id="jibie" size="20" value="">(数字越大，排名越前)</td>

          </tr>
       <tr >

            

	<td>产品图片：</td>

           

	 <td><input style="width:300px" class="input-text" name="proimg" type="text" id="proimg" size="20" value="">

	 

     <input name="PrUpload" id="PrUpload" type="button" style="width:80px;" value="上传图片">                            



	 </td>

     </tr>
     
       <tr >

            <td>详细介绍：</td>
	
            <td><textarea name="content1" style="width:700px;height:200px;visibility:hidden;"><?php echo htmlspecialchars($htmlData); ?></textarea></td>
          


          </tr>


		  
          

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确定添加 " ></td>

          </tr>

        </table>

      

	  </form>

	  

	  </td>

  </tr>

</table>
    </div></div>
    






<?php

}

//////////////////////////////////////////

if($act == "save_add_product"){



       $proname   = trim($_POST["proname"]);
	   $jibie   = trim($_POST["jibie"]);
	   $proimg   = trim($_POST["proimg"]);
	   $htmlData = '';
	   if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
	}

	  

	   $a          = 0;



	    

		   


	   $sql="insert into tgs_product set proname='".$proname."',jibie='".$jibie."',proimg='".$proimg."',saytext='".$htmlData."'";

	   mysql_query($sql) or die("err:".$sql);

	   echo "<script>alert('添加产品成功');location.href='admin.php?act=product'</script>";

      

	 

	   exit; 

}



////编辑产品

if($act == "edit_product"){ 

 $id  = $_GET['id'];

 $sql = "select * from tgs_product where id=".$id." limit 1";

 $res = mysql_query($sql);

 $arr = mysql_fetch_array($res);

 $jibie  = $arr['jibie'];
 $proimg  = $arr['proimg'];
 $saytext  = $arr['saytext'];
 $proname  = $arr['proname'];

 

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="admin.php?">产品设置</a> <span class="c-gray en">&gt;</span> 编辑产品 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_product">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

		<table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑产品名称</td></tr>

          <tr >

            <td width="20%"> 产品名称：</td>

            <td width="80%" ><input name="proname" type="text" class="input-text" id="proname" style="width:300px" value="<?php echo $proname?>" size="20"></td>

          </tr>
       <tr >

            <td width="20%"> 别级：</td>

            <td width="80%" ><input name="jibie" type="text" class="input-text" id="jibie" style="width:300px" value="<?php echo $jibie?>" size="20">请输入1-10之间的数字，数字越大，排名越高</td>

          </tr>
          
            <tr >

            

	<td>产品图片：</td>

           

	 <td><input style="width:300px" class="input-text" name="proimg" type="text" id="proimg" size="20" value="<?php echo $proimg;?>">

	 

     <input name="PrUpload" id="PrUpload" type="button" style="width:80px;" value="上传图片">                            



	 </td>

     </tr>
     
       <tr >

            <td>详细介绍：</td>
	
            <td><textarea name="content1" style="width:700px;height:200px;visibility:hidden;"><?php echo $saytext;?></textarea></td>
          


          </tr>
         

          <tr >

            <td>&nbsp;</td>

            <td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 确 定 " ></td>

          </tr>

        </table>      

	  </form>	  

	  </td>

  </tr>

</table>
    </div></div>



<?php

}



////保存编辑的等级权限信息//////////////////////////////////////

if($act == "save_edit_product"){



       $id         = $_POST['id'];

	   $proname   = trim($_POST["proname"]);
	   $proimg   = trim($_POST["proimg"]);
	   $jibie   = trim($_POST["jibie"]);

	    $htmlData = '';
	   if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
	}


	   $a          = 0;

	   if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=dengji'</script>";

			   exit;

	  }

	  

	   
		



		   $sql="update tgs_product set proname='".$proname."',jibie='".$jibie."',proimg='".$proimg."',saytext='".$htmlData."' where id=".$id." limit 1";

	       mysql_query($sql) or die("err:".$sql);

		   $a= 1;

	


	   if($a == 1){

         echo "<script>alert('更新产品成功');</script>";

	   }else{

	     echo "<script>alert('更新产品失败!!');</script>";

	   }

	   echo "<script>window.location.href='?act=product'</script>";



	   exit; 



}

////删除等级//////////////////////////////////////

if($act == "delete_product"){



      $id         = $_GET['id'];

	   

	  if(!$id){

			   echo "<script>alert('id参数有误');window.location.href='?act=superadmin'</script>";

			   exit;

	  }



	  

	  $sql="delete from tgs_product where id=".$id." limit 1";

	  mysql_query($sql) or die("err:".$sql);

		 

	   

      echo "<script>alert('删除成功！');</script>";

	  echo "<script>window.location.href='?act=product'</script>";

	  exit; 



}


////csv读取函数

function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"')

{

      $d = preg_quote($d);

      $e = preg_quote($e);

      $_line = "";

      $eof   = false;

      while ($eof != true)

      {

         $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));

         $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);

         if ($itemcnt % 2 == 0)

            $eof = true;

      }

      $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));      $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';

      preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

      $_csv_data = $_csv_matches[1];

      for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++)

      {       $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);

         $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);

      }

      return empty($_line) ? false : $_csv_data;

}

?>

</body>

</html>