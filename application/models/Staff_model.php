<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model {


    function insert_staff($data)
    {
        $this->db->insert("emp_details",$data);
        return $this->db->insert_id();
    }

    function select_staff()
    {
        $this->db->order_by('emp_details.id','DESC');
        $this->db->select("emp_details.*");
        $this->db->from("emp_details");        
        $qry=$this->db->get();
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function select_staff_byID($id)
    {
        $this->db->where('emp_details.id',$id);
        $this->db->select("emp_details.*");
        $this->db->from("emp_details");        
        $qry=$this->db->get();
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function select_staff_byEmail($email)
    {

        $this->db->where('email',$email);
        $qry=$this->db->get('emp_details');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    


    function delete_staff($id)
    {
        $this->db->where('emp_details.id',$id);
        $this->db->select("emp_details.pic");
        $this->db->from("emp_details");        
        $qry=$this->db->get();
        if($qry->num_rows()>0)
		{
			$result=$qry->result_array();
			if($result[0]['pic']!='default-pic.jpg')
			{		
				@unlink('uploads/profile-pic/'.$result[0]['pic']);
			}	
		}	
		
		$this->db->where('id', $id);
        $this->db->delete("emp_details");
        $this->db->affected_rows();		
    }

    
    function update_staff($data,$id)
    {
        $this->db->where('id', $id);
        $this->db->update('emp_details',$data);
        $this->db->affected_rows();
    }

    

    
    




}
