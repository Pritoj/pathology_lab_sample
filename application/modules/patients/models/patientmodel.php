<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Patientmodel extends CI_Model{
	/*
	* Function Name : addPatient
	* Description : Adds a patient to the list
	* Params : 
	*			$data - Patient Info data
	*					contains 'firstName','lastName','emailAddress','password'
	*/
	function addPatient($data){
		//set role ad patient
		$data['role'] = 'Patient';
		//md5 the password
		$data['password'] = md5($data['password']);
		//run the query
		$query = $this->db->insert('users',$data);
		if($query){
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : editPatient
	* Description : Edits Patient Info 
	* Params : 
	*			$patientId - Patient Id to edit
	*			$patientInfo - Patient Info data
	*							contains 'firstName','lastName','emailAddress','password'
	*/
	function editPatient($patientId,$patientInfo){
		//md5 the password
		if(isset($patientInfo['password'])){
			$patientInfo['password'] = md5($patientInfo['password']);
		}
		//the only data to change is firstName, lastName, emailAddress or password
		//an array for the where conditions
		$whereArr = array(
			'userId' => $patientId,
			'role' => 'Patient'
		);
		$query = $this->db->update('users',$patientInfo,$whereArr);
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : deletePatient
	* Description : Deletes Patient. This will also delete any records associated 
	* with said patient since we have set cascade in the db 
	* Params : 
	*			$patientId - Patient Id to delete
	*/
	function deletePatient($patientId){
		$whereArr = array(
			'userId' => $patientId,
			'role' => 'Patient'
		);
		$query = $this->db->delete('users',$whereArr);
		if($query){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	* Function Name : getPatientList
	* Description : Gives the list of patients on the database
	* Params : 
	*			$filtering [optional]- array of filtering fields like array('username'=>'abc') would
	*			filter out results with abc in the username field
	*/
	function getPatientList($filtering = array()){
		//get the where conditions if required
		$filterConds = getFilterString($filtering);
		$SQL = "
			SELECT 
				u.userId
				, u.firstName
				, u.lastName
				, u.emailAddress
			FROM
				users u
			WHERE
				1
				AND u.role = 'Patient'
				$filterConds
		";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
}