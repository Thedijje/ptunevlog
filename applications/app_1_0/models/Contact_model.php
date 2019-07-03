<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Contact Us Model
 */
class Contact_model extends CI_Model {

	public function list($contact_id=NULL,$user_id=NULL){
		$this->db->select('*');
		$this->db->from('contact msg');
		$this->db->join('users		user',	'user.user_id	=	msg.contact_user_id',	'left');
		$this->db->join('admin		admin',	'admin.id	=	msg.contact_replied_by',	'left');

		if($contact_id!=NULL):
			$this->db->where('msg.contact_id',$contact_id);
		endif;
		
		if($user_id!=NULL):
			$this->db->where('msg.contact_user_id',$user_id);
		endif;
		
		$this->db->order_by('msg.contact_id','desc');
		$query				=	$this->db->get();
		
		if(!$query){
			log_message('error','Unable to fetch contact messages at the moment, Mysql error is #'.mysql_error());
			return false;
		}
		
		if($query->num_rows()<1){
			log_message('debug','No contact messages found ');
			return array();
		}

		if($contact_id!=NULL){
			return $query->row();
		}
		return $query->result();
	}

}