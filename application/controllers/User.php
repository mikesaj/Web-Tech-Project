<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    var $UserID;
    var $TypeID;
    var $FirstName='mikesaj';
    var $LastName;
    var $Email;
    var $School;
    var $City;
    var $State;
    var $Country;
    var $MatchRequesed;
    var $IsVerfied;
    var $ReportedbyID;
    var $Password;
    var $UserName;
    var $Grade;
    var $Bio;
    var $Points;
    
    var $sql;
    
    public function __construct() {
        parent:: __construct();
            
        $this->load->model('database'); //model
    } 
        
    public function index(){}
    public function signup($firstname, $lastname, $email, $password){ 
        //echo $this->FirstName;
        
        $password = md5($password);
        
        $this->sql = "INSERT INTO USER (firstName, lastName, email, password, typeID)".
        "VALUES ('MIKE', 'SAJ', '$email', '$password', 1)";        
                
        $bool = $this->database->insert2db($this->sql); 
        
        if($bool == FALSE){
           //display '0' as false
           echo "{0}";            
        }
        else{
           //display 1 as true
           echo "{1}";
        }
        
        

        
    }
    
    public function signin($email, $password) {
        $password = md5($password);        
         $this->sql = "SELECT * FROM USER WHERE EMAIL = '$email' AND PASSWORD = '$password'";
         
         $data = $this->database->db_query($this->sql);
         echo json_encode($data);         
    }
    
}
