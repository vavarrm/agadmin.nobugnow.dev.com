<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api extends CI_Controller {
	
	private $request = array();
	private $_user = array();
	
	public function __construct() 
	{
		parent::__construct();	
		
		$get = $this->input->get();
		$this->request = json_decode(trim(file_get_contents('php://input'), 'r'), true);
		$this->load->model('Admin_Model', 'admin');

		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='';
		$gitignore =array(
			'login',
			'logout'
		);		
		
		try 
		{
			if(!in_array($this->uri->segment(2),$gitignore))
			{
				
				if(
					$get['sess']	==""
				){
					$array = array(
						'message' 	=>'reponse 必傳參數為空' ,
						'type' 		=>'api' ,
						'status'	=>'002'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;
				}	
				
				$encrypt_admin_data = $this->session->userdata('encrypt_admin_data');
			
				if(empty($encrypt_admin_data)){
					$array = array(
						'message' 	=>'尚未登入' ,
						'type' 		=>'api' ,
						'status'	=>'999'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;
				}	
			
				$decrypt_admin_data= $this->decryptUser($get['sess'], $encrypt_admin_data);
				if(empty($decrypt_admin_data))
				{
					$array = array(
						'message' 	=>'無法取得使用者資料' ,
						'type' 		=>'api' ,
						'status'	=>'999'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;	
				}
				$this->_user = $decrypt_admin_data;
			}
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$this->myLog->error_log($parames);
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->response($output);
			exit;
		}
    }
	
	public function test()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='測試用';
		$output['message'] = '成功取得';
		
		try 
		{
			$this->user->insert();
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->myLog->error_log($parames);
		}
		
		$this->response($output);
	}
	
	private function decryptUser($rsa_randomKey, $encrypt_admin_data)
	{
		$randomKey =  $this->token->privateDecrypt($rsa_randomKey);
		$encrypt_admin_data = $this->session->userdata('encrypt_admin_data');
		$decrypt_admin_data = $this->token->AesDecrypt($encrypt_admin_data , $randomKey );
		$admin_data = unserialize($decrypt_admin_data);
		return $admin_data;
	}
	
	public function getUser()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得使用者登入資料';
		$output['message'] = '成功';
		try 
		{
			
			
			$encrypt_admin_data = $this->session->userdata('encrypt_admin_data');
			
			if(empty($encrypt_admin_data)){
				$array = array(
					'message' 	=>'尚未登入' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			
			$decrypt_admin_data= $this->decryptUser($get['sess'], $encrypt_admin_data);
			unset($this->_user['u_passwd']);
			$output['body']['user'] = $this->_user ;
			
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$this->myLog->error_log($parames);
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
		}
		
		$this->response($output);
	}
	
	private function doLogin($row)
	{
		$randomKey = $this->token->getRandomKey();
		$rsaRandomKey = $this->token->publicEncrypt($randomKey);
		$encrypt_admin_data = $this->token->AesEncrypt(serialize($row), $randomKey);
		$this->session->set_userdata('encrypt_admin_data', $encrypt_admin_data);
		$encrypt_admin_data = $this->session->userdata('encrypt_admin_data');
		$urlRsaRandomKey = urlencode($rsaRandomKey) ;
		return $urlRsaRandomKey ;
	}
	
	public function login()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='登入';
		$output['message'] = '登入成功';
		
		try 
		{
			if(
				$this->request['account']	==""|| 
				$this->request['passwd']	=="" 
			){
				$array = array(
					'message' 	=>'reponse 必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			$row = $this->user->getUesrByAccount($this->request['account']);
			if(empty($row))
			{
				$array = array(
					'message' 	=>'查無此帳號' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if($row['u_passwd'] !=md5($this->request['passwd']))
			{
				$array = array(
					'message' 	=>'密碼錯誤' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			$sess = $this->doLogin($row);
			$output['body'] = array(
				'sess' =>$sess 
			);
			
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$this->myLog->error_log($parames);
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
		}
		
		$this->response($output);
	}
	
	public function logout()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='登出';
		$output['message'] = '成功';
		try 
		{
			$this->session->unset_userdata('encrypt_admin_data');
			
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$this->myLog->error_log($parames);
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
		}
		
		$this->response($output);
	}
	
	private function response($output)
	{
		
		$output = array(
			'body'		=>$output['body'],
			'title'		=>$output['title'],
			'status'	=>$output['status'],
			'message'	=>$output['message']
		);
		
		header('Content-Type: application/json');
		echo json_encode($output , true);
	}
}
