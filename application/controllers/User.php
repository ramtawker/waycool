<?php

class User extends CI_Controller {

public function __construct(){

  parent::__construct();
  $this->load->helper('url');
  $this->load->model('user_model');
  $this->load->library('session');
  $this->load->library('facebook');
  require_once APPPATH.'third_party/src/Google_Client.php';
	require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';

}

/**
  *
  * @author : Ram
  * @date   : 09 Jun 2019
  * @params : 
  * @return : view
  *
  **/
public function index()
{
  $data['authUrl'] =  $this->facebook->login_url();
  $this->load->view("register.php",$data);
}

/**
    * register user
    * @author : Ram
    * @date   : 09 Jun 2019
    * @params : user_name, user_email, user_age, user_mobile, location.
    * @return : view
    *
**/
public function register_user(){
  $pwd = uniqid();
  $user=array(
  'user_name'=>$this->input->post('user_name'),
  'user_email'=>$this->input->post('user_email'),
  'user_password'=>md5($pwd),
  'user_age'=>$this->input->post('user_age'),
  'user_mobile'=>$this->input->post('user_mobile'),
  'location'=>$this->input->post('location')
  );
  print_r($user);

  $email_check=$this->user_model->email_check($user['user_email']);

  if($email_check){
    $this->user_model->register_user($user);
    $subject = "Waycool - Registration Successful";
    $message = "
    <p>Hi ".$this->input->post('user_name')."</p>
    <p>Thanks for registering with us, Please find the UserName and Password below </p>
    <p>User Id: ".$this->input->post('user_email')."</p><br>
    <p>Password: ".$pwd." </p>
    <p>Thanks,</p>
    ";
    $this->sendEmail($user['user_email'], $subject, $message);
    $this->session->set_flashdata('success_msg', 'Registered successfully.Now login to your account.');
    redirect('user/login_view');
  }
  else{
    $this->session->set_flashdata('error_msg', 'Email already exists!');
    redirect('user');
  }

}

/**
    * load login view
    * @author : Ram
    * @date   : 09 Jun 2019
    * @return : view
    *
**/
public function login_view(){
  $this->load->view("login.php");
}

/**
    * user login function
    * @author : Ram
    * @date   : 09 Jun 2019
    * @params : user_email, user_password.
    * @return : view
    *
**/
function login_user(){
  $user_login=array(

  'user_email'=>$this->input->post('user_email'),
  'user_password'=>md5($this->input->post('user_password'))

    );

    $data=$this->user_model->login_user($user_login['user_email'],$user_login['user_password']);
      if($data)
      {
        $this->session->set_userdata('user_id',$data['user_id']);
        $this->session->set_userdata('user_email',$data['user_email']);
        $this->session->set_userdata('user_name',$data['user_name']);
        $this->session->set_userdata('user_age',$data['user_age']);
        $this->session->set_userdata('user_mobile',$data['user_mobile']);

        $this->load->view('user_profile.php');

      }
      else{
        $this->session->set_flashdata('error_msg', 'Error occured,Try again.');
        $this->load->view("login.php");
      }
}

/**
    * load user profile view
    * @author : Ram
    * @date   : 09 Jun 2019
    * @return : view
    *
**/
function user_profile(){
  $this->load->view('user_profile.php');
}
/**
  *
  * @author : Ram
  * @date   : 09 Jun 2019
  * @params : user_name, user_email, user_age, user_mobile, location.
  * @return : view
  *
**/
public function user_logout(){
  $this->session->sess_destroy();
  redirect('user/login_view', 'refresh');
}

/**
    * send email
    * @author : Ram
    * @date   : 09 Jun 2019
    * @params : email, subject, message.
    * @return : 
    *
**/
  function sendEmail($email, $subject, $message){
    $this->load->library('email');

    //SMTP & mail configuration
    $config = array(
        'protocol'  => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => '', // please add your email and enable access to other apps in Google Console.
        'smtp_pass' => '', // please add your gmail password.
        'mailtype'  => 'html',
        'charset'   => 'utf-8'
    );
    $this->email->initialize($config);
    $this->email->set_mailtype("html");
    $this->email->set_newline("\r\n");
    $this->email->to($email);
    $this->email->from('waycool@waycool.in','waycool.in');
    $this->email->subject('How to send email via SMTP server in CodeIgniter');
    $this->email->message($message);
    //Send email
    $this->email->send();
  }

  /**
    * google login function
    * @author : Ram
    * @date   : 09 Jun 2019
    * @params : client id and client secret.
    * @return : redirects to the view.
    *
**/
  public function google_login()
	{
	
		$clientId = GOOGLE_CLIENT_ID; //Google client ID
		$clientSecret = GOOGLE_CLIENT_SECRET; //Google client secret
		$redirectURL = base_url() .'user/google_login';
		
		//https://curl.haxx.se/docs/caextract.html

		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		
		if(isset($_GET['code']))
		{
			$gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['token'])) 
		{
			$gClient->setAccessToken($_SESSION['token']);
		}
		
		if ($gClient->getAccessToken()) {
      $userProfile = $google_oauthV2->userinfo->get();
      $this->load->view("register.php", $userProfile);
    } else {
      $url = $gClient->createAuthUrl();
      header("Location: $url");
      exit;
    }
  }
  
/**
    * facebook login function
    * @author : Ram
    * @date   : 09 Jun 2019
    * @return : redirects to the view.
    *
**/
  public function fb_login(){
		$userData = array();
		if($this->facebook->is_authenticated()){
      $userProfile = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,gender,locale,picture');
      $userProfile['name'] = $userProfile['first_name'].' '.$userProfile['last_name'];
      $userProfile['authUrl'] =  $this->facebook->login_url();      
      $this->load->view("register.php", $userProfile);			
		} else {
      $data['authUrl'] =  $this->facebook->login_url();
	    $this->load->view('register.php',$data);
    }
  }

}

?>
