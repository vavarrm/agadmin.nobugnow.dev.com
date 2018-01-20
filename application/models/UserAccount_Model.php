<?php
	class UserAccount_Model extends CI_Model 
	{
		function __construct()
		{
			
			parent::__construct();
			$this->load->database();
		}
		
		public function getBalance($u_id)
		{
			try 
			{
				
				$sql ="SELECT IFNULL(SUM(ua_value),0) as balance FROM user_account WHERE ua_u_id =? AND ua_status = 'allowed'";
				$bind = array(
					$uid
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
				$row =  $query->row_array();
				$query->free_result();
				return $row;
				
			}catch(MyException $e)
			{
				throw $e;
			}
		}
		
		public function withdrawal($ary)
		{
			try 
			{
				
				$balance = $this->getBalance($ary['u_id']);
				
			}catch(MyException $e)
			{
				throw $e;
			}
		}
	}
?>