<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/*
* Thi sfile is a bit of a hack. Since codeigniter only loads these from core
* We have two different controllerswhich will be auto loaded
*/

/*
*	All operator controllers will inherit this class
*	It is basically a login checker so we know the operator provileges are satisfied
*/

class MY_Opcontroller extends CI_Controller{
    function __construct(){
        parent::__construct();
        //check if token has been sent
        $token = $this->input->post('token');
        if($token){
            //token is sent. so check it
            $tokenData = getInfoFromToken($token);
            if($tokenData){
                //if token role is not correct 
                //send an error
                if($tokenData->role != 'Operator'){
                    //if this person is not authorized as an Operator send an error
                    show_error('You are not authorized',500);
                }
            }
            else{
                //an invalid token was sent so
                //show error
                show_error('You are not authorized',500);
                exit();
            }
        }
        else{
            //token was not sent. Sent unauthorized
            show_error('You are not authorized',500);
            exit();
        }
        
    }
    
}

class MY_Patientcontroller extends CI_Controller{
    function __construct(){
        parent::__construct();
        $token = $this->input->post('token');
        if($token){
            //token is sent. so check it
            $tokenData = getInfoFromToken($token);
            if($tokenData){
                //if token role is not correct 
                //send an error
                if($tokenData->role != 'Patient'){
                    //if this person is not authorized as a Patient Send an error
                    show_error('You are not authorized',500);
                }
            }
            else{
                //an invalid token was sent so
                //show error
                show_error('You are not authorized',500);
                exit();
            }
        }
        else{
            //token was not sent. Sent unauthorized
            show_error('You are not authorized',500);
            exit();
        }
    }
    
}
