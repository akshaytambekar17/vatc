<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class EmployeeController extends MY_Controller {
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }
    public function getEmployeesList_get(){
        $employee_list = $this->Employee->getEmployees();
        if(!empty($employee_list)){
            $result['success'] = true;
            $result['data'] = $employee_list;
        }else{
            $result['success'] = false;
            $result['message'] = 'No Employees data found.';
        }
        $this->response($result);
    }
    public function getEmployee_get(){
        $get = $this->input->get();
        $details = $get;
        if(!empty($details['employee_id'])){
            $employee_details = $this->Employee->getEmployeeById($details['employee_id']);
            if(!empty($employee_details)){
                $result['success'] = true;
                $result['data'] = $employee_details;
            }else{
                $result['success'] = false;
                $result['message'] = 'No Employees data found.';
            }
        }else{
            $result['success'] = false;
            $result['message'] = 'Please Select Employee.';
        }
        $this->response($result);
    }
}
