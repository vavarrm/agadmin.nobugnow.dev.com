<?php
	class Rechargenit_Model extends CI_Model 
	{
		function __construct()
		{
			
			parent::__construct();
			$this->load->database();
		}
		
		public function changeStatus($ary = array(), $status)
		{
			if(is_array($ary))
			{
				$str = join("','", $ary);
			}
			$sql = sprintf("UPDATE user_account SET ua_status = ? WHERE ua_id IN('%s')", $str);
			$bind[] = $status;
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
			}
			$affected_rows =  $this->db->affected_rows();
			return $affected_rows;
		}
		
		public function getAccountAuditList($ary=array())
		{
			$where =" WHERE 1=1 ";
			$order="";
			$gitignore = array(
				'limit',
				'p',
				'order'
			);
			$limit = sprintf(" LIMIT %d, %d",abs($ary['p']-1)*$ary['limit'],$ary['limit']);
			if(is_array($ary))
			{
				foreach($ary as $key =>$row)
				{
				
					if(in_array($key, $gitignore) || $row['value']==='' )	
					{
						continue;
					}

					
					if($key =="start_time" || $key=="end_time"  )
					{
						// echo $key;
						if($row['value']!='')
						{
							$where .=sprintf(" AND DATE_FORMAT(`ua_add_datetime`, '%s') %s ?", '%Y-%m-%d', $row['operator']);					
							$bind[] = $row['value'];
						}
					}else
					{
						$where .=sprintf(" AND %s %s ?", $key, $row['operator']);					
						$bind[] = $row['value'];
					}
				}
			}
			
			if(is_array($ary['order']))
			{
				$order =" ORDER BY ";
				foreach($ary['order'] AS $key =>$value)
				{
					$order.=sprintf( '%s,%s', $key, $value);
				}
			}
			
			$sql = "SELECT 
						u.u_account,
						ad.ad_account,
						ua.*
					FROM 
						user_account AS ua 
							INNER JOIN user AS u  ON ua.ua_to = u.u_id
							INNER JOIN admin AS ad  ON ua.ua_from = ad.ad_id
					";
			$search_sql = $sql.$where.$order.$limit ;
			// echo $search_sql;
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
			}
			return $output;
		}
		
		public function addBalance($ary)
		{
			$this->db->trans_start();
			$sql = "INSERT INTO user_account 
					(ua_value,	ua_type, ua_add_datetime, ua_from, ua_to, ua_remarks)
					VALUES(?,?,NOW(),?,?,?)";
			$bind= array(
				$ary['ua_balance'],
				$ary['ua_type'],
				$ary['ua_from'],
				$ary['ua_to'],
				$ary['ua_remarks']
			);
			$query = $this->db->query($sql, $bind);
			$affected_rows =  $this->db->affected_rows();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>$error['message'] ,
					'type' 		=>'db' ,
					'status'	=>'001'
				);
				
				$MyException->setParams($array);
			}
			
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
			}
			
			return $affected_rows;
		}
		
		public function getTypeList($outin, $ary)
		{
			$where = " 1=1 ";
			if(!empty($ary))
			{
				$uat_id_in = join("','",$ary);
				$where .=sprintf("  AND uat_id IN('%s')", $uat_id_in);
			}
			$bind =array(
				$outin
			);
			$where .=" AND uat_out_in =?";
			$sql ="SELECT * FROM user_account_type WHERE ".$where ."  ORDER BY uat_id";
			
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
			}
			return $rows ;
		}
	}
?>