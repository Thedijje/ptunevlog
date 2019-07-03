<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Web_Controller {
	
	 function __construct(){
        parent::__construct(); // needed when adding a constructor to a controller
		$this->login->check_admin_login();
	
    }

	public function add_new()
	{
		$data['title']			=	'Add Pages';
		$data['heading']	=	'Add Pages';
		$data['icon']		=	'fa fa-book';
		$data['pages']		=	$this->lib->get_table('pages',array('page_id'=>'asc'),NULL,50);
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/pages',$data);
		$this->load->view('admin/includes/footer',$data);
	}
	
		public function index()
	{
		$data['title']			=	'All Pages';
		$data['heading']	=	'All Pages';
		$data['icon']		=	'fa fa-book';
		$data['pages']		=	$this->lib->get_table('pages',array('page_id'=>'asc'),NULL,50);
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/all_pages',$data);
		$this->load->view('admin/includes/footer',$data);
	}
	
	public function add(){
		$data = $this->input->post();
		if(!$data){
			$this->lib->redirect_msg('Invalid request, please try again soon','danger','admin/pages');	
		}
		
		$check = $this->lib->count_data('pages','page_url',$data['page_url']);
		if($check>0){
			$this->lib->redirect_msg('URL already Taken, Please use Another URL','danger','admin/pages');
		}
		
		if(($_FILES['head_image']['name'])){
		if (!is_dir('static/images/featured_pages/')) {
				mkdir('static/images/featured_pages/',0777);
			}
		
			$path='static/images/featured_pages/';
			$upload_file=$this->lib->upload_file($path,'head_image');
			if($upload_file){
			$data['page_head_image']=$upload_file;
			}
		}else{
			unset($data['page_head_image']);
		}
			$data['page_url'] =str_replace(' ','_',$data['page_url']);
			$data['page_status']	=	1;
			$data['page_added_on'] = time();
		$insert						=	$this->db->insert('pages',$data);
		if($insert){
			$this->lib->redirect_msg('Page added successfully','success','admin/pages');
		}else{
			$this->lib->redirect_msg('Something went wrong with server, Please try again in sometime','danger','admin/pages');
		}
	}
	
	public function edit($id){
		if(!$id){
			$this->lib->display_alert('Invalid request!','danger','admin/pages');
		}
		$data['title']			=	'Edit Pages';
		$data['heading']	=	'Edit Pages';
		$data['icon']		=	'fa fa-book';
		$data['cat_info']	=	$this->lib->get_row_array('pages',array('page_id'=>base64_decode($id)));
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/page_edit',$data);
		$this->load->view('admin/includes/footer',$data);	
	}
	
	public function save(){
		$data		=	$this->input->post();
		if(!$data){
			$this->lib->display_alert('Invalid request!','danger','admin/pages');
		}
	   if(($_FILES['head_image']['name'])){
		if (!is_dir('static/images/featured_pages/')) {
				mkdir('static/images/featured_pages/',0777);
			}
		
			$path='static/images/featured_pages/';
			$upload_file=$this->lib->upload_file($path,'head_image');
			if($upload_file){
			$data['page_head_image']=$upload_file;
			}
		}else{
		unset($data['page_head_image']);
		}
		
		$id			=	base64_decode($data['page_id']);
		unset($data['page_id']);
		
		$update	=	$this->lib->update('pages',$data,'page_id',$id);
		if($update){
			$this->lib->redirect_msg('Update successfully completed!','success','admin/pages');
		}else{
			$this->lib->redirect_msg('Update not completed! please try again soon','danger','admin/pages');
		}
		
	}
	
	public function toogle_active($id){
		if(!$id){
			$this->lib->redirect_msg('Invalid request!','danger','admin/pages');
		}
		
		$status		=	$this->lib->get_row_array('pages',array('page_id' => base64_decode($id)));
		$row = $status->page_status;
		 if($row==1){
		$val=0;
		}else{
		$val=1;
		}
	     $newdata = array(
			'page_status' => $val
			);
	
	   $update=$this->lib->update('pages',$newdata,'page_id',base64_decode($id));
	   if($update){
		$this->lib->redirect_msg('Status changed','success','admin/pages');
	   }else{
			$this->lib->redirect_msg('Some Problem occurred please try again later ','danger','admin/pages');
		}
	}
	
	public function del($id){
		if(!$id){
			$this->lib->redirect_msg('Invalid request!','danger','admin/pages');
		}
		
		$delete	=	$this->lib->del('pages','page_id',base64_decode($id));
		if($delete){
			$this->lib->redirect_msg('Deletion successfully completed!','success','admin/pages');
		}else{
			$this->lib->redirect_msg('Error in deleting item, please try again soon!','warning','admin/pages');
		}
		
	}
}
