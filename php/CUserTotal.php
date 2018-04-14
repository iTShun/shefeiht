<?php

require_once 'AppDatas.php';
require_once 'Medoo.php';
// Using Medoo namespace
use Medoo\Medoo;

/*
用户操作功能：登录核对-login、身份核对-checkUserStatus
*/
class UserTotal
{
	protected $database_table_name = 'user_total';

	public function __construct()
	{
		global $app;
		try {
			$sql = "CREATE TABLE IF NOT EXISTS `" . $this->tableName() . "`(
			   `u_id` bigint not null comment '用户ID' primary key AUTO_INCREMENT,
			   `g_id`  tinyint default NULL comment '用户组别',
			   `username` varchar(15) not null comment '用户名',
			   `password` varchar(32) not null comment '用户密码',
			   `name` varchar(8) comment '名字',
			   `status`  tinyint not null comment '用户身份'
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

	public function login($username, $password)
	{
		global $app;

		$data_datas = $app['db_database']->select($this->tableName(), "*", array("username" => $username));
		
		$datas = $app['hint']['isset']($data_datas, 0);
		
		if (is_array($datas) && $datas[ 'username' ] == $username)
		{
			if ($datas[ 'password' ] == md5($password))
			{
				foreach ($datas as $key => $value) 
				{
					$this->database_datas[$key] = $value;
				}
				return $datas;
			}
			else
			{
				$app['hint']['hint']('密码错误!');
				$app['hint']['back_up']();
			}
		}
		else
		{
			$app['hint']['hint']('用户名不存在!');
			$app['hint']['back_up']();
		}
		
		return null;
	}

	public function check($condition, $one = true)
	{
		global $app;

		$data_datas = $app['db_database']->select($this->tableName(), "*", $condition);
		
		$datas = $data_datas;
		if ($one)
			$datas = $app['hint']['isset']($data_datas, 0);

		if (is_array($datas))
			return $datas;

		return null;
	}

	public function checkUserStatus($username, $arr)
	{
		global $app;

		$datas = $this->check(array("username" => $username));

		if (is_array($datas))
		{
			for ($i=0; $i < count($arr); $i++) 
			{ 
				if ($arr[$i] == $datas['status'])
					return true;
			}

			return false;
		}

		return false;
	}

	public function getUserStatus($status)
	{
		switch ($status) {
			case '0':
				return 'Sysdba';
				break;

			case '1':
				return 'Admin';
				break;

			case '2':
				return 'Normal';
				break;

			case '3':
				return 'Office';
				break;
			
			default:
				return null;
				break;
		}
	}
}

?>