<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
    
	public function list($admin_id=NULL,$status=1){
		$this->db->select('*');
		$this->db->from('admin 	admin');
		$this->db->join('users 	user','user.user_id=admin.front_user','left');
		if($admin_id!=NULL){
			$this->db->where('admin.id',$admin_id);
			$query 	=	$this->db->get();
			if($query AND $query->num_rows()>0){
				return $query->result();
			}
			return false;
		}
		$this->db->where('admin.status',    $status);
		$this->db->order_by('admin.added_on','desc');
		$query 	=	$this->db->get();
		return $query->result();

	}

    /**
     * 
     *  */
    public function disable_admin($week=3)
    {
        
        $inactive_period    =   strtotime('-'.$week.' week');
        
        //  Check admin who is inactive for 3 weeks
        $admin_list =   $this->lib->get_multi_where('admin',array('status'=>1, 'admin_last_activity<='=>$inactive_period));
        
        if(!$admin_list){
            return false;
        }

        $i = 0;
        foreach($admin_list as $list){
            
            log_message('error','Admin account is being inactivated for user# '.$list->email.' -- '.current_url());
            $this->_inactivate_admin($list->id);
            $i = $i++;
        }

        $this->notification->slack_notify("{$i} admin accounts inactivated for not being used for 3 or more weeks");
        log_message('error',"{$i} admin accounts inactivated for not being used for 3 or more weeks");




    }

    private function _inactivate_admin($admin_id=0)
    {

        $this->lib->update('admin',array('status'=>2),'id',$admin_id);
        
        $this->send_email->admin_inactivate($admin_id);
        
    }

}