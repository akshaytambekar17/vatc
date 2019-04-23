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
class CandidateFeesPaidModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateFeesPaid() {
        $this->db->order_by('candidate_fees_paid_id','DESC');
        return $this->db->get('tbl_candidate_fees_paid')->result_array();
    }
    
    public function getCandidateFeesPaidByGroupByCandidateEnrolledId( $limit = '', $offset = 0 ) {
        $this->db->select('tcfp.*,tccf.*,c.*,u.*,sum(tcfp.fees_paid) as feesPaid,tbm.batch_name');
        $this->db->from('tbl_candidate_fees_paid tcfp');
        $this->db->join('tbl_candidate_course_fees tccf','tccf.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id=tccf.batch_id');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_candidate c','c.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->group_by('tcfp.candidate_enrolled_id');
        $this->db->order_by('candidate_fees_paid_id','DESC');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_array();
    }
    
    public function getCandidateFeesPaidByGroupByCandidateEnrolledIdByCandidateName( $name, $limit = '', $offset = 0 ) {
        $this->db->select('tcfp.*,tccf.*,c.*,u.*,sum(tcfp.fees_paid) as feesPaid,tbm.batch_name');
        $this->db->from('tbl_candidate_fees_paid tcfp');
        $this->db->join('tbl_candidate_course_fees tccf','tccf.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id=tccf.batch_id');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_candidate c','c.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        if( !empty( $name ) ){
            $name = trim($name);
            if( true == strpos($name, ' ') ){
                $name = explode(" ", $name);
                $where = '(u.first_name like "%'.$name[0].'%" && u.last_name like "%'.$name[1].'%")';
            }else {
                $where = '(u.first_name like "%'.$name.'%" or u.last_name like "%'.$name.'%")';
            }
            $this->db->where($where);
        }
        $this->db->group_by('tcfp.candidate_enrolled_id');
        $this->db->order_by('tcfp.candidate_fees_paid_id','DESC');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_array();
    }
    
    public function getCandidateFeesPaidByCandidateEnrolledIdWithJoin( $candidateEnrolledId ) {
        $this->db->select('tcfp.*,tccf.*,c.*,u.*');
        $this->db->from('tbl_candidate_fees_paid tcfp');
        $this->db->join('tbl_candidate_course_fees tccf','tccf.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_candidate c','c.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('tcfp.candidate_enrolled_id',$candidateEnrolledId);
        $this->db->order_by('tcfp.candidate_fees_paid_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getCandidateFeesPaidById($candidateFeesPaidId) {
        $this->db->select('tcfp.*,tccf.*,c.*,u.*,tcm.course_name,tbm.batch_name');
        $this->db->from('tbl_candidate_fees_paid tcfp');
        $this->db->join('tbl_candidate_course_fees tccf','tccf.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tcfp.candidate_enrolled_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id=tce.course_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id=tccf.batch_id');
        $this->db->join('tbl_candidate c','c.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('tcfp.candidate_fees_paid_id',$candidateFeesPaidId);
        $this->db->order_by('tcfp.candidate_fees_paid_id','DESC');
        return $this->db->get()->row_array();
    }
    
    public function getCandidateFeesPaidByCandidateEnrolledIdWithoutJoin($candidateEnrolledId) {
        $this->db->where('candidate_enrolled_id',$candidateEnrolledId);
        $this->db->order_by("payment_sequence","asc");
        return $this->db->get('tbl_candidate_fees_paid')->result_array();
    }
    
    public function getCandidateCourseFeesByCandidateId($id) {
        $this->db->where('candidate_id',$id);
        return $this->db->get('tbl_candidate_fees_paid')->result_array();
    }
    
    public function getCandidateCourseFeesByBatchId($id) {
        $this->db->where('batch_id',$id);
        return $this->db->get('tbl_candidate_fees_paid')->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_fees_paid', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    
    public function update($updateData){
        $this->db->where('candidate_fees_paid_id',$updateData['candidate_fees_paid_id']);
        $this->db->update('tbl_candidate_fees_paid',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('candidate_fees_paid_id',$id);
        $this->db->delete('tbl_candidate_fees_paid'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
