<?php 
class Loginmodel extends CI_Model{
	/*
	* Function Name : checkUserInfo
	* Description : Gets the user data from email and md5 password
	* Params : 
	*			$username - string
	*			$password - md5 of password supplied
	*/
	function checkUserInfo ($userEmail,$password){
		//get all user data 
		//from the email and password
		$SQL = "
			SELECT
				u.userId
				, u.firstName
				, u.lastName
				, u.emailAddress
				, u.role
			FROM
				users u
			WHERE
				u.emailAddress LIKE ".$this->db->escape($userEmail)."
				AND u.password LIKE '".md5($password)."'
		";
		
		$query = $this->db->query($SQL);
		if($query->num_rows()>0){
			//a result was returned
			return $query->result_array();
		}
		else{
			return FALSE;
		}
	}
	/*
	* Description : this function just takes a user details array and sets them in the session
	* 				it also sets the session variable `loggedIn` to 1 and session variable `role` to the role provided in the details
	* Params : 
	*			$details - An array with user details
	*/
	function loginUser($details){
		//create an array with all the data
		$data = array(
			'loggedIn' => 1,
			'role' => $details['role'],
			'userDetails' => $details
		);
		$this->session->set_userdata($data);
	}
}