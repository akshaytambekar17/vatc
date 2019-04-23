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
class CandidateFeedbackModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateFeedbacks() {
        $this->db->order_by('candidate_feedback_id','DESC');
        return $this->db->get('tbl_candidate_feedback')->result_array();
    }
    public function getCandidateFeedbackById($id) {
        $this->db->select('cf.*,u.*,c.*');
        $this->db->from('tbl_candidate_feedback cf');
        $this->db->join('tbl_candidate c','c.candidate_id = cf.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cf.candidate_feedback_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateFeedbackByCandidateId($id) {
        $this->db->select('cf.*,u.*,c.*');
        $this->db->from('tbl_candidate_feedback cf');
        $this->db->join('tbl_candidate c','c.candidate_id = cf.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cf.candidate_id',$id);
        return $this->db->get()->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_feedback', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_candidate_feedback',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('tbl_candidate_feedback'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
