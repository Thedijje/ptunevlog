<?php
class Video_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }


    public function list($filter=array())
    {
        $this->db->select('*');
        $this->db->from('videos video');

        if(isset($filter['lang']) AND $filter['lang']!=''){
            $this->db->where('video.lang', $filter['lang']);
        }

        if(isset($filter['keyword']) AND $filter['keyword']!=''){
            $this->db->or_like('video.description', $filter['keyword']);
        }

        $query  =   $this->db->get();
        return $query->result();
    }

    public function by_topic($topics='')
    {
        if($topics==''){
            return false;
        }

        $this->db->select('video_id');
        $this->db->from('video_topics');
        $this->db->where_in('topic_id', $topics);
        $this->db->group_by('video_id');
        $query  =   $this->db->get();
        
        return $query->result_array();
    }
}