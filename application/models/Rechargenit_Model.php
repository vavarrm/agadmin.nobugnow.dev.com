<?php
	class Rechargenit_Model extends CI_Model 
	{
		function __construct()
		{
			
			parent::__construct();
			$this->load->database();
			
		}
		
		

		public function changeStatus($ary = array(), $status, $admin)
		{
			try {
				$this->db->trans_start();
				
				if(is_array($ary))
				{
					$str = join("','", $ary);
				};
				if($status== 'payment')
				{
					$sql =" SELECT 
							(
								SELECT COUNT(*) 
								FROM  
									user_account 
								WHERE 
									ua_u_id = ua.ua_u_id  AND 
									DATE_FORMAT(NOW(),'%Y-%m-%d') =  DATE_FORMAT(ua_upd_date,'%Y-%m-%d') 
									AND ua_status = 'payment'
							) AS today_payment_number,
							(
								SELECT IFNULL(0,SUM(ua_value)) 
								FROM  
									user_account 
								WHERE 
									ua_u_id = ua.ua_u_id  AND 
									DATE_FORMAT(NOW(),'%Y-%m-%d') =  DATE_FORMAT(ua_upd_date,'%Y-%m-%d') 
									AND ua_status = 'payment'
							) AS today_payment_value
							FROM 
								 user_account AS ua
							WHERE 
							ua.ua_id IN ('".$str."')";
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
						throw  $MyException;
						
					}
					$rows = $query->result_array();
					if(!empty($rows))
					{
						foreach($rows as $row)
						{;
							if($row['today_payment_number'] >3 || $row['today_payment_value'] >=300000)
							{
								$MyException = new MyException();
								$array = array(
									'message' 	=>'提款超过限制，一天只能出款三次，最高额度为300000',
									'type' 		=>'db' ,
									'status'	=>'999'
								);
								
								$MyException->setParams($array);
								throw  $MyException;
								break;
							}
						}
					}
				}
				
				
				$sql = "UPDATE user_account SET ua_status = ? ,  ua_upd_date = DATE_FORMAT(NOW(),'%Y-%m-%d')  WHERE ua_id IN('".$str."')";
				$bind =  array(
					$status
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
					throw  $MyException;
				}
				$affected_rows =  $this->db->affected_rows();
				
				if(is_array($ary) && count($ary)>0  && $affected_rows >0)
				{
					foreach($ary as $value)
					{
						$sql="INSERT INTO user_account_record(uar_am_id, uar_ua_id, uar_action, uar_add_datetime, uar_change_status)
								VALUES(?, ?,'change_status',NOW(),?)";
						$bind=array(
							$admin['ad_id'],
							$value,
							$status,
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
							throw  $MyException;
						}
					}
				}
				
				$this->db->trans_commit();
				return $affected_rows;
			}
			catch (Exception $e) {
				$this->db->trans_rollback();
				throw $e;
			}
		}
		
		public function getList($ary=array())
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
							if($row['logic'] =="")
							{
								$logic =" AND "; 
							}else{
								$logic = $row['logic'];
							}
							$where .=sprintf(" %s DATE_FORMAT(`ua_add_datetime`, '%s') %s ?",$logic  ,'%Y-%m-%d', $row['operator']);					
							$bind[] = $row['value'];
						}
					}else
					{
						$where .=sprintf(" AND %s %s ?", $key, $row['operator']);					
						$bind[] = $row['value'];
					}
				}
			}
			
			if(is_array($ary['order']) && !empty($ary['order']))
			{
				$order =" ORDER BY ";
				foreach($ary['order'] AS $key =>$value)
				{
					$order_ary[]=sprintf( '%s %s ', $key, $value);
				}
				$order.=join(',',$order_ary);
			}
			
			$sql = "SELECT
						u.u_account,
						CASE
							WHEN  ua_status = 'noAllowed' THEN '拒绝'
							WHEN  ua_status = 'audit' THEN '审核中'
							WHEN  ua_status = 'payment' THEN '已出款'
							WHEN  ua_status = 'recorded' THEN '已入帐'
						END AS 	ua_status_show,
						ua.*,
						ad.*,
						ub.*,
						bi.*,
						(
							SELECT COUNT(*) 
							FROM  
								user_account 
							WHERE 
								ua_u_id = ua.ua_u_id  AND 
								DATE_FORMAT(NOW(),'%Y-%m-%d') =  DATE_FORMAT(ua_upd_date,'%Y-%m-%d') 
								AND ua_status = 'payment'
						) AS today_payment_number,
						(
							SELECT IFNULL(0,SUM(ua_value)) 
							FROM  
								user_account 
							WHERE 
								ua_u_id = ua.ua_u_id  AND 
								DATE_FORMAT(NOW(),'%Y-%m-%d') =  DATE_FORMAT(ua_upd_date,'%Y-%m-%d') 
								AND ua_status = 'payment'
						) AS today_payment_value,
						CASE
							WHEN  ua_status = 'payment' THEN '1'
							WHEN  ua_status = 'stopPayment' THEN '1'
							ELSE '0'
						END AS 	withdrawal_disabled 
					FROM 
						user_account AS ua 
							INNER JOIN user AS u  ON ua.ua_u_id = u.u_id
							LEFT JOIN admin AS ad  ON ua.ua_from = ad.ad_id
							LEFT JOIN user_bank_info AS ub ON ub.ub_id = ua_ub_id
							LEFT JOIN bank_info AS bi ON ub.ub_bank_id = bi_id
					";
			$search_sql = $sql.$where.$order.$limit ;
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
				throw  $MyException;
			}
			return $output;
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
							INNER JOIN user AS u  ON ua.ua_u_id = u.u_id
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
				throw  $MyException;
			}
			return $output;
		}
		
		public function addBalance($ary)
		{
			$this->db->trans_start();
			$sql = "INSERT INTO user_account 
					(ua_value,	ua_type, ua_add_datetime, ua_from, ua_u_id, ua_remarks)
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
				throw  $MyException;
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
				throw  $MyException;
			}
			return $rows ;
		}
	}
?>