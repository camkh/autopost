<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model
{
    
    function __construct() {
        
        parent::__construct();
    }
    
    public function insert($table, $data) {
        
        $this->db->insert($table, $data);
        
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
    
    function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        
        // First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } 
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } 
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        
        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } 
        elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } 
        elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } 
        elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } 
        elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } 
        elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }elseif (preg_match('/Mozilla/i', $u_agent)) {
            $bname = 'Mozilla';
            $ub = "Mozilla";
        } else {
            $bname = $u_agent;
            $ub = $u_agent;
        }
        
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            
            // we have no matching number just continue
            
        }
        
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            
            // we will have two since we are not using 'other' argument yet
            // see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } 
            else {
                $version = $matches['version'][1];
            }
        } 
        else {
            $version = $matches['version'][0];
        }
        
        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }
        
        return array('userAgent' => $u_agent, 'name' => $bname, 'version' => $version, 'platform' => $platform, 'pattern' => $pattern);
    }



    public function select($field, $table, $where = 0, $order = 0, $group = 0) {
        
        $this->db->select($field);
        
        $this->db->from($table);
        
        if (!empty($where)) {
            
            $this->db->where($where);
        }
        
        if (!empty($order)) {
            
            $this->db->order_by($order);
        }
        
        if (!empty($group)) {
            
            $this->db->group_by($group);
        }
        
        $query = $this->db->get();
        
        if ($query) {
            
            return $query->result();
        } 
        else {
            
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
    
    function join($table, $tablejoin, $fields = '*', $where = NULL, $order = NULL, $group = NULL, $limit = NULL) {
        
        $this->db->select($fields);
        
        $this->db->from($table);
        
        if (is_array($tablejoin)) {
            
            foreach ($tablejoin as $value) {
                
                $this->db->join($value['table'], $value['field1'] . '=' . $value['field2']);
            }
        }
        
        if (!empty($where)) {
            
            $this->db->where($where);
        }
        
        if (!empty($order)) {
            
            $this->db->order_by($order, "desc");
        }
        
        if (!empty($group)) {
            
            $this->db->group_by($group);
        }
        
        if (!empty($limit)) {
            
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
    
    public function delete($table, $fields, $where) {
        
        $this->db->where_in($fields, array($where));
        
        $this->db->delete($table);
    }
}

