<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api extends CI_Controller {
	
	private $request = array();
	private $_user = array();
	private $admin_data = array();
	
	public function __construct() 
	{
		parent::__construct();	
		
		$get = $this->input->get();
		$post = $this->input->post();
		$this->request = json_decode(trim(file_get_contents('php://input'), 'r'), true);
		$this->load->model('Admin_Model', 'admin');
		$this->load->model('User_Model', 'user');
		$this->load->model('Rechargenit_Model', 'rechargenit');
		$this->load->model('Announcemet_Model', 'announcemet');
		$this->load->model('Banner_Model', 'banner');
		$this->load->model('Website_Model', 'website');

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
				$this->admin_data =$decrypt_admin_data;
	
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
			
			// $this->checkPermissions();
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
	

	
	public function addBigBalance()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='新增大轮播图';
		$output['message'] = '新增成功';
		$post = $this->input->post();
		try
		{
			if(
				$post['order'] ==="" 
			){
				$array = array(
					'message' 	=>'必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$ary  =array(
				'bb_order'=>$post['order'] ,
			);
			$affected_rows  = $this->banner->add($ary);
			
			if($affected_rows == 0)
			{
				$array = array(
					'message' 	=>'新增失败' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->myLog->error_log($parames);
		}
		$this->myfunc->gotoUrl('/admin/renterTemplates#!/website/bigBannerList',$output['message'] );
	}
	
	public function editBigBalance()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='更新banner';
		$output['message'] = '更新成功';
		$post = $this->input->post();
		try
		{
			if(
				$post['bb_order'] =="" || 
				$post['bb_id'] =="" 
			){
				$array = array(
					'message' 	=>'必傳參數為空11' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$ary  =array(
				'bb_order'=>$post['bb_order'] ,
				'bb_id'=>$post['bb_id'],
			);
			$affected_rows  = $this->banner->update($ary);
			
			if($affected_rows == 0)
			{
				$array = array(
					'message' 	=>'更新公告失败' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->myLog->error_log($parames);
		}
		$this->myfunc->gotoUrl('/admin/renterTemplates#!/website/bigBannerList/',$output['message'] );
	}
	
	
	public function editFooter()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='更新连结';
		$output['message'] = '更新成功';
		$post = $this->input->post();
		try
		{
			if(
				$post['wechat_account'] =="" || 
				$post['qq_account'] =="" 
			){
				$array = array(
					'message' 	=>'必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			$ary  =array(
				'wechat_account'=>$post['wechat_account'] ,
				'qq_account'=>$post['qq_account'],
			);
			$affected_rows  = $this->website->updFooter($ary);
			
			if($affected_rows == 0)
			{
				$array = array(
					'message' 	=>'更新失败' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->myLog->error_log($parames);
		}
		$this->myfunc->gotoUrl('/admin/renterTemplates#!/website/editFooter/',$output['message'] );
	}
	
	public function editFooterInit()
	{
		$output['status'] = 100;
		$output['body'] =array(
		);
		$output['title'] ='推广连结设定页';
		$output['message'] = '取得成功';
		
		
		try 
		{
			$ary = array(
				'wechat_account',
				'wechat_qr_image',
				'qq_account',
				'qq_qr_image'
			);
			$rows = $this->website->getListByKey($ary);
			if(empty($rows['list']))
			{
				$array = array(
					'message' 	=>'取得资料失败' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			foreach($rows['list'] as $key =>$value)
			{
				$output['body']['list'][$value['we_key']] = $value;
			}
			
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
	
	public function delBigBanner()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='刪除轮播图';
		$output['message'] = '刪除成功';
		try 
		{
			if(
				!is_array($this->request['bb_id']) ||
				count($this->request['bb_id']) == 0
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
			$this->banner->del($this->request['bb_id']);
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
	
	public function editBigBalanceInit()
	{
		$output['status'] = 100;
		$output['body'] =array(
		);
		$output['title'] ='修改大圖';
		$output['message'] = '执行成功';
		
		
		try 
		{
			if(
				$this->request['bb_id']	==""
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
			$output['body']['row'] = $this->banner->getRow($this->request['bb_id']);
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
	
	public function bigBannerList()
	{
		$output['status'] = 100;
		$output['body'] =array(
		);
		$output['title'] ='大圖輪播列表';
		$output['message'] = '执行成功';
		
		$ary['limit'] = (isset($this->request['limit']))?$this->request['limit']:5;
		$ary['p'] = (isset($this->request['p']))?$this->request['p']:1;
		$start_time = (isset($this->request['start_time']))?$this->request['start_time']:'';
		$end_time = (isset($this->request['end_time']))?$this->request['end_time']:'';
		$order = (isset($this->request['order']))?$this->request['order']:array();
		
		try 
		{
			$ary['order']=$order ;
			$output['body'] = $this->banner->getList($ary);
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
	
	public function doWithdrawalAudit()
	{
		$output['status'] = 100;
		$output['body'] =array(
			'affected_rows'	=>0
		);
		$output['title'] ='修改提款状态';
		$output['message'] = '成功';
		try 
		{
			if(
				$this->request['ua_id']	==""||
				$this->request['ua_status']	==""
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
			
			// if(
				// $this->request['ua_status']	=="payment" ||
				// $this->request['ua_status']	=="stopPayment" 
			// ){
				// $array = array(
					// 'message' 	=>'已出款拒绝出款不能在修改' ,
					// 'type' 		=>'api' ,
					// 'status'	=>'999'
				// );
				// $MyException = new MyException();
				// $MyException->setParams($array);
				// throw $MyException;
			// }
			
			$affected_rows  = $this->rechargenit->changeStatus(array($this->request['ua_id']), $this->request['ua_status'], $this->admin_data );
			if($affected_rows  == 0)
			{
				$array = array(
					'message' 	=>'无资料更新' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$output['affected_rows']= $affected_rows ;
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
	
	public function withdrawalAuditList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得提款列表';
		$output['message'] = '成功';
		$ary['limit'] = (isset($this->request['limit']))?$this->request['limit']:5;
		$ary['p'] = (isset($this->request['p']))?$this->request['p']:1;
		$start_time = (isset($this->request['start_time']))?$this->request['start_time']:'';
		$end_time = (isset($this->request['end_time']))?$this->request['end_time']:'';
		$u_account = (isset($this->request['u_account']))?$this->request['u_account']:'';
		$order = (isset($this->request['order']))?$this->request['order']:array();
		try 
		{
			$ary['start_time'] =  array('operator' => '>=' , 'value'=>$start_time); 
			$ary['end_time'] =  array('operator' => '<=' , 'value'=>$end_time); 
			$ary['u_account'] =  array('operator' => '<=' , 'value'=>$u_account); 
			if(is_array($order) && count($order) >0)
			{
				$ary['order']=$order ;
			}else
			{
				$ary['order'] = array(
					'ua_add_datetime' =>	'DESC'
				);
			}
			$ary['ua_type'] = array(
				'value' => 3,
				'operator' => "=",
			);
			
			$output['body'] = $this->rechargenit->getList($ary);
			$output['body']['actions'] = $this->admin->getActionlist($this->request['am_id']);
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
	
	public function editAnnouncemet()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='更新公告';
		$output['message'] = '更新成功';
		$post = $this->input->post();
		try
		{
			if(
				$post['title'] =="" ||
				$post['content'] =="" ||
				$post['type'] =="" || 
				$post['an_id'] =="" 
			){
				$array = array(
					'message' 	=>'必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$ary  =array(
				'an_title'=>$post['title'] ,
				'an_content'=>$post['content'],
				'an_type'=>$post['type'],
				'an_id' =>$post['an_id']
			);
			$affected_rows  = $this->announcemet->update($ary);
			
			if($affected_rows == 0)
			{
				$array = array(
					'message' 	=>'新增公告失败' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->myLog->error_log($parames);
		}
		$this->myfunc->gotoUrl('/admin/renterTemplates#!/user/announcemetList/',$output['message'] );
	}
	
	public function getAnnouncemet()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得公告';
		$output['message'] = '取得成功';
		try 
		{
			if(
				$this->request['an_id'] =="" 
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
			$output['body']['row'] = $this->announcemet->getRow($this->request['an_id']);
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
	
	public function delAnnouncemet()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='刪除公告';
		$output['message'] = '刪除成功';
		try 
		{
			if(
				$this->request['an_id'] =="" ||
				!is_array($this->request['an_id']) ||
				count($this->request['an_id']) == 0
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
			$this->announcemet->del($this->request['an_id']);
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
	
	public function addAnnouncemet()
	{
		
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='新增公告';
		$output['message'] = '成功新增';
		$post = $this->input->post();
		try
		{
			if(
				$post['title'] =="" ||
				$post['content'] =="" ||
				$post['type'] =="" 
			){
				$array = array(
					'message' 	=>'必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$ary  =array(
				'an_title'=>$post['title'] ,
				'an_content'=>$post['content'],
				'an_type'=>$post['type']
			);
			$affected_rows = $this->announcemet->add($ary);
			
			if($affected_rows == 0)
			{
				$array = array(
					'message' 	=>'新增公告失败' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
		}catch(MyException $e)
		{
			$parames = $e->getParams();
			$parames['class'] = __CLASS__;
			$parames['function'] = __function__;
			$output['message'] = $parames['message']; 
			$output['status'] = $parames['status']; 
			$this->myLog->error_log($parames);
		}
		$this->myfunc->gotoUrl('/admin/renterTemplates#!/user/announcemetList/',$output['message'] );
	}
	
	public function getAnnouncemetList()
	{
		
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得公告列表';
		$output['message'] = '成功取得';
		$ary['limit'] = (isset($this->request['limit']))?$this->request['limit']:5;
		$ary['p'] = (isset($this->request['p']))?$this->request['p']:1;
		$an_type = (isset($this->request['type']))?$this->request['type']:'';
		$start_time = (isset($this->request['start_time']))?$this->request['start_time']:'';
		$end_time = (isset($this->request['end_time']))?$this->request['end_time']:'';
		$order= (isset($this->request['order']))?$this->request['order']:array();
		try 
		{
			$ary['start_time'] =  array('operator' => '>=' , 'value'=>$start_time); 
			$ary['end_time'] =  array('operator' => '<=' , 'value'=>$end_time); 
			$ary['an_type'] =  array('operator' => '=' , 'value'=>$an_type); 
			// var_dump($ary);
			if(count($order)>0)
			{
				$ary['order'] =$order;
			}
			else{
				$ary['order'] = array(
					'an_datetime' =>	'DESC'
				);
			}
			$output['body'] = $this->announcemet->getList($ary);
			$output['body']['actions'] = $this->admin->getActionlist($this->request['am_id']);
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
	
	public function adminList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得管理员列表';
		$output['message'] = '成功取得';
		$ary['limit'] = (isset($this->request['limit']))?$this->request['limit']:5;
		$ary['p'] = (isset($this->request['p']))?$this->request['p']:1;
		$ad_account = (isset($this->request['account']))?$this->request['account']:'';
		try 
		{
			$ary['ad.ad_account'] =  array('value' =>$ad_account , 'operator' =>'=');
			$ary['order'] = array(
				'ad.ad_id'=>'DESC'
			);
			// var_dump($ary);
			$output['body'] = $this->admin->adminList($ary);
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
	
	public function addAdmin()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='新增后台帐号';
		$output['message'] = '成功新增';
		
		try 
		{
			if(
				$this->request['account'] ==""|| 
				$this->request['passwd']	 ==""|| 
				$this->request['passwd_confirm']	 ==""|| 
				$this->request['role']	 =="" 
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
			
			if(strlen($this->request['account']) <8 || strlen($this->request['account'])>12){
				$array = array(
					'message' 	=>'帳號長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if(strlen($this->request['passwd']) <8 || strlen($this->request['passwd'])>12){
				$array = array(
					'message' 	=>'密碼長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if($this->request['passwd'] != $this->request['passwd_confirm']){
				$array = array(
					'message' 	=>'密碼两次输入不一致' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			
			$accountIsExist = $this->admin->accountIsExist($this->request['account']);
			if($accountIsExist >0)
			{
				$array = array(
					'message' 	=>'此帳號已存在' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			$ary =array(
				'ad_account'	=>$this->request['account'],
				'ad_passwd'		=>$this->request['passwd'],
				'ad_role'		=>$this->request['role'],
			);
			
			$this->admin->insert($ary);
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
	
	public function getAdminRoleList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得角色选单';
		$output['message'] = '成功取得';
		
		try 
		{
			$output['body']['list'] = $this->admin->getAdminRoleList();
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
	
	private function checkPermissions()
	{
		$post = $this->input->post();
		$nocheck = array(
			'login',
			'logout',
			'getMenu',
			'getAdminRoleList',
			'getActionList'
		);
		if(!in_array($this->uri->segment(2), $nocheck))
		{
			if($this->request['am_id']=="" && $post['am_id'] =="")
			{
				$array = array(
					'message' 	=>'reponse 必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;	
			}
			if($this->_user['ad_role'] !=1)
			{
				
			}
		}
	}
	
	public function getActionList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得功能项';
		$output['message'] = '成功取得功能项目';
		
		try 
		{
			if(
				$this->request['am_id']	==""
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
			
			$output['body']['actionlist'] = $this->admin->getActionlist($this->request['am_id']);
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
	
	public function addChildUser()
	{
		$output['status'] = '100';
		$output['message'] = '註冊成功'; 
		$output['body'] =array();
		$output['title'] ='註冊總代用戶';	
		try 
		{
			if(
				$this->request['name']	==""|| 
				$this->request['account']	==""|| 
				$this->request['passwd']	=="" ||
				$this->request['superior']	=="" 
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
			
			if(strlen($this->request['name']) <8 || strlen($this->request['name'])>12){
				$array = array(
					'message' 	=>'暱稱長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if(strlen($this->request['account']) <8 || strlen($this->request['account'])>12){
				$array = array(
					'message' 	=>'帳號長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if(strlen($this->request['passwd']) <8 || strlen($this->request['passwd'])>12){
				$array = array(
					'message' 	=>'密碼長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if($this->request['name'] == $this->request['account']){
				$array = array(
					'message' 	=>'使用者名稱不能與帳號相同' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			
			$accountIsExist = $this->user->accountIsExist($this->request['account']);
			if($accountIsExist ==1)
			{
				$array = array(
					'message' 	=>'使用者帳號已存在' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			$ary =array(
				'superior_id'	=>$this->request['superior'],
				'u_name'		=>$this->request['name'],
				'u_account'		=>$this->request['account'],
				'u_passwd'		=>md5($this->request['passwd']),
			);
			
			$this->user->insert($ary);
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
	
	public function addParentUser()
	{
		$output['status'] = '100';
		$output['message'] = '註冊成功'; 
		$output['body'] =array();
		$output['title'] ='註冊總代用戶';	
		try 
		{
			if(
				$this->request['name']	==""|| 
				$this->request['account']	==""|| 
				$this->request['passwd']	=="" ||
				$this->request['superior']	=="" 
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
			
			if(strlen($this->request['name']) <8 || strlen($this->request['name'])>12){
				$array = array(
					'message' 	=>'暱稱長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if(strlen($this->request['account']) <8 || strlen($this->request['account'])>12){
				$array = array(
					'message' 	=>'帳號長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if(strlen($this->request['passwd']) <8 || strlen($this->request['passwd'])>12){
				$array = array(
					'message' 	=>'密碼長度為8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			if($this->request['name'] == $this->request['account']){
				$array = array(
					'message' 	=>'使用者名稱不能與帳號相同' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			
			$accountIsExist = $this->user->accountIsExist($this->request['account']);
			if($accountIsExist ==1)
			{
				$array = array(
					'message' 	=>'使用者帳號已存在' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
			$ary =array(
				'superior_id'	=>$this->request['superior'],
				'u_name'		=>$this->request['name'],
				'u_account'		=>$this->request['account'],
				'u_passwd'		=>md5($this->request['passwd']),
			);
			
			$this->user->insert($ary);
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
	
	public function childUserList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得下级帐号';
		$output['message'] = '成功取得';
		try 
		{
			if(
				$this->request['superior_id']	==""
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
			$output['body'] = $this->user->getUserListBySuperiorId($this->request['superior_id']);
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
	
	public function getUserByID()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得使用者资料';
		$output['message'] = '成功取得';
		try 
		{
			if(
				$this->request['u_id']	==""
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
			$output['body']['user'] = $this->user->getUserByID($this->request['u_id']);
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
	
	public function setMoneyPasswdForm()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得修改使用者密码表单';
		$output['message'] = '成功取得';
		try 
		{
			if(
				$this->request['u_id']	==""
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
			$output['body']['user'] = $this->user->getUserByID($this->request['u_id']);
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
	
	
	public function setUserPasswdForm()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得修改使用者密码表单';
		$output['message'] = '成功取得';
		try 
		{
			if(
				$this->request['u_id']	==""
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
			$output['body']['user'] = $this->user->getUserByID($this->request['u_id']);
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
	
	public function doSetUserPasswd()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='设定使用者密码';
		$output['message'] = '成功设定';
		try 
		{
			if(
				$this->request['u_id']	=="" ||
				$this->request['passwd']	=="" ||
				$this->request['passwd_confirm']	=="" 
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
			
			if(
				$this->request['passwd'] != $this->request['passwd_confirm']
			){
				$array = array(
					'message' 	=>'两次输入密码不一致' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			
			if(
				strlen($this->request['passwd']) <8 ||
				strlen($this->request['passwd']) >12 
			){
				$array = array(
					'message' 	=>'密码长度为8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			$ary = array(
				'u_passwd' =>$this->request['passwd'],
				'u_id' =>$this->request['u_id'],
			);
			
			$user = $this->user->getUserByID($u_id);
			if($user['u_passwd'] == md5($this->request['passwd']))
			{
				$array = array(
					'message' 	=>'不能与资金密码一致' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$this->user->setUserPasswd($ary);

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
	
	public function getParentInfo()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得總代資料';
		$output['message'] = '成功取得';
		$ary['u_id'] = (isset($this->request['u_id']))?$this->request['u_id']:5;
	
		try 
		{
			if($ary['u_id']  =="")
			{
				$array = array(
						'message' 	=>'reponse 必傳參數為空' ,
						'type' 		=>'api' ,
						'status'	=>'002'
					);
					$MyException = new MyException();
					$MyException->setParams($array);
					throw $MyException;
			}
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
	
	public function setMoneyPasswd()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='设定使用者資金密码';
		$output['message'] = '成功设定';
		try 
		{
			if(
				$this->request['u_id']	=="" ||
				$this->request['passwd']	=="" ||
				$this->request['passwd_confirm']	=="" 
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
			
			if(
				$this->request['passwd'] != $this->request['passwd_confirm']
			){
				$array = array(
					'message' 	=>'两次输入密码不一致' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			
			if(
				strlen($this->request['passwd']) <8 ||
				strlen($this->request['passwd']) >12 
			){
				$array = array(
					'message' 	=>'密码长度为8~12位' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			$ary = array(
				'u_passwd' =>$this->request['passwd'],
				'u_id' =>$this->request['u_id'],
			);
			$user = $this->user->getUserByID($this->request['u_id']);
			if($user['u_money_passwd'] == md5($this->request['passwd']))
			{
				$array = array(
					'message' 	=>'不能与使用者密码一致' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$this->user->setMoneyPasswd($ary);

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
	
	public function userList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='使用者列表';
		$output['message'] = '成功取得';
		$ary['limit'] = (isset($this->request['limit']))?$this->request['limit']:5;
		$ary['p'] = (isset($this->request['p']))?$this->request['p']:1;
		$ary['u.u_superior_id'] = (isset($this->request['superior_id']))?$this->request['superior_id']:0;
		$ary['u.u_account'] = (isset($this->request['u_account']))?$this->request['u_account']:'';
		if($ary['u.u_account']  !="")
		{
			unset($ary['u.u_superior_id']);
		}
		try 
		{
			$output['body'] = $this->user->getList($ary);
			$output['body']['actions'] = $this->admin->getActionlist($this->request['am_id']);
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
	
	private function createTree(&$list, $parent)
	{
		$tree = array();
		foreach ($parent as $k=>$l)
		{
			if(isset($list[$l['id']]))
			{
				$l['nodes'] = $this->createTree($list, $list[$l['id']]);
			}
			$tree[] = $l;
		} 
		return $tree;
	}
	
	
	
	public function getUserAccount()
	{
		try 
		{
			if(
				$this->request['u_id']	==""
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
			$row = $this->user->getUesrAccount($this->request['u_id']);
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
			
			if($row['ad_passwd'] !=md5($this->request['passwd']))
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
	}
	
	public function doRechargeAudit()
	{
		$output['status'] = 100;
		$output['body'] =array(
			'affected_rows' => 0
		);
		$output['title'] ='审核充值';
		$output['message'] = '成功';
	
		try 
		{
			if(
				$this->request['ua_id']	=="" ||
				count($this->request['ua_id'])	==0 ||
				$this->request['ua_status']	 ==""
			)
			{
				$array = array(
					'message' 	=>'reponse 必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			$affected_rows  = $this->rechargenit->changeStatus(array($this->request['ua_id']),$this->request['ua_status'], $this->admin_data);
			if($affected_rows  ==0)
			{
				$array = array(
					'message' 	=>'无资料更新' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			$output['body']['affected_rows'] =$affected_rows;
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
	
	public function  rechargeAuditList()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得审核列表';
		$output['message'] = '成功';
	
		try 
		{
			$ary['limit'] = (isset($this->request['limit']))?$this->request['limit']:5;
			$ary['p'] = (isset($this->request['p']))?$this->request['p']:1;
			$end_time = (isset($this->request['end_time']))?$this->request['end_time']:'';
			$start_time = (isset($this->request['start_time']))?$this->request['start_time']:'';
			$u_account = (isset($this->request['u_account']))?$this->request['u_account']:'';
			
			$ary['start_time'] =array('value' =>$start_time, 'operator' =>'>=');
			$ary['end_time'] =array('value' =>$end_time, 'operator' =>'<=');
			$ary['u_account'] =array('value' =>$u_account, 'operator' =>'<=');
			$ary['ua.ua_type'] =  array('value' =>'1', 'operator' =>'=');
			$ary['ua.ua_value'] =  array('value' =>0, 'operator' =>'>');
			if(is_array($this->request['order']) && count($this->request['order'])>0)
			{
				$ary['order'] = $this->request['order'];
			}
			$output['body'] = $this->rechargenit->getList($ary);
			$output['body']['actions'] = $this->admin->getActionlist($this->request['am_id']);
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
	
	public function addBalance()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='系统充值';
		$output['message'] = '成功';
		try 
		{
			if(
				$this->request['u_id']	=="" ||
				$this->request['uat_id']	==""
			)
			{
				$array = array(
					'message' 	=>'reponse 必傳參數為空' ,
					'type' 		=>'api' ,
					'status'	=>'002'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			
			if(
				intval($this->request['u_balance']) ==0
			)
			{
				$array = array(
					'message' 	=>'馀额要大于0' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}	
			$ary = array(
				'u_id' =>$this->request['u_id'],
				'ua_type' =>$this->request['uat_id'],
				'ua_from' =>$this->admin_data['ad_id'],
				'ua_to' =>$this->request['u_id'],
				'ua_balance' =>intval($this->request['u_balance']),
				'ua_remarks' =>intval($this->request['ua_remarks']),
			);
			$affected_rows = $this->rechargenit->addBalance($ary);
			if($affected_rows <=0)
			{
				$array = array(
					'message' 	=>'更新馀额失败' ,
					'type' 		=>'api' ,
					'status'	=>'999'
				);
				$MyException = new MyException();
				$MyException->setParams($array);
				throw $MyException;
			}
			
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
	
	public function rechargeForm()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='充值表单';
		$output['message'] = '成功';
		
		try 
		{
			if(
				$this->request['u_id']	==""
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
			
			$output['body']['options'] = $this->rechargenit->getTypeList('in', array(1));
			$output['body']['user'] = $this->user->getBalance($this->request['u_id']);
			
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
	
	public function getMenu()
	{
		$output['status'] = 100;
		$output['body'] =array();
		$output['title'] ='取得權限表單';
		$output['message'] = '成功';
		
		try 
		{

			$arr = $this->admin->getMenu();
			$new = array();
			foreach ($arr as $value){
				$new[$value['parent_id']][] = $value;
			}
			$tree = $this->createTree($new, $new[0]);
			$output['body']['menulist']= $tree;
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
			$row = $this->admin->getUesrByAccount($this->request['account']);
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
			
			if($row['ad_passwd'] !=md5($this->request['passwd']))
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
