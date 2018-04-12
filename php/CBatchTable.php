<?php

require_once 'AppDatas.php';
require_once 'Medoo.php';
// Using Medoo namespace
use Medoo\Medoo;

/*
批次表操作功能：发布、查询
*/
class BatchTable
{
	protected $database_table_name = 'batch_table';

	public function __construct()
	{
		global $app;
		try {
		$sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName() . "`(
			   `u_id` bigint not null comment 'ID' primary key,
			   `g_id`  tinyint not null comment '组别',
			   `name` varchar(8) comment '名字',
			   `phone` varchar(20) not null comment '电话',
			   `wechat` varchar(30) comment '微信号',
			   `status`  tinyint not null comment '状态',
			   `control` varchar(32) comment '操作状态',
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
			$datas = $app['db_database']->select($this->tableName(), '*', array("ORDER" => array("time" => "DESC")));

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
}

?>