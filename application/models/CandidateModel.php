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
class CandidateModel extends CI_Model {
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getCandidates() {
        $this->db->select('u.*,c.*');
        $this->db->from('tbl_candidate c');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->order_by('c.candidate_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getCandidateById($id) {
        $this->db->select('u.*,c.*');
        $this->db->from('tbl_candidate c');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('c.candidate_id',$id);
        return $this->db->get()->row_array();
    }
    public function getEnquiredCandidates( $limit = '', $offset = 0 ) {
        $this->db->select('u.*,c.*');
        $this->db->from('tbl_candidate c');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('c.status_id',ENQUIRED);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('c.candidate_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getEnquiredCandidateByCandidateName( $name, $limit = '', $offset = 0 ) {
        $this->db->select('u.*,c.*');
        $this->db->from('tbl_candidate c');
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
        $this->db->where('c.status_id',ENQUIRED);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('c.candidate_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getCandidateByUserId($id) {
        $this->db->select('u.*,c.*');
        $this->db->from('tbl_candidate c');
        $this->db->join('tbl_users u','u.user_id = c.user_id');
        $this->db->where('c.user_id',$id);
        return $this->db->get()->row_array();
    }
    public function getCandidateByEmailId($email) {
        $this->db->where('email',$email);
        return $this->db->get('tbl_candidate')->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_candidate',$updateData);
        $user_details = $this->getCandidateById($updateData['candidate_id']);
        return $user_details;
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
