<?php
class Login extends CI_Controller{
	function __construct(){
		parent::__construct();
		//load the login model since it will definitely be used
		//in a majorit of the funcitons in this file
		$this->load->model('loginmodel');
	}
	//main function of this file.
	//opens the loginpage and redirects to dashboard
	function login(){
		
		//validate the input
		$this->form_validation->set_rules('username', 'Email Id','trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password','trim|required|min_length[6]|max_length[255]');
		if($this->form_validation->run() == FALSE){
			//there was a problem with the login info provided
			// show the errors with out checking in db
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
			return;
		}
		else{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			//check if usenamr and password are OK
			$checkLogin = $this->loginmodel->checkUserInfo($username,$password);
			if($checkLogin === FALSE){
				sendJSONResponse(FALSE,2,array('err'=>array('details'=>'Invalid login details')));
				//exit the function
				return;
			}
			else{
				//username and password checks out
				//create token
				$token = createToken($checkLogin[0]);
				sendJSONResponse(TRUE,1,array('success'=>1,'token'=>$token,'data'=>$checkLogin[0]));
				return;
			}
		}
		
	}
}