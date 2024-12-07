<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }

    public function index()
    {
        //$data['department']=$this->Department_model->select_departments();
        //$data['country']=$this->Home_model->select_countries();
        $this->load->view('admin/header');
        $this->load->view('admin/add-staff');
        $this->load->view('admin/footer');
    }

    public function manage()
    {
        $data['content']=$this->Staff_model->select_staff();
        $this->load->view('admin/header');
        $this->load->view('admin/manage-staff',$data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {
        $this->form_validation->set_rules('txtname', 'Full Name', 'required');
        $this->form_validation->set_rules('txtaddress', 'Address', 'required');
        $this->form_validation->set_rules('txtdesignation', 'Designation', 'required');
        $this->form_validation->set_rules('txtsalary', 'Salary', 'required|numeric');
		$this->form_validation->set_rules('txtemail', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('txtmobile', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
        
        
        $name=$this->input->post('txtname');
        $address=$this->input->post('txtaddress');
        $designation=$this->input->post('txtdesignation');
        $salary=$this->input->post('txtsalary');     
        $email=$this->input->post('txtemail');
		$mobile=$this->input->post('txtmobile');	
        

        if($this->form_validation->run() !== false)
        {
            $this->load->library('image_lib');
            $config['upload_path']= 'uploads/profile-pic/';
            $config['allowed_types'] ='gif|jpg|png|jpeg';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('filephoto'))
            {
                $image='default-pic.jpg';
            }
            else
            {
                $image_data =   $this->upload->data();

                $configer =  array(
                  'image_library'   => 'gd2',
                  'source_image'    =>  $image_data['full_path'],
                  'maintain_ratio'  =>  TRUE,
                  'width'           =>  150,
                  'height'          =>  150,
                  'quality'         =>  50
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();
                
                $image=$image_data['file_name'];
            }
            $login=$this->Home_model->insert_login(array('user_name'=>$email,'password'=>md5($mobile),'usertype'=>2));
            if($login>0)
            {
                $data=$this->Staff_model->insert_staff(array('id'=>$login,'name'=>$name,'address'=>$address,'designation'=>$designation,'salary'=>$salary,'email'=>$email,'mobile'=>$mobile,'pic'=>$image));
            }
            
            if($data==true)
            {
                
                $this->session->set_flashdata('success', "New Employee Added Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, New Employee Adding Failed.");
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $this->index();
            return false;

        } 
    }

    public function update()
    {
        $this->load->helper('form');
        $this->form_validation->set_rules('txtname', 'Full Name', 'required');
        $this->form_validation->set_rules('txtaddress', 'Address', 'required');
        $this->form_validation->set_rules('txtdesignation', 'Designation', 'required');
        $this->form_validation->set_rules('txtsalary', 'Salary', 'required|numeric');
		$this->form_validation->set_rules('txtemail', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('txtmobile', 'Mobile Number ', 'required|regex_match[/^[0-9]{10}$/]');
        
        $id=$this->input->post('txtid');
        $name=$this->input->post('txtname');
        $address=$this->input->post('txtaddress');
        $designation=$this->input->post('txtdesignation');
        $salary=$this->input->post('txtsalary');
		$email=$this->input->post('txtemail');
		$mobile=$this->input->post('txtmobile');		

        if($this->form_validation->run() !== false)
        {
            $this->load->library('image_lib');
            $config['upload_path']= 'uploads/profile-pic/';
            $config['allowed_types'] ='gif|jpg|png|jpeg';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('filephoto'))
            {
                $data=$this->Staff_model->update_staff(array('name'=>$name,'address'=>$address,'designation'=>$designation,'salary'=>$salary,'email'=>$email,'mobile'=>$mobile),$id);
            }
            else
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
				
				$image_data =   $this->upload->data();

                $configer =  array(
                  'image_library'   => 'gd2',
                  'source_image'    =>  $image_data['full_path'],
                  'maintain_ratio'  =>  TRUE,
                  'width'           =>  150,
                  'height'          =>  150,
                  'quality'         =>  50
                );
                $this->image_lib->clear();
                $this->image_lib->initialize($configer);
                $this->image_lib->resize();

                $data=$this->Staff_model->update_staff(array('name'=>$name,'address'=>$address,'designation'=>$designation,'salary'=>$salary,'email'=>$email,'mobile'=>$mobile,'pic'=>$image_data['file_name']),$id);
            }
            
            if($this->db->affected_rows() > 0)
            {
                $this->session->set_flashdata('success', "Employee Updated Succesfully"); 
            }else{
                $this->session->set_flashdata('error', "Sorry, Employee Updated Failed.");
            }
            redirect(base_url()."manage-staff");
        }
        else{
            $this->index();
            return false;

        } 
    }


    function edit($id)
    {
        //$data['department']=$this->Department_model->select_departments();
        //$data['country']=$this->Home_model->select_countries();
        $data['content']=$this->Staff_model->select_staff_byID($id);
        $this->load->view('admin/header');
        $this->load->view('admin/edit-staff',$data);
        $this->load->view('admin/footer');
    }


    function delete()
    {
        extract($_POST);
		$this->Home_model->delete_login_byID($id);
        $data=$this->Staff_model->delete_staff($id);
        if($this->db->affected_rows() > 0)
        {
            $resp['status'] = 'success';
			$this->session->set_flashdata('success', "Employee Deleted Succesfully");
			
        }else{
            $this->session->set_flashdata('error', "Sorry, Employee Delete Failed.");
        }
        //redirect($_SERVER['HTTP_REFERER']);
		
		echo json_encode($resp);
    }

    



}
