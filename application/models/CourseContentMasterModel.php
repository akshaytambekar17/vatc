<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin_model
 *
 * @author comc
 */
class CourseContentMasterModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCourseContentMaster() {
        $this->db->order_by('course_content_id','DESC');
        return $this->db->get('tbl_course_content_master')->result_array();
    }
    public function getCourseContentMasterById( $id ) {
        $this->db->select('ccm.*,cm.*');
        $this->db->from('tbl_course_content_master ccm');
        $this->db->join('tbl_course_master cm','cm.course_id = ccm.course_id');
        $this->db->where('ccm.course_content_id',$id);
        return $this->db->get()->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_course_content_master', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_course_content_master',$updateData);
        if($this->db->affected_rows()){
            $user_details = $this->getCandidateById($updateData['candidate_id']);
            return $user_details;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('tbl_course_content_master'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
