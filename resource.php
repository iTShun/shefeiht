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

    document.myform.action="action.php?act=delagent";

    if(confirm("确定要删除选中的记录吗？本操作不可恢复！"))

        return true;

    else

      return false;

  }else if(document.myform.Action.value=="export_resource"){

    document.myform.action="?act=export_resource";



  }else if(document.myform.Action.value=="exportall_resource"){

    document.myform.action="?act=exportall_resource";



  }else if(document.myform.Action.value=="apply_resource"){

    document.myform.action="?act=apply_resource";

  }
}

</SCRIPT>

<?php

include 'head.php';

$act = $_GET["act"];

////资源设置
if($act == "")

{

?>

<?php

  global $database;

  $id = trim($_REQUEST['id']);

  $phone = trim($_REQUEST['phone']);

  $wechat = trim($_REQUEST['wechat']);

  $name = trim($_REQUEST['rname']);

  $h       = trim($_REQUEST["h"]);

  $resourcetype = explode(',', $cf['resource_type']);

  $resourcestatus = explode(',', $cf['resource_status']);

  $resourcequdao = explode(',', $cf['resource_qudao']);

  $curresourcetype = trim($_REQUEST['resourcetype']);

  if($curresourcetype == "" && is_array($resourcetype)){

    $curresourcetype = $cf['cursection'] - 1;

    if ($cf['cursection'] != 1 && $cf['cursection']%2 != 0)
      $curresourcetype = $cf['cursection'] - 2;

   }

   $curresourcestatus = trim($_REQUEST['resourcestatus']);

  if($curresourcestatus == "" && is_array($resourcestatus)){

    $curresourcestatus = $cf['cursection'] - 2;

    if ($cf['cursection'] != 1 && $cf['cursection']%2 != 0)
      $curresourcestatus = $cf['cursection'] - 3;

   }

   $curresourcequdao = trim($_REQUEST['resourcequdao']);

  if($curresourcequdao == "" && is_array($resourcequdao)){

       $curresourcequdao = -1;

   }

   $curcrew = trim($_REQUEST['crew']);

   if ($curcrew == "")
   {

      $curcrew = -1;

   }

   $timetype = trim($_REQUEST['timetype']);
   if($timetype == "")
   {
    $timetype = $curresourcetype;
   }

   $start_date = trim($_REQUEST['start_pickdate']);
  $start_time = trim($_REQUEST['start_picktime']);
  $end_date = trim($_REQUEST['end_pickdate']);
  $end_time = trim($_REQUEST['end_picktime']);

  $limit=$database->select("section", "limit", array("id"=>$cf['cursection']));
  $limit_date = date('Y-m-d', strtotime('-7 days'));
  if ($limit[0])
    $limit = json_decode($limit[0], true);

  if (isset($limit['days']) || isset($limit['applydays']))
  {
    if ($curresourcestatus == 0 && isset($limit['days']))
      $day = $limit['days'];
    else if (isset($limit['applydays']))
      $day = $limit['applydays'];

    $limit_date = date('Y-m-d', strtotime($day.' days'));

    if ($start_date == "")
      $start_date = $limit_date;
    else
    {
      $limit_days = date("d", strtotime($limit_date));
      $days = date("d", strtotime($start_date));
      if (($days-$limit_days) > abs($limit['days']))
        $start_date = $limit_date;
    }
  }

  if ($start_time == "")
    $start_time = "00:00";

  if ($end_date == "")
      $end_date = date('Y-m-d');

  if ($end_time == "")
    $end_time = "23:59";

   $data_arr = array("ORDER"=>array("id"=>"DESC"));
   
   if ($id != "")
    $data_arr['id'] = $id;

  if ($phone != "")
    $data_arr['phone'] = $phone;

  if ($wechat != "")
    $data_arr['wechat'] = $wechat;

  if ($name != "")
    $data_arr['name'] = $name;

  if ($curresourcestatus != -1)
    $data_arr['status'] = $curresourcestatus;

  if ($curresourcequdao != -1)
    $data_arr['qudao'] = $resourcequdao[$curresourcequdao];

  if ($h == "1")
    $data_arr['ORDER'] = array("id"=>"ASC");

  if ($curresourcetype != 0)
  {
    $data_arr['type'] = $curresourcetype;
    if($cf['cursection'] != 1)
      $data_arr['curcontrol'] = $cf['curid'];

    if ($h == "1")
      $data_arr['ORDER'] = array("applytime"=>"ASC");
    else
      $data_arr['ORDER'] = array("applytime"=>"DESC");
  }

  if($curcrew != -1)
    $data_arr['curcontrol'] = $curcrew;

  if ($start_date != "" && $start_time != "" && $end_date != "" && $end_time != "")
  {
    $arr = array("addtime","applytime","checktime","submittime");
    $data_arr[$arr[$timetype]."[<>]"] = array($start_date . ' ' . $start_time, $end_date . ' ' . $end_time);
  }
  
  $showsize      = trim($_REQUEST['showsize']);

  if($showsize == ""){

       $pagesize = $cf['list_num'];//每页所要显示的数据个数。

   $showsize       = $cf['list_num'];

   }

   else{

     $pagesize = $showsize;

   }
   
   $total    = $database->count("resource", $data_arr);

   $filename = "?id=".$id."&showsize=".$showsize."&h=".$h."&resourcetype=".$curresourcetype."&resourcequdao=".$curresourcequdao."&resourcestatus=".$curresourcestatus."&curcrew=".$curcrew."&timetype=".$timetype."&start_pickdate=".$start_date."&start_picktime=".$start_time."&end_pickdate=".$end_date."&end_picktime=".$end_time."";

  $currpage  = intval($_REQUEST["page"]);

  if(!is_int($currpage))

  $currpage=1;

  if(intval($currpage)<1)$currpage=1;

  if(intval($currpage-1)*$pagesize>$total)$currpage=1;

  if(($total%$pagesize)==0){

    $totalpage=intval($total/$pagesize);

     }

    else

      $totalpage=intval($total/$pagesize)+1;

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="resource.php?">资源管理</a> <span class="c-gray en">&gt;</span> 资源列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
	<div class="text-c">

    <table cellpadding="3" cellspacing="0" class="table_98">
      <form action="?" method="post" name="form1">
        <tr>
          
        <td>
          <?php if($cf['cursection'] == 1) { ?>

          ID：<input type="text" style="width:100px" class="input-text" name="id" size="20" value="<?=$id?>" />&nbsp;

          <?php } ?>

          手机号：<input type="text" style="width:100px" class="input-text" name="phone" size="20"  value="<?=$phone?>">&nbsp;微信号：<input style="width:100px" class="input-text" type="text" name="wechat" size="30" value="<?=$wechat?>">&nbsp;姓名：<input style="width:100px" class="input-text" type="text" name="rname" size="20" value="<?=$name?>" />

          <?php if($cf['cursection'] == 1) { ?>

              &nbsp;类型：<span class="select-box inline">
  <select  name="resourcetype" id="resourcetype" class="select" >
                     <?php
                     echo '<option value="'.$curresourcetype.'">'.$resourcetype[$curresourcetype].'</option>';

  foreach ($resourcetype as $key => $value) {
    if ($key != $curresourcetype)
      echo '<option value="'.$key.'">'.$value.'</option>';
  }
  ?>

              </select></span>

              &nbsp;渠道：<span class="select-box inline">
          <select  name="resourcequdao" id="resourcequdao" class="select" >

          <?php
            if ($curresourcequdao == -1)
              echo '<option value="-1">全部</option>';
            else
            {
              echo '<option value="'.$curresourcequdao.'">'.$resourcequdao[$curresourcequdao].'</option>';
              echo '<option value="-1">全部</option>';
            }
          foreach ($resourcequdao as $key => $value) {
            if ($key != $curresourcequdao)
              echo '<option value="'.$key.'">'.$value.'</option>';
          }
          ?>
           </select></span>

           <?php } if ($curresourcetype != 2) { ?>

                        &nbsp;状态：<span class="select-box inline">
  <select  name="resourcestatus" id="resourcestatus" class="select" >

  <?php
    if ($curresourcestatus == -1)
      echo '<option value="-1">全部</option>';
    else
    {
      echo '<option value="'.$curresourcestatus.'">'.$resourcestatus[$curresourcestatus].'</option>';
      if ($cf['cursection'] == 1)
        echo '<option value="-1">全部</option>';
    }

    $begin = 0;
    $end = count($resourcestatus);

    if ($curresourcetype == 1)
      $end = 2;

    for ($i=$begin; $i < $end; $i++) { 
      $key = $i;
      $value = $resourcestatus[$i];

      if ($key != $curresourcestatus)
        echo '<option value="'.$key.'">'.$value.'</option>';
    }
  ?>
   </select></span>

   <?php } if($cf['cursection'] == 1) { ?>

   <?php if ($curresourcetype == 1) { ?>

   &nbsp;组员：<span class="select-box inline">
  <select  name="crew" id="crew" class="select" >

      <?php
         $groups = $database->select("admin", "*", array("section"=>2, "ORDER"=>array("id"=>"ASC")));
         $curtemp = $database->select("admin", "*", array("id"=>$curcrew));

        if ($curcrew == -1)
          echo '<option value="-1">全部</option>';
        else
        {
          if (isset($curtemp[0]) && $curcrew == $curtemp[0]['id'])
          {
            $arr = $curtemp[0];
            echo '<option value="'.$curcrew.'">'.$arr['group'].'-'.$arr['name'].'</option>';
          }
          echo '<option value="-1">全部</option>';
        }

        for ($i=0; $i < count($groups); $i++) { 
          if (isset($groups[$i]))
          {
            $arr = $groups[$i];
            if ($curcrew != $groups[$i]['id'])
              echo '<option value="'.$arr['id'].'">'.$arr['group'].'-'.$arr['name'].'</option>';

            $crews = $database->select("admin", "*", array("section"=>$arr['section']+1, "group[~]"=>$arr['group']."-", "ORDER"=>array("id"=>"ASC")));

            if (is_array($crews))
            {
              for ($j=0; $j < count($crews); $j++) { 
                if (isset($crews[$j]) && $curcrew != $crews[$j]['id'])
                {
                  $arr1 = $crews[$j];
                  echo '<option value="'.$arr1['id'].'">&nbsp;'.$arr1['group'].'-'.$arr1['name'].'</option>';
                }
              }
            }
          }
        }

      ?>
       </select></span>
    <?php } ?>

   &nbsp;时间类型：<span class="select-box inline">
  <select  name="timetype" id="timetype" class="select" >
      <?php
        $arr = array("添加时间","发布时间","提审时间","提交时间");
        echo '<option value="'.$timetype.'">'.$arr[$timetype].'</option>';

  foreach ($arr as $key => $value) {
    if ($key != $timetype)
      echo '<option value="'.$key.'">'.$value.'</option>';
  }
  ?>
     </select></span>
  <?php } ?>

           &nbsp;<input type="text" style="width:90px" class="input-text" name="start_pickdate" id="start_pickdate" value="<?=$limit_date?>" placeholder="开始日期" />
           <input type="text" style="width:70px" class="input-text" name="start_picktime" id="start_picktime" placeholder="开始时间" />
          <span>-</span>
          <input type="text" style="width:90px" class="input-text" name="end_pickdate" id="end_pickdate" value="<?=date('Y-m-d')?>" placeholder="结束日期" />
          <input type="text" style="width:70px" class="input-text" name="end_picktime" id="end_picktime" placeholder="结束时间" />

          <input type="hidden" name="showsize" id="showsize" value="<?=$showsize?>" />

          <input name="submit" class="btn btn-success" type="submit" id="submit" value="查找"> </td>

        </tr>
      </form>
    </table>

    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

      <?php 
        //添加权限判断
        if ($curresourcetype < 2)
          echo get_privilege_code("添加资源");
      ?>
  <tr>

    <td valign="top">

      <form method="post" name="myform" id="myform" action="?" onsubmit="return ConfirmDel();">

      <input type="hidden" name="id" value="<?=$id?>" />

      <input type="hidden" name="h" value="<?=$h?>" />

      <input type="hidden" name="resourcetype" value="<?=$curresourcetype?>" />

      <input type="hidden" name="resourcequdao" value="<?=$curresourcequdao?>" />

      <input type="hidden" name="resourcestatus" value="<?=$curresourcestatus?>" />

      <input type="hidden" name="curcrew" value="<?=$curcrew?>" />

      <input type="hidden" name="start_pickdate" value="<?=$start_date?>" />

      <input type="hidden" name="start_picktime" value="<?=$start_time?>" />

      <input type="hidden" name="end_pickdate" value="<?=$end_date?>" />

      <input type="hidden" name="end_picktime" value="<?=$end_time?>" />

      <table cellpadding="3" cellspacing="0">

        <tr>

          <td height="20">
            <?php 
              //添加权限判断
              echo get_privilege_code("导出资源");
            ?>

            </td>

      <td align="right" style="text-align:right !important">

        显示条数 <input style="width:50px" class="input-text" type="text" name="showsize" id="showsize" value="<?=$pagesize?>" size="8" onchange="javascript:submit()" /> &nbsp;&nbsp;&nbsp;&nbsp;

          当前第<?=$currpage?>页, 共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

              <?php if($currpage==1){?>

              首页&nbsp;上一页&nbsp;

              <?php } else {?>

              <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;

              <?php }

        if($currpage==$totalpage)

        {?>

        下一页&nbsp;尾页&nbsp;

              <?php }else{?>

              <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;

              <?php }?>
<span class="select-box inline">
        <select name='page' size='1' id="page" class="select" onchange='javascript:submit()'>

        <?php

        for($i=1;$i<=$totalpage;$i++)

        {

        ?>

         <option value="<?php echo $i; ?>" <?php if ($currpage==$i) echo "selected"; ?>> 第<?php echo $i;?>页</option>

         <?php }?>

         </select></span>

        </td>

        </tr>

    </table>

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

		<tr>

          <?php 
              //添加权限判断
            if (get_privilege_code("导出资源") != "") {
          ?>

          <td width="5%"><strong>
            <INPUT TYPE="checkbox" NAME="chkAll" id="chkAll" title="全选"  onclick="CheckAll(this.form)">
            &nbsp;全选</strong></td>

            <?php } ?>

          <td width="4%"><strong>序号</strong></td>
          <?php
            echo resourcetype_convert_table_html($curresourcetype);
          ?>

		</tr>

		<?php

			global $database;

      $resources = $database->select("resource", "*", $data_arr);
      $index = ($currpage - 1) * $showsize;

			for ($i=0; $i < $showsize; $i++, $index++) { 
          if (isset($resources[$index])) {
	        	$arr = $resources[$index];	
            $descs = json_decode($arr['desc'], true);
            if (isset($descs[$curresourcetype]))
              $arr['desc'] = $descs[$curresourcetype];
            else
              $arr['desc'] = '';
		?>

        <tr >
          <?php 
              //添加权限判断
            if (get_privilege_code("导出资源") != "") {
          ?>
          <td><input name="chk[]" type="checkbox" id="chk[]" value="<?php echo $arr["id"];?>"></td>
          <?php } ?>
          <td><?php echo $index+1;?></td>

          <?php if ($curresourcetype == 0) { ?>
           <td><?php echo $arr["id"];?></td>
           <td><?php echo $arr["uid"];?></td>
           <td><?php echo $arr["name"];?></td>
           <td><?php echo $arr["phone"];?></td>
           <td><?php echo $arr["wechat"];?></td>
           <td><?php
            if (strlen($arr["qrcode"]))
            {
              $qrcode=$arr["qrcode"];
            echo "<a href=\"$qrcode\" target=\"_blank\">查看二维码</a>";
              }
            else {
              echo 未上传二维码;
              }

            ?></td>
            <td><?php echo $arr["qq"];?></td>
            <td><?php echo $arr["idcard"];?></td>
            <td><?php echo $arr["addr"];?></td>
            <td><?php echo $arr["desc"];?></td>
            <td><?php echo $resourcestatus[$arr["status"]];?></td>
            <td><?php echo $arr["addtime"];?></td>
            <td><?php echo $arr["applytime"];?></td>
            <td><?php echo $arr["checktime"];?></td>
            <td><?php echo $arr["submittime"];?></td>
            <td><?php echo $arr["qudao"];?></td>
            <td>
             <a title="编辑" href="?act=edit_superresource&id=<?php echo $arr["id"];?>&resourcetype=<?php echo $curresourcetype;?>&resourcestatus=<?php echo $curresourcestatus;?>&resourcequdao=<?php echo $curresourcequdao;?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; 
             <a title="回收" href="?act=recycle_superresource&id=<?=$arr['id']?>" onclick="return confirm('确认要回收吗?')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>&nbsp;&nbsp; 
             <a title="删除" href="?act=delete_superresource&id=<?=$arr['id']?>" onclick="return confirm('确认要删除吗?')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
            </td>
          <?php } else if ($curresourcetype == 1) { ?>
           <td><?php echo $arr["name"];?></td>
           <td><?php echo $arr["phone"];?></td>
           <td><?php echo $arr["wechat"];?></td>
           <td><?php
            if (strlen($arr["qrcode"]))
            {
              $qrcode=$arr["qrcode"];
            echo "<a href=\"$qrcode\" target=\"_blank\">查看二维码</a>";
              }
            else {
              echo 未上传二维码;
              }

            ?></td>
            <td><?php echo $arr["qq"];?></td>
            <td><?php echo $arr["addr"];?></td>
            <td><?php echo $arr["desc"];?></td>
            <td><?php echo $resourcestatus[$arr["status"]];?></td>
            <?php 

              $control = $database->select("admin", "*", array("id"=>$arr["curcontrol"]));
              if($control[0])
              {
                $arr['curcontrol'] = $control[0]["group"].'-'.$control[0]["name"];
              }

            ?>
            <td><?php echo $arr["curcontrol"];?></td>
            <td><?php echo $arr["applytime"];?></td>
            <td>
             <a title="编辑" href="?act=edit_superresource&id=<?php echo $arr["id"];?>&resourcetype=<?php echo $curresourcetype;?>&resourcestatus=<?php echo $curresourcestatus;?>&resourcequdao=<?php echo $curresourcequdao;?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>&nbsp;&nbsp; 
             <a title="回收" href="?act=recycle_superresource&id=<?=$arr['id']?>" onclick="return confirm('确认要回收吗?')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>&nbsp;&nbsp; 
            </td>
          <?php } else if ($curresourcetype == 2) { ?>
           <td><?php echo $arr["name"];?></td>
           <td><?php echo $arr["phone"];?></td>
           <td><?php echo $arr["wechat"];?></td>
           <td><?php
            if (strlen($arr["qrcode"]))
            {
              $qrcode=$arr["qrcode"];
            echo "<a href=\"$qrcode\" target=\"_blank\">查看二维码</a>";
              }
            else {
              echo 未上传二维码;
              }

            ?></td>
            <td><?php echo $arr["qq"];?></td>
            <td><?php echo $arr["addr"];?></td>
            <td><?php echo checktable_convert_status($arr["status"]);?></td>
            <td><?php echo $arr["checktime"];?></td>
            <td>
             <a title="一键授权" href="?act=submit_superresource&id=<?=$arr['id']?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">一键授权</i></a>
            </td>
          <?php } else if ($curresourcetype == 3) { ?>
            <td><?php echo $arr["name"];?></td>
            <td><?php echo $arr["phone"];?></td>
            <td><?php echo $arr["wechat"];?></td>
            <td><?php
              if (strlen($arr["qrcode"]))
              {
                $qrcode=$arr["qrcode"];
              echo "<a href=\"$qrcode\" target=\"_blank\">查看二维码</a>";
                }
              else {
                echo 未上传二维码;
                }

            ?></td>
            <td><?php echo $arr["qq"];?></td>
            <td><?php echo $arr["addr"];?></td>
            <td><?php echo $arr["desc"];?></td>
            <td><?php echo $resourcestatus[$arr["status"]];?></td>
            <td><?php echo $arr["submittime"];?></td>
            <td>
              <a title="编辑" href="?act=edit_superresource&id=<?php echo $arr["id"];?>&resourcetype=<?php echo $curresourcetype;?>&resourcestatus=<?php echo $curresourcestatus;?>&resourcequdao=<?php echo $curresourcequdao;?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
            </td>
          <?php } ?>

        </tr>

		<?php

      }
		}

		?>

		</table>

    <table cellpadding="3" cellspacing="0" class="table_98">
      <tr>

        <td >

          <?php 
              //添加权限判断
            if (get_privilege_code("导出资源") != "") {
          ?>

    <INPUT TYPE="checkbox" NAME="chkAll2" id="chkAll2" title="全选"  onclick="CheckAll2(this.form)">&nbsp;全选

        <input name="Action" type="hidden" id="Action" value="">

            <?php 
              //添加权限判断
              echo get_privilege_code("导出资源");

              }
            ?>

         </td>

        <td align="right" style="text-align:right !important">



        当前第<?=$currpage?>页,&nbsp;共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

              <?php if($currpage==1){?>

              首页&nbsp;上一页&nbsp;

              <?php } else {?>

              <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;

              <?php }

        if($currpage==$totalpage)

        {?>

        下一页&nbsp;尾页&nbsp;

              <?php }else{?>

              <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;

              <?php }?>

        </td>
      </tr>
    </table>
    </form>

	</td>

  </tr>

</table>
  
</div></div>

<?php

}

////添加资源

if ($act == "add") {
  
  $resourcequdao = explode(',', $cf['resource_qudao']);
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="resource.php?">资源管理</a> <span class="c-gray en">&gt;</span> 添加资源 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98" id="id_add_superresource">

  <tr>

    <td valign="top">

	

	<form name="form1" method="post" enctype="multipart/form-data" action="?act=save_add_superresource">


		<table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">

          <tr>

            <td colspan="2" align="center">添加资源</td></tr>

          <tr >

            <td width="20%"> 姓名：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" id="name"  value=""></td>

          </tr>

          <tr >

            <td width="20%"> 电话：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="phone" type="text" id="phone"  value=""></td>

          </tr>

          <tr >

            <td width="20%"> 微信：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="wechat" type="text" id="wechat"  value=""></td>

          </tr>

          <tr >

            <td width="20%"> QQ：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="qq" type="text" id="qq"  value=""></td>

          </tr>

          <?php if($cf['cursection'] == 1) { ?>
          <tr >

            <td width="20%"> 渠道：</td>

            <td>
              <select name="qudao" id="qudao">
                <?php
                  foreach ($resourcequdao as $key => $value) {
                    echo '<option value="'.$value.'">'.$value.'</option>';
                  }
                ?>
              </select>
            </td>

          </tr>
          <?php }else{ ?>

          <input type="hidden" name="qudao" value="<?=$resourcequdao[count($resourcequdao)-1]?>" />
          <input type="hidden" name="group" value="<?=$cf['curid']?>" />

          <?php } ?>

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

if ($act == "save_add_superresource") {

	global $database;

	$name = trim($_POST["name"]);

  $phone = trim($_POST["phone"]);

  $wechat = trim($_POST["wechat"]);

  $qq = trim($_POST["qq"]);

  $qudao = trim($_POST["qudao"]);

  $group = trim($_POST["group"]);

  if (strlen($phone) < 11)
    $phone = "";

	if($phone=="" && $wechat=="" && $qq==""){

      echo "<script>alert('联系方式不能为空');window.location.href='?'</script>";

      exit;
    }  

    if ($phone=="")
      $phone="NULL";

    if ($wechat=="")
      $wechat="NULL";

    if ($qq=="")
      $qq="NULL";

   $b=$database->has("resource", array("OR"=>array("phone"=>$phone, "wechat"=>$wechat, "qq"=>$qq)));

   if ($phone=="NULL")
      $phone="";

    if ($wechat=="NULL")
      $wechat="";

    if ($qq=="NULL")
      $qq="";

    $data_arr = array("name"=>$name, "phone"=>$phone, "wechat"=>$wechat, "qq"=>$qq, "type"=>0, "status"=>0, "addtime"=>date($cf['time_format']), "qudao"=>$qudao);
    if($group != "")
    {
      $control = array();
      $control[0] = $group;
      $data_arr['type'] = $cf['cursection'] - 1;
      $data_arr['applytime'] = date($cf['time_format']);
      $data_arr['control'] = json_encode($control);
    }

	if (!$b && is_object($database->insert("resource", $data_arr)))
      echo "<script>alert('资源添加成功');</script>";
    else if ($b)
      echo "<script>alert('添加资源存在');</script>";
    else
      echo "<script>alert('资源添加失败');</script>";

  	echo "<script>window.location.href='?'</script>";

    exit; 
?>


<?php

}

////编辑资源

if($act == "edit_superresource"){ 

	global $database;

  $resourcetype = explode(',', $cf['resource_type']);
  $resourcestatus = explode(',', $cf['resource_status']);

  $curresourcetype = trim($_REQUEST['resourcetype']);

   $curresourcestatus = trim($_REQUEST['resourcestatus']);

  	$id  = $_GET['id'];

  	$b = $database->select("resource", "*", array("id"=>$id));
  	if (!is_array($b))
    	exit;

    $arr = $b[0];

    $descs = json_decode($b[0]['desc'], true);
    if (isset($descs[$curresourcetype]))
      $arr['desc'] = $descs[$curresourcetype];
    else
      $arr['desc'] = '';
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="resource.php?">资源管理</a> <span class="c-gray en">&gt;</span> 资源列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">
    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

  

  <form name="form1" method="post" enctype="multipart/form-data" action="?act=save_edit_superresource&resourcetype=<?php echo $curresourcetype;?>">

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

    <input type="hidden" name="id" id="id" value="<?=$id?>" />

    <table cellpadding="3" cellspacing="1" class="table_50">

          <tr>

            <td colspan="2" align="center">编辑资源</td></tr>

            <?php if ($curresourcetype != 3) { ?>
          <tr >

            <td width="20%"> 姓名：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="name" type="text" id="name" value="<?=$arr['name']?>"></td>

          </tr>

          <tr >

            <td width="20%"> 电话：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="phone" type="text" id="phone" value=""> (如不修改则无需添写)</td>

          </tr>

          <tr >

            <td width="20%"> 微信：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="wechat" type="text" id="wechat" value=""> (如不修改则无需添写)</td>

          </tr>

          <tr >
              <td width="20%"> 二维码：</td>
              
              <td width="80%" ><input style="width:300px" class="input-text" name="qrcode" type="text" id="qrcode" value=""> <a onclick="window.open('filemain.php?act=qrcode', '','width=700,height=550,scrollbars=yes')"><img src="images/changeimg.gif" border="0", align="absbottom"> </a>(如不修改则无需添写)</td>

            </form>
          </tr>

          <tr >

            <td width="20%"> QQ：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="qq" type="text" id="qq" value=""> (如不修改则无需添写)</td>

          </tr>
          <?php } ?>

          <?php if ($curresourcetype == 0) { ?>
            <tr >

            <td width="20%"> 身份证：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="idcard" type="text" id="idcard" value=""> (如不修改则无需添写)</td>

          </tr>
          <?php } if ($curresourcetype != 3) { ?>

          <tr >

            <td width="20%"> 地址：</td>

            <td width="80%" ><input style="width:300px" class="input-text" name="addr" type="text" id="addr" value=""> (如不修改则无需添写)</td>

          </tr>
          <?php } ?>

          <tr >

            <td width="20%"> 备注：</td>

            <td width="80%" ><textarea style="width:400px" class="textarea" name="desc" id="desc" cols="50" rows="5"></textarea></td>

          </tr>

          <tr >
            <td width="20%"> 状态：</td>
          <td><select  name="status" id="status" class="select" >

            <?php
              echo '<option value="'.$arr['status'].'">'.$resourcestatus[$arr['status']].'</option>';

              $begin = 0;
              $end = count($resourcestatus);

              for ($i=$begin; $i < $end; $i++) { 
                $key = $i;
                $value = $resourcestatus[$i];

                if ($key != $arr['status'])
                  echo '<option value="'.$key.'">'.$value.'</option>';
              }

            ?>
          </select></td>
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

////保存编辑的资源//////////////////////////////////////

if($act == "save_edit_superresource"){

  global $database;

  $id = $_POST['id'];

  $resourcetype = explode(',', $cf['resource_type']);

  $resourcestatus = explode(',', $cf['resource_status']);

  $curresourcetype = trim($_REQUEST['resourcetype']);

  if(!$id){

      echo "<script>alert('id参数有误');window.location.href='?'</script>";

      exit;
  }  

  if(!isset($resourcetype[$curresourcetype])){

      echo "<script>alert('类型参数有误');window.location.href='?'</script>";

      exit;
  }  

  $b = $database->select("resource", "*", array("id"=>$id));
    if (!is_array($b))
      exit;

  $arr = $b[0];
  $descs = json_decode($b[0]['desc'], true);

  $name = trim($_POST["name"]);

  $phone = trim($_POST["phone"]);

  $wechat = trim($_POST["wechat"]);

  $qrcode = trim($_POST["qrcode"]);

  $qq = trim($_POST["qq"]);

  $idcard = trim($_POST["idcard"]);

  $addr = trim($_POST["addr"]);

  $desc = trim($_POST["desc"]);

  $status = trim($_POST["status"]);

  $arr = array();
  
  if ($name != "")
    $arr["name"] = $name;

  if ($phone != "")
    $arr["phone"] = $phone;

  if ($wechat != "")
    $arr["wechat"] = $wechat;

  if ($qrcode != "")
    $arr["qrcode"] = $qrcode;

  if ($qq != "")
    $arr["qq"] = $qq;

  if ($idcard != "")
    $arr["idcard"] = $idcard;

  if ($addr != "")
    $arr["addr"] = $addr;

  if ($desc != "")
  {
    if (isset($descs[$curresourcetype]))
    {
      $descs[$curresourcetype] = $desc;
      $desc = json_encode($descs);
    }
    else
    {
      if (!is_array($descs))
        $descs = array();

      $descs[$curresourcetype] = $desc;
      $desc = json_encode($descs);
    }
    $arr["desc"] = $desc;
  }

  if ($status != "")
  {
    $arr["status"] = $status;
    if($status == 2)
    {
      if (isset($resourcetype[$curresourcetype+1]))
      {
        if ($curresourcetype == 1)
          $arr["type"] = $curresourcetype+1;
        else
          $arr["type"] = count($resourcetype) - 1;
        
        $arr["status"] = 0;
        $arr["checktime"] = date($cf["time_format"]);
      }
      else
      {
        $arr["type"] = 0;
      }
    }
    else if ($status == 3) 
    {
      $arr["type"] = 0;
    }
  }

  $olddatas = $database->select("resource", "*", array("id"=>$id));

  if ($database->has("resource", array("id"=>$id)) && is_object($database->update("resource", $arr, array("id"=>$id))))
  {
    $database->insert("resource_update", array("old"=>json_encode($olddatas[0]), "update"=>json_encode($arr), "admin"=>$_COOKIE['username']."-".$_COOKIE['name'], "time"=>date($cf['time_format'])));
    echo "<script>alert('资源更新成功');</script>";
  }
  else
    echo "<script>alert('资源更新失败');</script>";

  echo "<script>window.location.href='?'</script>";

  exit;
?>


<?php

}

////一键授权//////////////////////////////////////
if($act == "submit_superresource"){
  
  global $database;
  $id = $_GET['id'];

  if ($database->has("resource", array("AND"=>array("id"=>$id, "type"=>2))))
  {
    //Todo
    $database->update("resource", array("type"=>3, "submittime"=>date($cf['time_format'])), array("id"=>$id));
    echo "<script>alert('授权成功');</script>";
  }
  else
    echo "<script>alert('授权失败');</script>";

  echo "<script>window.location.href='?'</script>";

  exit;
}

////回收资源//////////////////////////////////////
if($act == "recycle_superresource"){
  global $database;

  $id = $_GET['id'];

  if ($database->has("resource", array("id"=>$id)) && is_object($database->update("resource", array("type"=>0, "curcontrol"=>0), array("id"=>$id))))
    echo "<script>alert('资源回收成功');</script>";
  else
    echo "<script>alert('资源回收失败');</script>";

  echo "<script>window.location.href='?'</script>";

  exit;

}

////删除资源//////////////////////////////////////

if($act == "delete_superresource"){

  global $database;

  $id = $_GET['id'];

  if ($database->has("resource", array("id"=>$id)) && is_object($database->delete("resource", array("id"=>$id))))
    echo "<script>alert('资源删除成功');</script>";
  else
    echo "<script>alert('资源删除失败');</script>";

  echo "<script>window.location.href='?'</script>";

  exit;

?>


<?php

}

if($act == "export_resource") {

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 资源管理 <span class="c-gray en">&gt;</span> 导出提示 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>资源导出提示</b></td>

  </tr>

  <tr>

    <td>

  <ul class="exli">

   <li>1、“导出”方式直接生成CSV格式文档。</li>

   <li>2、请注意导出的文档编码，支持“ANSI简体中文”和“UTF-8”编码两种文档，请使用Ms Excel、 Notepad++、 EditPlus等软件打开和编辑文档。</li>

   <li>3、csv文档均以英文逗号做为分隔符。</li>

   <li>4、如果你是备份资源信息，下边的选项请全部选择。</li>

   </ul>

  </td>

  </tr>

  <form name="form1" enctype="multipart/form-data" method="post" action="export.php?act=export_resource" target="_blank">

  <tr>

    <td style="line-height:30px;">资源信息：

        <input type="hidden" name="chk" id="chk" value="<?=implode(",",$_POST['chk'])?>" />

    <input type="checkbox" name="field_uid" id="field_uid" value="1" checked="checked" />代理编号

    <input type="checkbox" name="field_name" id="field_name" value="1" checked="checked" />姓名

    <input type="checkbox" name="field_phone" id="field_phone" value="1" checked="checked" />手机

    <input type="checkbox" name="field_wechat" id="field_wechat" value="1" checked="checked" />微信

    <input type="checkbox" name="field_qrcode" id="field_qrcode" value="1" checked="checked" />二维码

    <input type="checkbox" name="field_qq" id="field_qq" value="1" checked="checked" />QQ

    <input type="checkbox" name="field_idcard" id="field_idcard" value="1" checked="checked" />身份证号

    <input type="checkbox" name="field_addr" id="field_addr" value="1" checked="checked" />地址

    <input type="checkbox" name="field_desc" id="field_desc" value="1" checked="checked" />备注

    <input type="checkbox" name="field_type" id="field_type" value="1" checked="checked" />类型

    <input type="checkbox" name="field_control" id="field_control" value="1" checked="checked" />操作员

    <input type="checkbox" name="field_status" id="field_status" value="1" checked="checked" />状态

    <input type="checkbox" name="field_addtime" id="field_addtime" value="1" checked="checked" />添加时间

    <input type="checkbox" name="field_applytime" id="field_applytime" value="1" checked="checked" />发布时间

    <input type="checkbox" name="field_checktime" id="field_checktime" value="1" checked="checked" />提审时间

    <input type="checkbox" name="field_submittime" id="field_submittime" value="1" checked="checked" />提交时间

    <input type="checkbox" name="field_qudao" id="field_qudao" value="1" checked="checked" />来源渠道
    
  </td>

    </tr>

  <tr>

 <td>文档编码

 <select name="file_encoding">

<option value="gbk">简体中文</option>

<option value="utf8">UTF-8</option>

 </select>

 <input type="submit" name="Submit" value=" 导出资源 "> （导出会转行）

 </td>

 </tr>

 </form>

</table>
</article>

<?php 

} 

if($act == "exportall_resource") {

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 资源管理 <span class="c-gray en">&gt;</span> 导出提示 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>资源导出提示</b></td>

  </tr>

  <tr>

    <td>

  <ul class="exli">

   <li>1、“导出”方式直接生成CSV格式文档。</li>

   <li>2、请注意导出的文档编码，支持“ANSI简体中文”和“UTF-8”编码两种文档，请使用Ms Excel、 Notepad++、 EditPlus等软件打开和编辑文档。</li>

   <li>3、csv文档均以英文逗号做为分隔符。</li>

   <li>4、如果你是备份资源信息，下边的选项请全部选择。</li>

   </ul>

  </td>

  </tr>

  <form name="form1" enctype="multipart/form-data" method="post" action="export.php?act=exportall_resource" target="_blank">

  <tr>

    <td style="line-height:30px;">资源信息：

        <input type="hidden" name="chk" id="chk" value="<?=implode(",",$_POST['chk'])?>" />

    <input type="checkbox" name="field_uid" id="field_uid" value="1" checked="checked" />代理编号

    <input type="checkbox" name="field_name" id="field_name" value="1" checked="checked" />姓名

    <input type="checkbox" name="field_phone" id="field_phone" value="1" checked="checked" />手机

    <input type="checkbox" name="field_wechat" id="field_wechat" value="1" checked="checked" />微信

    <input type="checkbox" name="field_qrcode" id="field_qrcode" value="1" checked="checked" />二维码

    <input type="checkbox" name="field_qq" id="field_qq" value="1" checked="checked" />QQ

    <input type="checkbox" name="field_idcard" id="field_idcard" value="1" checked="checked" />身份证号

    <input type="checkbox" name="field_addr" id="field_addr" value="1" checked="checked" />地址

    <input type="checkbox" name="field_desc" id="field_desc" value="1" checked="checked" />备注

    <input type="checkbox" name="field_type" id="field_type" value="1" checked="checked" />类型

    <input type="checkbox" name="field_control" id="field_control" value="1" checked="checked" />操作员

    <input type="checkbox" name="field_status" id="field_status" value="1" checked="checked" />状态

    <input type="checkbox" name="field_addtime" id="field_addtime" value="1" checked="checked" />添加时间

    <input type="checkbox" name="field_applytime" id="field_applytime" value="1" checked="checked" />发布时间

    <input type="checkbox" name="field_checktime" id="field_checktime" value="1" checked="checked" />提审时间

    <input type="checkbox" name="field_submittime" id="field_submittime" value="1" checked="checked" />提交时间
    
    <input type="checkbox" name="field_qudao" id="field_qudao" value="1" checked="checked" />来源渠道

  </td>

    </tr>

  <tr>

 <td>文档编码

 <select name="file_encoding">

<option value="gbk">简体中文</option>

<option value="utf8">UTF-8</option>

 </select>

 <input type="submit" name="Submit" value=" 导出资源 "> （导出会转行）

 </td>

 </tr>

 </form>

</table>
</article>

<?php 

} 

///////导入////////////

if($act =="import"){

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 资源管理 <span class="c-gray en">&gt;</span> 导入资源 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <tr>

    <td><b>导入资源提示</b></td>

  </tr>

  <tr>

    <td>

  <ul class="exli">

  <li>1、“导入”方式支持 XLS、CSV、TXT三种格式文档，请按：<b><a href="../data/exemple/xls_resource_list.xls"><span class="red">XLS格式文件</span></a></b>、<b><a href="../data/exemple/csv_resource_list.csv"><span class="red">CSV格式文件</span></a></b>、<b><a href="../data/exemple/txt_resource_list.txt"><span class="red">TXT格式文件</span></a></b>，制作合适导入的标准文档,如果下载文档时是打开网页那请使用“右键另存为”下载文档。</li>

  <li>2、上述三个文档均为 “ANSI” 简体中文编码文档，在“导入”时选择“文档编码”为"UTF－8"导入时会有乱码。</li>

  <li>3、csv和txt文档均以英文逗号做为分隔符。</li>

  <li>4、程序对上传的文件大小不做限制，但一般空间都会有一个默认限制，一般为2M，所以上传的文件尽量小于2M。</li>

  <li>5、三个格式文档第一行的标题栏请不要删除，程序在导入过程中自动省略第一行。 </li>

  <li>6、如果用之前“导出选定的记录”导出的文档且是标准五项参数的文档，可直接导入。</li>

  </ul>

  </td>

  </tr>

  <tr>

    <td><form name="form1" enctype="multipart/form-data" method="post" action="action.php?act=save_uplod">

        文档编码：

    <label>

    <select name="file_encoding">

      <option value="gbk">简体中文</option>

      <option value="utf8">UTF-8</option>

    </select>

    </label>



    <label>

    <input type="file" name="file">

        </label>

      <label>

      <input class="btn btn-primary radius"  type="submit" name="Submit" value="上传资源">



      </label>

    </form>

    </td>

  </tr>

</table>
</article>

<?php 

} 

if($act == "apply") {
  
  global $database;

  $resourcequdao = explode(',', $cf['resource_qudao']);

  $resourcestatus = explode(',', $cf['resource_status']);

  $resourcetype = explode(',', $cf['resource_type']);

  $id = trim($_REQUEST['id']);

  $phone = trim($_REQUEST['phone']);

  $wechat = trim($_REQUEST['wechat']);

  $name = trim($_REQUEST['rname']);

  $h       = trim($_REQUEST["h"]);

  $curresourcequdao = trim($_REQUEST['resourcequdao']);

  if($curresourcequdao == "" && is_array($resourcequdao)){

       $curresourcequdao = -1;

   }

  $curresourcestatus = trim($_REQUEST['resourcestatus']);

  if($curresourcestatus == "" && is_array($resourcestatus)){

      $curresourcestatus = $cf['cursection'] - 2;

   }

  $curresourcetype = trim($_REQUEST['resourcetype']);

  if($curresourcetype == "" && is_array($resourcetype)){

      $curresourcetype = $cf['cursection'] - 1;

      if ($cf['cursection'] == 1 && $curresourcestatus == 1)
        $curresourcetype = 1;
   }

   $crew = $database->select("admin", "*", array("section"=>$cf['cursection']+1, "group[~]"=>$cf['curgroup']."-", "ORDER"=>array("id"=>"ASC")));

   $curcrew = trim($_REQUEST['crew']);

   if ($curcrew == "")
   {

      $curcrew = -1;

   }

   $timetype = trim($_REQUEST['timetype']);
   if($timetype == "")
   {
      $timetype = $curresourcetype;
   }

  $start_date = trim($_REQUEST['start_pickdate']);
  $start_time = trim($_REQUEST['start_picktime']);
  $end_date = trim($_REQUEST['end_pickdate']);
  $end_time = trim($_REQUEST['end_picktime']);

  $limit=$database->select("section", "limit", array("id"=>$cf['cursection']));
  $limit_date = date('Y-m-d', strtotime('-7 days'));

  if ($limit[0])
    $limit = json_decode($limit[0], true);

  if (isset($limit['days']))
  {
    $limit_date = date('Y-m-d', strtotime($limit['days'].' days'));

    if ($start_date == "")
      $start_date = $limit_date;
    else
    {
      $limit_days = date("d", strtotime($limit_date));
      $days = date("d", strtotime($start_date));
      if (($days-$limit_days) > abs($limit['days']))
        $start_date = $limit_date;
    }
  }

  if ($start_time == "")
    $start_time = "00:00";

  if ($end_date == "")
      $end_date = date('Y-m-d');

  if ($end_time == "")
    $end_time = "23:59";

  $time_arr = array("addtime","applytime","checktime","submittime");
  $data_arr = array("type"=>$curresourcetype, "ORDER"=>array("status"=>"ASC", $time_arr[$cf['cursection']-1]=>"DESC"));

   if ($id != "")
    $data_arr['id'] = $id;

  if ($phone != "")
    $data_arr['phone'] = $phone;

  if ($wechat != "")
    $data_arr['wechat'] = $wechat;

  if ($name != "")
    $data_arr['name'] = $name;

  if ($h == "1")
    $data_arr['ORDER'] = array("status"=>"ASC", $time_arr[$cf['cursection']-1]=>"ASC");

  if ($curresourcequdao != -1)
    $data_arr['qudao'] = $resourcequdao[$curresourcequdao];

  if ($curresourcestatus == -1)
    $data_arr['status[<]'] = 2;
  else
    $data_arr['status'] = $curresourcestatus;

  if($curcrew != -1 && isset($crew[$curcrew]))
    $data_arr['curcontrol'] = $crew[$curcrew]['id'];

  if($cf['cursection'] != 1 && $cf['cursection']%2 == 0)
  {
    $gtemp = array($cf['curid']);
    for ($i=0; $i < count($crew); $i++) { 
      $gtemp[] = $crew[$i]['id'];
    }
    $data_arr['curcontrol'] = $database->select("resource", "curcontrol", array("OR"=>array("curcontrol"=>$gtemp)));
  }

  if ($start_date != "" && $start_time != "" && $end_date != "" && $end_time != "")
  {
    $data_arr[$time_arr[$timetype]."[<>]"] = array($start_date . ' ' . $start_time, $end_date . ' ' . $end_time);
  }
  
  $showsize      = trim($_REQUEST['showsize']);

  if($showsize == ""){

       $pagesize = $cf['list_num'];//每页所要显示的数据个数。

   $showsize       = $cf['list_num'];

   }

   else{

     $pagesize = $showsize;

   }
   
   $total    = $database->count("resource", $data_arr);

    $filename = "?act=apply&id=".$id."&showsize=".$showsize."&h=".$h."&resourcetype=".$curresourcetype."&resourcequdao=".$curresourcequdao."&resourcestatus=".$curresourcestatus."&timetype=".$timetype."&crew=".$curcrew."&start_pickdate=".$start_date."&start_picktime=".$start_time."&end_pickdate=".$end_date."&end_picktime=".$end_time."";

  $currpage  = intval($_REQUEST["page"]);

  if(!is_int($currpage))

  $currpage=1;

  if(intval($currpage)<1)$currpage=1;

  if(intval($currpage-1)*$pagesize>$total)$currpage=1;

  if(($total%$pagesize)==0){

    $totalpage=intval($total/$pagesize);

     }

    else

      $totalpage=intval($total/$pagesize)+1;

?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="resource.php?">资源管理</a> <span class="c-gray en">&gt;</span> 发布资源 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<div class="page-container">
  <div class="text-c">

    <table cellpadding="3" cellspacing="0" class="table_98">
      <form action="?act=apply" method="post" name="form1">
        <tr>
          
        <td>
          <?php if($cf['cursection'] == 1) { ?>
          ID：<input type="text" style="width:100px" class="input-text" name="id" size="20" value="<?=$id?>" />
          <?php } ?>

          &nbsp;手机号：<input type="text" style="width:100px" class="input-text" name="phone" size="20"  value="<?=$phone?>">&nbsp;微信号：<input style="width:100px" class="input-text" type="text" name="wechat" size="30" value="<?=$wechat?>">&nbsp;姓名：<input style="width:100px" class="input-text" type="text" name="rname" size="20" value="<?=$name?>" />

              <?php if($cf['cursection'] == 1) { ?>

              &nbsp;渠道：<span class="select-box inline">
          <select  name="resourcequdao" id="resourcequdao" class="select" >

          <?php
            if ($curresourcequdao == -1)
              echo '<option value="-1">全部</option>';
            else
            {
              echo '<option value="'.$curresourcequdao.'">'.$resourcequdao[$curresourcequdao].'</option>';
              echo '<option value="-1">全部</option>';
            }
          foreach ($resourcequdao as $key => $value) {
            if ($key != $curresourcequdao)
              echo '<option value="'.$key.'">'.$value.'</option>';
          }
          ?>
           </select></span>

           <?php } ?>


                        &nbsp;状态：<span class="select-box inline">
  <select  name="resourcestatus" id="resourcestatus" class="select" >

  <?php
    if ($curresourcestatus == -1)
      echo '<option value="-1">全部</option>';
    else
    {
      echo '<option value="'.$curresourcestatus.'">'.$resourcestatus[$curresourcestatus].'</option>';
      if ($cf['cursection'] == 1)
        echo '<option value="-1">全部</option>';
    }

    $begin = 0;
    $end = 2;

    for ($i=$begin; $i < $end; $i++) { 
      $key = $i;
      $value = $resourcestatus[$i];

      if ($key != $curresourcestatus)
        echo '<option value="'.$key.'">'.$value.'</option>';
    }
  ?>
   </select></span>

   <?php if($cf['cursection']%2 == 0) { ?>
   &nbsp;组员：<span class="select-box inline">
  <select  name="crew" id="crew" class="select" >

      <?php
        if ($curcrew == -1)
          echo '<option value="-1">全部</option>';
        else
        {
          if (isset($crew[$curcrew]))
          {
            $arr = $crew[$curcrew];
            echo '<option value="'.$curcrew.'">'.$arr['group'].'-'.$arr['name'].'</option>';
          }
          echo '<option value="-1">全部</option>';
        }

        for ($i=0; $i < count($crew); $i++) { 
          if (isset($crew[$i]) && $curcrew != $i)
          {
            $arr = $crew[$i];
            echo '<option value="'.$i.'">'.$arr['group'].'-'.$arr['name'].'</option>';
          }
        }

      ?>
       </select></span>
    <?php } ?>

   <?php if($cf['cursection'] == 1) { ?>
   &nbsp;时间类型：<span class="select-box inline">
  <select  name="timetype" id="timetype" class="select" >
      <?php
        $arr = array("添加时间","发布时间");
        if(isset($arr[$timetype]))
          echo '<option value="'.$timetype.'">'.$arr[$timetype].'</option>';

  foreach ($arr as $key => $value) {
    if ($key != $timetype)
      echo '<option value="'.$key.'">'.$value.'</option>';
  }
  ?>
     </select></span>
  <?php } ?>

           &nbsp;<input type="text" style="width:90px" class="input-text" name="start_pickdate" id="start_pickdate" value="<?=$limit_date?>" placeholder="开始日期" />
           <input type="text" style="width:70px" class="input-text" name="start_picktime" id="start_picktime" placeholder="开始时间" />
          <span>-</span>
          <input type="text" style="width:90px" class="input-text" name="end_pickdate" id="end_pickdate" value="<?=date('Y-m-d')?>" placeholder="结束日期" />
          <input type="text" style="width:70px" class="input-text" name="end_picktime" id="end_picktime" placeholder="结束时间" />

          <input type="hidden" name="showsize" id="showsize" value="<?=$showsize?>" />

          <input name="submit" class="btn btn-success" type="submit" id="submit" value="查找"> </td>

        </tr>
      </form>
    </table>

    <table align="center" cellpadding="0" cellspacing="0" class="table_98">

  <tr>

    <td valign="top">

      <form method="post" name="myform" id="myform" action="?act=apply" onsubmit="return ConfirmDel();">

      <input type="hidden" name="id" value="<?=$id?>" />

      <input type="hidden" name="h" value="<?=$h?>" />

      <input type="hidden" name="resourcetype" value="<?=$curresourcetype?>" />

      <input type="hidden" name="resourcequdao" value="<?=$curresourcequdao?>" />

      <input type="hidden" name="resourcestatus" value="<?=$curresourcestatus?>" />

      <input type="hidden" name="crew" value="<?=$curcrew?>" />

      <input type="hidden" name="start_pickdate" value="<?=$start_date?>" />

      <input type="hidden" name="start_picktime" value="<?=$start_time?>" />

      <input type="hidden" name="end_pickdate" value="<?=$end_date?>" />

      <input type="hidden" name="end_picktime" value="<?=$end_time?>" />

      <table cellpadding="3" cellspacing="0">

        <tr>

          <td height="20">
            <?php 
              //添加权限判断
              echo get_privilege_code("发布记录");
            ?>

            </td>

      <td align="right" style="text-align:right !important">

        显示条数 <input style="width:50px" class="input-text" type="text" name="showsize" id="showsize" value="<?=$pagesize?>" size="8" onchange="javascript:submit()" /> &nbsp;&nbsp;&nbsp;&nbsp;

          当前第<?=$currpage?>页, 共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

              <?php if($currpage==1){?>

              首页&nbsp;上一页&nbsp;

              <?php } else {?>

              <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;

              <?php }

        if($currpage==$totalpage)

        {?>

        下一页&nbsp;尾页&nbsp;

              <?php }else{?>

              <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;

              <?php }?>
<span class="select-box inline">
        <select name='page' size='1' id="page" class="select" onchange='javascript:submit()'>

        <?php

        for($i=1;$i<=$totalpage;$i++)

        {

        ?>

         <option value="<?php echo $i; ?>" <?php if ($currpage==$i) echo "selected"; ?>> 第<?php echo $i;?>页</option>

         <?php }?>

         </select></span>

        </td>

        </tr>

    </table>

      <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        

    <tr>

          <td width="5%"><strong>
            <INPUT TYPE="checkbox" NAME="chkAll" id="chkAll" title="全选"  onclick="CheckAll(this.form)">
            &nbsp;全选</strong></td>

          <td width="4%"><strong>序号</strong></td>
          <?php if($cf['cursection'] == 1) { ?>
          <td width="4%"><strong>ID</strong></td>
          <?php } ?>
          <td width="7%"><strong>姓名</strong></td>
          <td width="7%"><strong>手机</strong></td>
          <td width="7%"><strong>微信号</strong></td>
          <td width="4%"><strong>二维码</strong></td>
          <td width="7%"><strong>QQ</strong></td>
          <td width="7%"><strong>地址</strong></td>
          <td width="7%"><strong>当前操作员</strong></td>
          <td width="7%"><strong>已操作员</strong></td>
          <td width="7%"><strong>状态</strong></td>
          <?php if($cf['cursection'] == 1) { ?>
          <td width="7%"><strong>添加时间</strong></td>
          <?php } ?>
          <td width="7%"><strong>发布时间</strong></td>
          <?php if($cf['cursection'] == 1) { ?>
          <td width="7%"><strong>渠道</strong></td>
          <?php } ?>

    </tr>

    <?php

      global $database;

      $resources = $database->select("resource", "*", $data_arr);
      $index = ($currpage - 1) * $showsize;
      $groupcrew = array();
      if($cf['cursection']%2 == 0)
      {
        for ($i=0; $i < count($crew); $i++) { 
          $groupcrew[] = $crew[$i]['id'];
        }
      }

      for ($i=0; $i < $showsize; $i++, $index++) { 
          if (isset($resources[$index])) {
            $arr = $resources[$index];  
            $controls = json_decode($arr['control'], true);
            $curcontrol = "";
            $arr['control'] = "";

            if ($cf['cursection']%2 == 0)
            {
              if (isset($controls['crew'])){
                $controls = explode(',', $controls['crew']);
              }
            }
            else
            {
              if (isset($controls['group'])){
                $controls = explode(',', $controls['group']);
              }
            }

            if (is_array($controls))
            {
              for ($i=0; $i < count($controls); $i++) { 
                  $key = $i;
                  $value = $controls[$i];

                  $control = $database->select("admin", "*", array("id"=>$value));

                  if($control[0])
                  {
                    if ($cf['cursection']%2 == 0 && in_array($control[0]['id'], $groupcrew))
                    {
                      if ($arr['control'] == "")
                        $arr['control'] = $control[0]["group"].'-'.$control[0]["name"];
                      else
                        $arr['control'] .= ','.$control[0]["group"].'-'.$control[0]["name"];
                      if ($arr['curcontrol'] == $value)
                          $curcontrol = $control[0]["group"].'-'.$control[0]["name"];
                    }
                    else if ($cf['cursection'] == 1)
                    {
                      if ($arr['control'] == "")
                        $arr['control'] = $control[0]["group"].'-'.$control[0]["name"];
                      else
                        $arr['control'] .= ','.$control[0]["group"].'-'.$control[0]["name"];
                    }
                  }
                }
            }

            if($curcontrol == "")
            {
              $curcontrol = $database->select("admin", "*", array("id"=>$arr['curcontrol']));
              if($curcontrol[0])
              {
                $arr['curcontrol'] = $curcontrol[0]["group"].'-'.$curcontrol[0]["name"];
              }
            }
            else
              $arr['curcontrol'] = $curcontrol;
    ?>

        <tr >
          <td><input name="chk[]" type="checkbox" id="chk[]" value="<?php echo $arr["id"];?>"></td>
          <td><?php echo $index+1;?></td>
          <?php if($cf['cursection'] == 1) { ?>
          <td><?php echo $arr["id"];?></td>
          <?php } ?>
          <td><?php echo $arr["name"];?></td>

          <td><?php echo $arr["phone"];?></td>

          <td><?php echo $arr["wechat"];?></td>

          <td><?php
        if (strlen($arr["qrcode"]))
        {
          $qrcode=$arr["qrcode"];
        echo "<a href=\"$qrcode\" target=\"_blank\">查看二维码</a>";
          }
        else {
          echo 未上传二维码;
          }

         ?></td>

          <td><?php echo $arr["qq"];?></td>
          <td><?php echo $arr["addr"];?></td>
          
          <td><?php echo $arr["curcontrol"];?></td>
          <td><?php echo $arr["control"];?></td>

          <td><?php echo $resourcestatus[$arr["status"]];?></td>
          <?php if($cf['cursection'] == 1) { ?>
          <td><?php echo $arr["addtime"];?></td>
          <?php } ?>
          <td><?php echo $arr["applytime"];?></td>
          <?php if($cf['cursection'] == 1) { ?>
          <td><?php echo $arr["qudao"];?></td>
          <?php } ?>

        </tr>

    <?php

      }
    }

    ?>

    </table>

    <table cellpadding="3" cellspacing="0" class="table_98">
      <tr>

        <td >

    <INPUT TYPE="checkbox" NAME="chkAll2" id="chkAll2" title="全选"  onclick="CheckAll2(this.form)">&nbsp;全选

        <input name="Action" type="hidden" id="Action" value="">

            <?php 
              //添加权限判断
              echo get_privilege_code("发布记录");
            ?>

         </td>

        <td align="right" style="text-align:right !important">



        当前第<?=$currpage?>页,&nbsp;共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

              <?php if($currpage==1){?>

              首页&nbsp;上一页&nbsp;

              <?php } else {?>

              <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;

              <?php }

        if($currpage==$totalpage)

        {?>

        下一页&nbsp;尾页&nbsp;

              <?php }else{?>

              <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;

              <?php }?>

        </td>
      </tr>
    </table>
    </form>

  </td>

  </tr>

</table>
  
</div></div>

<?php 
  
} 

if($act == "apply_resource") {
  
  global $database;

  if($cf['cursection'] == 1) {
    $groups = $database->select("admin", "*", array("section"=>2, "ORDER"=>array("id"=>"ASC")));
    $crew = $database->select("admin", "*", array("section"=>3, "ORDER"=>array("id"=>"ASC")));
  }
  else {
    $crew = $database->select("admin", "*", array("section"=>$cf['cursection']+1, "group[~]"=>$cf['curgroup']."-", "ORDER"=>array("id"=>"ASC")));
  }
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 资源管理 <span class="c-gray en">&gt;</span> 发布资源记录 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>

<br>

<article class="page-container">
<table align="center" cellpadding="3" cellspacing="1" class="table_98">

  <form name="form1" enctype="multipart/form-data" method="post" action="action.php?act=apply_resource">

    <input type="hidden" name="chk" id="chk" value="<?=implode(",",$_POST['chk'])?>" />

    <input type="hidden" name="resourcetype" id="resourcetype" value="<?=$cf['cursection']-1?>" />

<?php if($cf['cursection'] == 1) { ?>
  <tr>
    
    <td style="line-height:30px;">组别：

        <span class="select-box inline">
          <select  name="group" id="group" class="select" >

          <?php
            
            for ($i=0; $i < count($groups); $i++) { 
              if (isset($groups[$i]))
              {
                $arr = $groups[$i];
                echo '<option value="'.$arr['id'].'">'.$arr['group'].'-'.$arr['name'].'</option>';
              }
            }

          ?>
           </select></span>

    </td>
    

 </tr>
<?php } ?>

    <?php if($cf['cursection']%2 == 0) { ?>
     <tr>

        <td style="line-height:30px;">组员：

            <span class="select-box inline">
              <select  name="crew" id="crew" class="select" >

              <?php
                if ($cf['cursection'] == 1)
                  echo '<option value="">未选择</option>';

                for ($i=0; $i < count($crew); $i++) { 
                  if (isset($crew[$i]))
                  {
                    $arr = $crew[$i];
                    echo '<option value="'.$arr['id'].'">'.$arr['group'].'-'.$arr['name'].'</option>';
                  }
                }

              ?>
               </select></span>

        </td>

     </tr>
    <?php } ?>

    <tr> <td><input type="submit" name="Submit" value=" 发布资源 "> </td> </tr>

 </form>

</table>
</article>


<?php 
  
} 

if($act == "updatelog"){

  $data_arr = array("ORDER"=>array("time"=>"DESC"));

  $curcrew = trim($_REQUEST['crew']);

   if ($curcrew == "")
   {

      $curcrew = -1;

   }

   $start_date = trim($_REQUEST['start_pickdate']);
  $start_time = trim($_REQUEST['start_picktime']);
  $end_date = trim($_REQUEST['end_pickdate']);
  $end_time = trim($_REQUEST['end_picktime']);

    if ($start_date == "")
      $start_date = date('Y-m-d', strtotime('-7 days'));

   if ($start_time == "")
    $start_time = "00:00";

  if ($end_date == "")
      $end_date = date('Y-m-d');

  if ($end_time == "")
    $end_time = "23:59";

   if($curcrew != -1)
    $data_arr['curcontrol'] = $curcrew;

  if ($start_date != "" && $start_time != "" && $end_date != "" && $end_time != "")
  {
    $data_arr["time[<>]"] = array($start_date . ' ' . $start_time, $end_date . ' ' . $end_time);
  }

  $showsize      = trim($_REQUEST['showsize']);

  if($showsize == ""){

       $pagesize = $cf['list_num'];//每页所要显示的数据个数。

   $showsize       = $cf['list_num'];

   }

   else{

     $pagesize = $showsize;

   }
   
   $total    = $database->count("resource_update", $data_arr);

   $filename = "?act=updatelog&showsize=".$showsize."&curcrew=".$curcrew."&start_pickdate=".$start_date."&start_picktime=".$start_time."&end_pickdate=".$end_date."&end_picktime=".$end_time."";

  $currpage  = intval($_REQUEST["page"]);

  if(!is_int($currpage))

  $currpage=1;

  if(intval($currpage)<1)$currpage=1;

  if(intval($currpage-1)*$pagesize>$total)$currpage=1;

  if(($total%$pagesize)==0){

    $totalpage=intval($total/$pagesize);

     }

    else

      $totalpage=intval($total/$pagesize)+1;
?>

<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> <a href="resource.php?">资源管理</a> <span class="c-gray en">&gt;</span> 资源更新记录 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>


<div class="page-container">
  <div class="text-c">

    <table cellpadding="3" cellspacing="0" class="table_98">
      <form action="?act=updatelog" method="post" name="form1">

        <tr>
        <td>组员：<span class="select-box inline">
        <select  name="crew" id="crew" class="select" >

            <?php
               $groups = $database->select("admin", "*", array("section"=>2, "ORDER"=>array("id"=>"ASC")));
               $curtemp = $database->select("admin", "*", array("id"=>$curcrew));

              if ($curcrew == -1)
                echo '<option value="-1">全部</option>';
              else
              {
                if (isset($curtemp[0]) && $curcrew == $curtemp[0]['id'])
                {
                  $arr = $curtemp[0];
                  echo '<option value="'.$curcrew.'">'.$arr['group'].'-'.$arr['name'].'</option>';
                }
                echo '<option value="-1">全部</option>';
              }

              for ($i=0; $i < count($groups); $i++) { 
                if (isset($groups[$i]))
                {
                  $arr = $groups[$i];
                  if ($curcrew != $groups[$i]['id'])
                    echo '<option value="'.$arr['id'].'">'.$arr['group'].'-'.$arr['name'].'</option>';

                  $crews = $database->select("admin", "*", array("section"=>$arr['section']+1, "group[~]"=>$arr['group']."-", "ORDER"=>array("id"=>"ASC")));

                  if (is_array($crews))
                  {
                    for ($j=0; $j < count($crews); $j++) { 
                      if (isset($crews[$j]) && $curcrew != $crews[$j]['id'])
                      {
                        $arr1 = $crews[$j];
                        echo '<option value="'.$arr1['id'].'">&nbsp;'.$arr1['group'].'-'.$arr1['name'].'</option>';
                      }
                    }
                  }
                }
              }

            ?>
             </select></span>
          <input type="text" style="width:90px" class="input-text" name="start_pickdate" id="start_pickdate" value="<?=$start_date?>" placeholder="开始日期" />
           <input type="text" style="width:70px" class="input-text" name="start_picktime" id="start_picktime" placeholder="开始时间" />
          <span>-</span>
          <input type="text" style="width:90px" class="input-text" name="end_pickdate" id="end_pickdate" value="<?=date('Y-m-d')?>" placeholder="结束日期" />
          <input type="text" style="width:70px" class="input-text" name="end_picktime" id="end_picktime" placeholder="结束时间" />

          <input type="hidden" name="showsize" id="showsize" value="<?=$showsize?>" />

          <input name="submit" class="btn btn-success" type="submit" id="submit" value="查找"> </td>

        </tr>
      </form>
    </table>


    <table align="center" cellpadding="0" cellspacing="0" class="table_98">
      <tr>

    <td valign="top">

      <form method="post" name="myform" id="myform" action="?act=updatelog">
        <input type="hidden" name="curcrew" value="<?=$curcrew?>" />

        <input type="hidden" name="start_pickdate" value="<?=$start_date?>" />

        <input type="hidden" name="start_picktime" value="<?=$start_time?>" />

        <input type="hidden" name="end_pickdate" value="<?=$end_date?>" />

        <input type="hidden" name="end_picktime" value="<?=$end_time?>" />

        <table cellpadding="3" cellspacing="0">
          <tr>

          <td align="right" style="text-align:right !important">

        显示条数 <input style="width:50px" class="input-text" type="text" name="showsize" id="showsize" value="<?=$pagesize?>" size="8" onchange="javascript:submit()" /> &nbsp;&nbsp;&nbsp;&nbsp;

          当前第<?=$currpage?>页, 共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

              <?php if($currpage==1){?>

              首页&nbsp;上一页&nbsp;

              <?php } else {?>

              <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;

              <?php }

        if($currpage==$totalpage)

        {?>

        下一页&nbsp;尾页&nbsp;

              <?php }else{?>

              <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;

              <?php }?>
<span class="select-box inline">
        <select name='page' size='1' id="page" class="select" onchange='javascript:submit()'>

        <?php

        for($i=1;$i<=$totalpage;$i++)

        {

        ?>

         <option value="<?php echo $i; ?>" <?php if ($currpage==$i) echo "selected"; ?>> 第<?php echo $i;?>页</option>

         <?php }?>

         </select></span>

        </td>

        </tr>

    </table>

    <table cellpadding="3" cellspacing="1" class="table table-border table-bordered table-bg">        
    <tr>
          <td width="10%"><strong>序号</strong></td>
          <td width="30%"><strong>修改前数据</strong></td>
          <td width="30%"><strong>修改数据</strong></td>
          <td width="10%"><strong>操作人员</strong></td>
          <td width="10%"><strong>时间</strong></td>
    </tr>

    <?php

      global $database;

      $resources = $database->select("resource_update", "*", $data_arr);
      $index = ($currpage - 1) * $showsize;

      for ($i=0; $i < $showsize; $i++, $index++) { 
          if (isset($resources[$index])) {
            $arr = $resources[$index];  
      	    $arr["old"] = json_decode($arr["old"], true);
      	    if(isset($arr["old"]["desc"]))
      	    	$arr["old"]["desc"] = json_decode($arr["old"]["desc"], true);
            $arr["update"] = json_decode($arr["update"], true);
      	    if(isset($arr["update"]["desc"]))
      	    	$arr["update"]["desc"] = json_decode($arr["update"]["desc"], true);
            $admin = explode("-", $arr["admin"]);
            if($admin[0] && strlen($admin[0])){
              $arr["admin"] = $admin[0];
              $admin = $database->select("admin", "*", array("OR"=>array("username"=>$arr["admin"], "uname"=>$arr["admin"])));
              if($admin[0] && strlen($admin[0]['name']))
                $arr["admin"] = $admin[0]['name'];
            }
    ?>
    <tr>
      <td><?php echo $index+1;?></td>
      <td><?php echo var_dump($arr["old"]);?></td>
      <td><?php echo var_dump($arr["update"]);?></td>
      <td><?php echo $arr["admin"];?></td>
      <td><?php echo $arr["time"];?></td>
    </tr>

    <?php }} ?>
    </table>
    <table cellpadding="3" cellspacing="0" class="table_98">
      <tr>

        <td align="right" style="text-align:right !important">



        当前第<?=$currpage?>页,&nbsp;共<?=$totalpage?>页/<?php  echo $total;?>个记录&nbsp;

              <?php if($currpage==1){?>

              首页&nbsp;上一页&nbsp;

              <?php } else {?>

              <a href="<?php echo $filename;?>&page=1">首页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo ($currpage-1);?>">上一页</a>&nbsp;

              <?php }

        if($currpage==$totalpage)

        {?>

        下一页&nbsp;尾页&nbsp;

              <?php }else{?>

              <a href="<?php echo $filename;?>&page=<?php echo ($currpage+1);?>">下一页</a>&nbsp;<a href="<?php echo $filename;?>&page=<?php echo  $totalpage;?>">尾页</a>&nbsp;

              <?php }?>

        </td>
      </tr>
    </table>
  </form>

</div></div>

<?php 
  
} 

?>

<?php 

function resourcetype_convert_table_html($resourcetype)
{
  $html = "";
  switch ($resourcetype) {
    case '0':
      $html = '<td width="4%"><strong>ID</strong></td>
                <td width="4%"><strong>代理编号</strong></td>
                <td width="7%"><strong>姓名</strong></td>
                <td width="7%"><strong>手机</strong></td>
                <td width="7%"><strong>微信号</strong></td>
                <td width="4%"><strong>二维码</strong></td>
                <td width="7%"><strong>QQ</strong></td>
                <td width="7%"><strong>身份证</strong></td>
                <td width="7%"><strong>地址</strong></td>
                <td width="7%"><strong>备注</strong></td>
                <td width="7%"><strong>状态</strong></td>
                <td width="7%"><strong>添加时间</strong></td>
                <td width="7%"><strong>发布时间</strong></td>
                <td width="7%"><strong>提审时间</strong></td>
                <td width="7%"><strong>提交时间</strong></td>
                <td width="7%"><strong>渠道</strong></td>
                <td width="9%"><strong>操作</strong></td>';
      break;

    case '1':
      $html = '<td width="7%"><strong>姓名</strong></td>
                <td width="7%"><strong>手机</strong></td>
                <td width="7%"><strong>微信号</strong></td>
                <td width="4%"><strong>二维码</strong></td>
                <td width="7%"><strong>QQ</strong></td>
                <td width="7%"><strong>地址</strong></td>
                <td width="7%"><strong>备注</strong></td>
                <td width="7%"><strong>状态</strong></td>
                <td width="7%"><strong>操作员</strong></td>
                <td width="7%"><strong>发布时间</strong></td>
                <td width="9%"><strong>操作</strong></td>';
      break;

    case '2':
      $html = '<td width="7%"><strong>姓名</strong></td>
                <td width="7%"><strong>手机</strong></td>
                <td width="7%"><strong>微信号</strong></td>
                <td width="4%"><strong>二维码</strong></td>
                <td width="7%"><strong>QQ</strong></td>
                <td width="7%"><strong>地址</strong></td>
                <td width="7%"><strong>状态</strong></td>
                <td width="7%"><strong>提审时间</strong></td>
                <td width="9%"><strong>操作</strong></td>';
      break;

    case '3':
      $html = '<td width="7%"><strong>姓名</strong></td>
                <td width="7%"><strong>手机</strong></td>
                <td width="7%"><strong>微信号</strong></td>
                <td width="4%"><strong>二维码</strong></td>
                <td width="7%"><strong>QQ</strong></td>
                <td width="7%"><strong>地址</strong></td>
                <td width="7%"><strong>备注</strong></td>
                <td width="7%"><strong>状态</strong></td>
                <td width="7%"><strong>提交时间</strong></td>
                <td width="9%"><strong>操作</strong></td>';
      break;
    
    default:
      break;
  }

  return $html;
}
  
function checktable_convert_status($status)
{
  switch ($status) {
    case '0':
      return "待审核";
      break;

    case '1':
      return "已审核";
      break;
  }

  return "";
}

?>

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