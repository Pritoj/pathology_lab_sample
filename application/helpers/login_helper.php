<?php

//load the JWT lib 
require_once(FCPATH . 'libs/jwt/src/JWT.php');
use Firebase\JWT\JWT;
function createToken($userInfo){
	$key = JWT_KEY;
	$token = array(
	    "iss" => base_url(),
	    "aud" => base_url(),
	    "iat" => time(),
	    //expires in 2 months
	    //"exp" => time() + 60 * 60 * 24 * 365 * 10,  
	    "userId" => $userInfo['userId'],
	    "role" => $userInfo['role'],
	    "details" => $userInfo
	);
	$jwt = JWT::encode($token, $key);
	return $jwt;
}
function getInfoFromToken($token){
	$key = JWT_KEY;
	try{
		$tokenData = JWT::decode($token, $key, array('HS256'));
		return $tokenData;
	}
	catch(Exception $e){
		return false;
	}
}