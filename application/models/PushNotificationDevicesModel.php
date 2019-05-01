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
class PushNotificationDevicesModel extends CI_Model {

    //put your code here
    public function __construct() {
        parent::__construct();
    }
    
    public function getPushNotificationDevices() {
        return $this->db->get('push_notification_devices')->result_array();
    }
    
    public function getPushNotificationDevicesByUserId($userId) {
        $this->db->where('user_id',$userId);
        return $this->db->get('push_notification_devices')->row_array();
    }
	
	public function getPushNotificationDevByUserId($userId) {
        $this->db->where('user_id',$userId);
        return $this->db->get('push_notification_devices')->result_array();
    }
    
    public function insert($data){
        $this->db->insert('push_notification_devices', $data);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
    public function update($updateData){
        $this->db->where('user_id',$updateData['user_id']);
        $this->db->update('push_notification_devices',$updateData);
        return true;
    }

    public function delete($id) {
        $this->db->where('user_id',$id);
        $this->db->delete('push_notification_devices'); 
        if($this->db->affected_rows()){
            return true;
        }else{
            return false;
        }
    }
}
