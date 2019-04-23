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
class TrainersBatchScheduleModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getTrainersBatchSchedules() {
        $this->db->order_by('trainers_batch_schedule_id','DESC');
        return $this->db->get('tbl_trainers_batch_schedule')->result_array();
    }
    
    public function getTrainersBatchSchedulesGroupByBatchId( $limit = '', $offset = 0 ) {
        $this->db->group_by('batch_id');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('batch_id','DESC');
        $this->db->order_by('trainers_batch_schedule_id','DESC');
        return $this->db->get('tbl_trainers_batch_schedule')->result_array();
    }
    
    public function getTrainersBatchSchedulesByEmployeeId( $employee_id, $limit = '', $offset = 0 ) {
        $this->db->select('tbs.*,u.*,bm.batch_name,ccm.course_content');
        $this->db->from('tbl_trainers_batch_schedule tbs');
        $this->db->join('tbl_batch_master bm','bm.batch_id = tbs.batch_id');
        $this->db->join('tbl_course_content_master ccm','ccm.course_content_id = tbs.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tbs.employee_id',$employee_id);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('trainers_batch_schedule_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getTrainersBatchSchedulesByCourseContentByEmployeeId( $employeeId, $search , $limit = '', $offset = 0  ) {
        $this->db->select('tbs.*,u.*,bm.batch_name,ccm.course_content');
        $this->db->from('tbl_trainers_batch_schedule tbs');
        $this->db->join('tbl_batch_master bm','bm.batch_id = tbs.batch_id');
        $this->db->join('tbl_course_content_master ccm','ccm.course_content_id = tbs.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tbs.employee_id',$employeeId);
        if( !empty( $search ) ){
            $search = trim($search);
            $where = '(ccm.course_content like "%'.$search.'%")';
            $this->db->where($where);
        }
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result_array();
    }
    public function getTrainersBatchSchedulesByBatchId( $batchId ) {
        $this->db->select('tbs.*,u.*,bm.batch_name,ccm.course_content,cm.course_name');
        $this->db->from('tbl_trainers_batch_schedule tbs');
        $this->db->join('tbl_batch_master bm','bm.batch_id = tbs.batch_id');
        $this->db->join('tbl_course_content_master ccm','ccm.course_content_id = tbs.course_content_id');
        $this->db->join('tbl_course_master cm','cm.course_id = ccm.course_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tbs.batch_id',$batchId);
        $this->db->order_by('trainers_batch_schedule_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getTrainersBatchSchedulesById($id) {
        $this->db->select('tbs.*,u.*,bm.batch_name,ccm.course_content');
        $this->db->from('tbl_trainers_batch_schedule tbs');
        $this->db->join('tbl_batch_master bm','bm.batch_id = tbs.batch_id');
        $this->db->join('tbl_course_content_master ccm','ccm.course_content_id = tbs.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tbs.trainers_batch_schedule_id',$id);
        return $this->db->get()->row_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_candidate_enrolled', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('candidate_id',$updateData['candidate_id']);
        $this->db->update('tbl_candidate',$updateData);
        if($this->db->affected_rows()){
            $user_details = $this->getCandidateById($updateData['candidate_id']);
            return $user_details;
        }else{
            return false;
        }
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
