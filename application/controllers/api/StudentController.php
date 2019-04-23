<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class StudentController extends MY_Controller {
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function getStudentBatchScheduleList_get(){
        
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            
            $batchScheduleList = $this->StudentBatchSchedule->getStudentBatchScheduleByEmployeeId($details['employee_id']);
            $batchSchedulesGroupByBatchList = $this->StudentBatchSchedule->getStudentBatchSchedulesGroupByBatchId( $limit, $offset );
            if( !empty( $batchScheduleList ) && !empty( $batchSchedulesGroupByBatchList ) ){
                
                foreach( $batchSchedulesGroupByBatchList as $valueBatch ){
                    $batchSchedulesDetails = $valueBatch;
                    $i = 1;
                    foreach( $batchScheduleList as $valueBatchScheduleDetails ){
                        if( $valueBatchScheduleDetails['batch_id'] == $valueBatch['batch_id'] ){
                            if( 1 == $i ){
                                $toDate = $valueBatchScheduleDetails['date'];
                            }else{
                                $fromDate = $valueBatchScheduleDetails['date'];
                            }
                            $i++;
                            $batchName = $valueBatchScheduleDetails['batch_name'];
                            $courseName = $valueBatchScheduleDetails['course_name'];
                        }    
                    }
                    $batchSchedulesDetails['from_date'] = !empty( $fromDate ) ? $fromDate : $toDate ;
                    $batchSchedulesDetails['to_date'] = $toDate;
                    $batchSchedulesDetails['batch_name'] = $batchName;
                    $batchSchedulesDetails['course_name'] = $courseName;
                    $studentBatchScheduleList[] = $batchSchedulesDetails;
                    $batchSchedulesDetails = array();
                }
                
                $result['success'] = true;
                $result['data'] = $studentBatchScheduleList;
                
            }else{
                $result['success'] = false;
                $result['message'] = 'No Student Batch Schedule List';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
        }  
        $this->response($result);
        
    }
    public function getStudentBatchScheduleDetails_get(){
        
        $get = $this->input->get();
        if(!empty($get['batch_id'])){
            $details = $get;
            $studentBatchScheduleDetails = $this->StudentBatchSchedule->getStudentBatchScheduleByBatchId( $details['batch_id'] );
            
            if(!empty($studentBatchScheduleDetails)){
                $result['success'] = true;
                $result['data'] = $studentBatchScheduleDetails;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Student Batch Schedule Data Found';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae Select Batch.';
        }  
        $this->response($result);
    }
    
}
