<?php

	class Project_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
	}
    public function Add_ProjectData($data){
        $this->db->insert('project', $data);
    }
    public function Add_Tasks($data){
        $this->db->insert('pro_task', $data);
    }
    public function Add_Project_File($data){
        $this->db->insert('project_file', $data);
    }
    public function Add_FieldData($data){
        $this->db->insert('field_visit', $data);
    }
    public function Update_FieldData($id, $data){
        $this->db->where('id', $id);
        $this->db->update('field_visit', $data);
    }
    public function GetProjectsValue(){
        $sql = "SELECT * FROM `project`";
        $query=$this->db->query($sql);
        $result = $query->result();
		return $result;          
    } 
    public function GetFilebyFid($id){
        $sql = "SELECT * FROM `project_file` WHERE `project_file`.`id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;          
    } 
    public function GetNotesValueId($id){
        $sql = "SELECT * FROM `pro_notes` WHERE `pro_notes`.`id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;          
    }    
    public function GetAssetsCategory(){
        $sql = "SELECT * FROM `assets_category`";
        $query=$this->db->query($sql);
        $result = $query->result();
		return $result;          
    } 
    public function GetprojectDetails($id){
        $sql = "SELECT * FROM `project` WHERE `id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;            
    } 
    public function GetLogisTicValue($id){
        $sql = "SELECT * FROM `logistic_assign` WHERE `ass_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->result();
		return $result;            
    }
    public function update_ProjectData($id,$data){
        $this->db->where('id',$id);
        $this->db->update('project',$data);
    }
    public function Updated_Project_expenses($id,$data){
        $this->db->where('id',$id);
        $this->db->update('pro_expenses',$data);
    }
    public function Update_Tasks($id,$data){
        $this->db->where('id',$id);
        $this->db->update('pro_task',$data);
    }
    public function Update_Project_Notes($id,$data){
        $this->db->where('id',$id);
        $this->db->update('pro_notes',$data);
    }
    public function Update_members_Data($id,$data){
        $this->db->where('task_id',$id);
        $this->db->update('assign_task',$data);
    }
    public function Update_Assets($id,$data){
        $this->db->where('ass_id',$id);
        $this->db->update('assets',$data);
    }
    public function GetAllLogistice($id){
    $sql = "SELECT `logistic_assign`.*,
      `employee`.`em_id`,`first_name`,`last_name`,
      `assets`.`ass_name`
      FROM `logistic_assign`
      LEFT JOIN `employee` ON `logistic_assign`.`assign_id`=`employee`.`em_id`
      LEFT JOIN `assets` ON `logistic_assign`.`asset_id`=`assets`.`ass_id`
      WHERE `logistic_assign`.`project_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetTasksAllList($id){
    $sql = "SELECT `pro_task`.*,
      `project`.`pro_name`,
      `logistic_assign`.`log_qty`,`asset_id`
      FROM `pro_task`
      LEFT JOIN `project` ON `pro_task`.`pro_id`=`project`.`id`
      LEFT JOIN `logistic_assign` ON `pro_task`.`id`=`logistic_assign`.`task_id`
      WHERE `pro_task`.`pro_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetTasksOfficeList($id){
    $sql = "SELECT `pro_task`.*,
    `assign_task`.`assign_user`,
      `employee`.`em_id`,`first_name`,`em_image`,
      `project`.`pro_name`
      FROM `pro_task`
      LEFT JOIN `assign_task` ON `pro_task`.`id`=`assign_task`.`task_id`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      LEFT JOIN `project` ON `pro_task`.`pro_id`=`project`.`id`
      WHERE `pro_task`.`pro_id`='$id' AND `pro_task`.`task_type`='Office'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetTasksFiledList($id){
    $sql = "SELECT `pro_task`.*,
    `assign_task`.`assign_user`,
      `employee`.`em_id`,`first_name`,`em_image`,
      `project`.`pro_name`
      FROM `pro_task`
      LEFT JOIN `assign_task` ON `pro_task`.`id`=`assign_task`.`task_id`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      LEFT JOIN `project` ON `pro_task`.`pro_id`=`project`.`id`
      WHERE `pro_task`.`pro_id`='$id' AND `pro_task`.`task_type`='Field'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetTasksBothList($id){
    $sql = "SELECT `pro_task`.*,
    `assign_task`.`assign_user`,
      `employee`.`em_id`,`first_name`,`em_image`,
      `project`.`pro_name`
      FROM `pro_task`
      LEFT JOIN `assign_task` ON `pro_task`.`id`=`assign_task`.`task_id`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      LEFT JOIN `project` ON `pro_task`.`pro_id`=`project`.`id`
      WHERE `pro_task`.`pro_id`='$id' AND `pro_task`.`task_type`='Both'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetTasksValue($id){
    $sql = "SELECT `pro_task`.*,
    `assign_task`.`assign_user`,
      `employee`.`em_id`,`first_name`,`em_image`,
      `project`.`pro_name`
      FROM `pro_task`
      LEFT JOIN `assign_task` ON `pro_task`.`id`=`assign_task`.`task_id`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      LEFT JOIN `project` ON `pro_task`.`pro_id`=`project`.`id`
      WHERE `pro_task`.`id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;          
    }
    public function GetFilesList($id){
    $sql = "SELECT `project_file`.*,
      `employee`.`first_name`,`em_image`
      FROM `project_file`
      LEFT JOIN `employee` ON `project_file`.`assigned_to`=`employee`.`em_id`
      WHERE `project_file`.`pro_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetF_i_e_l_dApplication(){
    $sql = "SELECT `field_visit`.*,
      `employee`.`first_name`,`last_name`,`em_code`, `em_id`,
      `project`.`pro_name`
      FROM `field_visit`
      LEFT JOIN `employee` ON `field_visit`.`emp_id`=`employee`.`em_id`
      LEFT JOIN `project` ON `field_visit`.`project_id`=`project`.`id`";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }


    //Approve field application - update
    public function updateFieldVistApplication($fieldApplicationID, $data){
        
        $this->db->where('id', $fieldApplicationID);
        $this->db->update('field_visit', $data); 
        return true;        
    }

    // Get field visit data by id to populate form

    public function getFieldAuthDataByID($id){
        $sql = "SELECT `field_visit`.*,
      `employee`.`em_id`
      FROM `field_visit`
      LEFT JOIN `employee` ON `field_visit`.`emp_id`=`employee`.`em_id` 
      WHERE `field_visit`.`id`='$id'";
        $query = $this->db->query($sql);
        $result = $query->row();
        return $result; 
    }

    public function GetNotesList($id){
    $sql = "SELECT `pro_notes`.*,
      `employee`.`first_name`,`last_name`,`em_id`,`em_image`
      FROM `pro_notes`
      LEFT JOIN `employee` ON `pro_notes`.`assign_to`=`employee`.`em_id`
      WHERE `pro_notes`.`pro_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetExpensesList($id){
    $sql = "SELECT `pro_expenses`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_image`
      FROM `pro_expenses`
      LEFT JOIN `employee` ON `pro_expenses`.`assign_to`=`employee`.`em_id`
      WHERE `pro_expenses`.`pro_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetEmProjectsValue($id){
    $sql = "SELECT `assign_task`.`project_id`,`assign_user`,
      `project`.*
      FROM `assign_task`
      LEFT JOIN `project` ON `assign_task`.`project_id`=`project`.`id`
      WHERE `assign_task`.`assign_user`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetExpensesValue($id){
    $sql = "SELECT `pro_expenses`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_image`
      FROM `pro_expenses`
      LEFT JOIN `employee` ON `pro_expenses`.`assign_to`=`employee`.`em_id`
      WHERE `pro_expenses`.`id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;          
    }
    public function GetAllTasksList(){
    $sql = "SELECT `pro_task`.*,
      `project`.`pro_name`
      FROM `pro_task`
      LEFT JOIN `project` ON `pro_task`.`pro_id`=`project`.`id`";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function getProjectAssignUser($id){
    $sql = "SELECT `assign_task`.*,
      `employee`.`em_id`,`first_name`,`last_name`
      FROM `assign_task`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      WHERE `assign_task`.`project_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function getTaskAssignUser($id){
    $sql = "SELECT `assign_task`.*,
      `employee`.`first_name`,`em_image`
      FROM `assign_task`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      WHERE `assign_task`.`task_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function GetEmployesValue($id){
    $sql = "SELECT `assign_task`.*,
      `employee`.`first_name`,`em_image`
      FROM `assign_task`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      WHERE `assign_task`.`task_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;          
    }
    public function Add_Project_Notes($data){
        $this->db->insert('pro_notes',$data);
    }
    public function Add_Project_expenses($data){
        $this->db->insert('pro_expenses',$data);
    }
    public function Add_Assets($data){
        $this->db->insert('assets',$data);
    }
    public function insert_members_Data($data){
        $this->db->insert('assign_task',$data);
    }
    public function GetAllAssetsList(){
        $sql = "SELECT `assets`.*,
        `assets_category`.*
        FROM `assets`
        LEFT JOIN `assets_category` ON `assets`.`catid`=`assets_category`.`cat_id`";
        $query=$this->db->query($sql);
        $result = $query->result();
		return $result;         
    }
    public function GetAssetsQty($id){
        $sql = "SELECT * FROM `assets` WHERE `ass_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;         
    }
    public function GetAllLogisticList(){
        $sql = "SELECT `assets`.*,
        `assets_category`.*
        FROM `assets`
        LEFT JOIN `assets_category` ON `assets`.`catid`=`assets_category`.`cat_id`";
        $query=$this->db->query($sql);
        $result = $query->result();
		return $result;         
    }
    public function GetprojectVal($id){
        $sql = "SELECT * FROM `project` WHERE `id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;         
    }
    public function DeletPro($id){
        $this->db->delete('pro_task',array('id'=> $id));
    }
    public function DeletAssignuser($id){
        $this->db->delete('assign_task',array('task_id'=> $id));
    }
    public function Delet_members_Data($id){
        $this->db->delete('assign_task',array('task_id'=> $id));
    }
    public function DeletProFile($id){
        $this->db->delete('project_file',array('id'=> $id));
    }
    public function DeletExpensesByid($id){
        $this->db->delete('pro_expenses',array('id'=> $id));
    }
    public function DeletNotesByID($id){
        $this->db->delete('pro_notes',array('id'=> $id));
    }
    public function DeletAssetssByid($id){
        $this->db->delete('assets',array('id'=> $id));
    }
    public function DletProjectData($id){
        $this->db->delete('pro_notes', array('pro_id' => $id)); 
        $this->db->delete('pro_task', array('pro_id' => $id));
        $this->db->delete('project', array('id' => $id));
        $this->db->delete('pro_expenses', array('pro_id' => $id));
        $this->db->delete('project_file', array('pro_id' => $id));
        $this->db->delete('assign_task', array('project_id' => $id));
    }
    //Approve field application - update
    public function fieldVisitDoneAndUpdateAttendance($fieldApplicationID, $data){
        
        $this->db->where('id', $fieldApplicationID);
        $this->db->update('field_visit', $data); 
        return true;        
    }

    // Select data from field visit by ID
    public function  selectDataFromFieldVisitByID($fieldApplicationID) {
      $sql = "SELECT `field_visit`.* FROM `field_visit`
      WHERE `field_visit`.`id`='$fieldApplicationID'";
      $query=$this->db->query($sql);
      $result = $query->result();
      return $result; 
    }

    // Select attendance of the employee to update the attendance
    public function updateAttendanceByFieldVisitReturn($data){
        $this->db->insert_batch('attendance', $data);      
    }

    // Select attendance of the employee to update the attendance
    public function insertAttendanceByFieldVisitReturn($data){
        $this->db->insert('attendance', $data);      
    }

    }
?>