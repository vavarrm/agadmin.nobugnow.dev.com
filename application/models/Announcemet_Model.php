<?php
	class Announcemet_Model extends CI_Model 
	{
		public $CI ;
		function __construct()
		{
			
			parent::__construct();
			$this->CI =&get_instance();
			$this->load->database();
		}
		
		
		public function update($ary = array())
		{	
			try
			{
				$sql ="	UPDATE  announcemet 	
						SET an_title = ? , an_content=? , 	an_update_datetime = NOW()  
						WHERE an_id =?";
				$bind = array(
					$ary['an_title'],
					$ary['an_content'],
					$ary['an_id']
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
				
				$affected_rows = $this->db->affected_rows();
				
				
				if ($_FILES["image"]["error"] == 0)
				{
					$insert_id = $this->db->insert_id();
					$filename ='announcemet_'.$ary['an_id'] ;
					$config['file_name'] = md5($filename);
					$config['upload_path'] = FCPATH.'../'.$_SERVER['FRONT_DIR'].'/images/announcemet/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size']	= '2048';
					$config['max_width']  = '300';
					$config['max_height']  = '175';
					$config['overwrite']  = true;
					$this->CI->load->library('upload',$config);
					if(!$this->CI->upload->do_upload('image'))
					{
						$array = array(
							'message' 	=>$this->upload->display_errors('',''),
							'type' 		=>'api' ,
							'status'	=>'002'
						);
						$MyException = new MyException();
						$MyException->setParams($array);
						throw $MyException;
					}
					$affected_rows+=1;
				}
				
				if($affected_rows ==0)
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
			}	
			catch(MyException $e)
			{
				throw $MyException;
			}
			
			return 	$affected_rows ;
		}
		
		public function getRow($an_id)
		{
			$sql ="	SELECT 
					an_id,
					an_content AS  content,
					an_title AS  title,
					an_type AS type,
					an_image
					FROM announcemet WHERE an_id =?";
			$bind = array($an_id);
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
			
			return $row;
		}
		
		
		public function del($an_id = array())
		{
			if(count($an_id) == 0)
			{
				$MyException = new MyException();
				$array = array(
					'message' 	=>'无傳入參數' ,
					'type' 		=>'db' ,
					'status'	=>'999'
				);
				
				$MyException->setParams($array);
				throw $MyException;
			}
			$an_id_str = join("','", $an_id);
			$sql="DELETE FROM announcemet WHERE an_id IN('".$an_id_str."')";
			$query = $this->db->query($sql);
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
			

			
			
			return true;
		}
		
		public function add($ary = array())
		{
			try
			{
				$sql="INSERT INTO announcemet (an_title, an_content, an_datetime, an_type)
					  values(?, ?, NOW(),?)";
				$bind = array(
					$ary ['an_title'],
					$ary ['an_content'],
					$ary ['an_type'],
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
				
				$insert_id = $this->db->insert_id();
				$filename ='announcemet_'.$insert_id ;
				$config['file_name'] = md5($filename);
				$config['upload_path'] = FCPATH.'../'.$_SERVER['FRONT_DIR'].'/images/announcemet/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= '2048';
				$config['max_width']  = '300';
				$config['max_height']  = '175';
				$config['overwrite']  = true;

				if ($_FILES["image"]["error"] > 0)
				{
					$array = array(
						'message' 	=> $_FILES["image"]["error"],
						'type' 		=>'api' ,
						'status'	=>'002'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;
				}
				
				$this->CI->load->library('upload',$config);
				if(!$this->CI->upload->do_upload('image'))
				{
					$array = array(
						'message' 	=>$this->upload->display_errors('',''),
						'type' 		=>'api' ,
						'status'	=>'002'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;
				}
				
				$image= $this->CI->upload->data();
				$sql = "UPDATE announcemet SET an_image =? WHERE an_id =?";
				$bind = array(
					$image['file_name'],
					$insert_id 
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
				// exit;
				
			}catch(MyException $e)
			{
				throw $MyException;
			}
			return $this->db->affected_rows();
		}
		
		public function getList($ary = array())
		{
			$where .=" WHERE 1 = 1";
			$gitignore = array(
				'limit',
				'p',
				'order'
			);
			$limit = sprintf(" LIMIT %d, %d",abs($ary['p']-1)*$ary['limit'],$ary['limit']);

			if(is_array($ary))
			{
				foreach($ary as $key => $value)
				{
					if(in_array($key, $gitignore) ||  $value['value'] ==="" )	
					{
						continue;
					}
					if($key =="start_time" || $key=="end_time"  )
					{
						if($value['value']!='')
						{
							$where .=sprintf(" AND DATE_FORMAT(`an_datetime`, '%s') %s ?", '%Y-%m-%d', $value['operator']);					
							$bind[] = $value['value'];
						}
					}else
					{
						// var_dump($value);
						$where .=sprintf(" AND %s %s ?", $key, $value['operator']);					
						$bind[] = $value['value'];
					}
				}
			}
			
			if(is_array($ary['order']))
			{
				$order =" ORDER BY ";
				foreach($ary['order'] AS $key =>$value)
				{
					$order.=sprintf( ' %s %s ', $key, $value);
				}
			}
			
			$sql =" SELECT 
						*,
						substring(an_content, 1, 20) AS an_content_shot,
						CASE an_type
							 WHEN 'action' THEN '活动'
							 WHEN 'public' THEN '公告'
						END  AS an_type_str
					FROM 
						announcemet";
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
				throw $MyException;
			}
			return 	$output  ;
		}
		
	}
?>