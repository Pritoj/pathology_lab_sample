<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Patients extends MY_Opcontroller{
	function __construct(){
		parent::__construct();
		//load the patient model since it will be used all throughout
		$this->load->model('patientmodel');

		//saving the fields and their info
		//to be used when making forms for 
		//adding and editing
		$this->patientFields = array(
			'firstName' => array(
				'type' => 'text',
				'validation' => 'trim|required|max_length[255]',
				'label' => 'First Name'
			),
			'lastName' => array(
				'type' => 'text',
				'validation' => 'trim|required|max_length[255]',
				'label' => 'Last Name'
			),
			'emailAddress' => array(
				'type' => 'email',
				'validation' => 'trim|required|valid_email|is_unique[users.emailAddress]',
				'label' => 'Email Address'
			),
			'password' => array(
				'type' => 'text',
				'validation' => 'trim|required|min_length[6]|max_length[255]',
				'label' => 'Password'
			)
		);

		$this->patientEditFields = array(
			'firstName' => array(
				'type' => 'text',
				'validation' => 'trim|max_length[255]',
				'label' => 'First Name'
			),
			'lastName' => array(
				'type' => 'text',
				'validation' => 'trim|max_length[255]',
				'label' => 'Last Name'
			),
			'emailAddress' => array(
				'type' => 'email',
				'validation' => 'trim|valid_email',
				'label' => 'Email Address'
			),
			'password' => array(
				'type' => 'text',
				'validation' => 'trim|min_length[6]|max_length[255]',
				'label' => 'Password'
			)
		);

		
	}
	function getPatients(){
		//get all the patients
		$patients = $this->patientmodel->getPatientList();
		sendJSONResponse(TRUE,1,$patients);
	}	
	function addPatient(){
		
		//the operator is trying to add a patient
		//so validate
		foreach($this->patientFields as $fieldName=>$fieldInfo){
			//add validation rules
			$this->form_validation->set_rules($fieldName, $fieldInfo['label'],$fieldInfo['validation']);
		}
		//check the values
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}
		else{
			//form data was validated
			//let's add it to the db

			//we filter out the data from post since we may have a few unwanted fields
			$insertData = array();
			foreach($this->patientFields as $fieldName=>$fieldInfo){
				//add validation rules
				$insertData[$fieldName] = $this->input->post($fieldName);

			}
			$insertId = $this->patientmodel->addPatient($insertData);
			if($insertId!==FALSE){
				//data was inserted successfully

				//so get patient data which was inserted
				$patientData = $this->patientmodel->getPatientList(array('userId'=>$insertId));
				sendJSONResponse(TRUE,1,$patientData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}

		}
		
	}
	function editPatient(){
		//the operator is trying to add a patient
		//so validate
		foreach($this->patientEditFields as $fieldName=>$fieldInfo){
			//add validation rules
			//replace the required part in validation since it is not 
			//required
			$validaitonRules = str_replace('|required','',$fieldInfo['validation']);
			$validaitonRules = str_replace('|is_unique[users.emailAddress]','',$fieldInfo['validation']);
			$this->form_validation->set_rules($fieldName, $fieldInfo['label'],$validaitonRules);
		}
		//other than the required it must also check 
		//if the user Id has been sent
		$this->form_validation->set_rules('userId', 'Patient Id', 'trim|required|numeric');
		
		
		//check the values
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			//this will be done at the end of the function so no need to do 
			//anything here
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}
		else{
			//form data was validated
			//let's add it to the db
			//we filter out the data from post since we may have a few unwanted fields
			$editData = array();
			foreach($this->patientEditFields as $fieldName=>$fieldInfo){
				//add validation rules
				if($this->input->post($fieldName)){
					$insertData[$fieldName] = $this->input->post($fieldName);
				}
				

			}
			$userId = $this->input->post('userId');
			if($this->patientmodel->editPatient($userId,$insertData)!==FALSE){
				//data was updated successfully

				//so get patient data which was edited
				$patientData = $this->patientmodel->getPatientList(array('userId'=>$userId));
				sendJSONResponse(TRUE,1,$patientData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}

		}
	}
	function deletePatient(){
		$this->form_validation->set_rules('userId', 'Patient Id', 'trim|required|numeric');
		//check the values
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			//this will be done at the end of the function so no need to do 
			//anything here
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}
		else{
			$userId = $this->input->post('userId');
			if($this->patientmodel->deletePatient($userId)!==FALSE){
				//data was deleted successfully
				sendJSONResponse(TRUE,1);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
}