<?php

include 'head.php';

$act = $_GET["act"];

//上传增加数据库

if($act == "save_uplod"){

 if($_FILES['file']['size']>0 && $_FILES['file']['name']!="")

 {

	    $file_size_max    = 3072000; //3000k

		$store_dir        = "../upload/";

		$ext_arr          = array('csv','xls','txt');

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
//$file_type="csv";
//$filepath="C:\Users\Administrator\Desktop\list.csv";
	$file_encoding = $_POST["file_encoding"];
	$index = 0;

	if($file_type == "xls"){

	  // 创建 Reader.

	  $data = new Spreadsheet_Excel_Reader();

	  // 设置文本输出编码.

	  $data->setOutputEncoding('utf-8');

	  //读取Excel文件.

	  $data->read($filepath);

	  //error_reporting(E_ALL ^ E_NOTICE);

	  //$data->sheets[0]['numCols']为Excel列数

	  global $database;
	  $k=0;

	  for ($i = 2; $i < $data->sheets[0]['numRows']; $i++) {

	  	if (!is_null($data->sheets[0]['cells'][$i])) {
	  		++$index;
		  	$uid = $data->sheets[0]['cells'][$i][1];
		  	$name = $data->sheets[0]['cells'][$i][2];
		  	$phone = strlen($data->sheets[0]['cells'][$i][3]) == 11 ? $data->sheets[0]['cells'][$i][3] : "";
		  	$wechat = $data->sheets[0]['cells'][$i][4];
		  	$qrcode = $data->sheets[0]['cells'][$i][5];
		  	$qq = $data->sheets[0]['cells'][$i][6];
		  	$idcard = $data->sheets[0]['cells'][$i][7];
		  	$addr = $data->sheets[0]['cells'][$i][8];
		  	$desc = str_replace(";", ",", $data->sheets[0]['cells'][$i][9]);
		  	$type = $data->sheets[0]['cells'][$i][10];
		  	$control = str_replace(";", ",", $data->sheets[0]['cells'][$i][11]);
		  	$status = $data->sheets[0]['cells'][$i][12];
		  	$addtime = $data->sheets[0]['cells'][$i][13] == "" ? date($cf['time_format']) : date($cf['time_format'], strtotime($data->sheets[0]['cells'][$i][13]));
		  	$applytime = $data->sheets[0]['cells'][$i][14] == "" ? "" : date($cf['time_format'], strtotime($data->sheets[0]['cells'][$i][14]));
		  	$checktime = $data->sheets[0]['cells'][$i][15] == "" ? "" : date($cf['time_format'], strtotime($data->sheets[0]['cells'][$i][15]));
		  	$submittime = $data->sheets[0]['cells'][$i][16] == "" ? "" : date($cf['time_format'], strtotime($data->sheets[0]['cells'][$i][16]));
		  	$qudao = $data->sheets[0]['cells'][$i][17];

			 //判断上传的是否有重复

		  	if ($phone != "" || $wechat != "" || $qq != "")
		  	{
		  		if (!$database->has("resource", array("OR"=>array("uid"=>_is_NULL_($uid),"phone"=>_is_NULL_($phone),"wechat"=>_is_NULL_($wechat),"qq"=>_is_NULL_($qq)))))
	  			{
	  				++$k;
	  				$curcontrol = json_decode($control, true);
	  				if(is_array($curcontrol))
	  					$curcontrol = $curcontrol[count($curcontrol)-1];
	  				else
	  					$curcontrol = 0;
					echo var_dump(array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
		  	
	  				$database->insert("resource", array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
	  			}
	  			else
	  				echo var_dump(array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
		  	}
	  	}
	}	  

    ////导入csv文件///////////////////////////

	}elseif($file_type == "csv"){	   

	  setlocale(LC_ALL, 'zh_CN.UTF-8');

	   $file  = fopen($filepath,"r");  

	   global $database;

	   $k     = 0;

	   while(!feof($file) && $data = __fgetcsv($file))

	   {
		 $result = array();  

		   if($k > 0 && !empty($data))

		   {  

	   		++$index;
			  for($i=0;$i<22;$i++)

			  {

				  array_push($result,$data[$i]);

			  }			  

		      if($file_encoding == "gbk"){			   

		       $uid = iconv("gbk", "utf-8"."//IGNORE", $result[0]);

			   $name = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $phone = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $wechat = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $qrcode = iconv("gbk", "utf-8"."//IGNORE", $result[4]);

			   $qq = iconv("gbk", "utf-8"."//IGNORE", $result[5]);

			   $idcard = iconv("gbk", "utf-8"."//IGNORE", $result[6]);

			   $addr = iconv("gbk", "utf-8"."//IGNORE", $result[7]);

			   $desc = iconv("gbk", "utf-8"."//IGNORE", $result[8]);

			   $type = iconv("gbk", "utf-8"."//IGNORE", $result[9]);

			   $control = iconv("gbk", "utf-8"."//IGNORE", $result[10]);

			   $status = iconv("gbk", "utf-8"."//IGNORE", $result[11]);

			   $addtime = iconv("gbk", "utf-8"."//IGNORE", $result[12]);
		  	
			   $applytime = iconv("gbk", "utf-8"."//IGNORE", $result[13]);

			   $checktime = iconv("gbk", "utf-8"."//IGNORE", $result[14]);

			   $submittime = iconv("gbk", "utf-8"."//IGNORE", $result[15]);

			   $qudao = iconv("gbk", "utf-8"."//IGNORE", $result[16]);

			   /*$result_0 = iconv("gbk", "utf-8"."//IGNORE", $result[0]);

			   $result_1 = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $result_2 = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $result_3 = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $result_4 = iconv("gbk", "utf-8"."//IGNORE", $result[4]);

			   $result_5 = iconv("gbk", "utf-8"."//IGNORE", $result[5]);

			   $result_6 = iconv("gbk", "utf-8"."//IGNORE", $result[6]);

			   $result_7 = iconv("gbk", "utf-8"."//IGNORE", $result[7]);

			   $result_8 = iconv("gbk", "utf-8"."//IGNORE", $result[8]);

			   $result_9 = iconv("gbk", "utf-8"."//IGNORE", $result[9]);

			   $result_10 = iconv("gbk", "utf-8"."//IGNORE", $result[10]);

			   $result_11 = iconv("gbk", "utf-8"."//IGNORE", $result[11]);

			   $result_12 = iconv("gbk", "utf-8"."//IGNORE", $result[12]);

			   $result_13 = iconv("gbk", "utf-8"."//IGNORE", $result[13]);

			   $result_14 = iconv("gbk", "utf-8"."//IGNORE", $result[14]);

			   $result_15 = iconv("gbk", "utf-8"."//IGNORE", $result[15]);

			   $result_16 = iconv("gbk", "utf-8"."//IGNORE", $result[16]);*/

			   $result_17 = iconv("gbk", "utf-8"."//IGNORE", $result[17]);

			   $result_18 = iconv("gbk", "utf-8"."//IGNORE", $result[18]);

			   $result_19 = iconv("gbk", "utf-8"."//IGNORE", $result[19]);

			   $result_20 = iconv("gbk", "utf-8"."//IGNORE", $result[20]);

			   $result_21 = iconv("gbk", "utf-8"."//IGNORE", $result[21]);			  

			  }else{			  

			   $uid = $result[0];

			   $name = $result[1];

			   $phone = $result[2];

			   $wechat = $result[3];

			   $qrcode = $result[4];

			   $qq = $result[5];

			   $idcard = $result[6];

			   $addr = $result[7];

			   $desc = $result[8];

			   $type = $result[9];

			   $control = $result[10];

			   $status = $result[11];

			   $addtime = $result[12];

			   $applytime = $result[13];

			   $checktime = $result[14];

			   $submittime = $result[15];

			   $qudao = $result[16];

			   /*$result_0 = $result[0];

			   $result_1 = $result[1];

			   $result_2 = $result[2];

			   $result_3 = $result[3];

			   $result_4 = $result[4];

			   $result_5 = $result[5];

			   $result_6 = $result[6];

			   $result_7 = $result[7];

			   $result_8 = $result[8];

			   $result_9 = $result[9];

			   $result_10 = $result[10];

			   $result_11 = $result[11];

			   $result_12 = $result[12];

			   $result_13 = $result[13];

			   $result_14 = $result[14];

			   $result_15 = $result[15];

			   $result_16 = $result[16];*/

			   $result_17 = $result[17];

			   $result_18 = $result[18];

			   $result_19 = $result[19];

			   $result_20 = $result[20];

			   $result_21 = $result[21];

			  }  		

			  $phone = strlen($phone) == 11 ? $phone : "";

			  $desc = str_replace(";", ",", $desc);

			  $addtime = $addtime == "" ? date($cf['time_format']) : date($cf['time_format'], strtotime($addtime));

			  $applytime = $applytime == "" ? "" : date($cf['time_format'], strtotime($applytime));

			  $checktime = $checktime == "" ? "" : date($cf['time_format'], strtotime($checktime));

			  $submittime = $submittime == "" ? "" : date($cf['time_format'], strtotime($submittime));

			  //判断上传的是否有重复

			if ($phone != "" || $wechat != "" || $qq != "")
		  	{
		  		if (!$database->has("resource", array("OR"=>array("uid"=>_is_NULL_($uid),"phone"=>_is_NULL_($phone),"wechat"=>_is_NULL_($wechat),"qq"=>_is_NULL_($qq)))))
	  			{
	  				++$k;
	  				$curcontrol = json_decode($control, true);
	  				if(is_array($curcontrol))
	  					$curcontrol = $curcontrol[count($curcontrol)-1];
	  				else
	  					$curcontrol = 0;

	  				$database->insert("resource", array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
	  			}
	  			else
	  				echo var_dump(array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
		  	}

		  }  
		  if ($k == 0)
		  	++$k;
		 }

		 $k=$k-1;
		 fclose($file);

		 

    ///导入txt文件//////////////////////////////

	}elseif($file_type == "txt"){	    

		$row = file($filepath); //读出文件中内容到一个数组当中

		$k   = 1;//统计表中的记录数

		for ($i=1;$i<count($row);$i++)//开始导入记录 

		{ 
			++$index;
			$result = explode(",",$row[$i]);//读取数据到数组中，以英文逗号为分格符

			if($file_encoding == "gbk"){			   

		       $uid = iconv("gbk", "utf-8"."//IGNORE", $result[0]);

			   $name = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $phone = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $wechat = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $qrcode = iconv("gbk", "utf-8"."//IGNORE", $result[4]);

			   $qq = iconv("gbk", "utf-8"."//IGNORE", $result[5]);

			   $idcard = iconv("gbk", "utf-8"."//IGNORE", $result[6]);

			   $addr = iconv("gbk", "utf-8"."//IGNORE", $result[7]);

			   $desc = iconv("gbk", "utf-8"."//IGNORE", $result[8]);

			   $type = iconv("gbk", "utf-8"."//IGNORE", $result[9]);

			   $control = iconv("gbk", "utf-8"."//IGNORE", $result[10]);

			   $status = iconv("gbk", "utf-8"."//IGNORE", $result[11]);

			   $addtime = iconv("gbk", "utf-8"."//IGNORE", $result[12]);
		  	
			   $applytime = iconv("gbk", "utf-8"."//IGNORE", $result[13]);

			   $checktime = iconv("gbk", "utf-8"."//IGNORE", $result[14]);

			   $submittime = iconv("gbk", "utf-8"."//IGNORE", $result[15]);

			   $qudao = iconv("gbk", "utf-8"."//IGNORE", $result[16]);

			   /*$result_0 = iconv("gbk", "utf-8"."//IGNORE", $result[0]);

			   $result_1 = iconv("gbk", "utf-8"."//IGNORE", $result[1]);

			   $result_2 = iconv("gbk", "utf-8"."//IGNORE", $result[2]);

			   $result_3 = iconv("gbk", "utf-8"."//IGNORE", $result[3]);

			   $result_4 = iconv("gbk", "utf-8"."//IGNORE", $result[4]);

			   $result_5 = iconv("gbk", "utf-8"."//IGNORE", $result[5]);

			   $result_6 = iconv("gbk", "utf-8"."//IGNORE", $result[6]);

			   $result_7 = iconv("gbk", "utf-8"."//IGNORE", $result[7]);

			   $result_8 = iconv("gbk", "utf-8"."//IGNORE", $result[8]);

			   $result_9 = iconv("gbk", "utf-8"."//IGNORE", $result[9]);

			   $result_10 = iconv("gbk", "utf-8"."//IGNORE", $result[10]);

			   $result_11 = iconv("gbk", "utf-8"."//IGNORE", $result[11]);

			   $result_12 = iconv("gbk", "utf-8"."//IGNORE", $result[12]);

			   $result_13 = iconv("gbk", "utf-8"."//IGNORE", $result[13]);

			   $result_14 = iconv("gbk", "utf-8"."//IGNORE", $result[14]);

			   $result_15 = iconv("gbk", "utf-8"."//IGNORE", $result[15]);

			   $result_16 = iconv("gbk", "utf-8"."//IGNORE", $result[16]);*/

			   $result_17 = iconv("gbk", "utf-8"."//IGNORE", $result[17]);

			   $result_18 = iconv("gbk", "utf-8"."//IGNORE", $result[18]);

			   $result_19 = iconv("gbk", "utf-8"."//IGNORE", $result[19]);

			   $result_20 = iconv("gbk", "utf-8"."//IGNORE", $result[20]);

			   $result_21 = iconv("gbk", "utf-8"."//IGNORE", $result[21]);			  

			  }else{			  

			   $uid = $result[0];

			   $name = $result[1];

			   $phone = $result[2];

			   $wechat = $result[3];

			   $qrcode = $result[4];

			   $qq = $result[5];

			   $idcard = $result[6];

			   $addr = $result[7];

			   $desc = $result[8];

			   $type = $result[9];

			   $control = $result[10];

			   $status = $result[11];

			   $addtime = $result[12];

			   $applytime = $result[13];

			   $checktime = $result[14];

			   $submittime = $result[15];

			   $qudao = $result[16];

			   /*$result_0 = $result[0];

			   $result_1 = $result[1];

			   $result_2 = $result[2];

			   $result_3 = $result[3];

			   $result_4 = $result[4];

			   $result_5 = $result[5];

			   $result_6 = $result[6];

			   $result_7 = $result[7];

			   $result_8 = $result[8];

			   $result_9 = $result[9];

			   $result_10 = $result[10];

			   $result_11 = $result[11];

			   $result_12 = $result[12];

			   $result_13 = $result[13];

			   $result_14 = $result[14];

			   $result_15 = $result[15];

			   $result_16 = $result[16];*/

			   $result_17 = $result[17];

			   $result_18 = $result[18];

			   $result_19 = $result[19];

			   $result_20 = $result[20];

			   $result_21 = $result[21];

			  }  		

		    $phone = strlen($phone) == 11 ? $phone : "";

			$desc = str_replace(";", ",", $desc);

			$addtime = $addtime == "" ? date($cf['time_format']) : date($cf['time_format'], strtotime($addtime));

			$applytime = $applytime == "" ? "" : date($cf['time_format'], strtotime($applytime));

			$checktime = $checktime == "" ? "" : date($cf['time_format'], strtotime($checktime));

			$submittime = $submittime == "" ? "" : date($cf['time_format'], strtotime($submittime));

			  //判断上传的是否有重复

			if ($phone != "" || $wechat != "" || $qq != "")
		  	{
		  		if (!$database->has("resource", array("OR"=>array("uid"=>_is_NULL_($uid),"phone"=>_is_NULL_($phone),"wechat"=>_is_NULL_($wechat),"qq"=>_is_NULL_($qq)))))
	  			{
	  				++$k;
	  				$curcontrol = json_decode($control, true);
	  				if(is_array($curcontrol))
	  					$curcontrol = $curcontrol[count($curcontrol)-1];
	  				else
	  					$curcontrol = 0;

	  				$database->insert("resource", array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
	  			}
	  			else
	  				echo var_dump(array("uid"=>$uid, "name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qrcode"=>$qrcode, "qq"=>$qq, "idcard"=>$idcard, "addr"=>$addr, "desc"=>$desc, "type"=>$type, "curcontrol"=>$curcontrol, "control"=>$control, "status"=>$status, "addtime"=>$addtime, "applytime"=>$applytime, "checktime"=>$checktime, "submittime"=>$submittime, "qudao"=>$qudao));
		  	}

		}

		$k=$k-1;

		fclose($row);

	}

	$msg= "总记录".$index."/上传成功".$k."条记录";

	@unlink($filepath);

	echo "<script>alert('".$msg."');location.href='resource.php?'</script>";

	exit;

}

//发布资源

if($act == "apply_resource"){

	global $database;
	$chk = $_REQUEST["chk"];
	$chk = explode(",",$chk);///转化为数组
	$type = $_REQUEST["resourcetype"];
	$msg = "";
	$a = 0;

	if (is_array($chk) && $chk[0] == "")
	{
		$a = 2;
		$msg = "请先选择要分配的资源";
	}

	if ($cf['cursection'] == 1) {
		if (isset($_POST['group']) && $_POST['group'] != "") {
			if ($a == 0)
			{
				for($i=0;$i<count($chk);$i++) 
				{
					$id = $chk[$i];
					$b=$database->select("resource", "*", array("id"=>$id, "control[!~]"=>$_POST['group']));
					if ($b[0]) {
						$a = 1;
						$control = json_decode($b[0]['control'], true);
						if (is_null($control) && !isset($control['group']))
						{
							$control = array();
							$control['group'] = $_POST['group'];
						}
						else
							$control['group'] .= ','.$_POST['group'];
						
						$database->update("resource", array("curcontrol"=>$_POST['group'], "control"=>json_encode($control), "type"=>$type+1, "status"=>0, "applytime"=>date($cf["time_format"])), array("id"=>$id));
					}
					else
					{
						if ($msg == "")
							$msg = "该组别已经分配过了";
					}
				}
			}
		}
		else
		{
			if ($msg == "")
				$msg = "请先选择要分配的组别";
		}
	}
	
	if (isset($_POST['crew']) && $_POST['crew'] != "") {
		if ($a == 0)
		{
			for($i=0;$i<count($chk);$i++) 
			{
				$id = $chk[$i];
				$b=$database->select("resource", "*", array("id"=>$id, "control[!~]"=>$_POST['crew']));
				if ($b[0]) {
					$a = 1;
					$control = json_decode($b[0]['control'], true);
					if (is_null($control) && !isset($control['crew']))
					{
						$control = array();
						$control['crew'] = $_POST['crew'];
					}
					else
						$control['crew'] .= ','.$_POST['crew'];
					
					$database->update("resource", array("curcontrol"=>$_POST['crew'], "control"=>json_encode($control), "type"=>$type, "status"=>0, "applytime"=>date($cf["time_format"])), array("id"=>$id));
				}
				else
				{
					if ($msg == "")
						$msg = "该组员已经分配过了";
				}
			}
		}
	}
	else
	{
		if ($msg == "")
			$msg = "请先选择要分配的组员";
	}

	if ($msg == "" && $a == 0)
		$msg = "请先选择要分配的对象";

	if ($a == 1)
		$msg = "分配成功";

	echo "<script>alert('".$msg."');location.href='resource.php?act=apply'</script>";

	exit;
}


?>


<?php

function _is_NULL_($str) 
{
    if ($str == "")
        return "NULL";

    return $str;
}

//csv读取函数

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