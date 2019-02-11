 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    
	    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('project_model'); 
        $this->load->model('settings_model'); 
        $this->load->model('leave_model'); 
    }
    public function index(){
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
            redirect('dashboard/Dashboard');
            $data=array();
            #$data['settingsvalue'] = $this->dashboard_model->GetSettingsValue();
			$this->load->view('login');        
    }
    public function Settings(){
        if($this->session->userdata('user_login_access') != False) { 
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
        $this->load->view('backend/settings',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Add_Settings(){ 
        if($this->session->userdata('user_login_access') != False) { 
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $copyright = $this->input->post('copyright');
        $contact = $this->input->post('contact');
        $currency = $this->input->post('currency');
        $symbol = $this->input->post('symbol');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $address2 = $this->input->post('address2');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        // Validating Title Field
        $this->form_validation->set_rules('title', 'title','trim|required|min_length[5]|max_length[60]|xss_clean');
        // Validating description Field
        $this->form_validation->set_rules('description', 'description', 'trim|required|min_length[20]|max_length[512]|xss_clean');
        // Validating address Field
        $this->form_validation->set_rules('address', 'address', 'trim|min_length[5]|max_length[600]|xss_clean');
        $this->form_validation->set_rules('address2', 'address2', 'trim|min_length[5]|max_length[600]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
			} else {

            if($_FILES['img_url']['name']){
			$settings = $this->settings_model->GetSettingsValue();
            $file_name = $_FILES['img_url']['name'];
			$fileSize = $_FILES["img_url"]["size"]/1024;
			$fileType = $_FILES["img_url"]["type"];
/*			$new_file_name='';
            $new_file_name .= $title;*/
			$checkimage = "./assets/images/$settings->sitelogo";

            $config = array(
                'file_name' => $file_name,
                'upload_path' => "./assets/images/",
                'allowed_types' => "gif|jpg|png|jpeg|svg",
                'overwrite' => False,
                'max_size' => "13038", // Can be set to particular file size , here it is 220KB(220 Kb)
                'max_height' => "850",
                'max_width' => "850"
            );
    
            $this->load->library('Upload', $config);
            $this->upload->initialize($config);                
            if (!$this->upload->do_upload('img_url')) {
            echo $this->upload->display_errors();
			}
			else {
				if(file_exists($checkimage)){
            	unlink($checkimage);
				}
                $path = $this->upload->data();
                $img_url =$path['file_name'];
                $data = array();
                $data = array(
                    'sitelogo' => $img_url,
                    'sitetitle' => $title,
                    'description' => $description,
                    'copyright' => $copyright,
                    'contact' => $contact,
					'currency' => $currency,
					'symbol' => $symbol,
					'system_email'=>$email,
                    'address'=>$address,
					'address2'=>$address2
                );
            $success = $this->settings_model->SettingsUpdate($id,$data);
			echo 'Successfully Updated';
                #redirect("settings/Settings");
            #$this->session->set_flashdata('feedback','Successfully Updated');    
			}
        } else {
                $data = array();
                $data = array(
                    'sitetitle' => $title,
                    'description' => $description,
                    'copyright' => $copyright,
                    'contact' => $contact,
					'currency' => $currency,
                    'symbol' => $symbol,
					'system_email'=>$email,
                    'address'=>$address,
					'address2'=>$address2,
                );
            $success = $this->settings_model->SettingsUpdate($id,$data);
			echo 'Successfully Updated';
                #redirect("settings/Settings");
            #$this->session->set_flashdata('feedback','Successfully Updated');     
            }
		}
        
        }
    else{
		redirect(base_url() , 'refresh');
	}
}
}