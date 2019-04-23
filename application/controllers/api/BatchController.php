<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BatchController extends MY_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function getBatchList_get(){
        
        $batchList = $this->Batch->getBatches();
        if(!empty($batchList)){
            $result['success'] = true;
            $result['data'] = $batchList;
        }else{
            $result['success'] = false;
            $result['message'] = 'No Batch list found.';
        }
        
        $this->response($result);
    }
    public function getBatchesByCourseId_get(){
        $get = $this->input->get();
        if(!empty($get['course_id'])){
            $details = $get;
            $batch_list = $this->Batch->getBatchByCourseId($details['course_id']);
            if(!empty($batch_list)){
                $result['success'] = true;
                $result['data'] = $batch_list;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Batch list found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select the Course.';
        }
        
        $this->response($result);
    }
    public function getBatchShiftTimingList_get(){

        $get = $this->input->get();
        $details = $get;
        $limit = LIMIT;
        $offset = DEFAULT_OFFEST;
        if( !empty( $get['page_no'] ) ){
            $offset = $this->calculateOffset( $get['page_no'] );
        }
        
        if( !empty( $details['from_date'] ) && !empty( $details['to_date'] ) ){
            $batchShiftTiming = $this->BatchShiftTiming->getBatchShiftTimingsByFromDateToDate( $details['from_date'], $details['to_date'], $limit, $offset );
        }else if( !empty( $details['branch_id'] )){
            $batchShiftTiming = $this->BatchShiftTiming->getBatchShiftTimingsByBranchId( $details['branch_id'] );
        }else if ( !empty( $details['search'] ) ){
            $batchShiftTiming = $this->BatchShiftTiming->getBatchShiftTimingsByBatchNameByBranchName( $details['search'], $limit, $offset );
        }else{
            $batchShiftTiming = $this->BatchShiftTiming->getBatchShiftTimings( $limit, $offset );
        }

        if( !empty( $batchShiftTiming ) ){
            
            foreach( $batchShiftTiming as $value ){
                
                $batchShiftTiming = $value;
                $batchShiftTiming['timing_from'] = date('H:i',strtotime( $value['timing_from'] ) );
                $batchShiftTiming['timing_to'] = date('H:i',strtotime( $value['timing_to'] ) );
                $batchShiftTiming['timing_from_format'] = date('h:i a',strtotime( $value['timing_from'] ) );
                $batchShiftTiming['timing_to_format'] = date('h:i a',strtotime( $value['timing_to'] ) );
                $subBatchDetails = $this->SubBatch->getSubBatchById( $value['sub_batch_id'] );
                $batchShiftTiming['sub_batch_name'] = !empty( $subBatchDetails['sub_batch_name'] ) ? $subBatchDetails['sub_batch_name'] : '';
                $batchShiftTimingList[] = $batchShiftTiming;
            } 
            
            $result['success'] = true;
            $result['data'] = $batchShiftTimingList;
        }else{
            $result['success'] = false;
            $result['message'] = 'No Batch Shift Timing Data Found.';
        }

        $this->response($result);
    }
    public function getBatchShiftTimingDetails_get(){

        $get = $this->input->get();
        if( !empty( $get['batch_timing_id'] ) ){

            $details = $get;
            $batchShiftTimingDetails = $this->BatchShiftTiming->getBatchShiftTimingById($details['batch_timing_id']);

            if( !empty( $batchShiftTimingDetails) ){
                $batchShiftTimingDetails['timing_from'] = date('H:i',strtotime( $batchShiftTimingDetails['timing_from'] ) );
                $batchShiftTimingDetails['timing_to'] = date('H:i',strtotime( $batchShiftTimingDetails['timing_to'] ) );
                $batchShiftTimingDetails['timing_from_format'] = date('h:i a',strtotime( $batchShiftTimingDetails['timing_from'] ) );
                $batchShiftTimingDetails['timing_to_format'] = date('h:i a',strtotime( $batchShiftTimingDetails['timing_to'] ) );
                $subBatchDetails = $this->SubBatch->getSubBatchById( $batchShiftTimingDetails['sub_batch_id'] );
                $batchShiftTimingDetails['sub_batch_name'] = !empty( $subBatchDetails['sub_batch_name'] ) ? $subBatchDetails['sub_batch_name'] : '';
                $result['success'] = true;
                $result['data'] = $batchShiftTimingDetails;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Batch Shift Timing Data Found.';
            }

        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Batch Shift Timing.';
        }

        $this->response($result);
    }

    public function addBatchShiftTiming_post(){
        $post = $this->input->post();

        if( !empty( $post['batch_id'] ) && $post['batch_id'] != NULL && $post['batch_id'] != 'null' ) {
            if( !empty( $post['branch_id'] ) && $post['branch_id'] != NULL && $post['branch_id'] != 'null' ){
                if( !empty( $post['sub_batch_id'] ) && $post['sub_batch_id'] != NULL && $post['sub_batch_id'] != 'null' ){
                    
                    $details = $post;
                    $details['from_date'] = date('Y-m-d',strtotime( $details['from_date'] ) );
                    $details['to_date'] = date('Y-m-d',strtotime( $details['to_date'] ) );
                    $details['timing_from'] = date('h:i A',strtotime( $details['timing_from'] ) );
                    $details['timing_to'] = date('h:i A',strtotime( $details['timing_to'] ) );
                    $batchShiftTimingDetails = $this->BatchShiftTiming->getBatchShiftTimingByBatchIdBySubBatchIdByFromDateToDateByFromTimeToTime( $details );
                    if( empty( $batchShiftTimingDetails ) ){

                        $details['is_active'] = ACTIVE;
                        $details['created_date'] = CURRENTDATE;

                        $insertResult = $this->BatchShiftTiming->add( $details );
                        if( !empty( $insertResult ) ){
                            $result['success'] = true;
                            $result['message'] = 'Batch Shift Timing has been created successfully.';
                        }else{
                            $result['success'] = false;
                            $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                        }
                    }else{
                        $result['success'] = false;
                        $result['message'] = 'This batch is already exist';
                    }
                }else {
                    $result['success'] = false;
                    $result['message'] = 'Please Select Sub Batch.';
                }
            }else {
                $result['success'] = false;
                $result['message'] = 'Please Select Branch.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Batch.';
        }
        $this->response($result);
    }
    public function updateBatchShiftTiming_post(){
        $post = $this->input->post();

        if( !empty( $post['batch_timing_id'] )) {

            $details = $post;
            $details['from_date'] = date('Y-m-d',strtotime( $details['from_date'] ) );
            $details['to_date'] = date('Y-m-d',strtotime( $details['to_date'] ) );
            $details['timing_from'] = date('h:i A',strtotime( $details['timing_from'] ) );
            $details['timing_to'] = date('h:i A',strtotime( $details['timing_to'] ) );
            $batchShiftTimingDetails = $this->BatchShiftTiming->getBatchShiftTimingByBatchIdByFromDateToDateByFromTimeToTime( $details );
            if( empty( $batchShiftTimingDetails ) ){
                
                $updateResult = $this->BatchShiftTiming->update( $details );
                if( !empty( $updateResult ) ){
                    $result['success'] = true;
                    $result['message'] = 'Batch Shift Timing has been updated successfully.';
                }else{
                    $result['success'] = false;
                    $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                }
            }else{
                $result['success'] = false;
                $result['message'] = 'This batch is already exist';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Batch Shift Timing.';
        }
        $this->response($result);
    }
    
    public function getBatchActivitesList_get(){
        $get = $this->input->get();
        
        if(!empty($get['employee_id'])){
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            $activitiesList = $this->BatchActivites->getBatchActivitesByTranierId( $details['employee_id'], $limit, $offset );
            $batchActivitiesGroupByBatchList = $this->BatchActivites->getBatchActivitesGroupByBatchId( $limit, $offset );
            
            if( !empty( $activitiesList ) && !empty( $batchActivitiesGroupByBatchList ) ){
                
                foreach( $batchActivitiesGroupByBatchList as $valueBatch ){
                    
                    $batchActivityDetails = $valueBatch;
                    $i = 1;
                    foreach( $activitiesList as $valueActivitesDetails ){
                        if( $valueActivitesDetails['batch_id'] == $valueBatch['batch_id'] ){
                            if( 1 == $i ){
                                $toDate = $valueActivitesDetails['activity_date'];
                            }else{
                                $fromDate = $valueActivitesDetails['activity_date'];
                            }
                            $i++;
                            $batchName = $valueActivitesDetails['batch_name'];
                            $courseName = $valueActivitesDetails['course_name'];
                        }    
                    }
                    $batchActivityDetails['from_date'] = !empty( $fromDate ) ? $fromDate : $toDate ;
                    $batchActivityDetails['to_date'] = $toDate;
                    $batchActivityDetails['batch_name'] = $batchName;
                    $batchActivityDetails['course_name'] = $courseName;
                    $batchActivitiesList[] = $batchActivityDetails;
                    $batchActivityDetails = array();
                }
                $result['success'] = true;
                $result['data'] = $batchActivitiesList;
            }else{
                $result['success'] = false;
                $result['message'] = 'No batch activites list found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
        }    
        $this->response($result);
    }
    
    public function getBatchActivitesDetails_get(){
        
        $get = $this->input->get();
        if(!empty($get['batch_id'])){
            $details = $get;
            $batchActivitesList = $this->BatchActivites->getBatchActivitesByBatchId( $details['batch_id'] );
            
            if( !empty( $batchActivitesList ) ){
                foreach( $batchActivitesList as $value ){
                    $activityDetails = $value;
                    $batchActivitesStatusDetails = $this->BatchActivitesWorksheet->getBatchActivitesWorksheetByBatchActivitesId( $value['batch_activities_id'] );
                    if( !empty( $batchActivitesStatusDetails ) && count( $batchActivitesStatusDetails ) > 0 ){
                        $activityDetails['activity_status'] = 'performed';
                    }
                    $batchActivitesDetails[] = $activityDetails;
                }
                
                $result['success'] = true;
                $result['data'] = $batchActivitesDetails;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Activites found. ';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae Select Batch.';
        }  
        $this->response($result);
    }
    
    public function getBatchActivitesStatusDetails_get(){
        
        $get = $this->input->get();
        if(!empty($get['batch_activities_id'])){
            $details = $get;
            $batchActivitesDetails = $this->BatchActivitesWorksheet->getBatchActivitesWorksheetByBatchActivitesId( $details['batch_activities_id'] );
            
            if( !empty( $batchActivitesDetails ) ){
                $result['success'] = true;
                $result['data'] = $batchActivitesDetails;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Activites were performed. ';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae Select Activites.';
        }  
        $this->response($result);
    }
    
    public function getSubBatchList_get(){
        $get = $this->input->get();
        
        $details = $get;
        $subBatchList = $this->SubBatch->getSubBatches();

        if( !empty( $subBatchList ) ){
            $result['success'] = true;
            $result['data'] = $subBatchList;
        }else{
            $result['success'] = false;
            $result['message'] = 'No sub batch list found.';
        }
        
        $this->response($result);
    }
    
}
