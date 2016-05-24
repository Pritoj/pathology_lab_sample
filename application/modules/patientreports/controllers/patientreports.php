<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Patientreports extends MY_Patientcontroller{
	function __construct(){
		parent::__construct();
		$this->load->model('reports/reportsmodel');
	}
	function getReportDetails(){
		$this->form_validation->set_rules('reportId', 'Report Id','trim|required|numeric');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}else {
			//we know token must exist
			//so get info from token
			$token = $this->input->post('token');
			$tokenData = getInfoFromToken($token);
			$reportId = $this->input->post('reportId');
			$reportDetails = $this->reportsmodel->getReportDetails(array('reportId'=>$reportId,'patientId'=>$tokenData->userId));
			if(count($reportDetails)>0){
				sendJSONResponse(TRUE,1,$reportDetails[0]);
			}
			else{
				sendJSONResponse(FALSE,2,array('err'=>'Send report id'));
			}
			
		}
	}
	function getReports(){
		//get all the patients
		$token = $this->input->post('token');
		$tokenData = getInfoFromToken($token);
		$reports = $this->reportsmodel->getReports(array('patientId'=>$tokenData->userId,'status'=>'Published'));
		sendJSONResponse(TRUE,1,$reports);
	}
	function emailReport(){
		$this->form_validation->set_rules('reportId', 'Report Id','trim|required|numeric');
		$this->form_validation->set_rules('emailAddress', 'Email Id','trim|required|valid_email');
		if($this->form_validation->run() === FALSE){
			//there was a problem with the  info provided
			// show the errors 
			sendJSONResponse(FALSE,2,array('err'=>$this->form_validation->error_array()));
		}else {
			//we know token must exist
			//so get info from token
			$reportId = $this->input->post('reportId');
			$emailId = $this->input->post('emailAddress');
			$reportDetails = $this->reportsmodel->getReportDetails(array('reportId'=>$reportId));
			$html = $this->load->view('pdfreportview',array('reportDetails'=>$reportDetails[0]),true);
			$attach = createPDF($html,$reportId.".pdf",true);
			$this->email->from($reportDetails[0]['patientEmailAddress'], $reportDetails[0]['patientFirstName']." ".$reportDetails[0]['patientLastName']);
			$this->email->to($emailId);

			$this->email->subject('Report');
			$this->email->message('Hi, '.$reportDetails[0]['patientFirstName']." ".$reportDetails[0]['patientLastName']. " has set you a report");
			$this->email->attach($attach);
			$result = $this->email->send();



			sendJSONResponse(TRUE,1,$reportDetails[0]);
		}
	}
	
}