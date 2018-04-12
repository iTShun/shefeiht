<!DOCTYPE HTML>
<html>
<head>
<title>奢妃后台</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />

<!-- font-awesome icons CSS-->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons CSS-->

 <!-- side nav css file -->
 <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
 <!-- side nav css file -->
 
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
 
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->

<!-- calendar -->
<link rel="stylesheet" type="text/css" href="datedropper.css">
<link rel="stylesheet" type="text/css" href="timedropper.min.css">
<!--//calendar -->

<?php
require_once 'php/control.php';

function getCookieUsername()
{
	global $app;
	if(isset($_COOKIE['username']) && !empty($_COOKIE['username']) && $app['user_total']->checkUserStatus($_COOKIE['username'], array(2, 3)))
	{
		return $_COOKIE['username'];
	}
	else
	{
		return "<script>window.location.href='index.php';</script>";
	}
}
function getCookieName()
{
	global $app;
	if(isset($_COOKIE['username']) && !empty($_COOKIE['username']))
	{
		$user_datas = $app['user_total']->check(array("username"=>$_COOKIE['username']));
		return $user_datas['name'];
	}
	else
	{
		return "<script>window.location.href='index.php';</script>";
	}
}
?>

<script>

	var updateField_c = {};

	function getUrlParam(name) {
    	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
       	var r = window.location.search.substr(1).match(reg);
       	if (r != null) return unescape(r[2]); return null;
    }

	function select_all(obj)
	{
		if (obj)
		{
			$("input[type='checkbox']").each(function() {  
                this.checked = obj.checked;
            });
		}
	}

	function select_search_status(status)
	{
		if (status == 1)
		{
			document.getElementsByName('search_start_pickdate')[0].value=getDate({ date:1, datesub:7 });
		}
		else
		{
			document.getElementsByName('search_start_pickdate')[0].value=getDate({ date:1, datesub:31 });
		}
		document.getElementsByName('search_start_picktime')[0].value="00:00";
	}

    function search_batchtable_new(index, datas = {})
	{
		var arr = { c_id:'getbatchtable', pos:'#headingOne', index:index, control:1 };

		if (datas.hasOwnProperty('g_id'))
			arr['g_id'] = datas['g_id'];
		
		$.post('php/control.php', arr, function(msg){
			if (msg == 0)
				alert('无内容!');
			else
			{
				//alert(JSON.stringify(msg.page));
				$("#batch_table_new_tbody").html(msg.html);
				$("#batch_table_new_tfoot").html(msg.page);
			}
		},
		'json');

		return true;
	}

	function search_batchtable(index, datas = {})
	{
		if (index == 0 &&
			!datas.hasOwnProperty('status') &&
			!datas.hasOwnProperty('start_date') &&
			!datas.hasOwnProperty('start_time') &&
			!datas.hasOwnProperty('end_date') &&
			!datas.hasOwnProperty('end_time'))
		{
			alert('请输入查询内容!');
			return false;
		}
		
		var arr = { c_id:'getbatchtable', pos:'#headingTwo', index:index, control:2 };
		
		if (datas.hasOwnProperty('status'))
			arr['status'] = datas['status'];

		if (datas.hasOwnProperty('start_date'))
			arr['start_date'] = datas['start_date'];

		if (datas.hasOwnProperty('start_time'))
			arr['start_time'] = datas['start_time'];

		if (datas.hasOwnProperty('end_date'))
			arr['end_date'] = datas['end_date'];

		if (datas.hasOwnProperty('end_time'))
			arr['end_time'] = datas['end_time'];
		
		$.post('php/control.php', arr, function(msg){
			if (msg == 0)
				alert('无查询内容!');
			else
			{
				//alert(JSON.stringify(msg));
				$("#batch_table_tbody").html(msg.html);
				$("#batch_table_tfoot").html(msg.page);
			}
		},
		'json');

		return true;
	}

	function page_table(page, datas = {})
	{
		var pageType = 0;

		if (datas.hasOwnProperty('pageType'))
			pageType = datas['pageType'];

		if (page == 1)
		{
			if (pageType == 2)
				search_batchtable(1, datas);
			else if (pageType == 3)
				search_batchtable_new(1, datas);
		}
		else
		{
			var table_show_num = <?php echo $app['table_show_count']; ?>;
			var index = (page - 1) * table_show_num + 1;

			if (pageType == 2)
				search_batchtable(index, datas);
			else if (pageType == 3)
				search_batchtable_new(index, datas);
		}
	}

	function updateField(obj)
	{
		if (obj)
		{
			alert(obj);
			var strs = obj.name.split('_');

			if (!updateField_c[ strs[0] ])
				updateField_c[ strs[0] ] = {};

			updateField_c[ strs[0] ][ strs[1] ] = obj.value;
		}
	}

	function update_batchtable()
	{
		if (JSON.stringify(updateField_c) == '{}')
		{
			alert('请输入修改内容!');
			return false;
		}
		
		var arr = { c_id:'updatebatchdatas', update:updateField_c };

		$.post('php/control.php', arr, function(msg){
			if (msg == 0)
				alert('修改内容失败!');
			else
			{
				alert(msg);
				page_table(1, { pageType:2, status:2 });
			}
		});
	}

	function fix(num, length) 
	{
		return ('' + num).length < length ? ((new Array(length + 1)).join('0') + num).slice(-length) : '' + num;
	}

	function getDate(datas = {})
	{
		var now = new Date();

		if (datas.hasOwnProperty('date'))
		{
			if (datas.hasOwnProperty('datesub'))
			{
				var date = now.getDate();
				if (date > datas['datesub'])
					date = date - datas['datesub'];
				else
					date = 1;
				return now.getFullYear() + "-" + fix((now.getMonth() + 1),2) + "-" + fix(date,2);
			}

			if (datas.hasOwnProperty('dateadd'))
				return now.getFullYear() + "-" + fix((now.getMonth() + 1),2) + "-" + fix((now.getDate() + datas['dateadd']),2);

			return now.getFullYear() + "-" + fix((now.getMonth() + 1),2) + "-" + fix(now.getDate(),2);
		}

		if (datas.hasOwnProperty('time'))
		{
			if (datas.hasOwnProperty('timesub'))
			{
				var hours = now.getHours();
				if (hours > datas['timesub'])
					hours = hours - datas['timesub'];
				else
					hours = 0;
				return fix(hours,2) + ":" + fix(now.getMinutes(),2);
			}

			if (datas.hasOwnProperty('timeadd'))
				return fix(now.getHours() + datas['timeadd'],2) + ":" + fix(now.getMinutes(),2);

			return fix(now.getHours(),2) + ":" + fix(now.getMinutes(),2);
		}

		return now.getFullYear() + "-" + fix((now.getMonth() + 1),2) + "-" + fix(now.getDate(),2) + "T" + fix(now.getHours(),2) + ":" + fix(now.getMinutes(),2);;
	}

	$(document).ready(function(){
		page_table(1, { pageType:3 });
		page_table(1, { pageType:2, status:2 });

		document.getElementsByName('search_start_pickdate')[0].value=getDate({ date:1, datesub:31 });
		document.getElementsByName('search_start_picktime')[0].value="00:00";
		document.getElementsByName('search_end_pickdate')[0].value=getDate({ date:1 });
		document.getElementsByName('search_end_picktime')[0].value="23:59";
	});

</script>

</head> 
<body class="cbp-spmenu-push" oncontextmenu=self.event.returnValue=false onselectstart="return false">
	<div class="main-content">
		<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
			<!--left-fixed -navigation-->
			<aside class="sidebar-left">
				<nav class="navbar navbar-inverse">
					<div class="navbar-header">
			            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
			            <span class="sr-only">Toggle navigation</span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            </button>
			            <h1><a class="navbar-brand" href="Normal.php"><span class="fa fa-area-chart"></span> 奢妃</a></h1>
			        </div>

			        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			        	<ul class="sidebar-menu">
			        		<li class="treeview">
				                <a href="#">
				                	<i class="fa fa-folder"></i>
				                	<span>资源</span>
				                	<i class="fa fa-angle-left pull-right"></i>
				                </a>
				                <ul class="treeview-menu">
				                	<li>
				                		<a id="batch_table" href="Normal.php">
				                			<i class="fa fa-angle-right"></i>
				                			<span>批次表</span>
				                		</a>
				                	</li>
				                </ul>
				            </li>
			        	</ul>
			        </div>
			        <!-- /.navbar-collapse -->
				</nav>
			</aside>
		</div>
		<!--left-fixed -navigation-->

		<!-- header-starts -->
		<div class="sticky-header header-section ">
			<div class="header-left">
				<!--toggle button start-->
				<button id="showLeftPush"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
			</div>
			<div class="header-right">
				<div class="profile_details">
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img"><img src="images/2.jpg" alt=""> </span> 
									<div class="user-name">
										<p id="name"><?php echo getCookieName(); ?></p>
										<span id="username"><?php echo getCookieUsername(); ?></span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>
								</div>
							</a>
							<ul class="dropdown-menu drp-mnu">
								<li> <a href="index.php"><i class="fa fa-sign-out"></i> 退出</a> </li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
		<!-- header-end -->

		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
				<h3 class="title1">批次表</h3>
				<div class="panel-group tool-tips widget-shadow" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
						  <h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								新增
							</a>
						  </h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						  	<div class="panel-body">
								<div class="panel-body">
							  		<form action="php/control.php?c_id=batchtablenew" method="post" enctype="multipart/form-data">
										<table class="table table-bordered table-striped no-margin grd_tble">
											<thead>
												<tr> <th>名字</th> <th>电话</th> <th>微信号</th> <th>状态</th> <th>描述</th> <th>时间</th> <th>操作<h5><input type="checkbox" onchange="select_all(this);" >一键操作</h5></th> </tr>
											</thead>

											<tbody id="batch_table_new_tbody">
												
											</tbody>
										</table>
										
							            <input type="submit" class="btn btn-primary" value="提交" />
							            <div id="batch_table_new_tfoot">
							            </div>
						        	</form>
								</div>
						  	</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
						  <h4 class="panel-title">
							<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								数据
							</a>
						  </h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
						  	<div class="panel-body">
						  		<form action="#">
									<select name="search_status" onchange="select_search_status(this.value);">
										<option value="2">开发中</option>
										<option value="1">待开发</option>
									</select>
									<input type="text" class="input" name="search_start_pickdate" id="search_start_pickdate" size="9" placeholder="开始日期" />
									<input type="text" class="input" name="search_start_picktime" id="search_start_picktime" size="4" placeholder="开始时间" />
									<span>-</span>
									<input type="text" class="input" name="search_end_pickdate" id="search_end_pickdate" size="9" placeholder="结束日期" />
									<input type="text" class="input" name="search_end_picktime" id="search_end_picktime" size="4" placeholder="结束时间" />
									<input type="button" class="btn btn-primary" onclick="search_batchtable(1, { status:search_status.value, start_date:search_start_pickdate.value, start_time:search_start_picktime.value, end_date:search_end_pickdate.value, end_time:search_end_picktime.value });" value="查询" />
								</form>
								<br/>
								<form action="#">
									<table class="table table-bordered table-striped no-margin grd_tble">
										<thead>
											<tr> <th>名字</th> <th>电话</th> <th>微信号</th> <th>状态</th> <th>描述</th> <th>时间</th> </tr>
										</thead>

										<tbody id="batch_table_tbody">
											
										</tbody>
									</table>
									<input type="button" class="btn btn-primary" onclick="update_batchtable();" value="提交" />
								</form>
								<div id="batch_table_tfoot">
					            </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- main content end-->
		
	   	<!--footer-->
		<div class="footer">
		   <p>技术支持：Shun</p>
		</div>
	    <!--//footer-->
	</div>

	<!-- side nav js -->
	<script src='js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
      $('.sidebar-menu').SidebarNav()
    </script>
	<!-- //side nav js -->
	
	<!-- Classie --><!-- for toggle left push menu script -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->
		
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.js"> </script>
	<!-- //Bootstrap Core JavaScript -->

	<!-- calendar -->
	<script src="js/datedropper.min.js"></script>
	<script src="js/timedropper.min.js"></script>
	<script>
	$("#search_start_pickdate").dateDropper({
		animate: false,
		format: 'Y-m-d',
		maxYear: '2050'
	});
	$("#search_start_picktime").timeDropper({
		meridians: false,
		format: 'HH:mm',
	});
	$("#search_end_pickdate").dateDropper({
		animate: false,
		format: 'Y-m-d',
		maxYear: '2050'
	});
	$("#search_end_picktime").timeDropper({
		meridians: false,
		format: 'HH:mm',
	});
	</script>
	<!-- //calendar -->
</body>
</html>