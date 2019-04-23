<?php
require APPPATH . '/libraries/REST_Controller.php';
class MY_Controller extends \Restserver\Libraries\REST_Controller {
    
    private static $API_LEGACY_SERVER_KEY = 'AIzaSyBs9PFgHXBwrBuGLWPY4SAv9z85DLao4bY';
    
    public function __construct() {
        
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->database();
        $this->load->library(array('form_validation','encryption'));
        $this->load->helper(array('url', 'language','form'));
        date_default_timezone_set("Asia/Kolkata");
        define('CURRENTTIME',date('H:i:s'));
        define('CURRENTDATE',date('Y-m-d'));
        define('CURRENTDATETIME',date('Y-m-d H:i:s'));
    }
    function curlReq($url, $vars) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $vars);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $object = json_decode($buffer);
        return $object;
    }
    
    public function sendEmail( $to, $subject, $message, $attachment = ''){
        $this->load->library('email');
        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.asiatechcenter.co.in';
        $config['smtp_user'] = ADMIN_MAIL_ID;
        $config['smtp_pass'] = ADMIN_MAIL_PASSWORD;
        $config['smtp_port'] = 587;
        $config['smtp_crypto']   = "security";
        $config['charset']   = 'utf-8';
        $config['newline']   = "\r\n";
        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->from(FROM_ADMIN_MAIL_ID);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if( !empty( $attachment ) ){
            $this->email->attach($attachment);
        }
        if($this->email->send()){
            $result['success'] = true;
            $result['message'] = "Email has been sent Successfully";
        }else{
            $result['success'] = false;
            $result['message'] = "Something went wrong please try again";
        }
        return $result;
    }
    public function sendPushNotification($fcmData,$arrRegistrationIds) {
        
        $url = 'https://fcm.googleapis.com/fcm/send';
        $image = '';
        if(isset($fcmData['image_path'])){
            $image = $fcmData['image_path'];
        }
        $registrationIds  = array_chunk($arrRegistrationIds,1000);
        
        foreach ($registrationIds as $id){
            
            $fields = array (
                'priority' => 'high',
                //'content_available'=>true,
                'mediaUrl' => $image,
                'mutable-content'=> 1,//($image ? 1 : ''),
                'category' => 'Generic',
                'registration_ids' => $id,
                'data' => $fcmData,
                'notification' => $fcmData,
               );

            $fields = json_encode ( $fields );
            
            $headers = array (
                'Authorization:key='.self::$API_LEGACY_SERVER_KEY,
                'Content-Type:application/json'
                );

            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_POST, true );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

            $result = curl_exec ( $ch );
            curl_close ( $ch );
        }
    }
    public function calculateOffset( $pageNo ) {
        $limit = LIMIT;
        if( !empty( $pageNo ) ){
            return ( $limit * ( $pageNo - 1 ) );
        }else {
            return $offset = DEFAULT_OFFEST;
        }
    }
}
