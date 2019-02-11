 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logistice extends CI_Controller {

	    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('loan_model');
        $this->load->model('settings_model');    
        $this->load->model('leave_model');    
        $this->load->model('logistic_model');    
        $this->load->model('project_model');    
    }
    
/*    public function View(){
        if($this->session->userdata('user_login_access') != False) {
        $data['logisticview'] = $this->logistic_model->LogisticValue();
            
        $this->load->view('backend/logistic_list',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }*/
    public function logistic_support(){
        if($this->session->userdata('user_login_access') != False) {
        $data['projects'] = $this->project_model->GetProjectsValue();    
        /*$data['logisticview'] = $this->logistic_model->LogisticValue();*/    
        $data['supportview'] = $this->logistic_model->LogisticsupportValue();
        $data['employee'] = $this->employee_model->emselect();  
        $data['tasks'] = $this->project_model->GetAllTasksList();
        $data['assets'] = $this->project_model->GetAllAssetsList();    
        $this->load->view('backend/logistic_support',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }
    public function Add_Logistic(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('logid');
        $name = $this->input->post('logname');
        $qty = $this->input->post('logqty');
        $logdate = date("m/d/y");
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('logname', 'name details', 'trim|required|min_length[2]|max_length[220]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			redirect("loan/View");
			} else {
            $data = array();
                $data = array(
                    'name' => $name,
                    'qty' => $qty,
                    'entry_date' => $logdate
                );
            if(empty($id)){
                $success = $this->logistic_model->Add_LogisticeData($data);
                #$this->session->set_flashdata('feedback','Successfully Added');
                #redirect("loan/View");
                echo "Successfully Added";
            } else {
                $success = $this->logistic_model->Update_LogisticeData($id,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                #redirect("loan/View");
                echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Add_Logistic_Support(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('assid');
        $logid = $this->input->post('logid');
        $assignid = $this->input->post('assignid');
        $proid = $this->input->post('proid');
        $taskid = $this->input->post('taskid');
        $assignqty= $this->input->post('assignqty');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $backdate = $this->input->post('backdate');
        $backqty = $this->input->post('backqty');
        $remarks = $this->input->post('remarks');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('assignqty', 'Quantity', 'trim|required|min_length[1]|max_length[220]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			#redirect("loan/View");
			} else {
            $data = array();
                $data = array(
                    'asset_id' => $logid,
                    'assign_id' => $assignid,
                    'project_id' => $proid,
                    'task_id' => $taskid,
                    'log_qty' => $assignqty,
                    'start_date' => $startdate,
                    'end_date' => $enddate,
                    'back_date' => $backdate,
                    'back_qty' => $backqty,
                    'remarks' => $remarks
                );
            if(empty($id)){
                $success = $this->logistic_model->Add_LogisticeSupport($data);
                #$this->session->set_flashdata('feedback','Successfully Added');
                #redirect("loan/View");
                #echo "Successfully Added";
                $assets = $this->logistic_model->getAssetsQty($logid);
                $inqty = $assets->in_stock - $assignqty;
                $data = array();
                $data = array(
                    'in_stock' => $inqty
                ); 
                $this->logistic_model->Update_Assets($logid,$data);
                 echo "Successfully Updated";
            } else {
                $success = $this->logistic_model->Update_LogisticeSupport($id,$data);
                $assets = $this->logistic_model->getAssetsQty($logid);
                $inqty = $assets->in_stock + $backqty;
                $data = array();
                $data = array(
                    'in_stock' => $inqty
                ); 
                $this->logistic_model->Update_Assets($logid,$data);
                 echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Logisticebyib(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('id');
		$data['logisticevaluebyid'] = $this->logistic_model->GetLogisticeValueByid($id);
		echo json_encode($data);        
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Logisticesupportbyib(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('id');
		$data['logisticsupport'] = $this->logistic_model->GetLogisticesupportvalByid($id);
		echo json_encode($data);        
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function GetInstock(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('id');
		$instock = $this->logistic_model->GetINStock($id);
		echo $instock->in_stock;        
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Logisticedelet(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('D');
        $this->logistic_model->DeletLogistic($id);        
        }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function GetTaskforlogistic(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('id');
		$taskvalue = $this->logistic_model->GettaskByProid($id);
        foreach($taskvalue as $value){
            echo"<option value='$value->id'>$value->task_title</option>";
        }        
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }
    public function AssetscatByID(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('id');
		$data['assetscatval'] = $this->logistic_model->GetAssetsVal($id);
		echo json_encode($data);        
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }
    public function GetAssignforlogistic(){
    if($this->session->userdata('user_login_access') != False) {
		$id = $this->input->get('id');
		$emvalue = $this->logistic_model->GetAssignByProid($id);
        foreach($emvalue as $value){
            echo"<option value='$value->em_id'>$value->first_name $value->last_name</option>";
        }        
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }
    public function Assets_Category(){
    if($this->session->userdata('user_login_access') != False) {
        $data=array();
        $data['catvalue'] = $this->project_model->GetAssetsCategory();
        $this->load->view('backend/assets_category',$data);
    }
    else{
		redirect(base_url() , 'refresh');
	}        
    }
    public function Add_Assets_Category(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('catid');
        $cattype = $this->input->post('cattype');
        $catname = $this->input->post('catname');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('catname', 'Category name', 'trim|required|min_length[1]|max_length[220]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			} else {
            $data = array();
                $data = array(
                    'cat_name' => $catname,
                    'cat_status' => $cattype
                );
            if(empty($id)){
                $success = $this->logistic_model->Add_Assets_Category($data);
                 echo "Successfully Added";
            } else {
                $success = $this->logistic_model->Update_Assets_Category($id,$data);
                 echo "Successfully Updated";
            }
                       
        }
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }
    public function All_Assets(){
        if($this->session->userdata('user_login_access') != False) {         
        $data['assets'] = $this->project_model->GetAllAssetsList();
        $data['catvalue'] = $this->project_model->GetAssetsCategory();
        $this->load->view('backend/assets_view',$data);
        }
    else{
		redirect(base_url() , 'refresh');
	}            
    }
    public function Add_Assets(){
if($this->session->userdata('user_login_access') != False) {         
    $id = $this->input->post('aid');    
    $catid = $this->input->post('catid');    
	$name = $this->input->post('assname');
	$brand = $this->input->post('brand');
	$model= $this->input->post('model');		
	$code = $this->input->post('code');		
	$config = $this->input->post('config');		
	$purchase = $this->input->post('purchase');		
	$price = $this->input->post('price');		
	$qty = $this->input->post('pqty');				
	$stock = $qty;				
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('config', 'config','trim|required|min_length[2]|max_length[2024]|xss_clean');
        $this->form_validation->set_rules('pqty', 'Quantity','trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
			} else {
                $data = array();
                $data = array(
                    'catid' => $catid,
                    'ass_name' => $name,
                    'ass_brand' => $brand,
                    'ass_model' => $model,
                    'ass_code' => $code,
                    'configuration' => $config,
                    'purchasing_date' => $purchase,
                    'ass_price' => $price,
                    'ass_qty' => $qty,
                    'in_stock' => $stock
                );
         if(empty($id)){
            $success = $this->project_model->Add_Assets($data); 
			echo "Successfully Added";            
         } else {
             $value = $this->project_model->GetAssetsQty($id);
             $inqty = $qty - $value->ass_qty;
            $instock = $value->in_stock + $inqty;
                $data = array();
                $data = array(
                    'catid' => $catid,
                    'ass_name' => $name,
                    'ass_brand' => $brand,
                    'ass_model' => $model,
                    'ass_code' => $code,
                    'configuration' => $config,
                    'purchasing_date' => $purchase,
                    'ass_price' => $price,
                    'ass_qty' => $qty,
                    'in_stock' => $instock
                );
            $success = $this->project_model->Update_Assets($id,$data); 
			echo "Successfully Updated"; 
         }   
        } 
        }
    else{
		redirect(base_url() , 'refresh');
	}    
    } 
    public function AssetsByID(){
        if($this->session->userdata('user_login_access') != False) {  
		$id= $this->input->get('id');
		$data['assetsByid'] = $this->logistic_model->GetAssetsValueId($id);
		echo json_encode($data);
        }
    else{
		redirect(base_url() , 'refresh');
	}         
    }    
}
?>