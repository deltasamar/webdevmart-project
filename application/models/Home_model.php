<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    function logindata($un,$pw)
    {
        $this->db->where('user_name',$un);               
        $this->db->where('password',md5($pw));
        $qry=$this->db->get("login_details");
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function insert_login($data)
    {
        $this->db->insert("login_details",$data);
        return $this->db->insert_id();
    }

    
    function select_countries()
    {
        $qry=$this->db->get('country_tbl');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }
    
    function delete_login_byID($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("login_details");
        $this->db->affected_rows();
    }




}
