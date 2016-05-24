<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reportexport extends CI_Controller{
	function  __construct(){
		parent::__construct();
		$this->load->model('reports/reportsmodel');
	}
	function reportpdf($reportId){
		$reportDetails = $this->reportsmodel->getReportDetails(array('reportId'=>$reportId));
		$html = $this->load->view('pdfreportview',array('reportDetails'=>$reportDetails[0]),true);
		createPDF($html,$reportId.".pdf");
	}
}