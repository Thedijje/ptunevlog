<?php
class Video_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }


    public function list($filter=array())
    {

        $video_ids  =   $this->videos->by_topic($filter['topics']);


        $this->db->select('*');
        $this->db->from('videos video');

        
        $this->db->or_group_start();


        if(isset($filter['lang']) AND $filter['lang']!=''){
            $this->db->where_in('video.lang', explode(',',$filter['lang']));
        }

        if(isset($filter['topics']) AND $filter['topics']!=''){
            $this->db->where_in('video.id', array_merge($video_ids));
        }


        $this->db->group_end();

        if(isset($filter['keyword']) AND $filter['keyword']!=''){
            $this->db->group_start();

            $this->db->or_like('video.description', $filter['keyword']);

            $this->db->group_end();
        }
        
        $this->db->order_by('video.id','desc');


        $query  =   $this->db->get();
        
        return $query->result();
    }


    public function detail($video_id)
    {
        if(!$video_id){
            return false;
        }

        $this->db->select('*');
        $this->db->from('videos video');
        $this->db->where('id', $video_id);

        $query  =   $this->db->get();

        if($query){
            return $query->result();
        }

        return false;
    }

    public function by_topic($topics='')
    {
        if($topics==''){
            return false;
        }

        $topic_array    =   explode(',', $topics);


        $this->db->select('video_id');
        $this->db->from('video_topics');
        
        $this->db->or_where_in( 'topic_id', $topic_array );

        $this->db->group_by('video_id');
        
        $query  =   $this->db->get();
        

        
        $result_array   =   $query->result();

        if(!$result_array){
            return array();
        }

        foreach($result_array as $key){
            $video[]    =   $key->video_id;
        }
        

        return $video;
    }
}