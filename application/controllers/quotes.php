<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once("vendor/autoload.php"); 

use \Firebase\JWT\JWT;


class quotes extends CI_Controller {

 public function __construct()
 {
  parent::__construct();
  
  $this->load->model('quoteDb');
  $this->load->database();
  $this->load->helper('url'); 



 }



 function getting_quotes(){
  $quotes=$this->quoteDb->getting_quotes();
  $quotes_obj=json_decode($quotes);
  $arr = (array) $quotes_obj;
  $arr_len=count($arr)-1;
  $title = "";
  for($i=1;$i<=5;$i++){
  $title .= $i.'- ';
  $title .= $arr[rand(0,$arr_len)].' ';
  }
  $data['title'] = $title;
  $this->load->view('display',$data);

 }

 function verify(){
  $this->load->view('main');
  $password = $this->input->post('pass');
  if($this->quoteDb->can_login($password)){
    $user_id = $this->quoteDb->geting_userid($password);
    $jwt = JWT::encode(
      [ 'user_id' => $user_id],
      'thismykey',
      'HS512'
    );
    $this->quoteDb->insert_token($password,$jwt);
    redirect('http://localhost/challenge/quotes/getting_quotes/display');
  }
  

 }

 function fetch_api(){
  //Get header Authorization
function getAuthorizationHeader(){
  $headers = null;
  if (isset($_SERVER['Authorization'])) {
      $headers = trim($_SERVER["Authorization"]);
  }
  else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { 
      $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
  } elseif (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
      //print_r($requestHeaders);
      if (isset($requestHeaders['Authorization'])) {
          $headers = trim($requestHeaders['Authorization']);
      }
  }
  return $headers;
}

//get access token from header

function getBearerToken() {
$headers = getAuthorizationHeader();
// HEADER: Get the access token from the header
if (!empty($headers)) {
  if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
      return $matches[1];
  }
}
return null;
}

//get the token
$jwt1 = getBearerToken();
try {
  JWT::decode($jwt1, 'thismykey', array('HS512'));

  $quotes=$this->quoteDb->getting_quotes();
  $quotes_obj=json_decode($quotes);
  $arr = (array) $quotes_obj;
  $new_arr = array();
  $arr_len=count($arr)-1;
  for($i=1;$i<=5;$i++){
    array_push($new_arr,$arr[rand(0,$arr_len)]);
  }
  echo json_encode($new_arr);
} 

catch(Exception $e) {
  http_response_code(400);
  $error = array(
    'error' => 'you not authorized to get the information'
      );
  echo json_encode($error); 
}


 }
  
}

?>
