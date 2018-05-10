<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
$act = $_GET["act"];
/////////////////////
///导出资源信息/////////////////////////////////////////////
if($act=="export_resource")
{
	$chk = $_REQUEST["chk"];
	$chk = explode(",",$chk);///转化为数组
	$file_encoding = $_POST["file_encoding"];	
	$a   = 0;
	
	if(count($chk) > 0){	
		if($_POST['field_id']=="1"){
		 $content  = "ID";
		 $a        = 1;
		}	
		if($_POST['field_uid']=="1"){
		 	if($_POST['field_id']=="1")
		 		$content  = ",代理编号";
		 	else
		 		$content  = "代理编号";
		 $a        = 1;
		}
		if($_POST['field_name']==1){
		 $content .= ",姓名";
		 $a        = 1;
		}
		if($_POST['field_phone']==1){
		 $content .= ",手机";
		 $a        = 1;
		}
		if($_POST['field_wechat']==1){
		 $content .= ",微信";
		 $a        = 1;
		}
		if($_POST['field_qrcode']==1){
		 $content .= ",二维码";
		 $a        = 1;
		}
		if($_POST['field_qq']==1){
		 $content .= ",QQ";
		 $a        = 1;
		}
		if($_POST['field_idcard']==1){
		 $content .= ",身份证号";
		 $a        = 1;
		}
		if($_POST['field_addr']==1){
		 $content .= ",地址";
		 $a        = 1;
		}
		if($_POST['field_desc']==1){
		 $content .= ",备注";
		 $a        = 1;
		}
		if($_POST['field_type']==1){
		 $content .= ",类型";
		 $a        = 1;
		}
		if($_POST['field_control']==1){
		 $content .= ",操作员";
		 $a        = 1;
		}
		if($_POST['field_status']==1){
		 $content .= ",状态";
		 $a        = 1;
		}
		if($_POST['field_addtime']==1){
		 $content .= ",添加时间";
		 $a        = 1;
		}
		if($_POST['field_applytime']==1){
		 $content .= ",发布时间";
		 $a        = 1;
		}
		if($_POST['field_checktime']==1){
		 $content .= ",审核时间";
		 $a        = 1;
		}
		if($_POST['field_submittime']==1){
		 $content .= ",提交时间";
		 $a        = 1;
		}
		if($_POST['field_qudao']==1){
		 $content .= ",来源渠道";
		 $a        = 1;
		}
		
		$content  .= "\n";
		if($a == 0){
		    header("content-Type: text/html; charset=utf-8");
	        echo "<script>alert('请选择要导出的资源字段');history.back();</script>";
	        exit;
		}
		$countchk = count($chk);
		global $database;
		for($i=0;$i<$countchk;$i++)  
		{ 
			$b=$database->select("resource", "*", array("id"=>$chk[$i]));

			if ($b[0])
			{
				$arr = $b[0];

				if($_POST['field_id']=="1"){
					$content .= $arr["id"];
				}
				if($_POST['field_uid']=="1"){
					if($_POST['field_id']=="1")
						$content .= ",".$arr["uid"];
					else
						$content .= $arr["uid"];
				}
				if($_POST['field_name']=="1"){
					$content .= ",".$arr["name"];
				}
				if($_POST['field_phone']=="1"){
					$content .= ",".$arr["phone"];
				}
				if($_POST['field_wechat']=="1"){
					$content .= ",".$arr["wechat"];
				}
				if($_POST['field_qrcode']=="1"){
					$content .= ",".$arr["qrcode"];
				}
				if($_POST['field_qq']=="1"){
					$content .= ",".$arr["qq"];
				}
				if($_POST['field_idcard']=="1"){
					$content .= ",".$arr["idcard"];
				}
				if($_POST['field_addr']=="1"){
					$content .= ",".$arr["addr"];
				}
				if($_POST['field_desc']=="1"){
					$content .= ",".str_replace(",", ";", $arr["desc"]);
				}
				if($_POST['field_type']=="1"){
					$content .= ",".$arr["type"];
				}
				if($_POST['field_control']=="1"){
					$content .= ",".str_replace(",", ";", $arr["control"]);
				}
				if($_POST['field_status']=="1"){
					$content .= ",".$arr["status"];
				}
				if($_POST['field_addtime']=="1"){
					$content .= ",".$arr["addtime"];
				}
				if($_POST['field_applytime']=="1"){
					$content .= ",".$arr["applytime"];
				}
				if($_POST['field_checktime']=="1"){
					$content .= ",".$arr["checktime"];
				}
				if($_POST['field_submittime']=="1"){
					$content .= ",".$arr["submittime"];
				}
				if($_POST['field_qudao']=="1"){
					$content .= ",".$arr["qudao"];
				}
				$content .= "\n";
			}
		}//for结束

		if($file_encoding == "gbk"){
		 $content = iconv("utf-8", "gb2312"."//IGNORE", $content);
		}
		//$content = ob_gzip($content);////压缩文件
		$filename = "../upload/Resource_csv_".date("Ymd").".csv";///临时csv文件名称
		$fp = fopen($filename,'w+');//生成CSV文件
		if(fwrite($fp,$content)){		
		  header("content-Type: text/html; charset=utf-8");
		  echo "生成csv文件成功，<a href='".$filename."' target='_blank'>右击'目标另存为'文档</a>，下载后<a href='?act=delete_file&file=".$filename."'>删除此CSV文档</a>";
		}else{
		  header("content-Type: text/html; charset=utf-8");
		  echo "无法写入导出内容，upload文件夹应该为可读写权限。";
		}
		fclose($fp);
     }else{
	   header("content-Type: text/html; charset=utf-8");
	   echo "<script>alert('请选择要导出的资源信息');window.location.href='admin.php'</script>";
	   exit;
     }
}

///导出所有资源信息/////////////////////////////////////////////
if($act=="exportall_resource")
{
	$chk = $_REQUEST["chk"];
	$chk = explode(",",$chk);///转化为数组
	$file_encoding = $_POST["file_encoding"];	
	$a   = 0;
	
	if(count($chk) > 0){	
		if($_POST['field_id']=="1"){
		 $content  = "ID";
		 $a        = 1;
		}	
		if($_POST['field_uid']=="1"){
			if($_POST['field_id']=="1")
		 		$content  = ",代理编号";
		 	else
		 		$content  = "代理编号";
		 $a        = 1;
		}
		if($_POST['field_name']==1){
		 $content .= ",姓名";
		 $a        = 1;
		}
		if($_POST['field_phone']==1){
		 $content .= ",手机";
		 $a        = 1;
		}
		if($_POST['field_wechat']==1){
		 $content .= ",微信";
		 $a        = 1;
		}
		if($_POST['field_qrcode']==1){
		 $content .= ",二维码";
		 $a        = 1;
		}
		if($_POST['field_qq']==1){
		 $content .= ",QQ";
		 $a        = 1;
		}
		if($_POST['field_idcard']==1){
		 $content .= ",身份证号";
		 $a        = 1;
		}
		if($_POST['field_addr']==1){
		 $content .= ",地址";
		 $a        = 1;
		}
		if($_POST['field_desc']==1){
		 $content .= ",备注";
		 $a        = 1;
		}
		if($_POST['field_type']==1){
		 $content .= ",类型";
		 $a        = 1;
		}
		if($_POST['field_control']==1){
		 $content .= ",操作员";
		 $a        = 1;
		}
		if($_POST['field_status']==1){
		 $content .= ",状态";
		 $a        = 1;
		}
		if($_POST['field_addtime']==1){
		 $content .= ",添加时间";
		 $a        = 1;
		}
		if($_POST['field_applytime']==1){
		 $content .= ",发布时间";
		 $a        = 1;
		}
		if($_POST['field_checktime']==1){
		 $content .= ",审核时间";
		 $a        = 1;
		}
		if($_POST['field_submittime']==1){
		 $content .= ",提交时间";
		 $a        = 1;
		}
		if($_POST['field_qudao']==1){
		 $content .= ",来源渠道";
		 $a        = 1;
		}
		
		$content  .= "\n";
		if($a == 0){
		    header("content-Type: text/html; charset=utf-8");
	        echo "<script>alert('请选择要导出的资源字段');history.back();</script>";
	        exit;
		}
		global $database;
		$b=$database->select("resource", "*", array("id[!]"=>0));
		
		for($i=0;$i<count($b);$i++)  
		{ 
			if (isset($b[$i]))
			{
				$arr = $b[$i];
				
				if($_POST['field_id']=="1"){
					$content .= $arr["id"];
				}
				if($_POST['field_uid']=="1"){
					if($_POST['field_id']=="1")
						$content .= ",".$arr["uid"];
					else
						$content .= $arr["uid"];
				}
				if($_POST['field_name']=="1"){
					$content .= ",".$arr["name"];
				}
				if($_POST['field_phone']=="1"){
					$content .= ",".$arr["phone"];
				}
				if($_POST['field_wechat']=="1"){
					$content .= ",".$arr["wechat"];
				}
				if($_POST['field_qrcode']=="1"){
					$content .= ",".$arr["qrcode"];
				}
				if($_POST['field_qq']=="1"){
					$content .= ",".$arr["qq"];
				}
				if($_POST['field_idcard']=="1"){
					$content .= ",".$arr["idcard"];
				}
				if($_POST['field_addr']=="1"){
					$content .= ",".$arr["addr"];
				}
				if($_POST['field_desc']=="1"){
					$content .= ",".str_replace(",", ";", $arr["desc"]);
				}
				if($_POST['field_type']=="1"){
					$content .= ",".$arr["type"];
				}
				if($_POST['field_control']=="1"){
					$content .= ",".str_replace(",", ";", $arr["control"]);
				}
				if($_POST['field_status']=="1"){
					$content .= ",".$arr["status"];
				}
				if($_POST['field_addtime']=="1"){
					$content .= ",".$arr["addtime"];
				}
				if($_POST['field_applytime']=="1"){
					$content .= ",".$arr["applytime"];
				}
				if($_POST['field_checktime']=="1"){
					$content .= ",".$arr["checktime"];
				}
				if($_POST['field_submittime']=="1"){
					$content .= ",".$arr["submittime"];
				}
				if($_POST['field_qudao']=="1"){
					$content .= ",".$arr["qudao"];
				}
				$content .= "\n";
			}
		}//for结束

		if($file_encoding == "gbk"){
		 $content = iconv("utf-8", "gb2312"."//IGNORE", $content);
		}
		//$content = ob_gzip($content);////压缩文件
		$filename = "../upload/Resource_csv_".date("Ymd").".csv";///临时csv文件名称
		$fp = fopen($filename,'w+');//生成CSV文件
		if(fwrite($fp,$content)){		
		  header("content-Type: text/html; charset=utf-8");
		  echo "生成csv文件成功，<a href='".$filename."' target='_blank'>右击'目标另存为'文档</a>，下载后<a href='?act=delete_file&file=".$filename."'>删除此CSV文档</a>";
		}else{
		  header("content-Type: text/html; charset=utf-8");
		  echo "无法写入导出内容，upload文件夹应该为可读写权限。";
		}
		fclose($fp);
     }else{
	   header("content-Type: text/html; charset=utf-8");
	   echo "<script>alert('请选择要导出的资源信息');window.location.href='admin.php'</script>";
	   exit;
     }
}
elseif($act == "delete_file")//实现删除功能
{
  $filename = $_GET['file'];
  unlink($filename);
  //system('del $filename');
  //unlink(iconv("UTF-8","gb2312","'.$filename.'"));
  header("content-Type: text/html; charset=utf-8");
  echo "<script>alert('CSV文档删除成功');window.close()</script>";
  exit;
}
?>