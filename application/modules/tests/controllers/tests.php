<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tests extends MY_opController{
	function __construct(){
		parent::__construct();
		$this->load->model('testmodel');
	}
	function getTests(){
		//get all the patients
		$tests = $this->testmodel->getTests();
		sendJSONResponse(TRUE,1,$tests);
	}
	function addTest(){
		//add validation rules
		$this->form_validation->set_rules('testTypeName', 'Test Name','trim|required');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db

			//we know token must exist
			//so get info from token
			$token = $this->input->post('token');
			$tokenData = getInfoFromToken($token);
			$testData = array(
				'testTypeName' => $this->input->post('testTypeName'),
				'operatorId' => $tokenData->userId
			);
			$testId = $this->testmodel->addTest($testData);
			if($testId!==FALSE){
				//data was inserted successfully

				//so get test data which was inserted
				$testTypeData = $this->testmodel->getTests(array('testTypeId'=>$testId));
				sendJSONResponse(TRUE,1,$testTypeData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function addTestField(){
		$this->form_validation->set_rules('testFieldName', 'Test Field Name','trim|required');
		$this->form_validation->set_rules('testTypeId', 'Test Id','trim|required');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db

			
			$testFieldName =  $this->input->post('testFieldName');
			$testTypeId =  $this->input->post('testTypeId');
			$testFieldId = $this->testmodel->addTestField($testTypeId,$testFieldName);
			if($testFieldId!==FALSE){
				//data was inserted successfully

				//so get test dfield ata which was inserted
				$testFieldData = $this->testmodel->getTestFields(array('testFieldId'=>$testFieldId));
				sendJSONResponse(TRUE,1,$testFieldData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function addTestResult(){
		$this->form_validation->set_rules('reportId', 'Report Id','trim|required|numeric');
		$this->form_validation->set_rules('values[]', 'Test Field Values','required');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db
			$reportId =  $this->input->post('reportId');
			$values =  $this->input->post('values');
			$testResultId = TRUE;
			foreach($values as $testFieldId=>$value){
				$testResultId = $testResultId && $this->testmodel->addTestResult($testFieldId,$reportId,$value);
			}
			
			if($testResultId!==FALSE){
				//data was inserted successfully
				$this->load->model('reports/reportsmodel');
				//so get report for which this was done
				$reportData = $this->reportsmodel->getReportDetails(array('reportId'=>$reportId));
				sendJSONResponse(TRUE,1,$reportData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function editTest(){
		//add validation rules
		$this->form_validation->set_rules('testTypeName', 'Test Name','trim|required');
		$this->form_validation->set_rules('testTypeId', 'Test Name','trim|required|numeric');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db

			//we know token must exist
			//so get info from token
			$testName =  $this->input->post('testTypeName');
			$testId =  $this->input->post('testTypeId');

			if($this->testmodel->editTest($testId,$testName)!==FALSE){
				//data was inserted successfully

				//so get test data which was edited
				$testData = $this->testmodel->getTests(array('testTypeId'=>$testId));
				sendJSONResponse(TRUE,1,$testData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function deleteTest(){
		//add validation rules
		$this->form_validation->set_rules('testTypeId', 'Test Id','trim|required|numeric');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db

			$testId =  $this->input->post('testTypeId');

			if($this->testmodel->deleteTest($testId)!==FALSE){
				//data was inserted successfully

				//so get test data which was edited
				sendJSONResponse(TRUE,1);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function editTestField(){
		$this->form_validation->set_rules('testFieldName', 'Test Field Name','trim|required');
		$this->form_validation->set_rules('testFieldId', 'Test Field Id','trim|required');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db

			//we know token must exist
			//so get info from token
			
			$testFieldName =  $this->input->post('testFieldName');
			$testFieldId =  $this->input->post('testFieldId');
			if($this->testmodel->editTestField($testFieldId,$testFieldName)!==FALSE){
				//data was edited successfully

				//so get test field data which was edited
				$testFieldData = $this->testmodel->getTestFields(array('testFieldId'=>$testFieldId));
				sendJSONResponse(TRUE,1,$testFieldData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function deleteTestField(){
		//add validation rules
		$this->form_validation->set_rules('testFieldId', 'Field Id','trim|required|numeric');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db

			$testFieldId =  $this->input->post('testFieldId');

			if($this->testmodel->deleteTestField($testFieldId)!==FALSE){
				//data was inserted successfully

				//so get test data which was edited
				sendJSONResponse(TRUE,1);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
	function editTestResult(){
		$this->form_validation->set_rules('resultId', 'ResultId Id','trim|required|numeric');
		$this->form_validation->set_rules('value', 'Test Field Value','trim|required');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		} else {
			//all data is valid 
			//add test to the db
			$resultId =  $this->input->post('resultId');
			$value =  $this->input->post('value');
			if($this->testmodel->editTestResult($resultId,$value)!==FALSE){
				//data was inserted successfully

				//so get test result data which was inserted
				$testResultData = $this->testmodel->getTestResults(array('resultId'=>$resultId));
				sendJSONResponse(TRUE,1,$testResultData);
			}
			else{
				//something went wrong with the query error
				sendJSONResponse(FALSE,2,array('err'=>array('query'=>"Some error ouccred. Please try again")));
			}
		}
	}
}