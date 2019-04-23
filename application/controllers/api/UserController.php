<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class UserController extends MY_Controller {
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function login_post(){
        $post = $this->input->post();
	
        if( !empty( $post['username'] ) && !empty( $post['password'] ) ){
            $details = $post;
            
            $userLoginDetails = $this->UserLogin->getUserLoginByUsername($details['username']);
            if(!empty($userLoginDetails)){
                $hash = $this->encryption->encrypt($details['password']);
                
                if( $this->encryption->decrypt($userLoginDetails['password']) == $this->encryption->decrypt($hash) ){
                    
                    $credentialsDetails['userId'] =  $userLoginDetails['user_id'];
                    
                    if( !empty($details['app_role']) && ROLETRAINER == $details['app_role'] ){
                        
                        $credentialsDetails['roleName']	= ROLETRAINER;		 
                        $userDetails = $this->User->getUserByIdByRoleName( $credentialsDetails );
			$flag = !empty($userDetails)? true : false ;
                        $roleDetails = $this->Employee->getEmployeeByUserId( $userDetails['user_id'] );
                        
                    }else if( !empty( $details['app_role'] ) && ( ROLESTUDENT == $details['app_role'] ) ){
                        
                        $credentialsDetails['roleName']	= ROLESTUDENT;		 
                        $userDetails = $this->User->getUserByIdByRoleName( $credentialsDetails );
			$flag = !empty($userDetails)? true : false ;
                        $roleDetails = array();
                        
                    }else if ( empty( $details['app_role'] ) ){
                        
			$credentialsDetails['roleName']	= ROLECOUNSELLOR;		 
                        $userDetails = $this->User->getUserByIdByRoleName( $credentialsDetails );
			$flag = !empty($userDetails)? true : false ;
                        $roleDetails = $this->Employee->getEmployeeByUserId( $userDetails['user_id'] );
                        
                    }else {
                        $flag = false;
                        $roleDetails = '';
                    }
                    
                    if( ( true == $flag ) && !empty( $roleDetails ) ){
                        
                        if(!empty($details['token']) && $details['token'] != 'null' && $details['token'] != null && $details['token'] != 'undefined') {
                            
                            $fcmData = array('token' => $details['token'],
                                             'user_id' => $userDetails['user_id'],
                                             'type' => TYPE_ANDROID,
                                             'active' => 1,
                                             'updated_at' => CURRENTDATETIME
                                        );  
                            $this->PushNotificationDevices->insert($fcmData);
//                            $resultFcm = $this->PushNotificationDevices->getPushNotificationDevicesByUserId($userDetails['user_id']);
//                            if(empty($resultFcm)){
//                                $this->PushNotificationDevices->insert($fcmData);
//                            }else{
//                                $this->PushNotificationDevices->update($fcmData);
//                            }
                        }
                        $allDetails = array_merge($userDetails,$roleDetails);
                        $result['success'] = true;
                        $result['data'] = $allDetails;
                        
                    }else{
                        $result['success'] = false;
                        $result['message'] = 'Invalid User.Please login as as per role.';   
                    }
                }else{
                    $result['success'] = false;
                    $result['message'] = 'Incorrect Password.Please try again.';   
                }
            }else{
                $result['success'] = false;
                $result['message'] = 'Incorrect Username.Please try again.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae enter the email and password.';
        }  
        $this->response($result);
        
    }
    public function getProfile_get(){
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $userDetails = $this->Employee->getEmployeeById($details['employee_id']);
            if(!empty($userDetails)){
                $result['success'] = true;
                $result['data'] = $userDetails;
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
            $userDetails = $this->Employee->getEmployeeById($post['employee_id']);
            if(!empty($userDetails)){
                $details['user_id'] = $userDetails['user_id'];
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
            $userDetails = $this->Employee->getEmployeeById($post['employee_id']);
            if(!empty($userDetails)){
                //$userLoginDetails = $this->UserLogin->getUserLoginByPassword($details['old_password'],$userDetails['user_id']);
                $userLoginDetails = $this->UserLogin->getUserLoginByUserId($userDetails['user_id']);
                $hash = $this->encryption->encrypt($details['old_password']);
                if($this->encryption->decrypt($userLoginDetails['password']) == $this->encryption->decrypt($hash)){
                    
                    unset($details['old_password']);
                    unset($details['confirm_password']);
                    unset($details['employee_id']);
                    $details['user_id'] = $userDetails['user_id'];
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
            $userDetails = $this->Employee->getEmployeeById($details['employee_id']);
            if(!empty($userDetails)){
                $employee_details = $this->Employee->getEmployeeByUserId($userDetails['user_id']);
                $accepted_list = $this->CounsellerCandidate->getCounsellerCandidatesWithCandidate();
                $enrolled_list = $this->CandidateEnrolled->getCandidateEnrolled();
                $rejected_list = $this->CandidateRejected->getCandidateRejected();
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
    public function getTrainerDashboard_get(){
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $userDetails = $this->Employee->getEmployeeById($details['employee_id']);
            if(!empty($userDetails)){
                
                $employeeDetails = $this->Employee->getEmployeeByUserId($userDetails['user_id']);
                
                $trainerBatchScheduleList = $this->TrainersBatchSchedule->getTrainersBatchSchedulesByEmployeeId($employeeDetails['employee_id']);
                $studentBatchScheduleList = $this->StudentBatchSchedule->getStudentBatchScheduleByEmployeeId($employeeDetails['employee_id']);
                $batchShiftTimingList = $this->BatchShiftTiming->getBatchShiftTimings();
                $batchActivitiesList = $this->BatchActivites->getBatchActivitesByTranierId($employeeDetails['employee_id']);
                $trainersBatchList = $this->TrainersTheroticalSession->getTrainersTheroticalSessionByEmployeeIdByIsConductedBySessionDateByFromTime( $details['employee_id'] ); 
                
                $dashobard_details['trainer_batch_schedule_count'] = !empty($trainerBatchScheduleList) && count($trainerBatchScheduleList) >0?count($trainerBatchScheduleList):0;
                $dashobard_details['student_batch_schedule_count'] = !empty($studentBatchScheduleList) && count($studentBatchScheduleList) >0?count($studentBatchScheduleList):0;
                $dashobard_details['batch_shift_timing_count'] = !empty($batchShiftTimingList) && count($batchShiftTimingList) >0?count($batchShiftTimingList):0;
                $dashobard_details['batch_actitivites_count'] = !empty($batchActivitiesList) && count($batchActivitiesList) >0?count($batchActivitiesList):0;
                $dashobard_details['check_list_count'] = !empty( $trainersBatchList ) && count( $trainersBatchList ) > 0 ? count( $trainersBatchList ) : 0 ;
                
                $result['success'] = true;
                $result['data'] = $dashobard_details;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Employee Data found';
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
