<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    var $UserID;
    var $TypeID;
    var $FirstName;
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
    
    //method to allow users sign-in
    public function signup($firstname, $lastname, $email, $password){ 
        
        // hashing the password
        $password = md5($password);
        
        // generate token
        $token = $this->generateToken();
        
        // sql statement
        $this->sql = "INSERT INTO USER (firstName, lastName, email, password, typeID, token)".
        "VALUES ('MIKE', 'SAJ', '$email', '$password', 1, '$token')";        
        
        // Insert into database method
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);

    }
    
    //Sign-in method
    public function signin($email, $password) {
        // hashing the password
        $password = md5($password);        
        
        // sql statement
        $this->sql = "SELECT * FROM USER WHERE EMAIL = '$email' AND PASSWORD = '$password'";
         
        // query db method
         $data = $this->database->db_query($this->sql);
         
        // encode the data into json
         $json = json_encode($data);  

        // output data in JSON
         echo $json;
         
    }
    
    //Method to update user record
    public function EditUser($objUser) {
        // convert JSON data to array
        $user_object = json_decode($objUser, True);
        
        // loop the properties of the user to generate sql statement        
        foreach($user_object as $key => $item){
            $sql_condition .= $key . " = '$item', ";            
        }
        
        // Remove characters from the right side of a string:
        $sql_condition = rtrim($sql_condition,", ");
        
        $this->sql = "update user set $sql_condition WHERE userID = '".$objUser["userID"]."'";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);
        
    }
    
    // Method to reset password
    public function ForgotPassword($email) {
        //generate user account token
        $token = $this->generateToken();
        
        //update user account with new token
        $this->sql = "Update User set token = '$token' where email = '$email'";
        $bool = $this->database->insert2db($this->sql); 
        
        // if user token is updated
        if($bool != FALSE){
            echo "email:$email, token:$token";            
        }
    }
    
    // Method to reset user password
    public function ResetPassword($email, $password, $token) {
        $this->sql = "Update User set password = '$password' where email = '$email' and token = '$token'";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);        
    }
    
    // Method to delete a user
    public function DeleteUser($email) {
        
        $this->sql = "Delete from User where email = '$email'";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);         
        
    }
    
    // Method to get a user's information
    public function GetUser($email) {
        // sql statement
        $this->sql = "SELECT * FROM USER WHERE EMAIL = '$email'";
         
        // query db method
         $data = $this->database->db_query($this->sql);
         
        // encode the data into json
         $json = json_encode($data);  

        // output data in JSON
         echo $json;        
    }
    
    // Method to verify user account
    public function VerifyUser($email) {
        // sql statement
        $this->sql = "SELECT * FROM USER WHERE EMAIL = '$email' AND isVerified = 1";
        
        // query db method
         $data = $this->database->db_query($this->sql);
         
         //checks if recored is present and output result
         $this->db_record_check($data);
        
    }
    
    // Method to report user
    public function ReportUser($UserId, $ReportedById) {
        // sql statement
        $this->sql = "SELECT * FROM USER WHERE ReportedById = '$ReportedById' AND UserId = '$UserId'";
        
        // query db method
         $data = $this->database->db_query($this->sql);
         
         //checks if recored is present and output result
         $this->db_record_check($data);
        
    }
    
    // Method to List User By UserType
    public function ListUserByUserType($UserType) {
        // sql statement
        $this->sql = "
                SELECT * 
                FROM USER u
                INNER JOIN userstype ut
                ON ut.typeID = u.typeID                
                WHERE ut.typeName = '$UserType'";
        
        // query db method
         $data = $this->database->db_query($this->sql);
         
        // encode the data into json
         $json = json_encode($data);  

        // output data in JSON
         echo $json;        
    }
    
    /* helper function for db output status */

    ///helper function to generate token
    private function generateToken() {
        $token = md5(uniqid(rand(), true));
       return $token;
    }
    
    ///helper function to output json-boolean feedbck
    private function dbFeedBack($bool){
        if($bool == FALSE){
           //display '0' as false
           return "{0}";            
        }
        else{
           //display 1 as true
           return "{1}";
        }   
    }

    //check for record in db        
    private function db_record_check($data) {
          // count lenght of array
         $array_lenght = count($data);
         
         //if lenght is less than 1, means user account isnt verified
         if($array_lenght == 0){
             echo $this->dbFeedBack(FALSE);
         }  
         else {
             echo $this->dbFeedBack(TRUE);
         }       
    }
    


}
