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
class BatchModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getBatches() {
        return $this->db->get('tbl_batch_master')->result_array();
    }
    
    public function getBatchById($id) {
        $this->db->where('batch_id',$id);
        return $this->db->get('tbl_batch_master')->row_array();
    }
    
    public function getBatchByCourseId($id) {
        $this->db->where('course_id',$id);
        return $this->db->get('tbl_batch_master')->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_batch_master', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('batch_id',$updateData['batch_id']);
        $this->db->update('tbl_batch_master',$updateData);
        return true;
    }

    public function delete($id) {
        $this->db->where('batch_id',$id);
        $this->db->delete('tbl_batch_master'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
