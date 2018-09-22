<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mod_post extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    public function insert($table,$data){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    /**
    * 
    * @param undefined $field
    * @param undefined $table
    * @param undefined $where
    * @param undefined $order
    * @param undefined $group
    * 
    */
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
/**
* 
* @param undefined $table
* @param undefined $tablejoin
* @param undefined $fields
* @param undefined $where
* @param undefined $order
* @param undefined $group
* @param undefined $limit
* 
*/
function join($table,$tablejoin,$fields='*',$where=NULL,$order=NULL,$group=NULL,$limit=NULL){
    $this->db->select($fields);
    $this->db->from($table);
    if(is_array($tablejoin)){
        foreach($tablejoin as $value){
            
	        $this->db->join($value['table'], $value['field1'].'='. $value['field2']);
	  }	  
	}
    if(!empty($where)){
        $this->db->where($where);
    }
   if(!empty($order)){
       $this->db->order_by($order,"desc");
   }
   if(!empty($group)){
       $this->db->group_by($group);
   }
   if(!empty($limit)){
       $this->db->limit($limit);
   }
    $query = $this->db->get();
	return $query->result();
}
/**
* 
* @param undefined $table
* @param undefined $fields
* @param undefined $where
* 
*/

 public function delete($table,$fields,$where){
      $this->db->where_in($fields,array($where));
      $this->db->delete($table);
  }
 
}
