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
class CandidateEnrolledModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateEnrolled( $limit = '', $offset = 0 ) {
        $this->db->select('ce.*,u.*,c.*');
        $this->db->from('tbl_candidate_enrolled ce');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = ce.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','c.user_id = u.user_id');
        $this->db->where('c.status_id',ENROLLED);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('ce.candidate_enrolled_id','desc');
        return $this->db->get()->result_array();
    }
    public function getCandidateEnrolledByEmployeeId($employee_id) {
        $this->db->select('ce.*,u.*,c.*');
        $this->db->from('tbl_candidate_enrolled ce');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = ce.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','c.user_id = u.user_id');
        $this->db->where('cc.employee_id',$employee_id);
        $this->db->where('c.status_id',ENROLLED);
        return $this->db->get()->result_array();
    }
    public function getCandidateEnrolledByEmployeeIdCandidateName( $employee_id, $name ) {
        $this->db->select('ce.*,u.*,c.*');
        $this->db->from('tbl_candidate_enrolled ce');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = ce.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','c.user_id = u.user_id');
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
        
        $this->db->where('cc.employee_id',$employee_id);
        $this->db->where('c.status_id',ENROLLED);
        $this->db->order_by('ce.candidate_enrolled_id','desc');
        return $this->db->get()->result_array();
    }
    public function getCandidateEnrolledByCandidateName( $name, $limit = '', $offset = 0 ) {
        $this->db->select('ce.*,u.*,c.*');
        $this->db->from('tbl_candidate_enrolled ce');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = ce.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','c.user_id = u.user_id');
        if(!empty($name)){
            $where = '(u.first_name like "%'.$name.'%" or u.last_name like "%'.$name.'%")';
            $this->db->where($where);
        }
        $this->db->where('c.status_id',ENROLLED);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('ce.candidate_enrolled_id','desc');
        return $this->db->get()->result_array();
    }
    public function getCandidateEnrolledById($id) {
        $this->db->select('ce.*,u.*,c.*,co.*');
        $this->db->from('tbl_candidate_enrolled ce');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->join('tbl_course_master co','ce.course_id = co.course_id');
        $this->db->where('ce.candidate_enrolled_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateEnrolledByCandidateId($id) {
        $this->db->select('ce.*,u.*,c.*,co.*');
        $this->db->from('tbl_candidate_enrolled ce');
        $this->db->join('tbl_candidate c','c.candidate_id = ce.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->join('tbl_course_master co','ce.course_id = co.course_id');
        $this->db->where('ce.candidate_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateByEmailId($email) {
        $this->db->where('email',$email);
        return $this->db->get('tbl_candidate')->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_enrolled', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_candidate',$updateData);
        if($this->db->affected_rows()){
            $user_details = $this->getCandidateById($updateData['candidate_id']);
            return $user_details;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('tbl_candidate'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
