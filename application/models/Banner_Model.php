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
		
		
		public function addBig()
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
	}
?>