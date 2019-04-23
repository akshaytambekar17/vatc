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
class BatchShiftTimingModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getBatchShiftTimings( $limit = '', $offset = 0 ) {
        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->order_by('bst.batch_timing_id','desc');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_array();
    }
    
    public function getBatchShiftTimingsByBatchNameByBranchName( $search, $limit = '', $offset = 0 ) {
        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        if( !empty( $search ) ){
            $search = trim($search);
            $where = '(bam.batch_name like "%'.$search.'%" or brm.branch_name like "%'.$search.'%")';
            $this->db->where($where);
        }
        $this->db->order_by('bst.batch_timing_id','desc');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_array();
    }
    public function getBatchShiftTimingsByFromDateToDate($fromDate,$toDate) {
        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->where('bst.from_date',$fromDate);
        $this->db->where('bst.to_date',$toDate);
        $this->db->order_by('bst.batch_timing_id','desc');
        return $this->db->get()->result_array();
    }
    public function getBatchShiftTimingsByBranchId($branchId) {
        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->where('bst.branch_id',$branchId);
        return $this->db->get()->result_array();
    }
    public function getBatchShiftTimingById($id) {
        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->where('bst.batch_timing_id',$id);
        return $this->db->get()->row_array();
    }
    public function getBatchShiftTimingByBatchId($id) {
        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->where('bst.batch_id',$id);
        return $this->db->get()->row_array();
    }
    public function getBatchShiftTimingByBatchIdByFromDateToDateByFromTimeToTime( $data ) {

        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->where('bst.batch_id',$data['batch_id']);
        $this->db->where('bst.from_date',$data['from_date']);
        $this->db->where('bst.to_date',$data['to_date']);
        $this->db->where('bst.timing_from',$data['timing_from']);
        $this->db->where('bst.timing_to',$data['timing_to']);
        return $this->db->get()->row_array();

    }
    
    public function getBatchShiftTimingByBatchIdBySubBatchIdByFromDateToDateByFromTimeToTime( $data ) {

        $this->db->select('bst.*,brm.branch_name,bam.batch_name');
        $this->db->from('tbl_batch_shift_timing bst');
        $this->db->join('tbl_branch_master brm','brm.branch_id = bst.branch_id');
        $this->db->join('tbl_batch_master bam','bam.batch_id = bst.batch_id');
        $this->db->where('bst.batch_id',$data['batch_id']);
        $this->db->where('bst.sub_batch_id',$data['sub_batch_id']);
        $this->db->where('bst.from_date',$data['from_date']);
        $this->db->where('bst.to_date',$data['to_date']);
        $this->db->where('bst.timing_from',$data['timing_from']);
        $this->db->where('bst.timing_to',$data['timing_to']);
        return $this->db->get()->row_array();

    }

    public function add( $data ){

        $this->db->insert('tbl_batch_shift_timing', $data);
        if( $this->db->affected_rows() ){
            return true;
        }else{
            return false;
        }
    }
    public function update( $updateData ){

        $this->db->where('batch_timing_id',$updateData['batch_timing_id']);
        $this->db->update('tbl_batch_shift_timing',$updateData);
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
