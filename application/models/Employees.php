<?php

class Employees extends Zend_DB_Table
{
	protected $_name = "employees";


     /**
     * Get record by ID
     * @param type $id
     * @return type object
     */
    public function getById($id){
        $select = $this->select()
                        ->from($this->_name)
                        ->where('id=?',$id);
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function getByName($firstname,$lastname){
    	$first = ucwords(strtolower(trim($firstname)));
    	$last = ucwords(strtolower(trim($lastname)));
    	
    	$firstSegments = explode(" ",$firstname);
    	
        $select = $this->select()
                        ->from($this->_name)
                        ->where("firstname LIKE '%".$firstSegments[0]."%'")
                        ->where("lastname LIKE '%".$last."%'");
        $result = $this->fetchRow($select);
        return $result;
    }
    
    public function getByNameLocation($firstname,$lastname,$location){
    	$first = ucwords(strtolower(trim($firstname)));
    	$last = ucwords(strtolower(trim($lastname)));
    	
    	$firstSegments = explode(" ",$firstname);
    	try{
        $select = $this->select()
                        ->from($this->_name)
                        ->where("firstname = '$firstname' ")
                        ->where("lastname = '$lastname' ")
                        ->where("location = '$location' ");
        $result = $this->fetchRow($select);
        	return $result;
        } catch(Exception $e){
        	return false;
        }
    }
    
    
     /**
     * Get All Records
     * @return type object
     */
    public function getAll($term = false){
        $select = $this->select()
                        ->from($this->_name);
                
                        if($term){
                            $where = "(";
                            $where .= "firstname LIKE '%$term%' ";
                            $where .= " OR lastname LIKE '%$term%' ";
                            $where .= " OR position LIKE '%$term%' ";
                            $where .= " OR account_number LIKE '%$term%' ";
                            $where .= " OR cp_id LIKE '%$term%' ";
                            $where .= ")";
                            $select->where($where);
                        }

                        $select->order('lastname asc')->order('firstname asc')->order('location asc');
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function getAllByLocation($locationid){
        $select = $this->select()
                        ->from($this->_name)
                        ->where('location = ?',$locationid)                        
                        ->order('lastname asc')
                        ->order('firstname asc')
                        ->order('location asc')
        				;
        $result = $this->fetchAll($select);
        return $result;
    }
    
    /**
    * Update Record in Database
    * @param int $id
    * @param array $data 
    */
    function updateRecord($id,$data){
        $where =  "id = " . $id;
        $this->update($data,$where);
        return true;
    }
    
}
