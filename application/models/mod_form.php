<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mod_form extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function select($field, $table, $where=0, $order=0, $group=0){
    $this->db->select($field);
    $this->db->from($table);
    if(!empty($where)) {
        $this->db->where($where);
    }
    if(!empty($order)) {
        $this->db->order_by($order);
    }
    if(!empty($group)) {
        $this->db->group_by($group); 
    }       
    $query=$this->db->get();
    if($query){
        return $query->result();
    }else{
        return false;
    }
}   
}
