<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['user/login'] = 'api/UserController/login/';
$route['user/update-profile'] = 'api/UserController/update_profile/';
$route['user/change-password'] = 'api/UserController/changePassword/';
$route['user/get-profile'] = 'api/UserController/getProfile/';
$route['user/forgot-password'] = 'api/UserController/forgotPassword/';
$route['user/get-employee-dashboard'] = 'api/UserController/getEmployeeDashboard/';
$route['user/get-trainer-dashboard'] = 'api/UserController/getTrainerDashboard/';
$route['send-notification'] = 'api/UserController/sendNotification/';

$route['candidate/get-candidate'] = 'api/CandidateController/getCandidate/';
$route['candidate/get-enquired-candidate'] = 'api/CandidateController/getEnquiredCandidates/';
$route['candidate/get-accepted-candidate'] = 'api/CandidateController/getAcceptedCandidate/';
$route['candidate/accept-candidate'] = 'api/CandidateController/acceptCandidate/';
$route['candidate/enrolled-candidate'] = 'api/CandidateController/enrolledCandidate/';
$route['candidate/reject-candidate'] = 'api/CandidateController/rejectCandidate/';
$route['candidate/get-reject-candidate-list'] = 'api/CandidateController/getRejectedCandidateList/';
$route['candidate/get-enrolled-candidate'] = 'api/CandidateController/getEnrolledCandidate/';
$route['candidate/add-followup'] = 'api/CandidateController/addFollowup/';
$route['candidate/view-followup'] = 'api/CandidateController/viewFollowup/';
$route['candidate/get-candidate-enrolled-details'] = 'api/CandidateController/getEnrolledCandidateDetails/';
$route['candidate/add-remark'] = 'api/CandidateController/addRemark/';
$route['candidate/edit-remark'] = 'api/CandidateController/editRemark/';
$route['candidate/view-remark'] = 'api/CandidateController/viewRemark/';
$route['candidate/add-fees'] = 'api/CandidateController/addFees/';
$route['candidate/view-fees'] = 'api/CandidateController/viewFees/';
$route['candidate/add-paid-fees'] = 'api/CandidateController/addPaidFees/';
$route['candidate/view-paid-fees'] = 'api/CandidateController/viewPaidFees/';
$route['candidate/list-paid-fees'] = 'api/CandidateController/listPaidFees/';
$route['candidate/get-candidate-batch-list'] = 'api/CandidateController/getCandidateBatchList/';
$route['candidate/candidate-worksheet'] = 'api/CandidateController/candidateWorksheet/';

$route['course/get-courses'] = 'api/CourseController/getCourses/';
$route['course/get-course-by-id'] = 'api/CourseController/getCourseById/';

$route['batch/get-batch-list'] = 'api/BatchController/getBatchList/';
$route['batch/get-batches-by-course'] = 'api/BatchController/getBatchesByCourseId/';
$route['batch/get-batch-shift-timing-list'] = 'api/BatchController/getBatchShiftTimingList/';
$route['batch/get-batch-shift-timing-details'] = 'api/BatchController/getBatchShiftTimingDetails/';
$route['batch/add-batch-shift-timing'] = 'api/BatchController/addBatchShiftTiming/';
$route['batch/update-batch-shift-timing'] = 'api/BatchController/updateBatchShiftTiming/';
$route['batch/batch-activities-list'] = 'api/BatchController/getBatchActivitesList/';
$route['batch/get-batch-activities-details'] = 'api/BatchController/getBatchActivitesDetails/';
$route['batch/get-batch-activities-status-details'] = 'api/BatchController/getBatchActivitesStatusDetails/';
$route['batch/get-sub-batch-list'] = 'api/BatchController/getSubBatchList/';

$route['employee/get-employee-list'] = 'api/EmployeeController/getEmployeesList/';
$route['employee/get-employee'] = 'api/EmployeeController/getEmployee/';

$route['search-enquired-candidate'] = 'api/CandidateController/searchEnquiredCandidatesList/';
$route['search-accepted-candidate'] = 'api/CandidateController/searchAcceptedCandidateList/';
$route['search-enrolled-candidate'] = 'api/CandidateController/searchEnrolledCandidateList/';
$route['search-rejected-candidate'] = 'api/CandidateController/searchRejectedCandidateList/';

$route['trainer/get-trainer-batch-schedule-list'] = 'api/TrainerController/getTraniersBatchScheduleList/';
$route['trainer/get-trainer-batch-schedule'] = 'api/TrainerController/getTraniersBatchSchedule/';
$route['trainer/get-upcoming-trainers-therotical-batch-list'] = 'api/TrainerController/getUpcomingTraniersTheroticalBatchList/';
$route['trainer/check-out'] = 'api/TrainerController/checkOutSession/';


$route['student/get-student-batch-schedule-list'] = 'api/StudentController/getStudentBatchScheduleList/';
$route['student/get-student-batch-schedule-details'] = 'api/StudentController/getStudentBatchScheduleDetails/';

$route['send-paid-fees-invoice'] = 'api/CandidateController/sendPaidFeesInvoice/';
$route['download-paid-fees-invoice'] = 'api/CandidateController/downloadPdfPaidFeesInvoice/';

$route['branch/get-branch-list'] = 'api/BranchController/getBrancheList/';
$route['branch/get-branch-details'] = 'api/BranchController/getBrancheDetails/';
