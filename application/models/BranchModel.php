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
class BranchModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getBranches() {
        $this->db->order_by('branch_id','DESC');
        return $this->db->get('tbl_branch_master')->result_array();
    }
    
    public function getBranchById($id) {
        $this->db->where('branch_id',$id);
        return $this->db->get('tbl_branch_master')->row_array();
    }
    
    public function getBranchByCourseId($id) {
        $this->db->where('course_id',$id);
        return $this->db->get('tbl_branch_master')->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_branch_master', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('branch_id',$updateData['branch_id']);
        $this->db->update('tbl_branch_master',$updateData);
        return true;
    }

    public function delete($id) {
        $this->db->where('branch_id',$id);
        $this->db->delete('tbl_branch_master'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
