<?php
error_reporting(0);
require("../data/session_admin.php");
require("../data/head.php");
require('../data/reader.php');

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
<link rel="stylesheet" type="text/css" href="static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!-- calendar -->
<link rel="stylesheet" type="text/css" href="datedropper.css">
<link rel="stylesheet" type="text/css" href="timedropper.min.css">
<!--//calendar -->
<title>我的桌面</title>
</head>
<body>
<div class="page-container">
	<p class="f-20 text-success">欢迎使用<?=$cf['site_name']?> <span class="f-14"></span></p>
	
	<?php if($cf['cursection'] == 1 || ($cf['cursection']%2 == 0 && $cf['cursection'] != 6)){ ?>
	<table class="table table-border table-bordered table-bg mt-20">
		<thead>
			<tr>
				<th colspan="3" scope="col">业绩信息</th>
			</tr>
		</thead>

		<tbody>
			<?php 
				global $database;

				$qd_groups = get_groups_and_crews(2);

				$resourcestatus = explode(',', $cf['resource_status']);

				$cursection = trim($_REQUEST['section']);
				$curgroup = trim($_REQUEST['group']);
				$curcrew = trim($_REQUEST['crew']);

				$start_date = trim($_REQUEST['start_pickdate']);
				$start_time = trim($_REQUEST['start_picktime']);
				$end_date = trim($_REQUEST['end_pickdate']);
				$end_time = trim($_REQUEST['end_picktime']);

				if($cursection == "")
				{
					if($cf['cursection'] == 1)
						$cursection = -1;
					else
						$cursection = $cf['cursection'];
				}

				if($curgroup == "")
				{
					if($cf['cursection'] == 1)
						$curgroup = -1;
					else
						$curgroup = $cf['curid'];
				}

				if($curcrew == "")
					$curcrew = -1;
				else
				{
					if($curgroup == -1)
						$curcrew = -1;
				} 

				if ($start_date == "")
				    $start_date = date("Y-m-d", mktime(0, 0, 0, date("m"), 1,date("Y"))); 

				if ($start_time == "")
				    $start_time = "00:00";

				if ($end_date == "")
				    $end_date = date("Y-m-d", mktime(0, 0, 0, date("m")+1, 0, date("Y")));

				if ($end_time == "")
				  	$end_time = "23:59";

				$g_groups = array("2"=>array(), "4"=>array());

				if($cursection == -1)
				{
					$cursection = 2;//Todo

					$g_groups["2"] = get_section_totalscore($cursection, array($start_date . ' ' . $start_time, $end_date . ' ' . $end_time));
				}
				else
				{
					$g_groups["2"] = get_section_totalscore($cursection, array($start_date . ' ' . $start_time, $end_date . ' ' . $end_time));
				}

			?>

			<form action="?" method="post" name="form1">

			<tr>
				<?php if($cf['cursection'] == 1){ ?>
				部门：<span class="select-box inline">
  				<select  name="section" id="section" class="select" >
  					<?php
  						/*$section_arr = array("2"=>"招商", "4"=>"后端");

  						if($cursection == -1)
  							echo '<option value="-1">全部</option>';
  						else
  						{
  							echo '<option value="'.$cursection.'">'.$section_arr[$cursection].'</option>';
  							echo '<option value="-1">全部</option>';
  						}
						
						foreach ($section_arr as $key => $value) {
  							if($cursection != $key)
  								echo '<option value="'.$key.'">'.$section_arr[$key].'</option>';
  						}*/
  					?>
  					<option value="2">招商</option>
  				</select></span>

					&nbsp;&nbsp;组别：<span class="select-box inline">
  				<select  name="group" id="group" class="select" >
  					<?php
  						$curtemp = $database->select("admin", "*", array("id"=>$curgroup));

  						if ($curgroup == -1)
				          echo '<option value="-1">全部</option>';
				        else
				        {
				          if (isset($curtemp[0]) && $curgroup == $curtemp[0]['id'])
				          {
				            $arr = $curtemp[0];
				            echo '<option value="'.$curgroup.'">'.$arr['group'].'-'.$arr['name'].'</option>';
				          }
				          echo '<option value="-1">全部</option>';
				        }

				        $groups_ = null;

				        if($cursection == 2)
				        	$groups_ = $qd_groups;

  						for ($i=0; $i < count($groups_['groups']); $i++) { 
  							$arr = $qd_groups['groups'][$i];
  							if($curgroup != $arr['id'])
  								echo '<option value="'.$arr['id'].'">'.$arr['group'].'-'.$arr['name'].'</option>';
  						}
  					?>
  				</select></span>
				&nbsp;&nbsp;
				<?php }else{ ?>

				<input type="hidden" name="section" id="section" value="<?=$cursection?>" />

				<?php } ?>

					组员：<span class="select-box inline">
  				<select  name="crew" id="crew" class="select" >
  					<?php
					  	$gcurtemp = $database->select("admin", "*", array("id"=>$curgroup));
  						$curtemp = $database->select("admin", "*", array("id"=>$curcrew));

  						if ($curcrew == -1)
				          echo '<option value="-1">全部</option>';
				        else
				        {
				          if (isset($curtemp[0]) && $curcrew == $curtemp[0]['id'] && isset($gcurtemp[0]) && ($gcurtemp[0]['group'] == $curtemp[0]['group'] || strpos($curtemp[0]['group'], $gcurtemp[0]['group'].'-') !== false))
				          {
				            $arr = $curtemp[0];
				            echo '<option value="'.$curcrew.'">'.$arr['group'].'-'.$arr['name'].'</option>';
						  }
						  else
							$curcrew = -1;
				          echo '<option value="-1">全部</option>';
				        }

				        $groups_ = null;

				        if($cursection == 2)
				        	$groups_ = $qd_groups;

  						for ($i=0; $i < count($groups_['groups']); $i++) { 
  							$arr = $groups_['groups'][$i];
  							if($curgroup == $arr['id'] || $curgroup == -1)
  							{
  								if($curcrew != $arr['id'])
  									echo '<option value="'.$arr['id'].'">'.$arr['group'].'-'.$arr['name'].'</option>';

	  							if(isset($qd_groups['crews'][$i]))
	  							{
	  								for ($j=0; $j < count($groups_['crews'][$i]); $j++) { 
			  							$arr1 = $groups_['crews'][$i][$j];
			  							if($curcrew != $arr1['id'])
			  								echo '<option value="'.$arr1['id'].'">&nbsp;'.$arr1['group'].'-'.$arr1['name'].'</option>';
			  						}
	  							}
  							}
  						}
  					?>
  				</select></span>
				<input type="text" style="width:90px" class="input-text" name="start_pickdate" id="start_pickdate" value="<?=$start_date?>" placeholder="开始日期" />
	           <input type="text" style="width:70px" class="input-text" name="start_picktime" id="start_picktime" placeholder="开始时间" />
	          <span>-</span>
	          <input type="text" style="width:90px" class="input-text" name="end_pickdate" id="end_pickdate" value="<?=$end_date?>" placeholder="结束日期" />
	          <input type="text" style="width:70px" class="input-text" name="end_picktime" id="end_picktime" placeholder="结束时间" />&nbsp;&nbsp;

	          <input name="submit" class="btn btn-success" type="submit" id="submit" value="查找">

	        </tr>
	      </form>
	      	<?php if($cf['cursection'] == 1 || $cf['cursection'] == 2){ ?>
            
            <?php
				global $database;
			
				$groups_ = $qd_groups;
				$groups_info = $g_groups["2"];
				$msg = "";
				$temp = "<tr><td>%type%: 已开发: %2% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 待开发: %0% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 开发中: %1% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 废弃: %3%</td></tr>";
				$ec = array("%type%", "%0%", "%1%", "%2%", "%3%");

				if($curgroup == -1 && $curcrew == -1)
				{
					$total_data = array("总数");
					for ($i=0; $i < count($groups_info); $i++) { 
						if(isset($groups_info[$i]['total']))
							$total_data[] = $groups_info[$i]['total'];
					}
					$msg .= str_replace($ec, $total_data, $temp);

					//组别
					for ($i=0; $i < count($groups_["groups"]); $i++) { 
						$admin_arr = $groups_["groups"][$i];
						$groups_data = array();
						$groups_data[] = $admin_arr['group']."组-".$admin_arr['name'];

						for ($j=0; $j < count($resourcestatus); $j++) { 
							if(isset($groups_info[$j]['groups']))
							{
								$groups = $groups_info[$j]['groups'];
								if(isset($groups[$admin_arr['group']]))
									$groups_data[] = $groups[$admin_arr['group']];
								else
									$groups_data[] = 0;
							}
						}

						$msg .= str_replace($ec, $groups_data, $temp);
					}
				}
				else
				{
					if($curcrew != -1)
					{
						//组员
						$admin_arr = $database->select("admin", "*", array("id"=>$curcrew));
						if($admin_arr[0])
						{
							$admin_arr = $admin_arr[0];
							$crews_data = array();
							$crews_data[] = $admin_arr['group']."组-".$admin_arr['name'];

							for ($j=0; $j < count($resourcestatus); $j++) { 
								if(isset($groups_info[$j]['crews']))
								{
									$crews = $groups_info[$j]['crews'];
									if(isset($crews[$admin_arr['id']]))
										$crews_data[] = $crews[$admin_arr['id']];
									else
										$crews_data[] = 0;
								}
							}

							$msg .= str_replace($ec, $crews_data, $temp);
						}
					}
					else if($curgroup != -1)
					{
						//组别
						$admin_arr = $database->select("admin", "*", array("id"=>$curgroup));
						if($admin_arr[0])
						{
							$admin_arr = $admin_arr[0];
							$groups_data = array();
							$groups_data[] = $admin_arr['group']."组-".$admin_arr['name'];

							for ($j=0; $j < count($resourcestatus); $j++) { 
								if(isset($groups_info[$j]['groups']))
								{
									$groups = $groups_info[$j]['groups'];
									if(isset($groups[$admin_arr['group']]))
										$groups_data[] = $groups[$admin_arr['group']];
									else
										$groups_data[] = 0;
								}
							}

							$msg .= str_replace($ec, $groups_data, $temp);
						}
					}
				}

				echo $msg;
			?>
			<?php }else if($cf['cursection'] == 1 || $cf['cursection'] == 4){ ?>

			<?php } ?>
		</tbody>
	</table>

	<?php } ?>
</div>
<footer class="footer mt-20">
	<div class="container">
		<?=$cf['copyrighta']?>
	</div>
</footer>
<script type="text/javascript" src="lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="static/h-ui/js/H-ui.js"></script> 

<!-- calendar -->
  <script src="js/datedropper.min.js"></script>
  <script src="js/timedropper.min.js"></script>
  <script>
  $("#start_pickdate").dateDropper({
    animate: false,
    format: 'Y-m-d',
    maxYear: '2050'
  });
  $("#start_picktime").timeDropper({
    meridians: false,
    format: 'HH:mm',
  });
  $("#end_pickdate").dateDropper({
    animate: false,
    format: 'Y-m-d',
    maxYear: '2050'
  });
  $("#end_picktime").timeDropper({
    meridians: false,
    format: 'HH:mm',
  });

  $(document).ready(function(){

    document.getElementsByName('start_picktime')[0].value="00:00";
    document.getElementsByName('end_picktime')[0].value="23:59";
  });
  </script>
  <!-- //calendar -->

</body>
</html>