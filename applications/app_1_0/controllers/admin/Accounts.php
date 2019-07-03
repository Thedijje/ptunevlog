<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends Web_Controller {
	
	 function __construct(){
        parent::__construct(); // needed when adding a constructor to a controller
		$this->login->check_admin_login();
		$this->load->model('admin/Admin_account_model');

    }

	public function index(){
		$this->role->require_permission('account_view');
		$data['title']		=	'Manage Accounts';
		$data['heading']	=	'Manage Accounts';
		$data['icon']		=	'fa fa-money';
		
		$data['accounts']	=	$this->Admin_account_model->account_info();
		
		$search_bank		=	$this->input->get('bank');
		if($search_bank!=''){
			$data['accounts']	=	$this->Admin_account_model->account_info(NULL,array('bank.bank_id'=>$search_bank));
		}
		
		$search_key		=	$this->input->get('q');
		if($search_key!=''){
			$data['accounts']	=	$this->Admin_account_model->account_info(NULL,NULL,$search_key);
			$data['search_q']	=	$search_key;
		}
		
		
		
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/account',$data);
		$this->load->view('admin/includes/footer',$data);
	}
	
	public function account_view($ac_id){
		$this->role->require_permission('account_view','popup');
		if(!$ac_id){
			$this->lib->display_alert('Invalid request, please refresh page and try again','danger','ban');
		}
		$account_id		=	base64_decode($ac_id);
		$data['account']	=	$this->Admin_account_model->account_info($account_id);
		
		if(!$data['account']){
			$this->lib->display_alert('Account detail not found, either does not exist or removed','danger','ban');
		}
		
		$this->load->view('admin/ajax/account_detail_view',$data);
		
	}
	
	public function validate($account_id){
		$this->role->require_permission('account_view');
		if(!$account_id){
			$this->lib->redirect_msg('Invalid request, please refresh page and try again','warning','admin/accounts');
		}
		
		$account_id		=	base64_decode($account_id);
		
		$data['accounts']	=	$this->Admin_account_model->account_info($account_id);
		$data['val_amt']	=	$this->lib->get_row_array('account_verification',array('account_id'=>$account_id,'status'=>3));
		$data['other_ac']	=	$this->lib->get_multi_where('member_account',array('member_id'=>$data['accounts']->member_login_id,'account_type'=>1));
		
		if(!$data['accounts']){
			$this->lib->redirect_msg('Invalid account, or account does not exist, please refresh and try again','danger','admin/accounts');
		}
		
		
		$data['title']		=	'Account detail #'.$data['accounts']->account_number;
		$data['heading']	=	'Account detail #'.$data['accounts']->account_number;
		$data['icon']		=	'fa fa-money';
		
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/account_validate',$data);
		$this->load->view('admin/includes/footer',$data);
		
	}
	
	public function save_amt(){
		$this->role->require_permission('account_validate');
		$data		=	$this->input->post();
		$ac_id		=	base64_decode($data['account_id']);
		$account	=	$this->Admin_account_model->account_info($ac_id);
		if($account){
			$data['member_id']	=	$account->member_login_id;
			$data['account_id']	=	$account->ac_id;
			$data['status']		=	3;
			
			$query					=	$this->db->replace('account_verification',$data);
			if($query){
				$this->mailing->validation_amt_mail($account->ac_id);
				
				$this->lib->redirect_msg('Validation amount added and will be notified to user','success','admin/accounts/validate/'.base64_encode($account->ac_id));
			}else{
				$this->lib->redirect_msg('Error in adding validation amount, please try again soon','warning','admin/accounts/validate/'.base64_encode($account->ac_id));
			}
			
		}else{
		$this->lib->redirect_msg('Account details does not exist or not active at moment','warning','admin/accounts/');

		}
		
	}
	
	public function del_amt($ac_id,$ac_verf_id){
		$this->role->require_permission('account_validate');
		$data	=	$this->lib->get_row_array('account_verification',array('account_id'=>base64_decode($ac_id),'id'=>base64_decode($ac_verf_id)));
		if($data){
			$delete	=	$this->lib->del('account_verification','id',base64_decode($ac_verf_id));
			if($delete){
				$this->lib->redirect_msg('Verification amount removed successfully','success','admin/accounts/validate/'.$ac_id);
			}else{
				$this->lib->redirect_msg('unable to remove at the moment, please try again latter','warning','admin/accounts/validate/'.$ac_id);

			}
		}else{
			$this->lib->redirect_msg('Invalid request, please refresh page and try again','warning','admin/accounts/validate/'.$ac_id);

		}
	}
	
	public function verify_virtual_account($v_ac_id,$v_ac_num){
		$this->role->require_permission('account_validate');
		if(!$v_ac_id OR !$v_ac_num){
			$this->lib->redirect_msg('Invalid request, please refresh page and try again','danger','admin/accounts/');
		}
		
		$ac_id						=	base64_decode($v_ac_id);
		$ac_no					=	base64_decode($v_ac_num);
		
		$account					=	$this->lib->get_row_array('member_account',array('ac_id'=>$ac_id,'account_number'=>$ac_no,'ac_status'=>3,'account_type'=>1));
		if($account){
			$admin_info	=	$this->session->userdata('admin');
			$admin_id		=	$admin_info['id'];
			$data['ac_verified_on	']	=	time();
			$data['ac_verified_by	']	=	$admin_id;
			$data['ac_status	']			=	1;
			
		
			
			$update							=	$this->lib->update('member_account',$data,'ac_id',$ac_id);
			if($update){
				$this->mailing->virtual_ac_verified($account->ac_id,TRUE);
				$this->lib->redirect_msg('Account verified successfully!','success','admin/accounts/');
			}
		}else{
			$this->lib->redirect_msg('Account can not be verified, possible reasons: Account already verified, or Account type is not virtual, or account details is not in record','danger','admin/accounts/');
		}
		
	}
	
	public function virtual_ac_suspend($v_ac_id,$v_ac_num){
		$this->role->require_permission('account_validate');
		if(!$v_ac_id OR !$v_ac_num){
			$this->lib->redirect_msg('Invalid request, please refresh page and try again','danger','admin/accounts/');
		}
		
		$ac_id						=	base64_decode($v_ac_id);
		$ac_no					=	base64_decode($v_ac_num);
		
		$account					=	$this->lib->get_row_array('member_account',array('ac_id'=>$ac_id,'account_number'=>$ac_no,'ac_status'=>3,'account_type'=>1));
		if($account){
			$admin_info					=	$this->session->userdata('admin');
			$admin_id						=	$admin_info['id'];
			$data['ac_verified_on	']	=	time();
			$data['ac_verified_by	']	=	$admin_id;
			$data['ac_status	']			=	4;
			
		
			
			$update							=	$this->lib->update('member_account',$data,'ac_id',$ac_id);
			if($update){
				$this->mailing->virtual_ac_verified($account->ac_id,TRUE);
				$this->lib->redirect_msg('Account status updated successfully!','warning','admin/accounts/');
			}
		}else{
			$this->lib->redirect_msg('Account can not be processed, possible reasons: Account already verified, or Account type is not virtual, or account details is not in record','danger','admin/accounts/');
		}
	}
	
	
	public function cards($ac_id=NULL){
		
		if($ac_id=='blocking'){
			$this->block_request();
			exit();
		}
		
		$this->role->require_permission('account_card');
		$data['title']		=	'View ecards';
		$data['heading']	=	'View ecards';
		$data['icon']		=	'fa fa-credit-card';
		
		if($ac_id!=NULL){
			$data['Account']	=	$this->lib->get_row_array('member_account',array('ac_id'=>base64_decode($ac_id)));
			$data['cards']		=	$this->lib->get_by_id('ecard','account_id',base64_decode($ac_id));
		}else{
			$data['cards']		=	$this->lib->get_by_id('ecard','status',3);
		}
		
		
		
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/cards',$data);
		$this->load->view('admin/includes/footer',$data);
		
	}
	

	public function card_approve($card,$account){
		if(!$card OR !$account){
			$this->lib->redirect_msg('Invalid request, please try again soon','warning','admin/accounts');
		}
		
		$card_info			=	$this->lib->get_row_array('ecard',array('account_id'=>base64_decode($account),'card_num'=>base64_decode($card)));
		if(!$card_info){
			$this->lib->redirect_msg('Card info is not correct, please try refreshing page and try soon','warning','admin/accounts/cards/'.$card);
		}
		
		$this->lib->update('ecard',array('status'=>1),'id',$card_info->id);
		$this->lib->redirect_msg('Card status changed to <b>Active</b>','success','admin/accounts/cards/'.$account);
		
	}
	
	public function block_request(){
		
		$this->role->require_permission('card_block');
		$data['title']		=	'View ecard blocking requests';
		$data['heading']	=	'View ecards blocking requests';
		$data['icon']		=	'fa fa-ban';
		$data['request_card_block']		=	$this->Admin_account_model->get_card(NULL,'blocked');
		
		
		echo $this->load->view('admin/includes/header',$data,TRUE);
		echo $this->load->view('admin/cards_block_request',$data,TRUE);
		echo $this->load->view('admin/includes/footer',$data,TRUE);
		
	}
	
	public function view_card_detail($card_num,$ac_num){
		$this->role->require_permission('account_card','ajax');
		if(!$card_num OR !$ac_num){
			$this->lib->display_alert('Invalid Request','danger','ban');
			exit();
		}
		
		$check_card							=	$this->lib->get_row_array('ecard',array('card_num'=>base64_decode($card_num),'status'=>6));
		if(!$check_card){
			$this->lib->display_alert('Invalid card or card is not having blocking request for now','danger','ban');
			exit();
		}
		$data['card_details']		=	$this->Admin_account_model->get_card($check_card->id);
		$data['account_info']	=	$this->Admin_account_model->account_info(base64_decode($ac_num));
		if(!$data['account_info']){
			$this->lib->display_alert('No Active Account found for this card','danger','ban');
			exit();
		}
		
		$this->load->view('admin/ajax/card_details',$data);
		
	}
	
	public function block_card($card_num,$ac_num){
		$this->role->require_permission('card_block');
		if(!$card_num OR !$ac_num){
			$this->lib->display_alert('Invalid Request','danger','ban');
			exit();
		}
		
		
		$check_card							=	$this->lib->get_row_array('ecard',array('card_num'=>base64_decode($card_num),'status'=>6));
		if(!$check_card){
			$this->lib->display_alert('Invalid card or card is not having blocking request for now','danger','ban');
			exit();
		}
		
		$data['account_info']	=	$this->Admin_account_model->account_info(base64_decode($ac_num));
		if(!$data['account_info']){
			$this->lib->display_alert('No Active Account found for this card','danger','ban');
			exit();
		}
		$this->lib->update('ecard',array('status'=>7),'id',$check_card->id);
		$this->lib->redirect_msg('Card suspended successfully','success','admin/accounts/cards/blocking');
		
	}
	
	
	
	
}
