<?php

	class Loan_model extends CI_Model{


	function __consturct(){
	parent::__construct();
	
	}
    public function Add_LoanData($data){
        $this->db->insert('loan',$data);
    }
    public function Add_installData($data){
        $this->db->insert('loan_installment', $data);
    }
    public function loan_modeldata(){
    $sql = "SELECT `loan`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_code`
      FROM `loan`
      LEFT JOIN `employee` ON `loan`.`emp_id`=`employee`.`em_id` ORDER BY `loan`.`id` DESC";
        $query=$this->db->query($sql);
		$result = $query->result();
		return $result;  
    }
    public function LoanValselect($id){
    $sql = "SELECT `loan`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_code`
      FROM `loan`
      LEFT JOIN `employee` ON `loan`.`emp_id`=`employee`.`em_id`
      WHERE `loan`.`id`='$id'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;  
    }
    public function LoanValEmselect($id){
    $sql = "SELECT `loan`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_code`
      FROM `loan`
      LEFT JOIN `employee` ON `loan`.`emp_id`=`employee`.`em_id`
      WHERE `loan`.`emp_id`='$id' AND `loan`.`status`!='Done'";
        $query=$this->db->query($sql);
		$result = $query->row();
		return $result;  
    }
    public function installmentSelect(){
    $sql = "SELECT `loan_installment`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_code`
      FROM `loan_installment`
      LEFT JOIN `employee` ON `loan_installment`.`emp_id`=`employee`.`em_id` ORDER BY `loan_installment`.`id` DESC";        
        $query=$this->db->query($sql);
        $result = $query->result();
        return $result;  
    }
    public function GetLoanValuebyLId($loanid){
      $sql = "SELECT `loan`.*,
        `employee`.`em_id`,`first_name`,`last_name`,`em_code`
        FROM `loan`
        LEFT JOIN `employee` ON `loan`.`emp_id`=`employee`.`em_id`
        WHERE `loan`.`id`='$loanid'";
      $query=$this->db->query($sql);
  		$result = $query->row();
  		return $result;  
    }
    public function update_LoanData($loan_id, $data){
  		$this->db->where('id', $loan_id);
  		$this->db->update('loan', $data);
    }
    public function update_LoanDataVal($id,$data){
  		$this->db->where('id', $id);
  		$this->db->update('loan',$data);  
    }
    public function update_LoanInstallData($id,$data){
  		$this->db->where('id', $id);
  		$this->db->update('loan_installment',$data);  
    }
    public function GetEmployeeForloancheck($em_id){
        $sql = "SELECT * from `loan` WHERE `emp_id`='$em_id' AND `status`='Granted'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;
    }
    public function LoanInstallValEmselect($id){
        $sql = "SELECT * from `loan_installment` WHERE `id`='$id'";
        $query=$this->db->query($sql);
        $result = $query->row();
        return $result;
    }
}