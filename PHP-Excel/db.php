<?php 
	require dirname(__FILE__)."/dbconfig.php";//引入配置文件

	class db{
		public $conn=null;

		public function __construct($config){//构造方法 实例化类时自动调用 
				$this->conn=mysql_connect($config['host'],$config['username'],$config['password']) or die(mysql_error());//连接数据库
				mysql_select_db($config['database'],$this->conn) or die(mysql_error());//选择数据库
				mysql_query("set names ".$config['charset']) or die(mysql_error());//设定mysql编码
		}
		/**
		**根据传入sql语句 查询mysql结果集
		**/
		public function getResult($sql){
			$resource=mysql_query($sql,$this->conn) or die(mysql_error());//查询sql语句
			$res=array();
			while(($row=mysql_fetch_assoc($resource))!=false){
				$res[]=$row;
			}
			return $res;
		}
		/**
		** 根据传入年级数 查询每个年级的学生数据
		**/
		public function getDataByGrade($grade){
			$sql="select username,score,class from user where grade=".$grade." order by score desc";
			$res=self::getResult($sql);
			return $res;
		}

		/**
		** 查询所有的年级
		**/
		public function getAllGrade(){
			$sql="select distinct(grade) from user  order by grade asc";
			$res=$this->getResult($sql);
			return $res;
		}

		/**
		**根据年级数查询所有的班级
		**/
		public function getClassByGrade($grade){
			$sql="select distinct(class) from user where grade=".$grade." order by class asc";
			$res=$this->getResult($sql);
			return $res;
		}

		/**
		**根据年级数班级数查询学生信息
		**/
		public function getDataByClassGrade($class,$grade){
			$sql="select username,score from user where class=".$class." and grade=".$grade." order by score desc";
			$res=$this->getResult($sql);
			return $res;
		}
	}
?>