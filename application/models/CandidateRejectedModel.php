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
class CandidateRejectedModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidateRejected( $limit = '', $offset = 0 ) {
        $this->db->select('cr.*,u.*,c.*');
        $this->db->from('tbl_candidate_rejected cr');
        $this->db->join('tbl_candidate c','c.candidate_id = cr.candidate_id');
        $this->db->join('tbl_users u','c.user_id = u.user_id');
        $this->db->order_by('cr.candidate_rejected_id','DESC');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('cr.candidate_rejected_id','desc');
        return $this->db->get()->result_array();
    }
    public function getCandidateRejectedByEmployeeId($employee_id) {
        $this->db->select('cr.*,u.*,c.*');
        $this->db->from('tbl_candidate_rejected cr');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = cr.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = cr.candidate_id');
        $this->db->join('tbl_users u','c.user_id = u.user_id');
        $this->db->where('cc.employee_id',$employee_id);
        return $this->db->get()->result_array();
    }
    public function getCandidateRejectedByEmployeeIdCandidateName($employee_id,$name) {
        $this->db->select('cr.*,u.*,c.*');
        $this->db->from('tbl_candidate_rejected cr');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = cr.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = cr.candidate_id');
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
        return $this->db->get()->result_array();
    }
    public function getCandidateRejectedByCandidateName( $name, $limit = '', $offset = 0 ) {
        $this->db->select('cr.*,u.*,c.*');
        $this->db->from('tbl_candidate_rejected cr');
        $this->db->join('tbl_counsellor_candidate cc','cc.candidate_id = cr.candidate_id');
        $this->db->join('tbl_candidate c','c.candidate_id = cr.candidate_id');
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
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('cr.candidate_rejected_id','desc');
        return $this->db->get()->result_array();
    }
    public function getCandidateRejectedById($id) {
        $this->db->select('cr.*,u.*,c.*,co.*');
        $this->db->from('tbl_candidate_rejected cr');
        $this->db->join('tbl_candidate c','c.candidate_id = cr.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cr.candidate_rejected_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateRejectedByCandidateId($id) {
        $this->db->select('cr.*,u.*,c.*');
        $this->db->from('tbl_candidate_rejected cr');
        $this->db->join('tbl_candidate c','c.candidate_id = cr.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cr.candidate_id',$id);
        return $this->db->get()->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_rejected', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_rejected_id',$updateData['candidate_rejected_id']);
        $this->db->update('tbl_candidate_rejected',$updateData);
        if($this->db->affected_rows()){
            return true;
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
