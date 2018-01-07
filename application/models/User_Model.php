<?php
	class User_Model extends CI_Model 
	{
		function __construct()
		{
			
			parent::__construct();
			$this->load->database();
		}
		
		public function setMoneyPasswd($ary= array())
		{
			$sql ="UPDATE user SET u_money_passwd =md5(?) WHERE u_id =?";
			$bind = array(
				$ary['u_passwd'],
				$ary['u_id'],
			);
			$query = $this->db->query($sql, $bind);
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if($this->db->affected_rows()   == 0)
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>'无资料更新' ,
					'type' 		=>'db' ,
					'status'	=>'999'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			
			return 	true   ;	
		}
		
		
		public function setUserPasswd($ary= array())
		{
			$sql ="UPDATE user SET u_passwd =md5(?) WHERE u_id =?";
			$bind = array(
				$ary['u_passwd'],
				$ary['u_id'],
			);
			$query = $this->db->query($sql, $bind);
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if($this->db->affected_rows()   == 0)
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>'无资料更新' ,
					'type' 		=>'db' ,
					'status'	=>'999'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			
			return 	true   ;
		}
		
		public function getUserListBySuperiorId($u_superior_id)
		{
			$sql = "SELECT * , 'false' AS `show` FROM user WHERE u_superior_id = ?";
			$bind = array(
				$u_superior_id
			);
			$query = $this->db->query($sql, $bind);
			$rows = $query->result_array();
			$query->free_result();
			
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			$output = array(
				'list' =>$rows
			);
			return 	$output   ;
		}
		
		public function getUserByID($u_id)
		{
			$sql = "SELECT 
						u_name,
						u_account
					FROM user WHERE u_id = ?";
			$bind = array(
				$u_id
			);
			$query = $this->db->query($sql, $bind);
			$row = $query->row_array();
			$query->free_result();
			
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
	
			return 	$row   ;
		}
		
		public function getList($ary = array())
		{
			$where .=" WHERE 1 = 1";
			$gitignore = array(
				'limit',
				'p'
			);
			$limit = sprintf(" LIMIT %d, %d",abs($ary['p']-1)*$ary['limit'],$ary['limit']);
			if(is_array($ary))
			{
				foreach($ary as $key => $value)
				{
					if(in_array($key, $gitignore) || $value==='' )	
					{
						continue;
					}
					$where.=sprintf( " AND %s=?", $key);
					$bind[] = $value;
				}
			}
			$sql =" SELECT 
						u.u_id,
						u.u_superior_id,
						u.u_account,
						u2.u_account AS superior_account,
						'' AS nodes,
						'false' AS `show`,
						(SELECT COUNT(*)  FROM user u3 WHERE u.u_id = u3.u_superior_id) AS u_have_child
					FROM 
						user AS u LEFT JOIN  user u2 ON u.u_superior_id =  u2.u_id";
			$search_sql = $sql.$where.$limit ;
			$query = $this->db->query($search_sql, $bind);
			$rows = $query->result_array();
			
			$total_sql = sprintf("SELECT COUNT(*) AS total FROM(%s) AS t",$sql.$where) ;
			$query = $this->db->query($total_sql, $bind);
			$row = $query->row_array();
			
			$query->free_result();
			$output['list'] = $rows;
			$output['pageinfo']  = array(
				'total'	=>$row['total'],
				'pages'	=>ceil($row['total']/$ary['limit'])
			);
			
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			return 	$output  ;
			
		}
		
		public function accountIsExist($account)
		{
			
			$sql = "SELECT COUNT(*) as isExist FROM user WHERE u_account = ?";
			$bind = array(
				$account
			);
			$query = $this->db->query($sql, $bind);
			$row =  $query->row_array();
			$query->free_result();
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			return $row['isExist'];
		}
		
		public function insert($ary)
		{
			$sql="	INSERT INTO user(u_superior_id,u_name,u_account,u_passwd,u_add_datetime)
					VALUES(?,?,?,?,NOW())";
			$bind = array(
				$ary['superior_id'],
				$ary['u_name'],
				$ary['u_account'],
				$ary['u_passwd']
			);
			$query = $this->db->query($sql, $bind);
			$error = $this->db->error();
			if($error['message'] !="")
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
		}
		
	}
?>