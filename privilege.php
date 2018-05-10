<?php

include 'head.php';

$act = $_GET["act"];

if($act == "")

{

?>



<?php

}

////权限设置

if($act == "superprivilege")

{

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="privilege.php?act=superprivilege">权限管理</a> <span class="c-gray en">&gt;</span> 权限列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">

    <?php 
      //添加权限判断
      echo get_privilege_code("添加权限");
    ?>

    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="20%">权限</td>

          <td width="20%">父权限</td>

          <td width="10%">权限等级</td>

          <!--<td width="20%">权限代码</td>-->

          <td width="20%">操作</td>          

		</tr>

		<?php

			global $database;
			$privileges = $database->select("privilege", "*", array("id[!]"=>0));
      
		 for ($i=0; $i < count($privileges); $i++) { 
        $arr = $privileges[$i];
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_superprivilege&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["name"];?></a></td>

          <td><?php echo $arr["fname"];?></td>

          <td><?php echo $arr["level"];?></td>

          <!--<td><?php echo $arr["code"];?></td>-->

          <td>
          <a title="编辑" href="?act=edit_superprivilege&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; <a title="删除" href="?act=delete_superprivilege&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
          
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

////添加权限

if ($act == "add") {
  
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="privilege.php?act=superprivilege">权限管理</a> <span class="c-gray en">&gt;</span> 添加权限 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">


    <table align="center" cellpadding="0" cellspacing="0" class="table_98" id="id_add_superprivilege">

  <tr>

    <td valign="top">

	

	<form name="form1" id="form1" method="post" enctype="multipart/form-data" action="?act=save_add_superprivilege">

    

		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">添加权限</td></tr>

          <tr >

            <td width="20%"> 权限：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" value=""> *</td>

          </tr>

          <tr >

            <td>父权限：</td>

            <td><input style="width:300px" class="input-text" type="text" name="fname" value="" /></td>

          </tr>

		  <tr >

            <td>权限等级：</td>

            <td><input style="width:300px" class="input-text" type="text" name="level" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="" /> *</td>

          </tr>

           <tr >

            <td>权限代码：</td>

            <td><textarea name="code" form="form1" cols="65" rows="5"> </textarea> *</td>

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

if ($act == "save_add_superprivilege")
{
    global $database;

    $name = trim($_POST["name"]);

    $fname = trim($_POST["fname"]);

    $level = trim($_POST["level"]);

    $code = trim($_POST["code"]);

    if($name==""){

      echo "<script>alert('权限不能为空');window.location.href='?act=superprivilege'</script>";

      exit;
    }  

     if($level==""){

      echo "<script>alert('权限等级不能为空');window.location.href='?act=superprivilege'</script>";

      exit;
    }  

    if($code==""){

      echo "<script>alert('权限代码不能为空');window.location.href='?act=superprivilege'</script>";

      exit;
    }

    $b=$database->select("privilege", "*", array("name"=>$name));
    $f=1;
    if ($fname!="")
    {
      $f=$database->select("privilege", "*", array("OR"=>array("id"=>$fname, "name"=>$fname)));

      if ($f[0])
        $fname = $f[0]['name'];
    }

    if (!$b[0] && $f && is_object($database->insert("privilege", array("name"=>$name, "fname"=>$fname, "level"=>$level, "code"=>$code))))
    {
      $newprivilege=$database->select("privilege", "*", array("name"=>$name));
      $sysdba=$database->select("section", "*", array("id"=>1));
      if ($newprivilege[0] && $sysdba[0])
      {
        $database->update("section", array("privilege"=>$sysdba[0]["privilege"].",".$newprivilege[0]["id"]), array("id"=>1));
      }
      echo "<script>alert('权限添加成功');</script>";
    }
    else
      echo "<script>alert('权限添加失败');</script>";

    echo "<script>window.location.href='?act=superprivilege'</script>";

    exit; 

}

////编辑权限

if($act == "edit_superprivilege"){ 

  global $database;

  $id  = $_GET['id'];

  $b = $database->select("privilege", "*", array("id"=>$id));
  if (!is_array($b))
    exit;

  $arr = $b[0];

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="privilege.php?act=superprivilege">权限管理</a> <span class="c-gray en">&gt;</span> 权限列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

  

  <form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_superprivilege">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

    <table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑权限</td></tr>

          <tr >

            <td width="20%"> 权限：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" id="name" value="<?=$arr['name']?>"></td>

          </tr>

          <tr >

            <td>父权限</td>

            <td><input style="width:300px" class="input-text" type="text" name="fname" value="" /></td>

          </tr>

      <tr >

            <td>权限等级：</td>

            <td><input style="width:300px" class="input-text" type="text" name="level" value="" /></td>

          </tr>

           <tr >

            <td>权限代码：</td>

            <td><textarea name="code" form="form1" cols="65" rows="5"> </textarea></td>

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

////保存编辑的权限//////////////////////////////////////

if($act == "save_edit_superprivilege"){

  global $database;

  $id = $_POST['id'];

  $name = trim($_POST["name"]);

  $fname = trim($_POST["fname"]);

  $level = trim($_POST["level"]);

  $code = trim($_POST["code"]);

  $arr = array();

  if(!$id){

      echo "<script>alert('id参数有误');window.location.href='?act=superprivilege'</script>";

      exit;
  }  

  if ($name != "")
    $arr["name"] = $name;

  if ($fname != "")
    $arr["fname"] = $fname;

  if ($level != "")
    $arr["level"] = $level;

  if ($code != "")
    $arr["code"] = $code;
  
  if (is_object($database->update("privilege", $arr, array("id"=>$id))))
    echo "<script>alert('权限更新成功');</script>";
  else
    echo "<script>alert('权限更新失败');</script>";

  echo "<script>window.location.href='?act=superprivilege'</script>";

  exit;
?>


<?php

}

////删除权限//////////////////////////////////////

if($act == "delete_superprivilege"){

  global $database;

  $id = $_GET['id'];

  if (is_object($database->delete("privilege", array("id"=>$id))))
    echo "<script>alert('权限删除成功');</script>";
  else
    echo "<script>alert('权限删除失败');</script>";

  echo "<script>window.location.href='?act=superprivilege'</script>";

  exit;
?>


<?php

}

?>

</body>

</html>