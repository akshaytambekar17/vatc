<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class TrainerController extends MY_Controller {
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function getTraniersBatchScheduleList_get(){
        
        $get = $this->input->get();
        if( !empty( $get['employee_id'] ) ){
            
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            
//            if( !empty( $details['search'] ) ){
//                $batchScheduleList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByCourseContentByEmployeeId( $details['employee_id'], $details['search'], $limit, $offset );
//            }else{
//                $batchScheduleList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByEmployeeId( $details['employee_id'], $limit, $offset );
//            }
            $batchScheduleList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByEmployeeId( $details['employee_id'] );
            
            $batchSchedulesGroupByBatchList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesGroupByBatchId( $limit, $offset );
            if( !empty( $batchScheduleList ) && !empty( $batchSchedulesGroupByBatchList ) ){
                        
                foreach( $batchSchedulesGroupByBatchList as $valueBatch ){
                    $batchSchedulesDetails = $valueBatch;
                    $i = 1;
                    foreach( $batchScheduleList as $valueBatchScheduleDetails ){
                        if( $valueBatchScheduleDetails['batch_id'] == $valueBatch['batch_id'] ){
                            if( 1 == $i ){
                                $toDate = $valueBatchScheduleDetails['to_date'];
                            }else{
                                $fromDate = $valueBatchScheduleDetails['from_date'];
                            }
                            $i++;
                            $batchName = $valueBatchScheduleDetails['batch_name'];
                        }    
                    }
                    $batchSchedulesDetails['from_date'] = !empty( $fromDate ) ? $fromDate : $toDate ;
                    $batchSchedulesDetails['to_date'] = $toDate;
                    $batchSchedulesDetails['batch_name'] = $batchName;
                    $courseContentDetails = $this->CourseContentMaster->getCourseContentMasterById( $valueBatch['course_content_id'] );
                    $batchSchedulesDetails['course_name'] = $courseContentDetails['course_name'];
                    $trainerBatchScheduleList[] = $batchSchedulesDetails;
                    $batchSchedulesDetails = array();
                }
                
                $result['success'] = true;
                $result['data'] = $trainerBatchScheduleList;
                
            }else{
                $result['success'] = false;
                $result['message'] = 'No data found';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
        }  
        $this->response($result);
        
    }
    
    public function getTraniersBatchSchedule_get(){
        
        $get = $this->input->get();
        if( !empty( $get['batch_id'] ) ){
            $details = $get;
                
            $trainerBatchScheduleBatchList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByBatchId( $details['batch_id'] );
            
            if( !empty( $trainerBatchScheduleBatchList ) ){
                $result['success'] = true;
                $result['data'] = $trainerBatchScheduleBatchList;
            }else{
                $result['success'] = false;
                $result['message'] = 'No data found';
            }
            
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae select the batch.';
        }  
        $this->response($result);
    }
    
    public function getUpcomingTraniersTheroticalBatchList_get(){
        
        $get = $this->input->get();
        if( !empty( $get['employee_id'] ) ){
            
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            
//            if( !empty( $details['search'] ) ){
//                $batchScheduleList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByCourseContentByEmployeeId( $details['employee_id'], $details['search'], $limit, $offset );
//            }else{
//                $batchScheduleList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByEmployeeId( $details['employee_id'], $limit, $offset );
//            }
            $trainersBatchList = $this->TrainersTheroticalSession->getTrainersTheroticalSessionByEmployeeIdByIsConductedBySessionDateByFromTime( $details['employee_id'] );
            
            if( !empty( $trainersBatchList ) ){
                
                $result['success'] = true;
                $result['data'] = $trainersBatchList;
                
            }else{
                $result['success'] = false;
                $result['message'] = 'No data found';
                $result['data'] = [];
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
            $result['data'] = [];
        }  
        $this->response($result);
        
    }
    
    public function checkOutSession_post() {
        $post = $this->input->post();
        if( !empty( $post['employee_id'] ) ) {
            $details = $post;
            if( !empty( $details['trainers_therotical_session_id'] ) ) {
                $updateData = array(
                                    'trainers_therotical_session_id' => $details['trainers_therotical_session_id'],
                                    'check_out' => CURRENTTIME,
                                );
                $resultData = $this->TrainersTheroticalSession->update( $updateData );
                if( !empty( $resultData ) ) {
                    $result['success'] = true;
                    $result['message'] = 'Checkout has been done. Thank you for the session.';
                    $result['data'] = array();
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Something went wrong. Please try again later';
                    $result['data'] = array();
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Batch not found to checkout.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please login to store data.';
            $result['data'] = array();
        }
        $this->response($result);
        
    }
    
    public function getProfile_get(){
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $user_details = $this->Employee->getEmployeeById($details['employee_id']);
            if(!empty($user_details)){
                $result['success'] = true;
                $result['data'] = $user_details;
            }else{
                $result['success'] = false;
                $result['message'] = 'No data found';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please login to update details';
        }
        $this->response($result);
    }
    public function update_profile_post(){
        $post = $this->input->post();
        if(!empty($post['employee_id'])){
            $details = $post;
            $user_details = $this->Employee->getEmployeeById($post['employee_id']);
            if(!empty($user_details)){
                $details['user_id'] = $user_details['user_id'];
                unset($details['employee_id']);
                $update_result = $this->User->update($details);
                if(!empty($update_result)){
                    $employee_details = $this->Employee->getEmployeeByUserId($update_result['user_id']);
                    $all_details = array_merge($update_result,$employee_details);
                    $result['success'] = true;
                    $result['message'] = 'Profile has been updated successfully.';
                    $result['data'] = $all_details;
                }else{
                    $result['success'] = false;
                    $result['message'] = 'Oops..!Something went wrong.Please try agian';
                }
            }else{
                    $result['success'] = false;
                    $result['message'] = 'No employee data found';
                }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please login to update details';
        }
        $this->response($result);
    }
    public function changePassword_post(){
        $post = $this->input->post();
        if(!empty($post['employee_id'])){
            $details = $post;
            $user_details = $this->Employee->getEmployeeById($post['employee_id']);
            if(!empty($user_details)){
                //$user_login_details = $this->UserLogin->getUserLoginByPassword($details['old_password'],$user_details['user_id']);
                $user_login_details = $this->UserLogin->getUserLoginByUserId($user_details['user_id']);
                $hash = $this->encryption->encrypt($details['old_password']);
                if($this->encryption->decrypt($user_login_details['password']) == $this->encryption->decrypt($hash)){
                    
                    unset($details['old_password']);
                    unset($details['confirm_password']);
                    unset($details['employee_id']);
                    $details['user_id'] = $user_details['user_id'];
                    $details['password'] = $this->encryption->encrypt($details['password']);
                    $update_result = $this->UserLogin->update($details);
                    if(!empty($update_result)){
                        $result['success'] = true;
                        $result['message'] = 'Password has been successfully update.';
                    }else{
                        $result['success'] = false;
                        $result['message'] = 'Oops..!Something went wrong,Please try again';
                    }
                }else{
                        $result['success'] = false;
                        $result['message'] = 'Old password does not match.';
                    }
            }else{
                    $result['success'] = false;
                    $result['message'] = 'No employee data found';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please login to update details';
            
        }
        $this->response($result);
    }
    public function forgotPassword_post(){
        $post = $this->input->post();
        if(!empty($post['username'])){
            $details = $post;
            $userLoginDetails = $this->UserLogin->getUserLoginByUsername($details['username']);
            if(!empty($userLoginDetails)){
                $userDetails = $this->User->getUserById($userLoginDetails['user_id']);

                if(!empty($details['app_role']) && ROLETRAINER == $details['app_role']){
                    
                    $flag = ROLETRAINER == $userDetails['role_name']? true:false;
                    $roleDetails = $this->Employee->getEmployeeByUserId($userDetails['user_id']);
                    
                }else if(!empty($details['app_role']) && ROLESTUDENT == $details['app_role']){
                    
                    $flag = ROLESTUDENT == $userDetails['role_name']? true:false;
                    $roleDetails = array();
                    
                }else{
                    
                    $flag = ROLECOUNSELLOR == $userDetails['role_name']? true:false;
                    $roleDetails = $this->Employee->getEmployeeByUserId($userDetails['user_id']);
                    
                }
                
                if(true == $flag && !empty($roleDetails)){
                    
                    $new_password = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 8);
                    $mail_data['user_details'] = $userDetails;
                    $mail_data['new_password'] = $new_password;
                    
                    $to = $roleDetails['email'];
                    $subject = "New Reset password";
                    $message = $this->load->view('Email/forgot_password',$mail_data,TRUE);
                    $result_mail = $this->sendEmail($to, $subject, $message);
                    
                    if($result_mail['success']){
                        $update_details = array('user_id' => $userDetails['user_id'],
                                            'password' => $this->encryption->encrypt($new_password)
                                    );
                        $this->UserLogin->update($update_details);
                        $result['success'] = true;
                        $result['message'] = 'Mail has been sent successfully. Please check you inbox.';
                    }else{
                        $result['success'] = false;
                        $result['message'] = 'Mail cannot send. Something went wrong please try again later.';
                    }
                }else{
                    $result['success'] = false;
                    $result['message'] = 'Invalid User.Please login as as per role.'; 
                }
            }else{
                $result['success'] = false;
                $result['message'] = 'No username found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please enter username.';
        }
        $this->response($result);
    }
    public function getEmployeeDashboard_get(){
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $user_details = $this->Employee->getEmployeeById($details['employee_id']);
            if(!empty($user_details)){
                $employee_details = $this->Employee->getEmployeeByUserId($user_details['user_id']);
                $accepted_list = $this->CounsellerCandidate->getCounsellerCandidatesByEmployeeId($employee_details['employee_id']);
                $enrolled_list = $this->CandidateEnrolled->getCandidateEnrolledByEmployeeId($employee_details['employee_id']);
                $rejected_list = $this->CandidateRejected->getCandidateRejectedByEmployeeId($employee_details['employee_id']);
                $enquiry_list = $this->Candidate->getEnquiredCandidates();
                
                $dashobard_details['accepted_count'] = !empty($accepted_list) && count($accepted_list) >0?count($accepted_list):0;
                $dashobard_details['enrolled_count'] = !empty($enrolled_list) && count($enrolled_list) >0?count($enrolled_list):0;
                $dashobard_details['rejected_count'] = !empty($rejected_list) && count($rejected_list) >0?count($rejected_list):0;
                $dashobard_details['enquiry_count'] = !empty($enquiry_list) && count($enquiry_list) >0?count($enquiry_list):0;
                
                $result['success'] = true;
                $result['data'] = $dashobard_details;
            }else{
                $result['success'] = false;
                $result['message'] = 'No data found';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please login to update details';
        }
        $this->response($result);
    }
    public function sendNotification_post(){
        
        $post = $this->input->post();
        
        $details['title'] = $post['title'];
        $details['description'] = $post['description'];
        $details['id'] = 1;
        $type = "Testing for Counsellor";
        $location_ids = 0;
        $fcmData = array(
                    'alert' => '',
                    'badge' =>1,
                    'title' => $details['title'],
                    'body' => strip_tags($details['description']),
                    'notification_type' => $type,
                    'id'=> $details['id'],
                    'image_path'=> !empty($details['image'])?$details['image']:'',
                    'location_ids'=>$location_ids
        );
        $registrationIds = array();
        $fcmList =  $this->PushNotificationDevices->getPushNotificationDevices(); 
        if(!empty($fcmList)){
            foreach($fcmList as $fcm){
                array_push($registrationIds,$fcm['token']);
            }
            $this->sendPushNotification($fcmData,$registrationIds);
            $result['success'] = true;
            $result['message'] = 'Notification has been send succcessfully.';
            
        }else{
            $result['success'] = false;
            $result['message'] = 'No FCM token present.';
        }
        $this->response($result);
    }
}
