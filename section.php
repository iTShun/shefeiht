<?php

include 'head.php';

$act = $_GET["act"];

if($act == "")

{

?>



<?php

}

////部门设置

if($act == "supersection")

{

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="section.php?act=supersection">部门管理</a> <span class="c-gray en">&gt;</span> 部门列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">

    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

      <?php 
        //添加权限判断
        echo get_privilege_code("添加部门");
      ?>

  <tr>

    <td valign="top">

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <td width="10%">id</td>

          <td width="20%">部门</td>

          <td width="20%">权限</td>

          <td width="20%">限制</td>

          <td width="20%">操作</td>          

		</tr>

		<?php

			global $database;
			$sections = $database->select("section", "*", array("id[!]"=>0));
			$privileges = $database->select("privilege", "*", array("id[!]"=>0));

			$privilegenames = array();

			for ($i=0; $i < count($privileges); $i++) { 
				$arr = $privileges[$i];
				if (is_array($arr))
					$privilegenames[$arr["id"]] = $arr["name"];
			}

			for ($i=0; $i < count($sections); $i++) { 
	        	$arr = $sections[$i];	
		?>

        <tr >

          <td><?php echo $arr["id"];?></td>

          <td><a href="?act=edit_supersection&id=<?php echo $arr["id"];?>" title="编辑"><?php echo $arr["name"];?></a></td>

          <?php 
          	$privilege = $arr["privilege"];
          	
          	if ($privilege != "")
          	{
          		$temp = explode(",", $privilege);
          		$privilege = "";
          		for ($j=0; $j < count($temp); $j++) { 
          			if (isset($privilegenames[$temp[$j]]))
	          		{
	          			$privilege = $privilege . $privilegenames[$temp[$j]] . ",";
	          		}
          		}
          		$privilege = rtrim($privilege, ",");
          	}
          ?>
          <td><?php echo $privilege;?></td>

          <td><?=$arr["limit"];?></td>

          <td>
          <a title="编辑" href="?act=edit_supersection&id=<?php echo $arr["id"];?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; <a title="删除" href="?act=delete_supersection&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
          
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

////添加部门

if ($act == "add") {

  global $database;
  $privileges = $database->select("privilege", "*", array("id[!]"=>0));
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="section.php?act=supersection">部门管理</a> <span class="c-gray en">&gt;</span> 添加部门 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98" id="id_add_supersection">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_supersection">


		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">添加部门</td></tr>

          <tr >

            <td width="20%"> 部门：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" id="name"  value=""> *</td>

          </tr>

          <tr >

            <td>权限：</td>

            <td>
            	<?php 
	            	for($i=0;$i<count($privileges);$i++){
	            		$arr = $privileges[$i];
	            ?>

            	<Label><input type="checkbox" name="<?=$arr["id"]?>"> <?=$arr["name"]?></Label>
            	&nbsp;

            	<?php

				}

				?>
            </td>

            

          </tr>

		  <tr >

            <td>权限限制：</td>

            <td width="80%" ><textarea style="width:400px" class="textarea" name="limit" id="limit" cols="50" rows="5"></textarea></td>

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

if ($act == "save_add_supersection") {

	global $database;

	$name = trim($_POST["name"]);

	if($name==""){

      echo "<script>alert('部门不能为空');window.location.href='?act=supersection'</script>";

      exit;
    }  

	$privilege = "";

	foreach ($_POST as $key => $value) {
		if ($key != "name" && $value == "on")
		{
			$privilege = $privilege . $key . ",";
		}
	}

	$privilege = rtrim($privilege, ",");

	$limit = $_POST["limit"];

   $b=$database->select("section", "*", array("name"=>$name));

	if (!$b[0] && is_object($database->insert("section", array("name"=>$name, "privilege"=>$privilege, "limit"=>$limit))))
      echo "<script>alert('部门添加成功');</script>";
    else
      echo "<script>alert('部门添加失败');</script>";

  	echo "<script>window.location.href='?act=supersection'</script>";

    exit; 
?>


<?php

}

////编辑部门

if($act == "edit_supersection"){ 

	global $database;

  	$id  = $_GET['id'];

  	$b = $database->select("section", "*", array("id"=>$id));
  	if (!is_array($b))
    	exit;

    $arr = $b[0];
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="section.php?act=supersection">部门管理</a> <span class="c-gray en">&gt;</span> 部门列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

  

  <form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_supersection">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

    <table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑部门</td></tr>

          <tr >

            <td width="20%"> 部门：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" id="name" value="<?=$arr['name']?>"></td>

          </tr>

          <tr >

            <td>权限</td>

            <td>
            	<?php 
            		$privileges = $database->select("privilege", "*", array("id[!]"=>0));
            		$temp = explode(",", $arr["privilege"]);
            		$parr = array();

            		for ($i=0; $i < count($temp); $i++) { 
            			if (isset($temp[$i]) && $temp[$i] != "")
            				$parr[$temp[$i]] = $temp[$i];
            		}
            		
	            	for($i=0;$i<count($privileges);$i++){
	            		$arr = $privileges[$i];
	            		if (isset($parr[$arr['id']])) {
	            ?>

            	<Label><input type="checkbox" name="<?=$arr["id"]?>" id="<?=$arr["id"]?>" checked> <?=$arr["name"]?></Label>
            	&nbsp;

            	<?php }else{ ?>

            	<Label><input type="checkbox" name="<?=$arr["id"]?>" id="<?=$arr["id"]?>"> <?=$arr["name"]?></Label>
            	&nbsp;

            	<?php

            		}
				}

				?>
            </td>

          </tr>

      <tr >

            <td>权限限制：</td>

            <td width="80%" ><textarea style="width:400px" class="textarea" name="limit" id="limit" cols="50" rows="5"></textarea></td>

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

if($act == "save_edit_supersection"){

  global $database;

  $id = $_POST['id'];

  if(!$id){

      echo "<script>alert('id参数有误');window.location.href='?act=supersection'</script>";

      exit;
  }  

  $name = trim($_POST["name"]);

  $privilege = "";

  foreach ($_POST as $key => $value) {
	if ($key != "name" && $value == "on")
	{
		$privilege = $privilege . $key . ",";
	}
  }

  $privilege = rtrim($privilege, ",");

  $limit = $_POST["limit"];

  $arr = array();
  
  if ($name != "")
    $arr["name"] = $name;

  if ($privilege != "")
    $arr["privilege"] = $privilege;

  if ($limit != "")
    $arr["limit"] = $limit;
  
  if (is_object($database->update("section", $arr, array("id"=>$id))))
    echo "<script>alert('部门更新成功');</script>";
  else
    echo "<script>alert('部门更新失败');</script>";

  echo "<script>window.location.href='?act=supersection'</script>";

  exit;
?>


<?php

}

////删除权限//////////////////////////////////////

if($act == "delete_supersection"){

  global $database;

  $id = $_GET['id'];

  if (is_object($database->delete("section", array("id"=>$id))))
    echo "<script>alert('部门删除成功');</script>";
  else
    echo "<script>alert('部门删除失败');</script>";

  echo "<script>window.location.href='?act=supersection'</script>";

  exit;
?>


<?php

}

?>


</body>

</html>