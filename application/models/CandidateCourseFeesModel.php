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
class CandidateCourseFeesModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateCourseFees() {
        $this->db->order_by('candidate_course_fees_id','DESC');
        return $this->db->get('tbl_candidate_course_fees')->result_array();
    }
    public function getCandidateCourseFeesById($id) {
        $this->db->where('candidate_course_fees_id',$id);
        return $this->db->get('tbl_candidate_course_fees')->row_array();
    }
    public function getCandidateCourseFeesByEnrolledId($CandidateEnrolledId) {
        $this->db->select('tccf.*,tu.*,tc.*,tcm.course_name,tbm.batch_name');
        $this->db->from('tbl_candidate_course_fees tccf');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tccf.candidate_enrolled_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id=tce.course_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id=tccf.batch_id');
        $this->db->join('tbl_candidate tc','tc.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users tu','tu.user_id = tc.user_id');
        $this->db->where('tccf.candidate_enrolled_id',$CandidateEnrolledId);
        return $this->db->get()->row_array();
    }
    public function getCandidateCourseFeesByCandidateId($id) {
        $this->db->select('ccf.*,u.*,c.*');
        $this->db->from('tbl_candidate_course_fees ccf');
        $this->db->join('tbl_candidate_enrolled ce','ce.candidate_enrolled_id = ccf.candidate_enrolled_id');
        $this->db->join('tbl_candidate c','c.candidate_id = ccf.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('ce.candidate_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateCourseFeesByBatchId( $batchId ) {
        $this->db->select('ccf.*,u.*,c.*');
        $this->db->from('tbl_candidate_course_fees ccf');
        $this->db->join('tbl_candidate_enrolled ce','ce.candidate_enrolled_id = ccf.candidate_enrolled_id');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('batch_id',$batchId);
        return $this->db->get()->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_course_fees', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_course_fees_id',$updateData['candidate_course_fees_id']);
        $this->db->update('tbl_candidate_course_fees',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
    public function updateByCandidateEnrolledId($updateData){
        $this->db->where('candidate_enrolled_id',$updateData['candidate_enrolled_id']);
        $this->db->update('tbl_candidate_course_fees',$updateData);
        return $this->getCandidateCourseFeesByEnrolledId($updateData['candidate_enrolled_id']);
    }

    public function delete($id) {
        $this->db->where('candidate_course_fees_id',$id);
        $this->db->delete('tbl_candidate_course_fees'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    /***
        @prashant 
        do not delete
        ***/

    public function getMaxPrnByBatch($batchId) {
        $sql = "select Max(candidate_prn) as prn from tbl_candidate_course_fees where batch_id = ? ";
        $query = $this->db->query($sql, array($batchId));
        $data = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
        }
        return $data;
    }

}
