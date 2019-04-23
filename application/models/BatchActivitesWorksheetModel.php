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
class BatchActivitesWorksheetModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getBatchActivitesWorksheet() {
        $this->db->select('tbaw.*,u.*,bm.batch_name');
        $this->db->from('tbl_batch_activities_worksheet tbaw');
        $this->db->join('tbl_batch_activities tba','tba.batch_activities_id = tbaw.batch_activities_id');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tbaw.candidate_enrolled_id');
        $this->db->join('tbl_candidate tc','c.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users tu','tc.user_id = tu.user_id');
        $this->db->order_by('batch_activities_worksheet_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getBatchActivitesWorksheetByBatchActivitesId( $batchActivitesId ) {
        $this->db->select('tbaw.*,tba.*,tu.*');
        $this->db->from('tbl_batch_activities_worksheet tbaw');
        $this->db->join('tbl_batch_activities tba','tba.batch_activities_id = tbaw.batch_activities_id');
        $this->db->join('tbl_candidate_enrolled tce','tce.candidate_enrolled_id = tbaw.candidate_enrolled_id');
        $this->db->join('tbl_candidate tc','tc.candidate_id = tce.candidate_id');
        $this->db->join('tbl_users tu','tc.user_id = tu.user_id');
        $this->db->where('tbaw.batch_activities_id',$batchActivitesId);
        $this->db->order_by('batch_activities_worksheet_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getBatchActivitesWorksheetByBatchId( $batchId ) {
        $this->db->select('tba.*,u.*,bm.batch_name');
        $this->db->from('tbl_batch_activities tba');
        $this->db->join('tbl_batch_master bm','bm.batch_id = tba.batch_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tba.trainer_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tba.batch_id',$batchId);
        $this->db->order_by('batch_activities_id','DESC');
        return $this->db->get()->result_array();
    }
    public function getBatchActivitesWorksheetByTranierId( $employeeId, $limit = '', $offset = 0 ) {
        $this->db->select('tba.*,u.*,bm.batch_name,tcm.course_name');
        $this->db->from('tbl_batch_activities tba');
        $this->db->join('tbl_batch_master bm','bm.batch_id = tba.batch_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id = bm.course_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tba.trainer_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('ef.employee_id',$employeeId);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('batch_activities_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getBatchActivitesWorksheetGroupByBatchId( $limit = '', $offset = 0 ) {
        $this->db->group_by('batch_id');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('batch_id','DESC');
        $this->db->order_by('batch_activities_id','DESC');
        return $this->db->get('tbl_batch_activities')->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_batch_activities', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('batch_id',$updateData['batch_id']);
        $this->db->update('tbl_batch_activities',$updateData);
        return true;
    }

    public function delete($id) {
        $this->db->where('batch_id',$id);
        $this->db->delete('tbl_batch_activities'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
