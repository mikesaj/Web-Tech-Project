<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class database extends CI_Model {
        
    public function __construct()
    {       parent::__construct();
	$this->load->database();
    }

        
    public function insert2db($sql) {        
            $bool = $this->db->query($sql);
            return $bool;        
    }
    
    public function db_query($sql) {
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}