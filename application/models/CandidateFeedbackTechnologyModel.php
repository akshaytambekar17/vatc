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
class CandidateFeedbackTechnologyModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateFeedbacks() {
        $this->db->order_by('candidate_feedback_technology_id','DESC');
        return $this->db->get('tbl_candidate_feedback_technology')->result_array();
    }
    public function getCandidateFeedbackTechnologyById($id) {
        $this->db->select('cft.*,u.*,c.*');
        $this->db->from('tbl_candidate_feedback_technology cft');
        $this->db->join('tbl_candidate c','c.candidate_id = cft.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cft.candidate_feedback_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateFeedbackTechnologyByCandidateId($id) {
        $this->db->select('cft.*,u.*,c.*');
        $this->db->from('tbl_candidate_feedback_technology cft');
        $this->db->join('tbl_candidate c','c.candidate_id = cft.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cft.candidate_id',$id);
        return $this->db->get()->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_feedback_technology', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_candidate_feedback_technology',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('tbl_candidate_feedback_technology'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
