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
class UserModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getUsers() {
        $this->db->order_by('id','DESC');
        return $this->db->get('tbl_users')->result_array();
    }
    public function getUserById($id) {
        $this->db->select('u.*,ru.*,rm.*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_role_users ru','ru.user_id = u.user_id');
        $this->db->join('tbl_role_master rm', 'rm.role_id = ru.role_id');
        $this->db->where('u.user_id',$id);
        return $this->db->get()->row_array();
    }
    
    public function getUserByIdByRoleName( $data ) {
        $this->db->select('u.*,ru.*,rm.*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_role_users ru','ru.user_id = u.user_id');
        $this->db->join('tbl_role_master rm', 'rm.role_id = ru.role_id');
        $this->db->where('ru.user_id',$data['userId']);
        $this->db->where('rm.role_name',$data['roleName']);
        return $this->db->get()->row_array();
    }
    
    public function getUserByEmailId($email) {
        $this->db->select('u.*,rs.*');
        $this->db->from('tbl_users u');
        $this->db->join('tbl_role_users ru','ru.user_id = u.user_id');
        $this->db->where('u.email',$email);
        return $this->db->get()->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_users', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('user_id',$updateData['user_id']);
        $this->db->update('tbl_users',$updateData);
        if($this->db->affected_rows()){
            $user_details = $this->getUserById($updateData['user_id']);
            return $user_details;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('tbl_users'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
