<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Reportsmodel extends CI_Model{
	/*
	* Function Name : getReportDetails
	* Description : gets reports with their details
	* Params : 
	*			$filtering [optional]- array of filtering fields like array('username'=>'abc') would
	*			filter out results with abc in the username field
	*/
	function getReportDetails($filtering=array()){
		$this->load->model('tests/testmodel');
		//get all the reports
		$reports = $this->getReports($filtering);
		//innitialize return array
		$retArr = array();
		foreach($reports as $r){
			$testResults = $this->testmodel->getTestResults(array('reportId'=>$r['reportId']));
			//group results based on tests
			$testArr = array();
			foreach($testResults as $tr){
				//check if this test type has been used
				if(!isset($testArr[$tr['testTypeId']])){
					//if not innitialized, 
					//innitialize it
					$testArr[$tr['testTypeId']] = array(
						'testTypeName' => $tr['testTypeName'],
						'testTypeId' => $tr['testTypeId'],
						'fields' => array()
					);
				}
				$testArr[$tr['testTypeId']]['fields'][] = $tr;
			}

			$r['tests'] = array();
			//now just convert to test array
			//to use with ng-repeat filter
			foreach ($testArr as $test) {
				$r['tests'][]=$test;
			}
			$retArr[] = $r;
		}
		return $retArr;
	}
	/*
	* Function Name : getReports
	* Description : gets reports 
	* Params : 
	*			$filtering [optional]- array of filtering fields like array('username'=>'abc') would
	*			filter out results with abc in the username field
	*/
	function getReports($filtering = array()){
		//these are how we need to replace the report conditions
		$replications = array(
			'patientFirstName'=>'pat.firstName',
			'patientLastName'=>'pat.lastName',
			'operatorFirstName'=>'op.firstName',
			'operatorLastName'=>'op.lastName'
		);
		$filterConds = getFilterString($filtering,$replications);
		$SQL="
			SELECT
				r.reportId
				, r.patientId
				, pat.firstName AS patientFirstName
				, pat.lastName AS patientLastName
				, pat.emailAddress AS patientEmailAddress
				, r.operatorId
				, op.firstName AS operatorFirstName
				, op.lastName AS operatorLastName
				, r.doctorName
				, r.diagnosis
				, r.microscopicExam
				, r.grossExam
				, r.otherComments
				, r.status
				, r.reportDate
			FROM
				reports r
				LEFT JOIN users pat
					ON pat.userId = r.patientId
				LEFT JOIN users op
					ON op.userId = r.operatorId
			WHERE
				1
				$filterConds

		";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}

	/*
	* Function Name : addReport
	* Description : Adds a report 
	* Params : 
	*			$data - array with report info, ie
	*					patientId,operatorId,doctorName,diagnosis,microscopicExam,
	*					grossExam,otherComments
	*/
	function addReport($data){
		//run the query
		$query = $this->db->insert('reports',$data);
		if($query){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : editReport
	* Description : Edits a report 
	* Params : 
	*			$reportId - Id of the report to edit
	*			$data - array with report info, ie
	*					patientId,operatorId,doctorName,diagnosis,microscopicExam,
	*					grossExam,otherComments
	*/
	function editReport($reportId,$data){
		//run the query
		$query = $this->db->update('reports',$data,array('reportId'=>$reportId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : deleteReport
	* Description : Deletes a report 
	* Params : 
	*			$reportId - Id of the report to delte
	*/
	function deleteReport($reportId){
		//run the query
		$query = $this->db->delete('reports',array('reportId'=>$reportId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}