<?php

class Substitutes extends Zend_DB_Table
{
	protected $_name = "substitutes";


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
    
     public function getBySecondaryId($id){
        $select = $this->select()
                        ->from($this->_name)
                        ->where('secondary_id=?',$id);
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
    
    
     /**
     * Get All Records
     * @return type object
     */
    public function getAll(){
        $select = $this->select()
                        ->from($this->_name)
                        ->where('active = 1')
                        ->order('lastname asc')
                        ->order('firstname asc');
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function getAllTotal($term){
        $select = $this->select()
                        ->from($this->_name);
                        if($term){
                            $where = "(";
                            $where .= "firstname LIKE '%$term%' ";
                            $where .= " OR lastname LIKE '%$term%' ";
                            $where .= " OR secondary_id LIKE '%$term%' ";
                            $where .= " OR id LIKE '%$term%' ";
                            $where .= " OR payrate LIKE '%$term%' ";
                            $where .= ")";
                            $select->where($where);
                        }
                        $select->order('lastname asc')
                        ->order('firstname asc');
        $result = $this->fetchAll($select);
        return $result;
    }
    
    
    public function getAllByLocation($locationid){
        $select = $this->select()
                        ->from($this->_name)
                        ->where('location = ?',$locationid)
                        ->order('location asc')
                        ->order('lastname asc');
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
