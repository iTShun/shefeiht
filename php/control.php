<?php

require_once 'AppDatas.php';
require_once 'Medoo.php';
require_once 'CUserTotal.php';
require_once 'CTotalTable.php';
require_once 'CBatchTable.php';
require_once 'CExploitTable.php';

// Using Medoo namespace
use Medoo\Medoo;

date_default_timezone_set('PRC');
date_default_timezone_set('Asia/Shanghai');

$app['user_total'] = new UserTotal();
$app['total_table'] = new TotalTable();
$app['batch_table'] = new BatchTable();
$app['exploit_table'] = new ExploitTable();

$c_id = $app['hint']['isset']($_GET, 'c_id');
$u_id = $app['hint']['isset']($_GET, 'u_id');
$addr = $app['hint']['isset']($_POST, 'addr');


if (is_null($c_id))
{
	$c_id = $app['hint']['isset']($_POST, 'c_id');
}

function batch_table_status_convert($status)
{
	switch ($status) {
		case '0':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="1">待开发</option>
										<option value="2">开发中</option>
										<option value="3">已开发</option>
										<option value="4">废弃</option>
									</select>';
			break;
		
		case '1':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="2">开发中</option>
										<option value="3">已开发</option>
										<option value="4">废弃</option>
										<option value="1">待开发</option>
									</select>';
			break;

		case '2':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="3">已开发</option>
										<option value="4">废弃</option>
										<option value="1">待开发</option>
										<option value="2">开发中</option>
									</select>';
			break;

		case '3':
			return '<select name="status_%u_id%" class="form-control2" onchange="updateField(this)">
										<option value="4">废弃</option>
										<option value="1">待开发</option>
										<option value="2">开发中</option>
										<option value="3">已开发</option>
									</select>';
			break;
	}
	return '';
}

function status_convert($status)
{
	switch ($status) {
		case '0':
			return '待开发';
			break;
		
		case '1':
			return '开发中';
			break;

		case '2':
			return '已开发';
			break;

		case '3':
			return '废弃';
			break;
	}
	return '0';
}

function source_convert($source)
{
	switch ($source) {
		case '1':
			return '百度';
			break;

		case '2':
			return '神马';
			break;

		case '3':
			return '360';
			break;

		case '4':
			return '其它';
			break;

		case '5':
			return '神马2';
			break;
	}
	return '0';
}

function control_convert($control, $str)
{
	if (strstr($control, $str))
		return '是';
	return '否';
}

function pagination($curPage, $tableSize, $postion, $pageMax, $append = '{}')
{
	global $app;

	$pageHtml = '<ul class="pagination pagination-sm">';

	$temp = '<li class="%status%" onclick="page_table(%page%,%append%);"><a href="%pos%">%sign%</a></li>';
	$ec = array("%status%", "%page%", "%sign%", "%pos%", "%append%");

	if ($pageMax == 1)
	{
		$pageHtml = $pageHtml . '<li class="disabled"><a>«</a></li>';
		$pageHtml = $pageHtml . '<li class="disabled"><a>‹</a></li>';

		$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion, $append), $temp);

		$pageHtml = $pageHtml . '<li class="disabled"><a>›</a></li>';
		$pageHtml = $pageHtml . '<li class="disabled"><a>»</a></li>';
	}
	else if ($pageMax == 2)
	{
		if ($curPage == 1)
		{
			$pageHtml = $pageHtml . '<li class="disabled"><a>«</a></li>';
			$pageHtml = $pageHtml . '<li class="disabled"><a>‹</a></li>';

			$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, $curPage + 1, $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, "›", $postion, $append), $temp);
			$pageHtml = $pageHtml . str_replace($ec, array('', $pageMax, "»", $postion, $append), $temp);
		}
		else
		{
			$pageHtml = $pageHtml . str_replace($ec, array('', 1, "«", $postion, $append), $temp);
			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, "‹", $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, $curPage - 1, $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion, $append), $temp);

			$pageHtml = $pageHtml . '<li class="disabled"><a>›</a></li>';
			$pageHtml = $pageHtml . '<li class="disabled"><a>»</a></li>';
		}
	}
	else if ($pageMax >= 3)
	{
		if ($curPage == 1)
		{
			$pageHtml = $pageHtml . '<li class="disabled"><a>«</a></li>';
			$pageHtml = $pageHtml . '<li class="disabled"><a>‹</a></li>';

			$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, $curPage + 1, $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 2, $curPage + 2, $postion, $append), $temp);

			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, "›", $postion, $append), $temp);
			$pageHtml = $pageHtml . str_replace($ec, array('', $pageMax, "»", $postion, $append), $temp);
		}
		else
		{
			$pageHtml = $pageHtml . str_replace($ec, array('', 1, "«", $postion, $append), $temp);
			$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, "‹", $postion, $append), $temp);

			if ($curPage == $pageMax)
			{
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 2, $curPage - 2, $postion, $append), $temp);

				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, $curPage - 1, $postion, $append), $temp);

				$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion, $append), $temp);

				$pageHtml = $pageHtml . '<li class="disabled"><a>›</a></li>';
				$pageHtml = $pageHtml . '<li class="disabled"><a>»</a></li>';
			}
			else
			{
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage - 1, $curPage - 1, $postion, $append), $temp);
				
				$pageHtml = $pageHtml . str_replace($ec, array('active', $curPage, $curPage, $postion, $append), $temp);
				
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, $curPage + 1, $postion, $append), $temp);
			
				$pageHtml = $pageHtml . str_replace($ec, array('', $curPage + 1, "›", $postion, $append), $temp);
				$pageHtml = $pageHtml . str_replace($ec, array('', $pageMax, "»", $postion, $append), $temp);
			}
		}
	}

	$pageHtml = $pageHtml . ' &ensp;<input type="number" min="1" max="' . $pageMax . '" value="' . $curPage . '" onchange="page_table(this.value,' . $append . ');" >页</ul><br/><h5><span>总: ' . $tableSize . '行/' . $pageMax . '页</span></h5>';

	return $pageHtml;
}

switch ($c_id) 
{
	case 'logining':
		{
			if (isset($_POST['username']) && isset($_POST['password']))
			{
				$datas = $app['user_total']->login($_POST['username'], $_POST['password'], $app);

				if (is_array($datas))
				{
					if (isset($_POST['checkbox']))
					{
						session_start();
						setcookie('username',$datas[ 'username' ],time()+3600*24*7,"/");
						setcookie('password',$_POST['password'],time()+3600*24*7,"/");
					}
					else
					{
						setcookie('username',$datas[ 'username' ],time()+3600*24*7,"/");
						setCookie('password','',time()-10);
					}

					if ($app['user_total']->getUserStatus($datas['status']) == 'Normal')
					{
						$app['hint']['goto']('../Normal.php');
					}
					else if ($app['user_total']->getUserStatus($datas['status']) == 'Office')
					{
						$app['hint']['goto']('../ShowTime.php');
					}
					else
					{
						$app['hint']['goto']('../Admin.php');
					}
				}
			}
			else
				$app['hint']['back_up']();
		}
		break;

	case 'totaltableadd':
		{
			if (isset($_POST['table_source']))
			{
				$source = $_POST['table_source'];
				$time = isset($_POST['table_time']) ? str_replace('T', ' ', $_POST['table_time']) : date("Y-m-d H:i:s");

				if (isset($_POST['table_add_wechat']) && !empty($_POST['table_add_wechat']))
				{
					if (!$app['total_table']->check(array("wechat" => $_POST['table_add_wechat'])))
					{
						$name = $app['hint']['isset']($_POST, 'table_add_name');
						$phone = isset($_POST['table_add_phone']) ? $_POST['table_add_phone'] : '';

						$app['total_table']->insert(array(
							"name" => $name, 
							"phone" => $phone,
							"addr" => '',
							"wechat" => $_POST['table_add_wechat'], 
							"source" => source_convert($source),
							"time" => $time));

						$app['hint']['hint']('添加成功!');
						$app['hint']['back_up']();
					}
					else
					{
						$app['hint']['hint']('内容已存在!');
						$app['hint']['back_up']();
					}
				}
				else if (isset($_POST['table_add_phone']) && !empty($_POST['table_add_phone']))
				{
					$name = $app['hint']['isset']($_POST, 'table_add_name');
					$datas = $app['total_table']->check(array("phone" => $_POST['table_add_phone']));

					if (!$app['total_table']->check(array("phone" => $_POST['table_add_phone'])))
					{
						$wechat = isset($_POST['table_add_wechat']) ? $_POST['table_add_wechat'] : '';

						$app['total_table']->insert(array(
							"name" => $name, 
							"phone" => $_POST['table_add_phone'], 
							"addr" => '',
							"wechat" => $wechat,
							"source" => source_convert($source),
							"time" => $time));

						$app['hint']['hint']('添加成功!');
						$app['hint']['back_up']();
					}
					else
					{
						$app['hint']['hint']('内容已存在!');
						$app['hint']['back_up']();
					}
				}
				else if (isset($_FILES["file"]["name"]))
				{
					if (empty($_FILES["file"]["name"]))
					{
						$app['hint']['hint']('请输入添加文件!');
						$app['hint']['back_up']();
					}
					else
					{
						$file_datas = $app['total_table']->upload($_FILES);
						if (is_array($file_datas))
						{
							$isWechat = $app['hint']['isset']($_POST, 'iswechat');

							$app['total_table']->importExcel($file_datas, $time, source_convert($source), $isWechat);

							$data_datas = $app['total_table']->check();
							$nums = 0;
							if (is_array($data_datas))
								$nums = count($data_datas);

							$temp = '%number%-%source%.%ext%';
							$ec = array('%number%', '%source%', '%ext%');

							$newFileName = iconv('UTF-8', 'GB18030', str_replace($ec, array($nums, source_convert($source), $file_datas['ext']), $temp));

							rename($file_datas['path'] . $_FILES["file"]["name"], $file_datas['path'] . $newFileName);

							$app['hint']['hint']('添加成功!');
							$app['hint']['back_up']();
						}
						else
						{
							$app['hint']['hint']($file_datas);
							$app['hint']['back_up']();
						}
					}
				}
			}
		}
		break;

	case 'gettotaltable':
		{
			if (isset($_POST['index']))
			{
				$table_show_num = $app['table_show_count'];

				$index = $_POST['index'] - 1;
				$search_datas = null;
				
				$u_id = $app['hint']['isset']($_POST, 'u_id');
				$name = $app['hint']['isset']($_POST, 'name');
				$phone = $app['hint']['isset']($_POST, 'phone');
				$wechat = $app['hint']['isset']($_POST, 'wechat');
				$status = $app['hint']['isset']($_POST, 'status');
				$source = $app['hint']['isset']($_POST, 'source');
				$start_date = $app['hint']['isset']($_POST, 'start_date');
				$start_time = $app['hint']['isset']($_POST, 'start_time');
				$end_date = $app['hint']['isset']($_POST, 'end_date');
				$end_time = $app['hint']['isset']($_POST, 'end_time');
				$append = '{';

				$isBatch = $app['hint']['isset']($_POST, 'batch');

				$selectArr = null;

				if ($isBatch)
				{
					if (is_null($selectArr))
						$selectArr = array();

					$selectArr['status'] = 0;
					$selectArr['ORDER'] = array("u_id" => "DESC");
				}

				if ($status && $status != -1)
				{
					if ($append == '{')
						$append = $append . 'status:' . $status;
					else
						$append = $append . ',status:' . $status;

					if (is_null($selectArr))
						$selectArr = array();

					if ($isBatch && !isset($selectArr['ORDER']))
						$selectArr['ORDER'] = array("u_id" => "DESC");

					$selectArr['status'] = ($status - 1);
				}

				if ($source && $source != -1)
				{
					if ($append == '{')
						$append = $append . 'source:' . $source;
					else
						$append = $append . ',source:' . $source;

					if (is_null($selectArr))
						$selectArr = array();

					if ($isBatch && !isset($selectArr['ORDER']))
						$selectArr['ORDER'] = array("u_id" => "DESC");

					$selectArr['source'] = source_convert($source);
				}

				if ($start_date && $start_time)
				{
					if ($append == '{')
					{
						$append = $append . "start_date:'" . $start_date . "'";
						$append = $append . ",start_time:'" . $start_time . "'";
					}
					else
					{
						$append = $append . ",start_date:'" . $start_date . "'";
						$append = $append . ",start_time:'" . $start_time . "'";
					}

					if (is_null($selectArr))
						$selectArr = array();

					$selectArr['time[>=]'] = $start_date . ' ' . $start_time;
				}

				if ($end_date && $end_time)
				{
					if ($append == '{')
					{
						$append = $append . "end_date:'" . $end_date . "'";
						$append = $append . ",end_time:'" . $end_time . "'";
					}
					else
					{
						$append = $append . ",end_date:'" . $end_date . "'";
						$append = $append . ",end_time:'" . $end_time . "'";
					}

					if (is_null($selectArr))
						$selectArr = array();

					$selectArr['time[<=]'] = $end_date . ' ' . $end_time;
				}

				$data_datas = null;

				if (is_null($selectArr))
					$data_datas = $app['total_table']->check();
				else if (is_array($selectArr))
					$data_datas = $app['total_table']->check($selectArr);

				if ($append == '{')
					$append = $append . 'pageType:1 }';
				else
					$append = $append . ', pageType:1 }';

				if ($u_id)
					$search_datas = $app['total_table']->check(array('u_id'=>$u_id));
				else if ($name)
					$search_datas = $app['total_table']->check(array('name'=>$name));
				else if ($phone)
					$search_datas = $app['total_table']->check(array('phone'=>$phone));
				else if ($wechat)
					$search_datas = $app['total_table']->check(array('wechat'=>$wechat));
				
				$failoverIndex = 0;
				$dbSize = count($data_datas);

				if (is_array($search_datas))
				{
					$arr = $app['hint']['in_array']($search_datas[0]['u_id'], $data_datas);

					if (is_array($arr))
						$index = $arr[0];
					else
						$index =0;

					$failoverIndex = $index % $table_show_num;
				}

				if ($index < 0 || $index > $dbSize)
					$index = 0;

				$resultDatas = array();
				$resultDatas['html'] = '';
				$resultDatas['page'] = '';

				$temp = '<td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_addr%</td> <td>%u_desc%</td> <td>%u_source%</td> <td>%u_time%</td>';
				$ec = array('%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_addr%', '%u_desc%', '%u_source%', '%u_time%');

				if ($isBatch)
				{
					$temp = '<td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_control%</td> <td>%u_source%</td> <td>%u_time%</td> <td>%c_name%</td>';
					$ec = array('%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_control%', '%u_source%', '%u_time%', '%c_name%');
				}

				for ($i=$index, $j=0; $j < $table_show_num - $failoverIndex; $i++, $j++)
				{
					if (isset($data_datas[$i]) && is_array($data_datas[$i]))
					{
						$datas = $data_datas[$i];

						$resultDatas['html'] = $resultDatas['html'] . '<tr>';

						if ($isBatch)
						{
							$batch_datas = $app['batch_table']->check(array('u_id' => $datas['u_id']), 'control');
							$control = $app['hint']['isset']($batch_datas, 0);
							if ($datas['status'] == 0)
							{
								$c_name = '<input type="checkbox" name="' . $datas['u_id'] . '">&nbsp;添加';
								$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], status_convert($datas['status']), $control, $datas['source'], $datas['time'], $c_name), $temp);
							}
							else
							{
								$c_name = '已添加';

								$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], status_convert($datas['status']), $control, $datas['source'], $datas['time'], $c_name), $temp);
							}
						}
						else
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], status_convert($datas['status']), $datas['addr'], $datas['desc'], $datas['source'], $datas['time']), $temp);
						}

						$resultDatas['html'] = $resultDatas['html'] . '</tr> ';
					}
				}

				$curPage = ($index / $table_show_num + 1) | 0;
				if ($curPage <= 0)
					$curPage = 1;

				$pos = isset($_POST['pos']) ? $_POST['pos'] : '';
				$pageMax = ceil($dbSize / $table_show_num);

				$resultDatas['page'] = pagination($curPage, $dbSize, $pos, $pageMax, $append);
				
				echo json_encode($resultDatas);
			}
		}
		break;

	case 'getgroupshtml':
		{
			$data_datas = $app['user_total']->check(array("g_id[!]"=>NULL), false);
			$size = count($data_datas);

			$temp = '<option value="%value%">%show%</option>';
			$ec = array('%value%', '%show%');
			$html = '<option value="0">未选择</option>';

			if (isset($_POST['c_id']))
				$html = '<option value="-1">全部</option>';

			for ($i=0; $i < $size; $i++) 
			{ 
				$datas = $data_datas[$i];

				$html = $html . str_replace($ec, array($datas['g_id'], $datas['g_id'] . '-' . $datas['name']), $temp);
			}

			echo $html;
		}
		break;

	case 'batchtableadd':
		{
			if (isset($_POST['g_id']))
			{
				$g_id = $_POST['g_id'];

				if ($g_id != 0)
				{
					$time = date("Y-m-d H:i:s");
					foreach ($_POST as $key => $value) 
					{
						if ($key != 'g_id')
						{
							$datas = $app['total_table']->check(array("u_id" => $key));
							if ($datas && is_array($datas))
							{
								$app['total_table']->update(array("status" => 1), array("u_id" => $key));
								$app['batch_table']->insert(array(
										'u_id' => $datas[0]['u_id'],
										'g_id' => $g_id,
										'name' => $datas[0]['name'],
										'phone' => $datas[0]['phone'],
										'wechat' => $datas[0]['wechat'],
										'status' => 0,
										'control' => '',
										'desc' => $datas[0]['desc'],
										'source' => $datas[0]['source'],
										'time' => $time
									));
							}
						}
					}
					$app['hint']['hint']('发布成功!');
					$app['hint']['back_up']();
				}
				else
				{
					$app['hint']['hint']('请选择组别!');
					$app['hint']['back_up']();
				}
			}
		}
		break;

	case 'getbatchtable':
		{
			if (isset($_POST['index']))
			{
				$table_show_num = $app['table_show_count'];

				$index = $_POST['index'] - 1;
				$g_id = $app['hint']['isset']($_POST, 'g_id');
				$status = $app['hint']['isset']($_POST, 'status');
				$source = $app['hint']['isset']($_POST, 'source');
				$start_date = $app['hint']['isset']($_POST, 'start_date');
				$start_time = $app['hint']['isset']($_POST, 'start_time');
				$end_date = $app['hint']['isset']($_POST, 'end_date');
				$end_time = $app['hint']['isset']($_POST, 'end_time');
				$control = $app['hint']['isset']($_POST, 'control');

				$selectArr = array();
				$append = '{';

				$selectArr['ORDER'] = array("time" => "DESC");

				if ($control)
				{
					if (!$g_id)
					{
						$user_datas = $app['user_total']->check(array("username" => $_COOKIE['username']));

						if (is_array($user_datas) && isset($user_datas['status']) && $user_datas['status'] > 1)
						{
							$g_id = $user_datas['g_id'];
						}
					}

					if ($control == 1)
						$selectArr['control'] = '';
					else if ($control == 2)
						$selectArr['control[!]'] = '';
				}

				if ($g_id && $g_id != -1)
				{
					if ($append == '{')
						$append = $append . 'g_id:' . $g_id;
					else
						$append = $append . ',g_id:' . $g_id;

					$selectArr['g_id'] = $g_id;
				}

				if ($status && $status != -1)
				{
					if ($append == '{')
						$append = $append . 'status:' . $status;
					else
						$append = $append . ',status:' . $status;
					
					$selectArr['status'] = ($status - 1);
				}

				if ($source && $source != -1)
				{
					if ($append == '{')
						$append = $append . 'source:' . $source;
					else
						$append = $append . ',source:' . $source;

					$selectArr['source'] = source_convert($source);
				}

				if ($start_date && $start_time)
				{
					if ($status && ($status-1) == 0 && $control == 2)
					{
						//待开发时间限制7天
						$start_date = date('Y-m-d', strtotime('-7 days'));
					}

					if ($append == '{')
					{
						$append = $append . "start_date:'" . $start_date . "'";
						$append = $append . ",start_time:'" . $start_time . "'";
					}
					else
					{
						$append = $append . ",start_date:'" . $start_date . "'";
						$append = $append . ",start_time:'" . $start_time . "'";
					}

					$selectArr['time[>=]'] = $start_date . ' ' . $start_time;
				}

				if ($end_date && $end_time)
				{
					if ($append == '{')
					{
						$append = $append . "end_date:'" . $end_date . "'";
						$append = $append . ",end_time:'" . $end_time . "'";
					}
					else
					{
						$append = $append . ",end_date:'" . $end_date . "'";
						$append = $append . ",end_time:'" . $end_time . "'";
					}

					$selectArr['time[<=]'] = $end_date . ' ' . $end_time;
				}

				if ($control && $control == 1)
				{
					if ($append == '{')
						$append = $append . 'pageType:3 }';
					else
						$append = $append . ', pageType:3 }';
				}
				else
				{
					if ($append == '{')
						$append = $append . 'pageType:2 }';
					else
						$append = $append . ', pageType:2 }';
				}
				
				$data_datas = $app['batch_table']->check($selectArr);
				$dbSize = count($data_datas);
				if ($index < 0 || $index > $dbSize)
					$index = 0;
				
				$resultDatas = array();
				$resultDatas['html'] = '';
				$resultDatas['page'] = '';

				$temp = '<td>%u_gid%</td> <td>%u_id%</td> <td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_desc%</td> <td>%u_control%</td> <td>%u_source%</td> <td>%u_time%</td>';
				$ec = array('%u_gid%', '%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_control%', '%u_source%', '%u_time%');

				if ($control && $control == 1)
				{
					$temp = '<td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_status%</td> <td>%u_desc%</td> <td>%u_time%</td>';
					$ec = array('%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_time%');
				}
				else if ($control && $control == 2)
				{
					$temp = '<td>%u_name%</td> <td>%u_phone%</td> <td><input type="text" name="wechat_%u_id%"  value="%u_wechat%" onchange="updateField(this)" placeholder="微信号"/></td> <td>%u_status%</td> <td><input type="text" name="desc_%u_id%"  value="%u_desc%" onchange="updateField(this)" placeholder="描述"/></td> <td>%u_time%</td>';
					$ec = array('%u_name%', '%u_phone%', '%u_wechat%', '%u_status%', '%u_desc%', '%u_time%', '%u_id%');
				}

				for ($i=$index, $j=0; $j < $table_show_num; $i++, $j++)
				{
					if (isset($data_datas[$i]) && is_array($data_datas[$i]))
					{
						$datas = $data_datas[$i];
						

						$resultDatas['html'] = $resultDatas['html'] . '<tr>';
						
						if ($control)
						{
							if ($control == 1)
							{
								$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['name'], $datas['phone'], $datas['wechat'], status_convert($datas['status']), $datas['desc'], $datas['time']), $temp) . '<td><input type="checkbox" name="' . $datas['u_id'] . '">&nbsp;已操作</td>';
							}
							else if ($control == 2)
							{
								$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['name'], $datas['phone'], $datas['wechat'], batch_table_status_convert($datas['status']), $datas['desc'], $datas['time'], $datas['u_id']), $temp);
							}
						}
						else
						{
							$user_datas = $app['user_total']->check(array("g_id" => $datas['g_id']));

							$name = '';
							if ($user_datas && is_array($user_datas))
								$name = '-' . $user_datas['name'];

							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['g_id'] . $name, $datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], status_convert($datas['status']), $datas['desc'], control_convert($datas['control'], $datas['g_id']), $datas['source'], $datas['time']), $temp);
						}

						$resultDatas['html'] = $resultDatas['html'] . '</tr> ';
					}
				}

				$curPage = ($index / $table_show_num + 1) | 0;
				if ($curPage <= 0)
					$curPage = 1;

				$pos = isset($_POST['pos']) ? $_POST['pos'] : '';
				$pageMax = ceil($dbSize / $table_show_num);

				$resultDatas['page'] = pagination($curPage, $dbSize, $pos, $pageMax, $append);
				
				echo json_encode($resultDatas);
			}
		}
		break;

	case 'batchtablenew':
		{
			$result = '修改失败!';
			$user_datas = $app['user_total']->check(array("username" => $_COOKIE['username']));

			if (is_array($user_datas) && isset($user_datas['g_id']))
			{
				foreach ($_POST as $key=>$value) 
				{
					if ($key)
					{
						$datas = $app['batch_table']->check(array("u_id" => $key));
						
						if ($datas && is_array($datas) && isset($datas[0]))
						{
							$control = $datas[0]['control'];
							if ($control == '')
								$app['batch_table']->update(array("control" => $user_datas['g_id'], 'status' => 1), array("u_id" => $key));
							else
								$app['batch_table']->update(array("control" => ',' . $user_datas['g_id'], 'status' => 1), array("u_id" => $key));
						}
					}
				}
				$result = '修改成功!';
			}

			$app['hint']['hint']($result);
			$app['hint']['back_up']();
		}
		break;

	case 'updatebatchdatas':
		{
			if (isset($_POST['update']))
			{
				$tableName = "batch_table";
				$wechat = $app['hint']['isset']($_POST['update'], 'wechat');
				$status = $app['hint']['isset']($_POST['update'], 'status');
				$desc = $app['hint']['isset']($_POST['update'], 'desc');

				$result = '';

				if ($wechat && is_array($wechat))
				{
					foreach ($wechat as $key => $value) 
					{
						$app['batch_table']->update(array("wechat" => $value), array("u_id" => $key));
					}

					$result = '修改内容成功!';
				}

				if ($status && is_array($status))
				{
					$time = date("Y-m-d H:i:s");
					foreach ($status as $key => $value) 
					{
						$app['batch_table']->update(array("status" => ($value - 1)), array("u_id" => $key));

						$app['total_table']->update(array("status" => ($value - 1)), array("u_id" => $key));

						if (($value - 1) == 2)
						{
							$batch_datas = $app['batch_table']->check(array("u_id" => $key));

							if (is_array($batch_datas) && isset($batch_datas[0]))
							{
								//已开发
								$app['exploit_table']->insert(array(
									'u_id' => $batch_datas[0]['u_id'],
									'g_id' => $batch_datas[0]['g_id'],
									'e_name' => '',
									'name' => $batch_datas[0]['name'],
									'phone' => $batch_datas[0]['phone'],
									'wechat' => $batch_datas[0]['wechat'],
									'addr' => '',
									'control' => $batch_datas[0]['control'],
									'source' => $batch_datas[0]['source'],
									'time' => $time
								));
							}
							
						}
					}

					$result = '修改内容成功!';
				}

				if ($desc && is_array($desc))
				{
					foreach ($desc as $key => $value) 
					{
						$app['batch_table']->update(array("desc" => $value), array("u_id" => $key));
					}

					$result = '修改内容成功!';
				}

				echo $result;
			}
		}
		break;

	case 'getexploittable':
		{
			if (isset($_POST['index']))
			{
				$table_show_num = $app['table_show_count'];

				$index = $_POST['index'] - 1;
				$search_datas = null;
				
				$u_id = $app['hint']['isset']($_POST, 'u_id');
				$name = $app['hint']['isset']($_POST, 'name');
				$phone = $app['hint']['isset']($_POST, 'phone');
				$wechat = $app['hint']['isset']($_POST, 'wechat');
				$source = $app['hint']['isset']($_POST, 'source');
				$start_date = $app['hint']['isset']($_POST, 'start_date');
				$start_time = $app['hint']['isset']($_POST, 'start_time');
				$end_date = $app['hint']['isset']($_POST, 'end_date');
				$end_time = $app['hint']['isset']($_POST, 'end_time');
				$control = $app['hint']['isset']($_POST, 'control');
				$append = '{';

				$selectArr = array();

				$selectArr['ORDER'] = array("time" => "DESC");

				if ($source && $source != -1)
				{
					if ($append == '{')
						$append = $append . 'source:' . $source;
					else
						$append = $append . ',source:' . $source;

					$selectArr['source'] = source_convert($source);
				}

				if ($start_date && $start_time)
				{
					if ($append == '{')
					{
						$append = $append . "start_date:'" . $start_date . "'";
						$append = $append . ",start_time:'" . $start_time . "'";
					}
					else
					{
						$append = $append . ",start_date:'" . $start_date . "'";
						$append = $append . ",start_time:'" . $start_time . "'";
					}

					$selectArr['time[>=]'] = $start_date . ' ' . $start_time;
				}

				if ($end_date && $end_time)
				{
					if ($append == '{')
					{
						$append = $append . "end_date:'" . $end_date . "'";
						$append = $append . ",end_time:'" . $end_time . "'";
					}
					else
					{
						$append = $append . ",end_date:'" . $end_date . "'";
						$append = $append . ",end_time:'" . $end_time . "'";
					}

					$selectArr['time[<=]'] = $end_date . ' ' . $end_time;
				}

				$data_datas = $app['exploit_table']->check($selectArr);

				if ($append == '{')
					$append = $append . 'pageType:4 }';
				else
					$append = $append . ', pageType:4 }';

				if ($u_id)
					$search_datas = $app['exploit_table']->check(array('u_id'=>$u_id));
				else if ($name)
					$search_datas = $app['exploit_table']->check(array('name'=>$name));
				else if ($phone)
					$search_datas = $app['exploit_table']->check(array('phone'=>$phone));
				else if ($wechat)
					$search_datas = $app['exploit_table']->check(array('wechat'=>$wechat));
				
				$failoverIndex = 0;
				$dbSize = count($data_datas);

				if (is_array($search_datas))
				{
					$arr = $app['hint']['in_array']($search_datas[0]['u_id'], $data_datas);

					if (is_array($arr))
						$index = $arr[0];
					else
						$index =0;

					$failoverIndex = $index % $table_show_num;
				}

				if ($index < 0 || $index > $dbSize)
					$index = 0;

				$resultDatas = array();
				$resultDatas['html'] = '';
				$resultDatas['page'] = '';

				$temp = '<td><input type="text" name="ename_%u_id%"  value="%u_ename%" size="8" onchange="updateField(this)" placeholder="员工姓名"/></td> <td>%u_id%</td> <td>%u_name%</td> <td><input type="text" name="phone_%u_id%"  value="%u_phone%" size="11" onchange="updateField(this)" placeholder="电话"/></td> <td><input type="text" name="wechat_%u_id%"  value="%u_wechat%" size="11" onchange="updateField(this)" placeholder="微信号"/></td> <td><input type="text" name="addr_%u_id%"  value="%u_addr%" onchange="updateField(this)" placeholder="地址"/></td> <td>%u_desc%</td> <td>%u_source%</td> <td>%u_time%</td>';
				$ec = array('%u_ename%', '%u_id%', '%u_name%', '%u_phone%', '%u_wechat%', '%u_addr%', '%u_desc%', '%u_source%', '%u_time%');

				if ($control && $control == 1)
				{
					$temp = '<td>%u_name%</td> <td>%u_phone%</td> <td>%u_wechat%</td> <td>%u_addr%</td> <td>%u_time%</td>';
					$ec = array('%u_name%', '%u_phone%', '%u_wechat%', '%u_addr%', '%u_time%');
				}

				for ($i=$index, $j=0; $j < $table_show_num - $failoverIndex; $i++, $j++)
				{
					if (isset($data_datas[$i]) && is_array($data_datas[$i]))
					{
						$datas = $data_datas[$i];

						$resultDatas['html'] = $resultDatas['html'] . '<tr>';

						if ($control && $control == 1)
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['name'], $datas['phone'], $datas['wechat'], $datas['addr'], $datas['time']), $temp);
						}
						else
						{
							$resultDatas['html'] = $resultDatas['html'] . str_replace($ec, array($datas['e_name'], $datas['u_id'], $datas['name'], $datas['phone'], $datas['wechat'], $datas['addr'], count(explode(',', $datas['control'])), $datas['source'], $datas['time']), $temp);
						}
						
						$resultDatas['html'] = $resultDatas['html'] . '</tr> ';
					}
				}

				$curPage = ($index / $table_show_num + 1) | 0;
				if ($curPage <= 0)
					$curPage = 1;

				$pos = isset($_POST['pos']) ? $_POST['pos'] : '';
				$pageMax = ceil($dbSize / $table_show_num);

				$resultDatas['page'] = pagination($curPage, $dbSize, $pos, $pageMax, $append);
				
				echo json_encode($resultDatas);
			}
		}
		break;

	case 'updateexploittable':
		{
			$result = '修改失败';

			if (isset($_POST['u_id']))
			{
				$u_id = $_POST['u_id'];
				$key = $app['hint']['isset']($_POST, 'key');
				$value = $app['hint']['isset']($_POST, 'value');

				if ($key && $value)
				{
					$data_datas = $app['exploit_table']->check(array('u_id'=>$u_id));

					if (is_array($data_datas))
					{
						if ($key == 'ename')
						{
							$key = 'e_name';
							$value = $data_datas[0]['g_id'] . '-' . $value;
						}

						$app['exploit_table']->update(array($key => $value), array('u_id'=>$u_id));

						$result = '修改成功';
					}
				}
			}

			echo $result;
		}
		break;
	
	default:
		break;
}

?>