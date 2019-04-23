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
class CandidateFeesStructureModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateFeesStructure() {
        $this->db->order_by('candidate_fees_structure_id','DESC');
        return $this->db->get('tbl_candidate_fees_structure')->result_array();
    }
    public function getCandidateFeesStructureByCourseFeesId($id) {
        $this->db->where('candidate_course_fees_id',$id);
        return $this->db->get('tbl_candidate_fees_structure')->result_array();
    }
    public function getCandidateCourseFeesByCourseFeesId($id) {
        $this->db->where('candidate_course_fees_id',$id);
        return $this->db->get('tbl_candidate_fees_structure')->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_fees_structure', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_fees_structure_id',$updateData['candidate_fees_structure_id']);
        $this->db->update('tbl_candidate_fees_structure',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('candidate_fees_structure_id',$id);
        $this->db->delete('tbl_candidate_fees_structure'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
