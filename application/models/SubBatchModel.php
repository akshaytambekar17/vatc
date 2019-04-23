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
class SubBatchModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getSubBatches() {
        $this->db->order_by('sub_batch_id','DESC');
        return $this->db->get('tbl_sub_batch_master')->result_array();
    }
    
    public function getSubBatchById( $id ) {
        $this->db->where('sub_batch_id',$id);
        return $this->db->get('tbl_sub_batch_master')->row_array();
    }
    
    
    public function add($data){
        $this->db->insert('tbl_sub_batch_master', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('sub_batch_id',$updateData['sub_batch_id']);
        $this->db->update('tbl_sub_batch_master',$updateData);
        return true;
    }

    public function delete($id) {
        $this->db->where('sub_batch_id',$id);
        $this->db->delete('tbl_sub_batch_master'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
