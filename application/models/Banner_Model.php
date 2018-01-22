<?php
	class Banner_Model extends CI_Model 
	{
		public $CI ;
		function __construct()
		{
			
			parent::__construct();
			$this->CI =&get_instance();
			$this->load->database();
		}
		
		public function del($ary = array())
		{
			if(count($ary ) == 0)
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
			$in_id_str = join("','", $ary);
			$sql="DELETE FROM  big_banner WHERE bb_id IN('".$in_id_str."')";
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
		
		public function update($ary = array())
		{	
			try
			{
				$sql ="	UPDATE  big_banner 	
						SET bb_order = ? 
						WHERE bb_id =?";
				$bind = array(
					$ary['bb_order'],
					$ary['bb_id']
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
					$filename ='big_banner_'.$ary['bb_id'] ;
					$config['file_name'] = md5($filename);
					$config['upload_path'] = FCPATH.'../'.$_SERVER['FRONT_DIR'].'/images/big_banner/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['max_size']	= '2048';
					$config['max_width']  = '3000';
					$config['max_height']  = '826';
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
				return 0;
			}
			
			return 	$affected_rows ;
		}
		
		public function getRow($bb_id)
		{
			try
			{
				$sql ="	SELECT 
							*
						FROM big_banner WHERE bb_id =?";
				$bind = array($bb_id);
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
			}catch(MyException $e)
			{
				throw $MyException;
				return false;
			}
			return $row;
		}
		
		public function getList($ary)
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
							$where .=sprintf(" AND DATE_FORMAT(`bb_add_datetime`, '%s') %s ?", '%Y-%m-%d', $value['operator']);					
							$bind[] = $value['value'];
						}
					}else
					{
						$where .=sprintf(" AND %s %s ?", $key, $value['operator']);					
						$bind[] = $value['value'];
					}
				}
			}
			
			if(is_array($ary['order']) && count($ary['order']) >1)
			{
				$order =" ORDER BY ";
				foreach($ary['order'] AS $key =>$value)
				{
					$order.=sprintf( ' %s %s ', $key, $value);
				}
			}
			
			$sql =" SELECT 
						*
					FROM 
						big_banner";
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
				throw $MyException;
			}
			return 	$output  ;
		}
		
		public function add($ary)
		{
			try
			{
				$sql="INSERT INTO big_banner (bb_order,bb_add_datetime)
					  values(?, NOW())";
				$bind = array(
					$ary['bb_order'],
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
				$filename ='big_banner_'.$insert_id ;
				$config['file_name'] = md5($filename);
				$config['upload_path'] = FCPATH.'../'.$_SERVER['FRONT_DIR'].'/images/big_banner/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size']	= '5120';
				$config['max_width']  = '3000';
				$config['max_height']  = '826';
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
				$sql = "UPDATE big_banner SET bb_image =? WHERE bb_id =?";
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
	}
?>