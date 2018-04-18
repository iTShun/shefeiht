<?php

require_once 'AppDatas.php';
require_once 'Medoo.php';
require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
// Using Medoo namespace
use Medoo\Medoo;

/*
总表操作功能：添加(上传)、查询、Excel导入/导出
*/
class TotalTable
{
	protected $database_table_name = 'total_table';

	public function __construct()
	{
		global $app;
		try {
		$sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName() . "`(
				   `u_id` bigint not null comment 'ID' primary key AUTO_INCREMENT,
				   `name` varchar(8) comment '名字',
				   `phone` varchar(20) not null comment '电话',
				   `addr` varchar(80) comment '地址',
				   `wechat` varchar(30) comment '微信号',
				   `status`  tinyint not null comment '状态',
				   `desc` varchar(32) comment '描述',
				   `source` varchar(8) comment '来源',
				   `time` datetime comment '时间'
				)AUTO_INCREMENT=0 ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			//InnoDB: 读少、写多。 MyISAM: 读多、写少。
			$app['db_database']->query( $sql )->fetchAll();
		}
		catch (PDOException $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function tableName()
	{
		return $this->database_table_name;
	}

	public function check($condition = null)
	{
		global $app;
		$datas = null;
		if ($condition)
			$datas = $app['db_database']->select($this->tableName(), '*', $condition);
		else
			$datas = $app['db_database']->select($this->tableName(), '*', array("ORDER" => array("u_id" => "ASC")));

		if ($datas && is_array($datas))
			return $datas;
		
		return null;
	}

	public function insert($datas)
	{
		global $app;
		$app['db_database']->insert($this->tableName(), $datas);
	}

	public function update($datas, $condition)
	{
		global $app;
		if ($condition)
			$app['db_database']->update($this->tableName(), $datas, $condition);
	}

	public function query($sql)
	{
		global $app;
		$app['db_database']->query( $sql )->fetchAll();
	}

	public function upload($files)
	{
		if (isset($files["file"]["name"]))
		{
			// 允许上传的文件后缀
			$allowedExts = array("xls", "xlsx");
			//默认路径
			$path = "../upload/";

			$fileName = pathinfo($files["file"]["name"], PATHINFO_BASENAME);
			$extension = strtolower(pathinfo($files["file"]["name"], PATHINFO_EXTENSION));

			if (in_array($extension, $allowedExts)
				&& $files["file"]["size"] < (1024 * 1024))   // 小于 1024 kb (1MB)
			{
				 if ($files["file"]["error"] > 0)
			    {
			       return "错误: " . $files["file"]["error"];
			    }
			    else
			    {
			    	// 判断当期目录下的 upload 目录是否存在该文件
			        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
			        if (!file_exists($path))
			        {
			        	//创建目录
			        	mkdir($path);
			        }

			        if (!file_exists($path . $files["file"]["name"]))
			        {
			            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
			            move_uploaded_file($files["file"]["tmp_name"], $path . $files["file"]["name"]);
			            return array('name'=>$fileName, 'ext'=>$extension, 'path'=>$path);
			        }
			        else
			        {
			            return '文件已经存在!';
			        }
			    }
			}
			else
			{
				return '未知文件格式!';
			}
		}

		return '上传文件失败!';
	}
	
	public function importExcel($files, $time, $source = 'NULL', $isWechat = false)
	{
		global $app;

		$filePath = $files['path'] . $files['name'];
		$objReader = null;
		$objPHPExcel = null;

		if($files['ext'] == 'xlsx')
		{
		    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
		    $objPHPExcel = $objReader->load($filePath,'utf-8');
		}elseif($files['ext'] == 'xls')
		{
		    $objReader = PHPExcel_IOFactory::createReader('Excel5');
		    $objPHPExcel = $objReader->load($filePath,'utf-8');
		}

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		$highestColumn = $sheet->getHighestColumn(); // 取得总列数

		for($j=1;$j<=$highestRow;$j++) 
		{
		    $str = '';
		    for ($k = 'A'; $k <= $highestColumn; $k++) 
		    {
		        $str .= $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue() . '\\';//读取单元格
		    }
		    $strs = explode("\\", $str);
		    $count = count($strs);
		   
		    switch ($count) 
		    {
		    	case 1:
		    	{
		    		if ($isWechat)
		    		{
		    			if (empty($strs[ 2 ]) || $this->check(array("wechat" => $strs[ 2 ])))
		    				break;

		    			$app['db_database']->insert($this->tableName(), array(
							"wechat" => $strs[ 2 ],
							"status" => 0,
							"source" => $source,
							"time" => $time
						));
		    		}
		    		else
		    		{
		    			if (empty($strs[ 1 ]) || strlen($strs[ 1 ]) != 11 || $this->check(array("phone" => $strs[ 1 ])))
		    				break;

		    			$app['db_database']->insert($this->tableName(), array(
							"phone" => $strs[ 1 ],
							"status" => 0,
							"source" => $source,
							"time" => $time
						));
		    		}
		    		
		    	}
		    		
		    		break;

		    	case 2:
		    	{
		    		if ($isWechat)
		    		{
		    			if (empty($strs[ 2 ]) || $this->check(array("wechat" => $strs[ 2 ])))
		    				break;

		    			$app['db_database']->insert($this->tableName(), array(
							"name" => $strs[ 0 ],
							"wechat" => $strs[ 2 ],
							"status" => 0,
							"source" => $source,
							"time" => $time
						));
		    		}
		    		else
		    		{
		    			if (empty($strs[ 1 ]) || strlen($strs[ 1 ]) != 11 || $this->check(array("phone" => $strs[ 1 ])))
		    				break;

		    			$app['db_database']->insert($this->tableName(), array(
							"name" => $strs[ 0 ],
							"phone" => $strs[ 1 ],
							"status" => 0,
							"source" => $source,
							"time" => $time
						));
		    		}
		    	}
		    		break;

		    	case 3:
		    	{
		    		if ((empty($strs[ 1 ]) && empty($strs[ 2 ])) || strlen($strs[ 1 ]) != 11 || $this->check(array("phone" => $strs[ 1 ], "wechat" => $strs[ 2 ])))
		    			break;

		    		$app['db_database']->insert($this->tableName(), array(
						"name" => $strs[ 0 ],
						"phone" => $strs[ 1 ],
						"wechat" => $strs[ 2 ],
						"status" => 0,
						"source" => $source,
						"time" => $time
					));
		    	}
		    		break;

		    	case 4:
		    	{
		    		if ((empty($strs[ 1 ]) && empty($strs[ 2 ])) || strlen($strs[ 1 ]) != 11 || $this->check(array("phone" => $strs[ 1 ], "wechat" => $strs[ 2 ])))
		    			break;

		    		$app['db_database']->insert($this->tableName(), array(
						"name" => $strs[ 0 ],
						"phone" => $strs[ 1 ],
						"wechat" => $strs[ 2 ],
						"addr" => $strs[ 3 ],
						"status" => 0,
						"source" => $source,
						"time" => $time
					));
		    	}
		    		break;
		    	
		    	default:
		    		break;
		    }
		}
	}

	public function exportExcel()
	{

	}

}

?>