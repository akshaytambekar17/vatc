<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CourseController extends MY_Controller {
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function getCourses_get(){
        
        $course_list = $this->CourseMaster->getCourseMaster();
        if(!empty($course_list)){
            $result['success'] = true;
            $result['data'] = $course_list;
        }else{
            $result['success'] = false;
            $result['message'] = 'No Course list found.';
        }
        
        $this->response($result);
    }
    public function getCourseById_get(){
        $get = $this->input->get();
        $details = $get;
        if(!empty($get['course_id'])){
            $course_details = $this->CourseMaster->getCourseMasterById($details['course_id']);
            if(!empty($course_details)){
                $result['success'] = true;
                $result['data'] = $course_details;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Course list found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Course.';
        }
        
        $this->response($result);
    }
    public function getAcceptedCandidate_get(){
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $candidate_list = $this->CounsellerCandidate->getCounsellerCandidatesByEmployeeId($details['employee_id']);
            if(!empty($candidate_list)){
                $result['success'] = true;
                $result['data'] = $candidate_list;
            }else{
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
        }    
        $this->response($result);
    }
    public function getEnrolledCandidate_get(){
        $get = $this->input->get();
        if(!empty($get['employee_id'])){
            $details = $get;
            $candidate_list = $this->CandidateEnrolled->getCandidateEnrolledByEmployeeId($details['employee_id']);
            if(!empty($candidate_list)){
                $result['success'] = true;
                $result['data'] = $candidate_list;
            }else{
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
        }    
        $this->response($result);
    }
    public function getEnrolledCandidateDetails_get(){
        $get = $this->input->get();
        if(!empty($get['candidate_id'])){
            $details = $get;
            $candidate_details = $this->CandidateEnrolled->getCandidateEnrolledByCandidateId($details['candidate_id']);
            if(!empty($candidate_details)){
                $result['success'] = true;
                $result['data'] = $candidate_details;
            }else{
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Plesae select candidate.';
        }    
        $this->response($result);
    }
    public function enrolledCandidate_post(){
        $post = $this->input->post();
        if(!empty($post['candidate_id'])){
            $details = $post;
            $details['status_id'] = ENROLLED;
            $details['created_date'] = CURRENTDATE;
            $result_details = $this->CandidateEnrolled->add($details);
            if(!empty($result_details)){
                unset($details['course_id']);
                unset($details['created_date']);
                $this->Candidate->update($details);
                $this->CounsellerCandidate->update($details);
                $result['success'] = true;
                $result['message'] = 'Candidate has been enrolled successfully.';
            }else{
                $result['success'] = false;
                $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please select candidate.';
        }
        $this->response($result);
    }
    public function rejectCandidate_post(){
        $post = $this->input->post();
        if(!empty($post['candidate_id'])){
            $details = $post;
            unset($details['candidate_remark']);
            $details['status_id'] = REJECTED;
            $update_result = $this->Candidate->update($details);
            if(!empty($update_result)){
                $this->CounsellerCandidate->update($details);
                $details['candidate_remark'] = $post['candidate_remark'];
                $details['created_date'] = CURRENTDATE;
                $this->CandidateRemark->add($details);
                $result['success'] = true;
                $result['message'] = 'Candidate has been rejected successfully.';
                $result['data'] = $update_result;
            }else{
                $result['success'] = false;
                $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please select candidate.';
        }
        $this->response($result);
    }
    public function addFollowup_post(){
        $post = $this->input->post();
        if(!empty($post['candidate_id'])){
            $followup_list = $this->CandidateFollowup->getCandidateFollowupByCandidateId($post['candidate_id']);
            if(empty($followup_list) || count($followup_list) <= TOTALFOLLOWUP){
                $details = $post;
                $details['status_id'] = ACCEPTED_CANDIDATE;
                $details['created_date'] = CURRENTDATE;
                $details['followup_date'] = date('Y-m-d',strtotime($details['followup_date']));
                $insert_result = $this->CandidateFollowup->add($details);
                if(!empty($insert_result)){
                    $result['success'] = true;
                    $result['message'] = 'Followup has been added successfully.';
                }else{
                    $result['success'] = false;
                    $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                }
            }else{
                $result['success'] = false;
                $result['message'] = 'Already '.TOTALFOLLOWUP.' followup has been added';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please select candidate.';
        }
        $this->response($result);
    }
    public function viewFollowup_get(){
        $get = $this->input->get();
        if(!empty($get['candidate_id'])){
            $followup_list = $this->CandidateFollowup->getCandidateFollowupByCandidateId($get['candidate_id']);
            if(!empty($followup_list)){
                $result['success'] = true;
                $result['data'] = $followup_list;
            }else{
                $result['success'] = false;
                $result['message'] = 'No followup data has been found';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please select candidate.';
        }
        $this->response($result);
    }
    
}
