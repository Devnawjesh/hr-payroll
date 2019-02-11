<?php

class Attendance_model extends CI_Model
{
    
    
    function __consturct()
    {
        parent::__construct();
        
    }
    public function Add_AttendanceData($data)
    {
        $this->db->insert('attendance', $data);
    }
    public function bulk_insert($data)
    {
        $this->db->insert_batch('attendance', $data);
    }
    public function em_attendance()
    {
        $sql    = "SELECT `attendance`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_code`
      FROM `attendance`
      LEFT JOIN `employee` ON `attendance`.`emp_id`=`employee`.`em_code` WHERE `attendance`.`status` = 'A' ORDER BY `attendance`.`id` DESC";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }
    public function em_attendanceFor($id)
    {
      $sql    = "SELECT `attendance`.*,
      `employee`.`em_id`,`first_name`,`last_name`,`em_code`
      FROM `attendance`
      LEFT JOIN `employee` ON `attendance`.`emp_id`=`employee`.`em_code` 
      WHERE `attendance`.`id`='$id'";
        $query  = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }
    public function Update_AttendanceData($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('attendance', $data);
    }
    public function bulk_Update($emid,$date,$data)
    {
        $this->db->where('emp_id', $emid);
        $this->db->where('atten_date', $date);
        $this->db->update('attendance', $data);
    }

    public function getPINFromID($employee_ID) {
      $sql = "SELECT `em_code` FROM `employee`
      WHERE `em_id`='$employee_ID'";
      $query=$this->db->query($sql);
      $result = $query->row();
      return $result;
    }

    public function getDuplicateVal($emid,$date) {
      $sql = "SELECT * FROM `attendance`
      WHERE `emp_id`='$emid' AND `atten_date`='$date'";
      $query=$this->db->query($sql);
      $result = $query->row();
      return $result;
    }

    public function getAttendanceDataByID($employee_id, $date_from, $date_to)
    {
      $sql    = "SELECT `attendance`.*,
      `employee`.`em_id`, CONCAT(`first_name`, ' ', `last_name`) AS name,`em_code`, TRUNCATE((ABS(( TIME_TO_SEC( TIMEDIFF( `signin_time`, `signout_time` ) ) )))/3600, 1) AS Hours
      FROM `attendance`
      LEFT JOIN `employee` ON `attendance`.`emp_id` = `employee`.`em_code` 
      WHERE (`attendance`.`emp_id` = '$employee_id') AND (`atten_date` BETWEEN '$date_from' AND '$date_to') AND (`attendance`.`status` = 'A')";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getTotalAttendanceDataByID($employee_PIN, $date_from, $date_to)
    {
      $sql = "SELECT TRUNCATE((SUM(ABS(( TIME_TO_SEC( TIMEDIFF( `signin_time`, `signout_time` ) ) )))/3600), 1) AS Hours FROM `attendance` WHERE (`attendance`.`emp_id`='$employee_PIN') AND (`atten_date` BETWEEN '$date_from' AND '$date_to') AND (`attendance`.`status` = 'A')";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }
    public function getAllAttendance()
    {
      $sql = "SELECT `attendance`.`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`,  TRUNCATE(ABS(( TIME_TO_SEC( TIMEDIFF( `signin_time`, `signout_time` ) ) )/3600), 1) AS Hours,
        CONCAT(`first_name`, ' ', `last_name`) AS name
       FROM `attendance`
        LEFT JOIN `employee` ON `attendance`.`emp_id` = `employee`.`em_code`
        WHERE `attendance`.`status` = 'A'";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }
}


?>