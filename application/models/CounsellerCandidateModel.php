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
class CounsellerCandidateModel extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getCounsellerCandidatesWithCandidate( $limit = '', $offset = 0 ) {
        $this->db->select('cc.*,u.*,c.*');
        $this->db->from('tbl_counsellor_candidate cc');
        $this->db->join('tbl_candidate c','c.candidate_id = cc.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('c.status_id',ACCEPTED_CANDIDATE);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('cc.candidate_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getCounsellerCandidatesByEmployeeId($employee_id) {
        $this->db->select('cc.*,u.*,c.*');
        $this->db->from('tbl_counsellor_candidate cc');
        $this->db->join('tbl_candidate c','c.candidate_id = cc.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cc.employee_id',$employee_id);
        $this->db->where('c.status_id',ACCEPTED_CANDIDATE);
        return $this->db->get()->result_array();
    }
    public function getCounsellerCandidatesByEmployeeIdCandidateName( $employee_id, $name ) {
        $this->db->select('cc.*,u.*,c.*');
        $this->db->from('tbl_counsellor_candidate cc');
        $this->db->join('tbl_candidate c','c.candidate_id = cc.candidate_id');
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
        $this->db->where('cc.employee_id',$employee_id);
        $this->db->where('c.status_id',ACCEPTED_CANDIDATE);
        return $this->db->get()->result_array();
    }
    public function getCounsellerCandidatesByCandidateName( $name, $limit = '', $offset = 0 ) {
        $this->db->select('cc.*,u.*,c.*');
        $this->db->from('tbl_counsellor_candidate cc');
        $this->db->join('tbl_candidate c','c.candidate_id = cc.candidate_id');
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
        $this->db->where('c.status_id',ACCEPTED_CANDIDATE);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('cc.candidate_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getCounsellerCandidatesRejectedByEmployeeId($employee_id) {
        $this->db->select('cc.*,u.*,c.*');
        $this->db->from('tbl_counsellor_candidate cc');
        $this->db->join('tbl_candidate c','c.candidate_id = cc.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cc.employee_id',$employee_id);
        $this->db->where('c.status_id',REJECTED);
        return $this->db->get()->result_array();
    }
    public function getCounsellerCandidatesAcceptedByCandidateId($candidate_id) {
        $this->db->select('cc.*,u.*,c.*');
        $this->db->from('tbl_counsellor_candidate cc');
        $this->db->join('tbl_candidate c','c.candidate_id = cc.candidate_id');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('cc.candidate_id',$candidate_id);
        $this->db->where('c.status_id',ACCEPTED_CANDIDATE);
        return $this->db->get()->row_array();
    }
    
    public function getUserLoginByPassword($data) {
        $this->db->where('username',$data['username']);
        $this->db->where('password',md5($data['password']));
        $user_login_details = $this->db->get('tbl_users_login')->row_array();
        if(!empty($user_login_details)){
            return $user_login_details;
        }else{
            return false;
        }
    }
    public function add($data){
        $this->db->insert('tbl_counsellor_candidate', $data);
        return true;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_counsellor_candidate',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
