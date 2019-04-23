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
class EmployeeModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getEmployees() {
        $this->db->select('u.*,e.*');
        $this->db->from('tbl_employee_info e');
        $this->db->join('tbl_users u','u.user_id = e.user_id');
        $this->db->order_by('e.employee_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getEmployeeById($id) {
        $this->db->select('u.*,e.*');
        $this->db->from('tbl_employee_info e');
        $this->db->join('tbl_users u','u.user_id = e.user_id');
        $this->db->where('e.employee_id',$id);
        return $this->db->get()->row_array();
    }
    public function getEmployeeByUserId($id) {
        $this->db->where('user_id',$id);
        return $this->db->get('tbl_employee_info')->row_array();
    }
    public function getEmployeeByEmailId($email) {
        $this->db->where('email',$email);
        return $this->db->get('tbl_employee_info')->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_employee_info', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('employee_id',$updateData['employee_id']);
        $this->db->update('tbl_employee_info',$updateData);
        if($this->db->affected_rows()){
            $user_details = $this->getEmployeeById($updateData['employee_id']);
            return $user_details;
        }else{
            return false;
        }
    }

    public function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('tbl_employee_info'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
