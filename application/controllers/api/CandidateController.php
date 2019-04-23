<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once FCPATH . "vendor/autoload.php";
include_once FCPATH . "vendor/dompdf/dompdf/lib/Cpdf.php";

use Dompdf\Dompdf;

class CandidateController extends MY_Controller {

    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }

    public function getCandidate_get() {
        $get = $this->input->get();
        if (!empty($get['candidate_id'])) {
            $details = $get;
            $candidate_details = $this->Candidate->getCandidateById($details['candidate_id']);
            if (!empty($candidate_details)) {
                $followup_details = $this->CandidateFollowup->getCandidateFollowupByCandidateId($details['candidate_id']);
                if (!empty($followup_details)) {
                    if (count($followup_details) >= TOTALFOLLOWUP) {
                        $candidate_details['is_complete_followup'] = 1;
                    } else {
                        $candidate_details['is_complete_followup'] = 0;
                    }
                    $candidate_details['followup_count'] = count($followup_details);
                } else {
                    $candidate_details['is_complete_followup'] = 0;
                    $candidate_details['followup_count'] = 0;
                }
                $result['success'] = true;
                $result['data'] = $candidate_details;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate details data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae select the candidate.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    public function getEnquiredCandidates_get() {
        $get = $this->input->get(); 
        
        $limit = LIMIT;
        $offset = DEFAULT_OFFEST;
        if( !empty( $get['page_no'] ) ){
            $offset = $this->calculateOffset( $get['page_no'] );
        }
        if ( !empty( $get['search'] ) && strlen($get['search']) >= STRINGLENGHT ){
            $candidateListDetails = $this->Candidate->getEnquiredCandidateByCandidateName( $get['search'], $limit, $offset );
        } else {
            $candidateListDetails = $this->Candidate->getEnquiredCandidates( $limit, $offset );
        }
        
        if ( !empty( $candidateListDetails ) ) {
            foreach( $candidateListDetails as $value ) {
               $value['created_date'] = date( 'd-m-Y ',strtotime( $value['created_date'] ) );
               $candidateList[] = $value;
            }
            $result['success'] = true;
            $result['data'] = $candidateList;
        } else {
            if( !empty( $get['page_no'] )  && $get['page_no'] > 1 ){
                $message = 'No more data found';
            }else{
                $message = 'No candidate data found.';
            }
            $result['success'] = false;
            $result['message'] = $message;
            $result['data'] = array();
        }
        $this->response($result);
    }

    public function getAcceptedCandidate_get() {
        $get = $this->input->get();
        if (!empty($get['employee_id'])) {
            
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            if( !empty( $details['search'] ) && strlen($get['search']) >= STRINGLENGHT ){
                $list = $this->CounsellerCandidate->getCounsellerCandidatesByCandidateName( $details['search'], $limit, $offset );
            } else {
                $list = $this->CounsellerCandidate->getCounsellerCandidatesWithCandidate( $limit, $offset );
            }
            
            if (!empty($list)) {
                foreach ($list as $value) {
                    $candidate_details = $value;
                    $candidate_details['created_date'] = date( 'd-m-Y ',strtotime( $value['created_date'] ) );
                    $followup_details = $this->CandidateFollowup->getCandidateFollowupByCandidateId($value['candidate_id']);
                    if (!empty($followup_details)) {
                        if (count($followup_details) >= TOTALFOLLOWUP) {
                            $candidate_details['is_complete_followup'] = 1;
                        } else {
                            $candidate_details['is_complete_followup'] = 0;
                        }
                        $candidate_details['followup_count'] = count($followup_details);
                    } else {
                        $candidate_details['is_complete_followup'] = 0;
                        $candidate_details['followup_count'] = 0;
                    }
                    $feedback_details = $this->CandidateFeedback->getCandidateFeedbackByCandidateId($value['candidate_id']);
                    if (!empty($feedback_details)) {
                        $candidate_details['is_feedback'] = 1;
                    } else {
                        $candidate_details['is_feedback'] = 0;
                    }
                    $candidate_list[] = $candidate_details;
                }
                $result['success'] = true;
                $result['data'] = $candidate_list;
            } else {
                if( !empty( $get['page_no'] )  && $get['page_no'] > 1 ){
                    $message = 'No more data found';
                }else{
                    $message = 'No candidate data found.';
                }
                $result['success'] = false;
                $result['message'] = $message;
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    public function getRejectedCandidateList_get() {
        $get = $this->input->get();
        if (!empty($get['employee_id'])) {
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            if( !empty( $details['search'] ) && strlen($details['search']) >= STRINGLENGHT ) {
                $list = $this->CandidateRejected->getCandidateRejectedByCandidateName( $details['search'], $limit, $offset );
            } else {
                $list = $this->CandidateRejected->getCandidateRejected( $limit, $offset );
            }
            if (!empty($list)) {
                $result['success'] = true;
                $result['data'] = $list;
            } else {
                if( !empty( $get['page_no'] )  && $get['page_no'] > 1 ){
                    $message = 'No more data found';
                }else{
                    $message = 'No candidate data found.';
                }
                $result['success'] = false;
                $result['message'] = $message;
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please login to get data.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    public function getEnrolledCandidate_get() {
        $get = $this->input->get();
        if (!empty($get['employee_id'])) {
            $details = $get;
            $limit = LIMIT;
            $offset = DEFAULT_OFFEST;
            if( !empty( $get['page_no'] ) ){
                $offset = $this->calculateOffset( $get['page_no'] );
            }
            if ( !empty( $details['search'] ) && strlen($details['search']) >= STRINGLENGHT ) {
                $list = $this->CandidateEnrolled->getCandidateEnrolledByCandidateName( $details['search'], $limit, $offset );
            } else {
                $list = $this->CandidateEnrolled->getCandidateEnrolled( $limit, $offset );
            }
            if (!empty($list)) {
                foreach ($list as $value) {
                    $candidate_details = $value;
                    //$candidate_details['created_date'] = date( 'd-m-Y ',strtotime( $value['created_date'] ) );
                    $candidate_fees_details = $this->CandidateCourseFees->getCandidateCourseFeesByEnrolledId($value['candidate_enrolled_id']);
                    if (!empty($candidate_fees_details['candidate_prn'])) {
                        $candidate_details['is_course_fees'] = 1;
                    } else {
                        $candidate_details['is_course_fees'] = 0;
                    }
                    $candidate_list[] = $candidate_details;
                }
                $result['success'] = true;
                $result['data'] = $candidate_list;
            } else {
                if( !empty( $get['page_no'] )  && $get['page_no'] > 1 ){
                    $message = 'No more data found';
                }else{
                    $message = 'No candidate data found.';
                }
                $result['success'] = false;
                $result['message'] = $message;
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    public function getEnrolledCandidateDetails_get() {
        $get = $this->input->get();
        if (!empty($get['candidate_id'])) {
            $details = $get;
            $candidate_details = $this->CandidateEnrolled->getCandidateEnrolledByCandidateId($details['candidate_id']);
            if (!empty($candidate_details)) {
                $course_fees_details = $this->CandidateCourseFees->getCandidateCourseFeesByEnrolledId($candidate_details['candidate_enrolled_id']);
                if (!empty($course_fees_details)) {
                    $candidate_details['final_fees'] = $course_fees_details['final_fees'];
                } else {
                    $candidate_details['final_fees'] = 0;
                }
                $result['success'] = true;
                $result['data'] = $candidate_details;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae select candidate.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    public function enrolledCandidate_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_id'])) {
            $details = $post;
            $enrolled_details = $this->CandidateEnrolled->getCandidateEnrolledByCandidateId($details['candidate_id']);
            if (empty($enrolled_details)) {
                unset($details['fees_discount']);
                unset($details['final_fees']);
                unset($details['reffered_by']);
                $details['created_date'] = CURRENTDATE;
                $enrolled_id = $this->CandidateEnrolled->add($details);
                if (!empty($enrolled_id)) {
                    unset($details['course_id']);
                    unset($details['created_date']);
                    $details['status_id'] = ENROLLED;
                    $this->Candidate->update($details);
                    $detailsCourseFees['candidate_enrolled_id'] = $enrolled_id;
                    $detailsCourseFees['fees_discount'] = $post['fees_discount'];
                    $detailsCourseFees['final_fees'] = $post['final_fees'];
                    $detailsCourseFees['reffered_by'] = $post['reffered_by'];
                    //$detailsCourseFees['status_id'] = ENROLLED;
                    $detailsCourseFees['created_date'] = CURRENTDATE;
                    $this->CandidateCourseFees->add($detailsCourseFees);

                    $result['success'] = true;
                    $result['message'] = 'Candidate has been enrolled successfully.';
                } else {
                    $result['success'] = false;
                    $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'This candidate has been already enrolled.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function acceptCandidate_post() {
        $post = $this->input->post();
        if (!empty($post['employee_id'])) {
            if (!empty($post['candidate_id'])) {
                $details = $post;
                $accepted_details = $this->CounsellerCandidate->getCounsellerCandidatesAcceptedByCandidateId($details['candidate_id']);
                if (empty($accepted_details)) {
                    $details['created_date'] = CURRENTDATE;
                    $result_data = $this->CounsellerCandidate->add($details);
                    if (!empty($result_data)) {
                        unset($details['employee_id']);
                        unset($details['created_date']);
                        $details['status_id'] = ACCEPTED_CANDIDATE;
                        $update_result = $this->Candidate->update($details);

                        $result['success'] = true;
                        $result['message'] = 'Candidate has been accepted successfully.';
                        $result['data'] = $update_result;
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'This candidate has been already accepted.';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Please Select Candidate.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Employee.';
        }
        $this->response($result);
    }

    public function rejectCandidate_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_id'])) {
            if (!empty($post['candidate_rejected_reason'])) {
                $details = $post;
                $reject_details = $this->CandidateRejected->getCandidateRejectedByCandidateId($details['candidate_id']);
                if (empty($reject_details)) {
                    $rejected_result = $this->CandidateRejected->add($details);
                    if (!empty($rejected_result)) {
                        unset($details['candidate_rejected_reason']);
                        $details['status_id'] = REJECTED;
                        $update_result = $this->Candidate->update($details);

                        $result['success'] = true;
                        $result['message'] = 'Candidate has been rejected successfully.';
                        $result['data'] = $update_result;
                    } else {
                        $result['success'] = false;
                        $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = 'This candidate has been already rejected';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Please Enter Rejected Reason.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function addFollowup_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_id'])) {
            $followup_list = $this->CandidateFollowup->getCandidateFollowupByCandidateId($post['candidate_id']);
            if (empty($followup_list) || count($followup_list) <= TOTALFOLLOWUP) {
                $details = $post;
                //$details['status_id'] = ACCEPTED_CANDIDATE;
                $details['created_date'] = CURRENTDATE;
                $details['followup_date'] = date('Y-m-d', strtotime($details['followup_date']));
                $insert_result = $this->CandidateFollowup->add($details);
                if (!empty($insert_result)) {
                    $result['success'] = true;
                    $result['message'] = 'Followup has been added successfully.';
                } else {
                    $result['success'] = false;
                    $result['message'] = 'OOPS...!,Something went wrong,Please try again.';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'Already ' . TOTALFOLLOWUP . ' followup has been added';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function viewFollowup_get() {
        $get = $this->input->get();
        if (!empty($get['candidate_id'])) {
            $candidate_details = $this->Candidate->getCandidateById($get['candidate_id']);
            if (!empty($candidate_details)) {
                $list = $this->CandidateFollowup->getCandidateFollowupByCandidateId($get['candidate_id']);
                if (!empty($list) && count($list) > 0) {
                    foreach ($list as $value) {
                        $followup = $value;
                        $userDetails = $this->Employee->getEmployeeById($followup['employee_id']);
                        if (!empty($userDetails)) {
                            $followup['employee_name'] = $userDetails['first_name'] . " " . $userDetails['last_name'];
                        } else {
                            $followup['employee_name'] = '';
                        }
                        $followup_list[] = $followup;
                    }
                } else {
                    $followup_list = array();
                }
                if (count($followup_list) >= TOTALFOLLOWUP) {
                    $candidate_details['is_complete_followup'] = 1;
                } else {
                    $candidate_details['is_complete_followup'] = 0;
                }

                $candidate_details['followup_count'] = count($followup_list);
                $candidate_details['follow_list'] = $followup_list;

                $result['success'] = true;
                $result['data'] = $candidate_details;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate has been found';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function addRemark_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_id'])) {
            if (!empty($post['candidate_remark']) || $post['candidate_remark'] != 'undefined') {
                $details = array();
                //$details['status_id'] = ACCEPTED_CANDIDATE;
                $details['candidate_remark'] = $post['candidate_remark'];
                $details['created_date'] = CURRENTDATE;
                $details['candidate_id'] = $post['candidate_id'];
                $details['created_by'] = !empty($post['user_id']) ? $post['user_id'] : 0;
                $this->CandidateRemark->add($details);
                unset($details['candidate_remark']);
                if (!empty($post['communication_skill'])) {
                    $details['feedback_parameters'] = FEEDBACK_COMMUNICATION;
                    $details['feedback_abilities'] = $post['communication_skill'];
                    $insert_result = $this->CandidateFeedback->add($details);
                }
                if (!empty($post['personality'])) {
                    $details['feedback_parameters'] = FEEDBACK_PERSONALITY;
                    $details['feedback_abilities'] = $post['personality'];
                    $insert_result = $this->CandidateFeedback->add($details);
                }
                if (!empty($post['technical'])) {
                    $details['feedback_parameters'] = FEEDBACK_TECHNICAL;
                    $details['feedback_abilities'] = $post['technical'];
                    $insert_result = $this->CandidateFeedback->add($details);
                }
                if (!empty($post['technology_abilites'])) {

                    $technology_abilities = explode(',', $post['technology_abilites']);
                    unset($details['feedback_parameters']);
                    unset($details['feedback_abilities']);
                    foreach ($technology_abilities as $value) {
                        $details['technology_abilities'] = $value;
                        $this->CandidateFeedbackTechnology->add($details);
                    }
                }
                $result['success'] = true;
                $result['message'] = 'Remark has been added successfully.';
            } else {
                $result['success'] = false;
                $result['message'] = 'Please Add Remark.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function editRemark_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_id'])) {
            $details = array();
            //$details['status_id'] = ACCEPTED_CANDIDATE;
            $details['candidate_remark'] = $post['candidate_remark'];
            $details['created_date'] = CURRENTDATE;
            $details['candidate_id'] = $post['candidate_id'];
            $details['created_by'] = !empty($post['user_id']) ? $post['user_id'] : 0;
            $return_result = $this->CandidateRemark->add($details);
            if (!empty($return_result)) {
                $result['success'] = true;
                $result['message'] = 'Remark has been updated successfully.';
            } else {
                $result['success'] = false;
                $result['message'] = 'OOPS...!, Something went wrong.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function viewRemark_get() {
        $get = $this->input->get();
        if (!empty($get['candidate_id'])) {
            $details = $get;
            $candidate_details = $this->Candidate->getCandidateById($details['candidate_id']);
            if (!empty($candidate_details)) {
                $remark_list = $this->CandidateRemark->getCandidateRemarkByAcceptedCandidateIdWithoutJoins($details['candidate_id']);
                if (!empty($remark_list)) {
                    foreach ($remark_list as $valueRemark) {
                        $remark = $valueRemark;
                        $userDetails = $this->User->getUserById($remark['created_by']);
                        $remark['employee_name'] = $userDetails['first_name'] . " " . $userDetails['last_name'];
                        $candidate_remark_list[] = $remark;
                    }
                    $candidate_details['remark_list'] = $candidate_remark_list;
                } else {
                    $candidate_details['remark_list'] = [];
                }
                $feedback_details = $this->CandidateFeedback->getCandidateFeedbackByCandidateId($details['candidate_id']);
                if (!empty($feedback_details)) {
                    $feedback_technology_details = $this->CandidateFeedbackTechnology->getCandidateFeedbackTechnologyByCandidateId($details['candidate_id']);
                    $candidate_details['is_feedback'] = 1;
                    $candidate_details['feedback_details'] = $feedback_details;
                    $candidate_details['feedback_technology_details'] = $feedback_technology_details;
                } else {
                    $candidate_details['is_feedback'] = 0;
                    $candidate_details['feedback_details'] = '';
                    $candidate_details['feedback_technology_details'] = '';
                }
                $result['success'] = true;
                $result['data'] = $candidate_details;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function addFees_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_enrolled_id'])) {
            $details = array();
            $candidate_course_list = $this->CandidateCourseFees->getCandidateCourseFeesByBatchId($post['batch_id']);

            $batch_details = $this->Batch->getBatchById($post['batch_id']);
            if (!empty($candidate_course_list)) {
                $batch_count = sprintf("%02d", 1) + count($candidate_course_list);
            } else {
                $batch_count = str_pad(1, 2, '0', STR_PAD_LEFT);
            }
            if (!empty($batch_details)) {
                $name = explode('ATC-', $batch_details['batch_name']);
                $batch_name = $name[1];
            } else {
                $batch_name = '';
            }

            /**
             * code by prashant for prn 
             * DO NOT DELETE
             */
            $prnArr = $this->CandidateCourseFees->getMaxPrnByBatch($post['batch_id']);

            if (!empty($prnArr)) {
                $prn = $prnArr[0]->prn + 1;
            } else {
                $prn = $batch_name . "01";
            }

            $details['candidate_enrolled_id'] = $post['candidate_enrolled_id'];
            $details['batch_id'] = $post['batch_id'];

            //$details['candidate_prn'] = $batch_name.''.$batch_count;
            $details['candidate_prn'] = $prn;

            $course_fees_details = $this->CandidateCourseFees->updateByCandidateEnrolledId($details);

            if (!empty($course_fees_details)) {
                $detailsFees = array();
                $detailsFees['candidate_course_fees_id'] = $course_fees_details['candidate_course_fees_id'];
                //$detailsFees['status_id'] = ENROLLED;
                $detailsFees['created_date'] = CURRENTDATE;

                if (!empty($post['fees_pay_sequence1'])) {
                    $detailsFees['fees_pay_sequence'] = $post['fees_pay_sequence1'];
                    $detailsFees['fees_pay_amount'] = $post['fees_pay_amount1'];
                    $detailsFees['fees_pay_date'] = date('Y-m-d', strtotime($post['fees_pay_date1']));
                    $result_data = $this->CandidateFeesStructure->add($detailsFees);
                }
                if (!empty($post['fees_pay_sequence2'])) {
                    $detailsFees['fees_pay_sequence'] = $post['fees_pay_sequence2'];
                    $detailsFees['fees_pay_amount'] = $post['fees_pay_amount2'];
                    $detailsFees['fees_pay_date'] = date('Y-m-d', strtotime($post['fees_pay_date2']));
                    $result_data = $this->CandidateFeesStructure->add($detailsFees);
                }
                if (!empty($post['fees_pay_sequence3'])) {
                    $detailsFees['fees_pay_sequence'] = $post['fees_pay_sequence3'];
                    $detailsFees['fees_pay_amount'] = $post['fees_pay_amount3'];
                    $detailsFees['fees_pay_date'] = date('Y-m-d', strtotime($post['fees_pay_date3']));
                    $result_data = $this->CandidateFeesStructure->add($detailsFees);
                }
                if (!empty($post['fees_pay_sequence4'])) {
                    $detailsFees['fees_pay_sequence'] = $post['fees_pay_sequence4'];
                    $detailsFees['fees_pay_amount'] = $post['fees_pay_amount4'];
                    $detailsFees['fees_pay_date'] = date('Y-m-d', strtotime($post['fees_pay_date4']));
                    $result_data = $this->CandidateFeesStructure->add($detailsFees);
                }
                if (!empty($post['fees_pay_sequence5'])) {
                    $detailsFees['fees_pay_sequence'] = $post['fees_pay_sequence5'];
                    $detailsFees['fees_pay_amount'] = $post['fees_pay_amount5'];
                    $detailsFees['fees_pay_date'] = date('Y-m-d', strtotime($post['fees_pay_date5']));
                    $result_data = $this->CandidateFeesStructure->add($detailsFees);
                }

                if (!empty($result_data)) {
                    $result['success'] = true;
                    $result['message'] = 'Fees has been added successfully.';
                } else {
                    $result['success'] = false;
                    $result['message'] = 'OOPS..!,Something went wrong,Please try again later';
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'OOPS..!,Something went wrong,Please try again later';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please select enrolled candidate.';
        }
        $this->response($result);
    }

    public function viewFees_get() {
        $get = $this->input->get();
        if (!empty($get['candidate_enrolled_id'])) {
            $details = $get;
            $candidate_details = $this->CandidateEnrolled->getCandidateEnrolledById($details['candidate_enrolled_id']);
            if (!empty($candidate_details)) {
                $candidate_course_fees_details = $this->CandidateCourseFees->getCandidateCourseFeesByEnrolledId($details['candidate_enrolled_id']);
                if (!empty($candidate_course_fees_details)) {
                    $batch_details = $this->Batch->getBatchById($candidate_course_fees_details['batch_id']);
                    $course_details = $this->CourseMaster->getCourseMasterById($batch_details['course_id']);
                    $candidate_course_fees_details['course_name'] = $course_details['course_name'];
                    $candidate_course_fees_details['batch_name'] = $batch_details['batch_name'];
                    $candidate_details['candidate_course_fees_details'] = $candidate_course_fees_details;
                } else {
                    $candidate_details['candidate_course_fees_details'] = '';
                }
                $candidateFeesStructureListDetails = $this->CandidateFeesStructure->getCandidateFeesStructureByCourseFeesId($candidate_course_fees_details['candidate_course_fees_id']);
                if ( !empty( $candidateFeesStructureListDetails ) ) {
                    foreach( $candidateFeesStructureListDetails as $value ){
                        $value['fees_pay_date'] = date( 'd-m-Y', strtotime( $value['fees_pay_date'] ) );
                        $candidateFeesStructureDetails[] = $value;
                    }
                    
                    $candidate_details['candidate_fees_structure'] = $candidateFeesStructureDetails;
                } else {
                    $candidate_details['candidate_fees_structure'] = '';
                }
                $result['success'] = true;
                $result['data'] = $candidate_details;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Candidate.';
        }
        $this->response($result);
    }

    public function addPaidFees_post() {
        $post = $this->input->post();
        if (!empty($post['candidate_enrolled_id']) && $post['candidate_id']) {
            $details = $post;
            $details['status_id'] = ENROLLED;
            $details['created_date'] = CURRENTDATE;
            $paid_result = $this->CandidateFeesPaid->add($details);
            if (!empty($paid_result)) {
                $result['success'] = true;
                $result['message'] = 'Fees has been paid successfully.';
            } else {
                $result['success'] = false;
                $result['message'] = 'OOPS..!,Something went wrong,Please try again later';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please select enrolled candidate.';
        }
        $this->response($result);
    }

    public function listPaidFees_get() {
        $get = $this->input->get();
        
        $limit = LIMIT;
        $offset = DEFAULT_OFFEST;
        if( !empty( $get['page_no'] ) && $get['page_no'] !=1 ){
            $offset = $this->calculateOffset( $get['page_no'] );
        }
        
        if( !empty( $get['search'] ) ){
            $feesPaidList = $this->CandidateFeesPaid->getCandidateFeesPaidByGroupByCandidateEnrolledIdByCandidateName( $get['search'], $limit, $offset );
        }else{
            $feesPaidList = $this->CandidateFeesPaid->getCandidateFeesPaidByGroupByCandidateEnrolledId( $limit, $offset );
        }

        if (!empty($feesPaidList)) {
            foreach ($feesPaidList as $feesPaid) {
                $feesPaidDetails = $feesPaid;
                $remainingFees = $feesPaid['final_fees'] - $feesPaid['feesPaid'];
                $feesPaidDetails['remaining_fees'] = $remainingFees;
                $candidateFeesPaidList[] = $feesPaidDetails;
            }
            $result['success'] = true;
            $result['data'] = $candidateFeesPaidList;
        } else {
            if( !empty( $get['page_no'] )  && $get['page_no'] > 1 ){
                $message = 'No more data found';
            }else{
                $message = 'No candidate data found.';
            }
            $result['success'] = false;
            $result['message'] = $message;
        }
        $this->response($result);
    }

    public function viewPaidFees_get() {
        $get = $this->input->get();

        if (!empty($get['candidate_enrolled_id'])) {
            $details = $get;
            $feesPaidListDetails = $this->CandidateFeesPaid->getCandidateFeesPaidByCandidateEnrolledIdWithoutJoin($details['candidate_enrolled_id']);

            if ( !empty( $feesPaidListDetails ) ) {
                $totalPaidFees = 0;
                $candidateCourseFeesDetails = $this->CandidateCourseFees->getCandidateCourseFeesByEnrolledId($details['candidate_enrolled_id']);

                foreach ( $feesPaidListDetails as $value ) {
                    $totalPaidFees = $totalPaidFees + $value['fees_paid'];
                    $value['payment_date'] = date( 'd-m-Y', strtotime( $value['payment_date'] ) );
                    $feesPaidDetails[] = $value;
                }
                $candidateFeesPaidDetails['candidate_course_fees_details'] = $candidateCourseFeesDetails;
                $candidateFeesPaidDetails['remaining_fees'] = $candidateCourseFeesDetails['final_fees'] - $totalPaidFees;
                $candidateFeesPaidDetails['total_fees_paid'] = $totalPaidFees;
                $candidateFeesPaidDetails['fees_paid_list'] = $feesPaidDetails;

                $result['success'] = true;
                $result['data'] = $candidateFeesPaidDetails;
            } else {
                $result['success'] = false;
                $result['message'] = 'No Paid Fees Found.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Enrolled Canidate.';
        }
        $this->response($result);
    }

    public function sendPaidFeesInvoice_get() {
        $get = $this->input->get();

        if (!empty($get['candidate_fees_paid_id'])) {
            $details = $get;
            $candidateFeesPaidDetails = $this->CandidateFeesPaid->getCandidateFeesPaidById($details['candidate_fees_paid_id']);

            if (!empty($candidateFeesPaidDetails)) {
                $feesPaidList = $this->CandidateFeesPaid->getCandidateFeesPaidByCandidateEnrolledIdWithoutJoin($candidateFeesPaidDetails['candidate_enrolled_id']);
                $totalPaidFees = 0;
                $tmpFees = 0; //@prashant
                foreach ($feesPaidList as $value) {
                    $tmpFees = $tmpFees + $value['fees_paid'];
                    if ($candidateFeesPaidDetails["payment_receipt_no"] == $value["payment_receipt_no"]) {
                        $totalPaidFees = $tmpFees;
                    }
                }
                $remainingFees = $candidateFeesPaidDetails['final_fees'] - $totalPaidFees;
                $candidateFeesPaidDetails['totalPaidFees'] = $totalPaidFees;
                $candidateFeesPaidDetails['remainginFees'] = $remainingFees;
                
                $generatePdfResult = $this->generatePdf( $candidateFeesPaidDetails );
                
                $mailData['candidateFeesPaidDetails'] = $candidateFeesPaidDetails;
                $to = $candidateFeesPaidDetails['email'];
                $subject = "Paid fees receipt ".$candidateFeesPaidDetails['payment_receipt_no'];
                $message = $this->load->view('Email/invoice', $mailData, TRUE);
                $resultMail = $this->sendEmail($to, $subject, $message, $generatePdfResult);

                if (!empty($resultMail)) {
                    $result['success'] = true;
                    $result['message'] = 'Invoice has been sent successfully.';
                    $result['data'] = $candidateFeesPaidDetails;
                } else {
                    $result['success'] = true;
                    $result['message'] = 'Cannot sent mail something went wrong.';
                    $result['data'] = $candidateFeesPaidDetails;
                }
            } else {
                $result['success'] = false;
                $result['message'] = 'No Paid Fees Found.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Paid Fees.';
        }
        $this->response($result);
    }

    public function downloadPdfPaidFeesInvoice_get() {
        $get = $this->input->get();

        if (!empty($get['candidate_fees_paid_id'])) {
            $details = $get;
            $candidateFeesPaidDetails = $this->CandidateFeesPaid->getCandidateFeesPaidById($details['candidate_fees_paid_id']);

            if (!empty($candidateFeesPaidDetails)) {
                $feesPaidList = $this->CandidateFeesPaid->getCandidateFeesPaidByCandidateEnrolledIdWithoutJoin($candidateFeesPaidDetails['candidate_enrolled_id']);
                $totalPaidFees = 0;
                $tmpFees = 0; //@prashant

                foreach ($feesPaidList as $value) {
                    //$totalPaidFees = $totalPaidFees + $value['fees_paid'];
                    $tmpFees = $tmpFees + $value['fees_paid'];
                    if ($candidateFeesPaidDetails["payment_receipt_no"] == $value["payment_receipt_no"]) {
                        $totalPaidFees = $tmpFees;
                    }
                }

                $remainingFees = $candidateFeesPaidDetails['final_fees'] - $totalPaidFees;
                $candidateFeesPaidDetails['totalPaidFees'] = $totalPaidFees;
                $candidateFeesPaidDetails['remainginFees'] = $remainingFees;

                $pdfData['candidateFeesPaidDetails'] = $candidateFeesPaidDetails;
                $pdfName = str_replace('/', '_', $candidateFeesPaidDetails['payment_receipt_no']);
                $pdfFilePath = FCPATH . "assets/paidFeesReceipt_" . $pdfName;
                $stream = false;
                $paper = 'A4';
                $orientation = "portrait";
                $pdf = new Dompdf;
                $html = $this->load->view('Pdf/invoice', $pdfData, TRUE);
                $pdf->loadHtml(trim($html));
                $pdf->set_paper($paper, $orientation);
                $pdf->render();
                if ($stream) {
                    $pdf->stream("paidFeesReceipt_" . $candidateFeesPaidDetails['payment_receipt_no'] . ".pdf", array("Attachment" => 0));
                } else {
                    $outputData = $pdf->output();
                    file_put_contents($pdfFilePath . ".pdf", $outputData);
                }

                $result['success'] = true;
                $result['message'] = 'Download has been done successfully.';
                $result['link'] = base_url() . "assets/paidFeesReceipt_" . $pdfName . ".pdf";
                $result['data'] = $candidateFeesPaidDetails;
            } else {
                $result['success'] = false;
                $result['message'] = 'No Paid Fees Found.';
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please Select Paid Fees.';
        }
        $this->response($result);
    }

    /****************** search api ******************/

    /**
     * Search by first, middle and last name Enquired candidate
     * */
    public function searchEnquiredCandidatesList_get() {
        $get = $this->input->get();
        $details = $get;

        if ( !empty( $details['search'] ) && strlen($details['search']) >= STRINGLENGHT ) {
            $candidateDetails = $this->Candidate->getEnquiredCandidateByCandidateName($details['search']);
        } else {
            $candidateDetails = $this->Candidate->getEnquiredCandidates();
        }
        if (!empty($candidateDetails)) {
            $result['success'] = true;
            $result['data'] = $candidateDetails;
        } else {
            $result['success'] = false;
            $result['message'] = 'No candidate details data found.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    /**
     * Search by first, middle and last name accepted candidate
     * */
    public function searchAcceptedCandidateList_get() {

        $get = $this->input->get();
        if (!empty($get['employee_id'])) {
            $details = $get;
            
            if ( !empty( $details['search'] ) && strlen($details['search']) >= STRINGLENGHT ) {
                $list = $this->CounsellerCandidate->getCounsellerCandidatesByCandidateName( $details['search'] );
            } else {
                $list = $this->CounsellerCandidate->getCounsellerCandidatesWithCandidate();
            }

            if (!empty($list)) {
                foreach ($list as $value) {
                    $candidate_details = $value;
                    $followup_details = $this->CandidateFollowup->getCandidateFollowupByCandidateId($value['candidate_id']);
                    if (!empty($followup_details)) {
                        if (count($followup_details) >= TOTALFOLLOWUP) {
                            $candidate_details['is_complete_followup'] = 1;
                        } else {
                            $candidate_details['is_complete_followup'] = 0;
                        }
                        $candidate_details['followup_count'] = count($followup_details);
                    } else {
                        $candidate_details['is_complete_followup'] = 0;
                        $candidate_details['followup_count'] = 0;
                    }
                    $feedback_details = $this->CandidateFeedback->getCandidateFeedbackByCandidateId($value['candidate_id']);
                    if (!empty($feedback_details)) {
                        $candidate_details['is_feedback'] = 1;
                    } else {
                        $candidate_details['is_feedback'] = 0;
                    }
                    $candidate_list[] = $candidate_details;
                }
                $result['success'] = true;
                $result['data'] = $candidate_list;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    /**
     * Search by first, middle and last name enrolled candidate
     * */
    public function searchEnrolledCandidateList_get() {

        $get = $this->input->get();
        if (!empty($get['employee_id'])) {
            $details = $get;
            if ( !empty($details['search'] ) && strlen($details['search']) >= STRINGLENGHT ) {
                $list = $this->CandidateEnrolled->getCandidateEnrolledByCandidateName($details['search']);
            } else {
                $list = $this->CandidateEnrolled->getCandidateEnrolled();
            }
            if (!empty($list)) {
                foreach ($list as $value) {
                    $candidate_details = $value;
                    $candidate_fees_details = $this->CandidateCourseFees->getCandidateCourseFeesByEnrolledId($value['candidate_enrolled_id']);
                    if (!empty($candidate_fees_details['candidate_prn'])) {
                        $candidate_details['is_course_fees'] = 1;
                    } else {
                        $candidate_details['is_course_fees'] = 0;
                    }
                    $candidate_list[] = $candidate_details;
                }
                $result['success'] = true;
                $result['data'] = $candidate_list;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
            $result['data'] = array();
        }
        $this->response($result);
    }

    /**
     * Search by first, middle and last name rejected candidate
     * */
    public function searchRejectedCandidateList_get() {
        $get = $this->input->get();
        if (!empty($get['employee_id'])) {
            $details = $get;
            if( !empty( $details['search'] ) && strlen($details['search']) >= STRINGLENGHT ) {
                $list = $this->CandidateRejected->getCandidateRejectedByCandidateName($details['search']);
            } else {
                $list = $this->CandidateRejected->getCandidateRejected();
            }
            if (!empty($list)) {
                $result['success'] = true;
                $result['data'] = $list;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Plesae login to get data.';
            $result['data'] = array();
        }
        $this->response($result);
    }
    
    public function generatePdf( $candidateFeesPaidDetails ){
        $feesPaidList = $this->CandidateFeesPaid->getCandidateFeesPaidByCandidateEnrolledIdWithoutJoin( $candidateFeesPaidDetails['candidate_enrolled_id'] );
        $totalPaidFees = 0;
        $tmpFees = 0; //@prashant

        foreach ($feesPaidList as $value) {
            //$totalPaidFees = $totalPaidFees + $value['fees_paid'];
            $tmpFees = $tmpFees + $value['fees_paid'];
            if ($candidateFeesPaidDetails["payment_receipt_no"] == $value["payment_receipt_no"]) {
                $totalPaidFees = $tmpFees;
            }
        }

        $remainingFees = $candidateFeesPaidDetails['final_fees'] - $totalPaidFees;
        $candidateFeesPaidDetails['totalPaidFees'] = $totalPaidFees;
        $candidateFeesPaidDetails['remainginFees'] = $remainingFees;

        $pdfData['candidateFeesPaidDetails'] = $candidateFeesPaidDetails;
        $pdfName = str_replace('/', '_', $candidateFeesPaidDetails['payment_receipt_no']);
        $pdfFilePath = FCPATH . "assets/paidFeesReceipt_" . $pdfName;
        $stream = false;
        $paper = 'A4';
        $orientation = "portrait";
        $pdf = new Dompdf;
        $html = $this->load->view('Pdf/invoice', $pdfData, TRUE);
        $pdf->loadHtml(trim($html));
        $pdf->set_paper($paper, $orientation);
        $pdf->render();
        if ($stream) {
            $pdf->stream("paidFeesReceipt_" . $candidateFeesPaidDetails['payment_receipt_no'] . ".pdf", array("Attachment" => 0));
        } else {
            $outputData = $pdf->output();
            file_put_contents($pdfFilePath . ".pdf", $outputData);
        }
        return $pdfFilePath.  ".pdf";    
    }
    
    public function getCandidateBatchList_get() {
        $get = $this->input->get();
        if( !empty( $get['batch_id'] ) ) {
            $details = $get;
            $candidateList = $this->CandidateCourseFees->getCandidateCourseFeesByBatchId( $details['batch_id'] );

            if( !empty( $candidateList ) ) {
                $result['success'] = true;
                $result['data'] = $candidateList;
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate details data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'No batch data details found.';
            $result['data'] = array();
        }
        $this->response($result);
    }
    
    public function candidateWorksheet_post() {
        $post = $this->input->post();
        if( !empty( $post['employee_id'] ) ) {
            
            $details = $post;
            $length = count( $details['candidate_enrolled_id'] );
            for( $i=0; $i < $length; $i++ ) {
                $worksheetData = array( 
                                'trainers_therotical_session_id' => $details['trainers_therotical_session_id'],
                                'candidate_enrolled_id' =>  $details['candidate_enrolled_id'][$i],
                                'is_present' => $details['is_present'][$i],
                                'created_date' => CURRENTDATE,
                                'created_by' => $details['employee_id']
                            );
                $resultData = $this->CandidateTheroticalWorksheet->add( $worksheetData );
            }
            $updateData = array(
                                    'trainers_therotical_session_id' => $details['trainers_therotical_session_id'],
                                    'check_in' => CURRENTTIME,
                                    'is_conducted' => 1
                                );
            $this->TrainersTheroticalSession->update( $updateData );
            
            if( !empty( $resultData ) ) {
                $result['success'] = true;
                $result['message'] = 'Candidate Presenty has been mark';
                $result['data'] = array();
            } else {
                $result['success'] = false;
                $result['message'] = 'No candidate details data found.';
                $result['data'] = array();
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'Please login to store data.';
            $result['data'] = array();
        }
        $this->response($result);
    }
    
    
}
