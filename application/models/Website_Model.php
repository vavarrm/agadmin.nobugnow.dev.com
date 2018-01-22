<?php
	class Website_Model extends CI_Model 
	{
		public $CI ;
		function __construct()
		{
			
			parent::__construct();
			$this->CI =&get_instance();
			$this->load->database();
		}
		
		public function getListByKey($ary = array())
		{
			$output= array('list' =>array());
			try
			{
				if(!is_array($ary) || count($ary)==0)
				{
					$array = array(
						'message' 	=>'参数传递错误' ,
						'type' 		=>'api' ,
						'status'	=>'002'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;
				}
				
				$in_ary= array();
				
				foreach($ary  as  $key =>$value)
				{
					$in_ary[] = $value ;
				}
				
				$in_str = join("','", $in_ary);
				
				$sql =sprintf("SELECT * FROM web_config WHERE we_key IN ('%s')", $in_str);
				$query = $this->db->query($sql);
				$rows = $query->result_array();

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
				
				$query->free_result();
				$output['list'] = $rows;
				
			}catch(MyException $e)
			{
				$this->db->trans_rollback();
				throw $MyException;
				return false;
			}
			return $output;
		}
		
		public function updFooter($ary)
		{
			$affected_rows = 0;
			$this->db->trans_start();
			try
			{
					$sql = "UPDATE web_config SET wc_value =? WHERE we_key ='wechat_account'";
					
					$bind = array(
						$ary['wechat_account']
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
					
					$affected_rows += $this->db->affected_rows();
					
					if ($_FILES["wechat_qr_image"]["error"] == 0)
					{
						$insert_id = $this->db->insert_id();
						$filename ='wechat_qr_image' ;
						$config['file_name'] = md5($filename);
						$config['upload_path'] = FCPATH.'../'.$_SERVER['FRONT_DIR'].'/images/webconfig/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['max_size']	= '2048';
						$config['max_width']  = '256';
						$config['max_height']  = '256';
						$config['overwrite']  = true;
						$this->CI->load->library('upload',$config);
						if(!$this->CI->upload->do_upload('wechat_qr_image'))
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
					
						$sql = "UPDATE web_config SET wc_value =? WHERE we_key ='wechat_qr_image'";
				
						$bind = array(
							$image['file_name']
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
						$affected_rows += 1;
					}
				
					
					$sql = "UPDATE web_config SET wc_value =? WHERE we_key ='qq_account'";
					$bind = array(
						$ary['qq_account']
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
					
					$affected_rows += $this->db->affected_rows();
					
					if ($_FILES["qq_qr_image"]["error"] == 0)
					{
						$insert_id = $this->db->insert_id();
						$filename ='qq_qr_image' ;
						$config['file_name'] = md5($filename);
						$config['upload_path'] = FCPATH.'../'.$_SERVER['FRONT_DIR'].'/images/webconfig/';
						$config['allowed_types'] = 'gif|jpg|png|jpeg';
						$config['max_size']	= '2048';
						$config['max_width']  = '256';
						$config['max_height']  = '256';
						$config['overwrite']  = true;
						$this->CI->load->library('upload',$config);
						if(!$this->CI->upload->do_upload('qq_qr_image'))
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
					
						$sql = "UPDATE web_config SET wc_value =? WHERE we_key ='qq_qr_image'";
				
						$bind = array(
							$image['file_name']
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
						$affected_rows += 1;
					}
			}
			catch(MyException $e)
			{
				 $this->db->trans_rollback();
				throw $MyException;
				return false;
			}
			$this->db->trans_complete();
			return $affected_rows ;
		}
	}
?>