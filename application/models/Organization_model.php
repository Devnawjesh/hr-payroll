<?php

class Organization_model extends CI_Model{


    	function __consturct(){
    	   parent::__construct();
    	
    	}
    public function depselect(){
        $query = $this->db->get('department');
        $result = $query->result();
        return $result;
        }
      public function Add_Department($data){
        $this->db->insert('department',$data);
      }

      public function department_delete($dep_id){
        $this->db->delete('department',array('id' => $dep_id ));
      }

      public function department_edit($dep){
          $sql    = "SELECT * FROM `department` WHERE `id`='$dep'";
          $query  = $this->db->query($sql);
          $result = $query->row();
          return $result;
      }
      public function Update_Department($id, $data){
        $this->db->where('id',$id);
        $this->db->update('department',$data);
      }

      public function Add_Designation($data){
        $this->db->insert('designation',$data);
      }
    public function designation_delete($des_id){
        $this->db->delete('designation',array('id'=> $des_id));
    }

      public function designation_edit($des){
          $sql    = "SELECT * FROM `designation` WHERE `id`='$des'";
          $query  = $this->db->query($sql);
          $result = $query->row();
          return $result;
      }
      public function Update_Designation($id, $data){
        $this->db->where('id',$id);
        $this->db->update('designation',$data);
      }
    public function desselect(){
        $query = $this->db->get('designation');
        $result = $query->result();
        return $result;
    }    
}
?>