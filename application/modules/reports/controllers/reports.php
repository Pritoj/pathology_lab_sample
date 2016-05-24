<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reports extends MY_opController{
	function __construct(){
		parent::__construct();
		$this->load->model('reportsmodel');
		$this->fields = array(
			'patientId' => array(
				'validation' => array(
					'add'=>'trim|required|max_length[255]|numeric',
					'edit' => 'trim|max_length[255]|numeric'
				),
				'label' => 'Patient Id'
			),
			'doctorName' => array(
				'validation' => array(
					'add'=>'trim|required|max_length[255]',
					'edit' => 'trim|max_length[255]'
				),
				'label' => 'Doctor Name'
			),
			'diagnosis' => array(
				'validation' => array(
					'add'=>'trim|required|alpha_numeric_spaces',
					'edit' => 'trim|alpha_numeric_spaces'
				),
				'label' => 'Diagnosis'
			),
			'microscopicExam' => array(
				'validation' => array(
					'add'=>'trim|required|alpha_numeric_spaces',
					'edit' => 'trim|alpha_numeric_spaces'
				),
				'label' => 'Microscopic Exam'
			),
			'grossExam' => array(
				'validation' => array(
					'add'=>'trim|required|alpha_numeric_spaces',
					'edit' => 'trim|alpha_numeric_spaces'
				),
				'label' => 'Gross Exam'
			),
			'otherComments' => array(
				'validation' => array(
					'add'=>'trim|alpha_numeric_spaces',
					'edit' => 'trim|alpha_numeric_spaces'
				),
				'label' => 'Other comments'
			),
			'status' => array(
				'validation' => array(
					'add'=>'trim|in_list[Draft,Published]',
					'edit' => 'trim|in_list[Draft,Published]'
				),
				'label' => 'Status'
			)
		);
	}
	function getReportDetails(){
		$this->form_validation->set_rules('reportId', 'Report Id','trim|required|numeric');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}else {
			$reportId = $this->input->post('reportId');
			$reportDetails = $this->reportsmodel->getReportDetails(array('reportId'=>$reportId));
			sendJSONResponse(TRUE,1,$reportDetails[0]);
		}
	}
	function getReports(){
		//get all the patients
		$reports = $this->reportsmodel->getReports();
		sendJSONResponse(TRUE,1,$reports);
	}
	function addReport(){
		//add validation rules
		foreach($this->fields as $fieldName=>$fieldInfo){
			//add validation rules
			$this->form_validation->set_rules($fieldName, $fieldInfo['label'],$fieldInfo['validation']['add']);
		}
		//check the values
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}else {
			//all data is valid 
			//add test to the db

			//we know token must exist
			//so get info from token
			$token = $this->input->post('token');
			$tokenData = getInfoFromToken($token);

			//we filter out the data from post since we may have a few unwanted fields
			$insertData = array();
			foreach($this->fields as $fieldName=>$fieldInfo){
				//add validation rules
				$insertData[$fieldName] = $this->input->post($fieldName);

			}
			$insertData['operatorId'] = $tokenData -> userId;
			$reportId = $this->reportsmodel->addReport($insertData);
			if($reportId!==FALSE){
				//data was inserted successfully

				//so get report data which was inserted
				$reportData = $this->reportsmodel->getReports(array('reportId'=>$reportId));
				sendJSONResponse(TRUE,1,$reportData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function editReport(){
		//add validation rules
		foreach($this->fields as $fieldName=>$fieldInfo){
			//add validation rules
			$this->form_validation->set_rules($fieldName, $fieldInfo['label'],$fieldInfo['validation']['edit']);
		}
		//we also need to check if the report ID to edit has been sent
		$this->form_validation->set_rules('reportId', 'Report Id','trim|required|numeric');
		//check the values
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}else {
			//all data is valid 
			//add test to the db

			//we know token must exist
			//so get info from token
			$token = $this->input->post('token');
			$tokenData = getInfoFromToken($token);

			//we filter out the data from post since we may have a few unwanted fields
			$insertData = array();
			foreach($this->fields as $fieldName=>$fieldInfo){
				//add validation rules
				if($this->input->post($fieldName)){
					$insertData[$fieldName] = $this->input->post($fieldName);
				}
			}
			$reportId = $this->input->post('reportId');
			if($this->reportsmodel->editReport($reportId,$insertData)!==FALSE){
				//data was inserted successfully

				//so get report data which was inserted
				$reportData = $this->reportsmodel->getReports(array('reportId'=>$reportId));
				sendJSONResponse(TRUE,1,$reportData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}

	function deleteReport(){
		$this->form_validation->set_rules('reportId', 'Report Id', 'trim|required|numeric');
		//check the values
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			//this will be done at the end of the function so no need to do 
			//anything here
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}
		else{
			$reportId = $this->input->post('reportId');
			if($this->reportsmodel->deleteReport($reportId)!==FALSE){
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