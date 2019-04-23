<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BranchController extends MY_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function getBrancheList_get(){
        
        $branchList = $this->Branch->getBranches();
        
        if(!empty($branchList)){
            $result['success'] = true;
            $result['data'] = $branchList;
        }else{
            $result['success'] = false;
            $result['message'] = 'No Branch list found.';
        }
        
        $this->response($result);
    }
    public function getBrancheDetails_get(){
        $get = $this->input->get();
        
        if(!empty($get['branch_id'])){
            
            $details = $get;
            $branchDetails = $this->Branch->getBranchById($details['branch_id']);
            
            if(!empty($branchDetails)){
                $result['success'] = true;
                $result['data'] = $branchDetails;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Branch list found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select the branch.';
        }
        
        $this->response($result);
    }
    public function getBranchShiftTimingList_get(){

        $get = $this->input->get();
        $details = $get;
        if( !empty( $details['from_date'] ) && !empty( $details['to_date'] ) ){
            $batchShiftTimingList = $this->BranchShiftTiming->getBranchShiftTimingsByFromDateToDate($details['from_date'],$details['to_date']);
        }else if( !empty( $details['branch_id'] )){
            $batchShiftTimingList = $this->BranchShiftTiming->getBranchShiftTimingsByBranchId($details['branch_id']);
        }else{
            $batchShiftTimingList = $this->BranchShiftTiming->getBranchShiftTimings();
        }

        if( !empty( $batchShiftTimingList ) ){
            $result['success'] = true;
            $result['data'] = $batchShiftTimingList;
        }else{
            $result['success'] = false;
            $result['message'] = 'No Branch Shift Timing Data Found.';
        }

        $this->response($result);
    }
    public function getBranchShiftTimingDetails_get(){

        $get = $this->input->get();
        if( !empty( $get['batch_timing_id'] ) ){

            $details = $get;
            $batchShiftTimingDetails = $this->BranchShiftTiming->getBranchShiftTimingById($details['batch_timing_id']);

            if( !empty( $batchShiftTimingDetails) ){
                $result['success'] = true;
                $result['data'] = $batchShiftTimingDetails;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Branch Shift Timing Data Found.';
            }

        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Branch Shift Timing.';
        }

        $this->response($result);
    }

    public function addBranchShiftTiming_post(){
        $post = $this->input->post();

        if( !empty( $post['batch_id'] )) {

            if( !empty( $post['branch_id'] ) ){

                $details = $post;
                $batchShiftTimingDetails = $this->BranchShiftTiming->getBranchShiftTimingByBranchIdByFromDateToDateByFromTimeToTime( $details );
                if( empty( $batchShiftTimingDetails ) ){

                    $details = $post;
                    $details['from_date'] = date('Y-m-d',strtotime( $details['from_date'] ) );
                    $details['to_date'] = date('Y-m-d',strtotime( $details['to_date'] ) );
                    $details['is_active'] = ACTIVE;
                    $details['created_date'] = CURRENTDATE;

                    $insert_result = $this->BranchShiftTiming->add( $details );
                    if( !empty( $insert_result ) ){
                        $result['success'] = true;
                        $result['message'] = 'Branch Shift Timing has been created successfully.';
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
                $result['message'] = 'Please Select Branch.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Branch.';
        }
        $this->response($result);
    }
    public function updateBranchShiftTiming_post(){
        $post = $this->input->post();

        if( !empty( $post['batch_timing_id'] )) {

            $details = $post;
            $batchShiftTimingDetails = $this->BranchShiftTiming->getBranchShiftTimingByBranchIdByFromDateToDateByFromTimeToTime( $details );
            if( empty( $batchShiftTimingDetails ) ){

                $details = $post;
                $details['from_date'] = date('Y-m-d',strtotime( $details['from_date'] ) );
                $details['to_date'] = date('Y-m-d',strtotime( $details['to_date'] ) );

                $update_result = $this->BranchShiftTiming->update( $details );
                if( !empty( $update_result ) ){
                    $result['success'] = true;
                    $result['message'] = 'Branch Shift Timing has been updated successfully.';
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
            $result['message'] = 'Please Select Branch Shift Timing.';
        }
        $this->response($result);
    }
    public function getBranchActivitesList_get(){
        $get = $this->input->get();
        
        if(!empty($get['employee_id'])){
            $details = $get;
            $batchActivitiesList = $this->BranchActivites->getBranchActivitesByTranierId($details['employee_id']);
            
            if(!empty($batchActivitiesList)){
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
    
}
