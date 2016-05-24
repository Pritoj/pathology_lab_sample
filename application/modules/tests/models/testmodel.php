<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Testmodel extends CI_Model{
	/************ GET FUNCTIONS **************/	
	function getTests($filtering=array()){
		//get the where conditions if required
		$filterConds = getFilterString($filtering);
		$SQL = "
			SELECT
				tt.testTypeId
				, tt.testTypeName
				, tt.operatorId
				, u.firstName AS operatorFirstName
				, u.lastName AS operatorLastName
			FROM
				testTypes tt 
				LEFT JOIN users u
					ON tt.operatorId = u.userId
			WHERE
				1
				$filterConds
		";
		$query = $this->db->query($SQL);
		$tests = $query->result_array();

		//an array to store results based on ID
		$retArr = array();
		//now we get test fields for all these tests
		foreach($tests as $t){
			$testFields = $this->getTestFields(array('testTypeId' => $t['testTypeId']));
			//add fields data to the result
			$t['fields'] = $testFields;
			//add an element to the return array based on the testtypeid
			$retArr[] = $t;
		}
		return $retArr;

	}

	function getTestFields($filtering=array()){
		//these are how we need to replace the test conditions
		$replications = array('testTypeId'=>'tt.testTypeId');
		$filterConds = getFilterString($filtering,$replications);
		$SQL = "
			SELECT
				tf.testFieldName
				, tf.testFieldId
				, tt.testTypeId
				, tt.testTypeName
				, tt.operatorId
				, u.firstName AS operatorFirstName
				, u.lastName AS operatorLastName
			FROM
				testFields tf
				LEFT JOIN testTypes tt 
					ON tf.testTypeId = tt.testTypeId
				LEFT JOIN users u
					ON tt.operatorId = u.userId
			WHERE
				1
				$filterConds
		";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}

	/*
	* Function Name : getReports
	* Description : gets reports 
	* Params : 
	*			$filtering [optional]- array of filtering fields like array('username'=>'abc') would
	*			filter out results with abc in the username field
	*/
	function getTestResults($filtering = array()){
		//these are how we need to replace the report conditions
		$replications = array(
			'testTypeId'=>'tt.testTypeId',
			'testFieldId'=>'tf.testFieldId'
		);
		$filterConds = getFilterString($filtering,$replications);
		$SQL = "
			SELECT
				tr.value
				, tf.testFieldName
				, tf.testFieldId
				, tt.testTypeId
				, tt.testTypeName
				, tt.operatorId
				, u.firstName AS operatorFirstName
				, u.lastName AS operatorLastName
			FROM
				testResult tr

				LEFT JOIN testFields tf
					ON tr.testFieldId = tf.testFieldId
				LEFT JOIN testTypes tt 
					ON tf.testTypeId = tt.testTypeId
				LEFT JOIN users u
					ON tt.operatorId = u.userId
			WHERE
				1
				$filterConds
		";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
	/************* ADD FUNCTIONS *************/

	/*
	* Function Name : addTest
	* Description : Adds a new test type 
	* Params : 
	*			$data - TEst Info data
	*/
	function addTest($data){
		//run the query
		$query = $this->db->insert('testTypes',$data);
		if($query){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : addTestField
	* Description : Adds a new test field to a test 
	* Params : 
	*			$testId - Id of the test type to which we are adding the field
	*			$fieldName - TEst Field Name 
	*/
	function addTestField($testId,$fieldName){
		$data = array(
			'testTypeId' => $testId,
			'testFieldName' => $fieldName
		);
		//run the query
		$query = $this->db->insert('testFields',$data);
		if($query){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : addTestResult
	* Description : Adds test results for a patient
	* Params : 
	*			$testFieldId - Test Field for which to add the result
	*			$reportId - Report for which this result is being inserted
	*			$value - Value of the test field
	*/
	function addTestResult($testFieldId,$reportId,$value){
		$data = array(
			'testFieldId' => $testFieldId,
			'reportId' => $reportId,
			'value' => $value
		);
		//run the query
		$query = $this->db->insert('testResult',$data);
		if($query){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}

	/*************** EDIT FUNCTIONS ***********/


	/*
	* Function Name : editTest
	* Description : Edits the test
	* Params : 
	*			$testId - the Id of the test to edit
	*			$testName - Name if the test since it's the only thing to be edited
	*/
	function editTest($testId,$testName){
		$data['testTypeName'] = $testName;
		//run the query
		$query = $this->db->update('testTypes',$data,array('testTypeId'=>$testId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	/*
	* Function Name : editTestField
	* Description : Edits name of a test field
	* Params : 
	*			$fieldId - Id of the test field to edit
	*			$fieldName - Test Field Name 
	*/
	function editTestField($fieldId,$fieldName){
		$data = array(
			'testFieldName' => $fieldName
		);
		//run the query
		$query = $this->db->update('testFields',$data,array('testFieldId'=>$fieldId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : editTestResult
	* Description : Edits test results for a patient
	* Params : 
	*			$resultId - Id of the result to edit
	*			$value - Value of the test field (this is the only thing to edit really)
	*/
	function editTestResult($resultId,$value){
		$data = array(
			'value' => $value
		);
		//run the query
		$query = $this->db->update('testResult',$data,array('resultId'=>$resultId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/********** DELETE FUNCTIONS ***********/
	/*
	* Function Name : deleteTest
	* Description : Deletes the test
	* Params : 
	*			$testId - the Id of the test to delete
	*			
	*/
	function deleteTest($testId){
		//run the query
		$query = $this->db->delete('testTypes',array('testTypeId'=>$testId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : deleteTestField
	* Description : Deletes the test field
	* Params : 
	*			$fieldId - the Id of the field to delete
	*			
	*/
	function deleteTestField($fieldId){
		//run the query
		$query = $this->db->delete('testFields',array('testFieldId'=>$fieldId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : deleteTestResult
	* Description : Deletes the test result
	* Params : 
	*			$resultId - the Id of the result to delete
	*			
	*/
	function deleteTestResult($resultId){
		//run the query
		$query = $this->db->delete('testResult',array('resultId'=>$resultId));
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}