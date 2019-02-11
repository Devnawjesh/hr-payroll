<?php

	class logistic_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
	}
/*    public function Add_LoanData($data){
        $this->db->insert('loan',$data);
    }*/
    public function Add_LogisticeData($data){
        $this->db->insert('logistic_asset',$data);
    }
    public function Add_LogisticeSupport($data){
        $this->db->insert('logistic_assign',$data);
    }
    public function Add_Assets_Category($data){
        $this->db->insert('assets_category',$data);
    }
/*    public function LogisticValue(){
    $sql = "SELECT * FROM `assets` WHERE `Assets_type`='Logistic' ORDER BY `id` DESC";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;  
    }*/
    public function GetINStock($id){
    $sql = "SELECT * FROM `assets` WHERE `ass_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;  
    }
    public function GetAssetsVal($id){
    $sql = "SELECT * FROM `assets_category` WHERE `cat_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;  
    }
    public function GetLogisticeValueByid($id){
    $sql = "SELECT * FROM `logistic_asset` WHERE `log_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;  
    }

    public function Update_LogisticeData($id,$data){
        $this->db->where('log_id',$id);
        $this->db->update('logistic_asset',$data);        
    }
    public function Update_LogisticeSupport($id,$data){
        $this->db->where('ass_id',$id);
        $this->db->update('logistic_assign',$data);        
    }
    public function Update_Assets($logid,$data){
        $this->db->where('ass_id',$logid);
        $this->db->update('assets',$data);        
    }
    public function LogisticsupportValue(){
        $sql = "SELECT `logistic_assign`.*,
        `employee`.`em_id`,`first_name`,`last_name`,
        `assets`.`ass_name`,
        `pro_task`.`id`,`task_title`
        FROM `logistic_assign`
        LEFT JOIN `employee` ON `logistic_assign`.`assign_id`=`employee`.`em_id`
        LEFT JOIN `assets` ON `logistic_assign`.`asset_id`=`assets`.`ass_id`
        LEFT JOIN `pro_task` ON `logistic_assign`.`task_id`=`pro_task`.`id`
        ORDER BY `logistic_assign`.`ass_id` DESC ";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;         
    }
    public function GetLogisticesupportvalByid($id){
        $sql = "SELECT `logistic_assign`.*,
        `employee`.`em_id`,`first_name`,`last_name`,
        `assets`.`ass_name`,
        `pro_task`.`id`,`task_title`
        FROM `logistic_assign`
        LEFT JOIN `employee` ON `logistic_assign`.`assign_id`=`employee`.`em_id`
        LEFT JOIN `assets` ON `logistic_assign`.`asset_id`=`assets`.`ass_id`
        LEFT JOIN `pro_task` ON `logistic_assign`.`task_id`=`pro_task`.`id`
        WHERE `logistic_assign`.`ass_id`='$id' ";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;         
    }
    public function GettaskByProid($id){
        $sql = "SELECT * FROM `pro_task` WHERE `pro_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;         
    }
    public function getAssetsQty($logid){
        $sql = "SELECT * FROM `assets` WHERE `ass_id`='$logid'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;         
    }
    public function GetAssignByProid($id){
    $sql = "SELECT `assign_task`.*,
      `employee`.*
      FROM `assign_task`
      LEFT JOIN `employee` ON `assign_task`.`assign_user`=`employee`.`em_id`
      WHERE `assign_task`.`task_id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;         
    }
    public function Update_Assets_Category($id,$data){
        $this->db->where('cat_id',$id);
        $this->db->update('assets_category',$data);
    } 
    public function GetAssetsValueId($id){
        $sql = "SELECT `assets`.*,
        `assets_category`.*
        FROM `assets`
        LEFT JOIN `assets_category` ON `assets`.`catid`=`assets_category`.`cat_id`
        WHERE `assets`.`ass_id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
		return $result;          
    }        
    }