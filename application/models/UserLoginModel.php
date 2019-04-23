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
class UserLoginModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getUserLogins() {
        return $this->db->get('trans_cms_page')->result_array();
    }
    public function getUserLoginByUsernamePassword($data) {
        $this->db->where('username',$data['username']);
        $this->db->where('password',md5($data['password']));
        $user_login_details = $this->db->get('tbl_users_login')->row_array();
        if(!empty($user_login_details)){
            return $user_login_details;
        }else{
            return false;
        }
    }
    public function getUserLoginByUsername($username) {
        $this->db->where('username',$username);
        $user_login_details = $this->db->get('tbl_users_login')->row_array();
        if(!empty($user_login_details)){
            return $user_login_details;
        }else{
            return false;
        }
    }
    public function getUserLoginByUserId($userId) {
        $this->db->where('user_id',$userId);
        $user_login_details = $this->db->get('tbl_users_login')->row_array();
        if(!empty($user_login_details)){
            return $user_login_details;
        }else{
            return false;
        }
    }
    public function getUserLoginByPassword($password,$user_id) {
        
        $this->db->where('user_id',$user_id);
        $this->db->where('password',md5($password));
        $user_login_details = $this->db->get('tbl_users_login')->row_array();
        if(!empty($user_login_details)){
            return $user_login_details;
        }else{
            return false;
        }
    }
    public function update($updateData){
        $this->db->where('user_id',$updateData['user_id']);
        $this->db->update('tbl_users_login',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
    
    
}
