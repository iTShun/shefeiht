<?php

include 'head.php';

$act = $_GET["act"];

if ($act == "qrcode") {
?>
<script type="text/javascript">
	
function changfile(file)
{

	//alert(file.value);

	/*if (window.opener != null && !window.opener.closed) {
        var txtName = window.opener.document.getElementById("qrcode");//获取父窗口中元素，也可以获取父窗体中的值
        txtName.value = file.value;//将子窗体中的值传递到父窗体中去
    }*/
    //window.close();
}


</script>
<?php 

}

if ($act == "save_qrcode") {
	
	if($_FILES['file']['size']>0 && $_FILES['file']['name']!="")

 {

	    $file_size_max    = 3072000; //3000k

		$store_dir        = "../upload/qrcode/";

		$ext_arr          = array('png','jpg','jpeg');

		$accept_overwrite = true;

		$date1            = date("YmdHis");

		$file_type        = extend($_FILES['file']['name']);

		$newname          = $date1.".".$file_type;

		//判断格式		

		if (in_array($file_type,$ext_arr) === false){

		  echo "<script>alert('上传的文件格式错误，请按要求的文件格式上传');history.back()</script>";

		  exit;

	   }

	    //判断文件的大小

		if ($_FILES['file']['size'] > $file_size_max) {

		  echo "<script>alert('对不起，你上传的文件大于3000k');history.back()</script>";

		  exit;

		}

		

		if (file_exists($store_dir.$_FILES['file']['name'])&&!$accept_overwrite)

		{

		  echo "<script>alert('文件已存在，不能新建');history.back()</script>";

		  exit;

		}

		if (!move_uploaded_file($_FILES['file']['tmp_name'],$store_dir.$newname)) {

		  echo "<script>alert('复制文件失败');history.back()</script>";

		  exit;

		}

	  $filepath = $store_dir.$newname;

	  

	 }else{

	   $filepath = "";

	   
	 }
	
	 if($filepath == ""){

	    echo "<script>alert('请先选择要上传的文件');history.back()</script>";

		exit;

	 }

	 echo "<script>if (window.opener != null && !window.opener.closed) {
        var txtName = window.opener.document.getElementById('qrcode');
        alert('上传成功');
        txtName.value = '$filepath';
    }window.close();</script>";
}

?>

<div class="page-container">
	<div class="text-c">
		<table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

    	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_<?=$act?>">

    		<table cellpadding="3" cellspacing="1" class="table_50">
			<tr><td width="80%" >文件: <input type="file" name="file" id="file">
			</td></tr>

			<tr><td>&nbsp;</td></tr>
			<tr><td><input class="btn btn-primary radius" type="submit" name="Submit" value=" 上 传 " >
			</td></tr>
		</form>
		</td>
	</tr>
</table>
	</div>
</div>

</body>

</html>