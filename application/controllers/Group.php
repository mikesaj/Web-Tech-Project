<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {
    
    var $sql;
    
    public function __construct() {
        parent:: __construct();
            
        $this->load->model('database'); //model
    }
    
    public function index(){}
    
    public function AddGroup($Name, $TeacherID, $Language, $Subject, $Description) {

        // sql statement
        $this->sql = "INSERT INTO GROUPS VALUES ('$Name', '$TeacherID', '$Language', '$Subject', '$Description')";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);    
    }

    public function UpdateGroup($objGroup) {
        // convert JSON data to array
        $group_object = json_decode($objGroup, True);
        
        // loop the properties of the group object to generate sql statement        
        foreach($group_object as $key => $item){
            $sql_condition .= $key . " = '$item', ";            
        }
        
        // Remove characters from the right side of a string:
        $sql_condition = rtrim($sql_condition,", ");
        
        $this->sql = "update groups set $sql_condition WHERE id = '".$objGroup["id"]."'";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);
    }


    public function DeleteGroup($GroupID) {
        $this->sql = "Delete from groups where id = '$GroupID'";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);          
    }
    
    
    public function GetGroup($GroupID) {
        // sql statement
        $this->sql = "SELECT * FROM groups WHERE id = '$GroupID'";
         
        // query db method
         $data = $this->database->db_query($this->sql);
         
        // encode the data into json
         $json = json_encode($data);  

        // output data in JSON
         echo $json; 
    }
    
    
    public function ListGroupByTeacher($TeacherID) {
        // sql statement
        $this->sql = "SELECT * FROM groups WHERE teacherMasterId = '$TeacherID'";
         
        // query db method
         $data = $this->database->db_query($this->sql);
         
        // encode the data into json
         $json = json_encode($data);  

        // output data in JSON
         echo $json; 
    }
    
    public function AddMember($GroupId, $UserId) {
        // sql statement
        $this->sql = "INSERT INTO GroupMembers VALUES ('$GroupId', '$UserId')";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);            
    }
    
    public function DeleteMember($GroupId, $UserId) {
        // sql statement
        $this->sql = "Delete from GroupMembers where groupId = '$GroupId' and userId = '$UserId'";
        $bool = $this->database->insert2db($this->sql); 
        
        // prints database feedback
        echo $this->dbFeedBack($bool);            
        
    }
    
    public function ListMembersByGroup($GroupId) {
        // sql statement
        $this->sql = "SELECT * FROM GroupMembers WHERE id = '$GroupId'";
         
        // query db method
         $data = $this->database->db_query($this->sql);
         
        // encode the data into json
         $json = json_encode($data);  

        // output data in JSON
         echo $json;              
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
    
}