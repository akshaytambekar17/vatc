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
class StudentBatchScheduleModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getStudentBatchSchedule() {
        
        $this->db->select('tsbs.*,tcscm.*,tcsscm.*,u.*,tbm.batch_name,tcm.course_name');
        $this->db->from('tbl_students_batch_schedule tsbs');
        $this->db->join('tbl_course_sub_sub_content_master tcsscm','tcsscm.course_sub_sub_content_id = tsbs.course_sub_sub_content_id');
        $this->db->join('tbl_course_sub_content_master tcscm','tcscm.course_sub_content_id = tcsscm.course_sub_content_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id = tsbs.batch_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id = tcscm.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tsbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        return $this->db->get()->result_array();
    }
    
    public function getStudentBatchScheduleById($id) {
        $this->db->select('tsbs.*,tcscm.*,tcsscm.*,u.*,tbm.batch_name,tcm.course_name');
        $this->db->from('tbl_students_batch_schedule tsbs');
        $this->db->join('tbl_course_sub_sub_content_master tcsscm','tcsscm.course_sub_sub_content_id = tsbs.course_sub_sub_content_id');
        $this->db->join('tbl_course_sub_content_master tcscm','tcscm.course_sub_content_id = tcsscm.course_sub_content_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id = tsbs.batch_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id = tcscm.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tsbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tsbs.students_batch_schedule_id',$id);
        return $this->db->get()->row_array();
    }
    
    public function getStudentBatchScheduleByBatchId( $batchId ) {
        
        $this->db->select('tsbs.*,tcscm.*,tcsscm.*,u.*,tbm.batch_name,tcm.course_name');
        $this->db->from('tbl_students_batch_schedule tsbs');
        $this->db->join('tbl_course_sub_sub_content_master tcsscm','tcsscm.course_sub_sub_content_id = tsbs.course_sub_sub_content_id');
        $this->db->join('tbl_course_sub_content_master tcscm','tcscm.course_sub_content_id = tcsscm.course_sub_content_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id = tsbs.batch_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id = tcscm.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tsbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tsbs.batch_id',$batchId);
        $this->db->order_by('students_batch_schedule_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getStudentBatchScheduleByEmployeeId( $employeeId, $limit = '', $offset = 0 ) {
        $this->db->select('tsbs.*,tcscm.*,tcsscm.*,u.*,tbm.batch_name,tcm.course_name');
        $this->db->from('tbl_students_batch_schedule tsbs');
        $this->db->join('tbl_course_sub_sub_content_master tcsscm','tcsscm.course_sub_sub_content_id = tsbs.course_sub_sub_content_id');
        $this->db->join('tbl_course_sub_content_master tcscm','tcscm.course_sub_content_id = tcsscm.course_sub_content_id');
        $this->db->join('tbl_batch_master tbm','tbm.batch_id = tsbs.batch_id');
        $this->db->join('tbl_course_master tcm','tcm.course_id = tcscm.course_content_id');
        $this->db->join('tbl_employee_info ef','ef.employee_id = tsbs.employee_id');
        $this->db->join('tbl_users u','ef.user_id = u.user_id');
        $this->db->where('tsbs.employee_id',$employeeId);
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('students_batch_schedule_id','DESC');
        return $this->db->get()->result_array();
    }
    
    public function getStudentBatchSchedulesGroupByBatchId( $limit = '', $offset = 0 ) {
        $this->db->group_by('batch_id');
        if( !empty( $limit ) ){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('batch_id','DESC');
        $this->db->order_by('students_batch_schedule_id','DESC');
        return $this->db->get('tbl_students_batch_schedule')->result_array();
    }
    
    public function add($data){
        $this->db->insert('tbl_students_batch_schedule', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('students_batch_schedule_id',$updateData['students_batch_schedule_id']);
        $this->db->update('tbl_students_batch_schedule',$updateData);
        return true;
    }

    public function delete($id) {
        $this->db->where('students_batch_schedule_id',$id);
        $this->db->delete('tbl_students_batch_schedule'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
