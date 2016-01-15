<?php

class Records extends Zend_DB_Table
{
	protected $_name = "records";


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
    
    public function getValuesForColumns($column){    	
    	
    	$select = $this->select();
    	
    	if('employee' == $column)
    	{
    		$select->from(array('r' => $this->_name));
    		$select->joinInner(array('e' => 'employees'), "e.id = r.employee");
    		$select->setIntegrityCheck(false);
    		$select->order('lastname asc')->order('firstname asc');
    	} 
    	else if('substitute' == $column)
    	{
    		$select->from(array('r' => $this->_name));
    		$select->joinInner(array('s' => 'substitutes'), "s.id = r.substitute");
    		$select->setIntegrityCheck(false);
    		$select->order('lastname asc')->order('firstname asc');    		    		
    	}
    	else if('user' == $column)
    	{
    		$select->from(array('r' => $this->_name));
    		$select->joinInner(array('u' => 'users'), "u.id = r.user");
    		$select->setIntegrityCheck(false);
    		$select->order('name asc');
    	}
    	else if('location' == $column)
    	{
    		$select->from($this->_name);
    	}
    	else if('reason' == $column)
    	{
    		$select->from($this->_name);
    	}
    	else 
    	{
    		$select->from($this->_name);
    	}    	
    	
        $select->group($column);
    	
        $result = $this->fetchAll($select);
        return $result;
    }
    
    public function searchByArray($data){    	
    	
    	$select = $this->select();
    	
    	$cols = array(
    			'r.id'
    			,'r.date'
    			,'r.employee'
    			,'r.location'
    			,'r.substitute'
    			,'r.reason'
    			,'r.notes'
    			,'r.percent'
    			,'r.leave_form'
    			,'r.user'
    			,'r.date_created'
    			,'r.acct'
    			,'r.payrate'
    			,'r.acct_two'
    			,'r.percent_two'
    			,'efirstname' => 'e.firstname'
    			,'elastname' => 'e.lastname'
    			,'account_number' => 'e.account_number'
    			,'account_number_two' => 'e.account_number_two'
    			,'ecp_id' => 'e.cp_id'
    			,'epaid' => 'e.paid'
    			,'sfirstname' => 's.firstname'
    			,'slastname' => 's.lastname'
    			,'spayrate' => 's.payrate'
    			,'scertified' => 's.certified'
    			,'ssn' => 's.ssn'
    			,'ssecondary_id' => 's.secondary_id'
    			,'lname' => 'l.name'
    	);
    	
    	$select->from(array('r' => $this->_name), $cols);
    	$select->setIntegrityCheck(false);
    	$select->joinInner(array('e' => 'employees'), "r.employee = e.id", null);
    	$select->joinInner(array('l' => 'locations'), "r.location = l.id", null);
    	$select->joinInner(array('s' => 'substitutes'), "r.substitute = s.id", null);
                 
        foreach($data AS $key => $value){
        	if(trim($value) <> ''){
		    	if($key == 'start_date'){
		        	$select->where('r.date >= ?',$value);		                    	
		        } 
		        else if($key == 'end_date')
		        {		                    		
		        	$select->where('r.date <= ?',$value);		                    
                } 
                else if($key == 'leave_form')
                {
                	if($value == "0"){
                    	$select->where("r.leave_form = 0 OR leave_form = ''");
                    }else {
                    	$select->where("r.leave_form = 1");
                    }
                }
                else if($key == 'paid')
                {
                    $select->setIntegrityCheck(false);
                    $select->joinInner(array('e' => 'employees'), 'employee = e.id', null);
                	$select->where('e.paid =?',$value);
                } else {		                    	
		        	$select->where('r.'.$key.' =?',$value);
		        }
           }
        }
                        
        $result = $this->fetchAll($select);
        return $result;
    }
     /**
     * Get All Records
     * @return type object
     */
    public function getAll($sort=false,$sorttype='desc',$search=array()){
        $select = $this->select();
        
        $cols = array(
        	'r.id'
        	,'r.date'
        	,'r.employee'
        	,'r.location'
        	,'r.substitute'
        	,'r.reason'
        	,'r.notes'
        	,'r.percent'
        	,'r.leave_form'
        	,'r.user'
        	,'r.date_created'
        	,'r.acct'
        	,'r.payrate'
        	,'r.acct_two'
        	,'r.percent_two'
        	,'efirstname' => 'e.firstname'
        	,'elastname' => 'e.lastname'
        	,'account_number' => 'e.account_number'
        	,'account_number_two' => 'e.account_number_two'
        	,'sfirstname' => 's.firstname'
        	,'slastname' => 's.lastname'
        );
        
        $select->from(array('r' => $this->_name), $cols);
        $select->setIntegrityCheck(false);
        $select->joinInner(array('e' => 'employees'), "r.employee = e.id", null);
        $select->joinInner(array('l' => 'locations'), "r.location = l.id", null);
        $select->joinInner(array('s' => 'substitutes'), "r.substitute = s.id", null);
        
        if($sort){
        	if($sort == 'employee')
        	{
        		$select->order("e.lastname $sorttype");
        		$select->order("e.firstname $sorttype");
        	}
        	else if($sort == 'location')
        	{
        		$select->order("l.name $sorttype");
        	}
        	else if($sort == 'substitute')
        	{
        		$select->order("s.lastname $sorttype");
        		$select->order("s.firstname $sorttype");
        	}
        	else 
        	{
        		$select->order("$sort $sorttype");
        	}            
        } else {
            $select->order('r.date desc');
        }
                        
        if(isset($search['startdate'])){
            $select->where('r.date >= ?',$search['startdate']);
            unset($search['startdate']);
        }
                        
        if(isset($search['enddate'])){
            $select->where('r.date <= ?',$search['enddate']);
            unset($search['enddate']);
        }

        if($search)
        {
        	foreach($search AS $key=>$value){
        		$select->where("r.$key = '$value' ");
        	}	
        }
        
        return $select;        
    }
    
    public function search($search){
        $select = $this->select()
                        ->from($this->_name);
                        
                        if(isset($search['startdate'])){
                        	$select->where('date >= ?',$search['startdate']);
                        	unset($search['startdate']);
                        }
                        
                         if(isset($search['enddate'])){
                        	$select->where('date <= ?',$search['enddate']);
                        	unset($search['enddate']);
                        }
                        
                        foreach($search AS $key=>$value){
                        	$select->where("$key = '$value' ");
                        }
        $result = $this->fetchAll($select);
        return $result;
    }
    
     public function getAllByLocation($location,$currentOnly=false,$search=array()){
     	
        $start = date("Y-m-1",strtotime("aug 2013"));
   
        $select = $this->select();
        
        $cols = array(
        	'r.id'
        	,'r.date'
        	,'r.employee'
        	,'r.location'
        	,'r.substitute'
        	,'r.reason'
        	,'r.notes'
        	,'r.percent'
        	,'r.leave_form'
        	,'r.user'
        	,'r.date_created'
        	,'r.acct'
        	,'r.payrate'
        	,'r.acct_two'
        	,'r.percent_two'
        	,'efirstname' => 'e.firstname'
        	,'elastname' => 'e.lastname'
        	,'account_number' => 'e.account_number'
        	,'account_number_two' => 'e.account_number_two'
        	,'sfirstname' => 's.firstname'
        	,'slastname' => 's.lastname'
        );        
        
        $select->from(array('r' => $this->_name), $cols);
        $select->setIntegrityCheck(false);
        $select->joinInner(array('e' => 'employees'), "r.employee = e.id", null);
        $select->joinInner(array('s' => 'substitutes'), "r.substitute = s.id", null);
        
        $select->where('r.location=?',$location);
                      
        if($currentOnly){
        	$select->where("r.date >= '$start'");
        }
        
        if(isset($search['startdate'])){
        	$select->where('r.date >= ?',$search['startdate']);
            unset($search['startdate']);
        }
                        
        if(isset($search['enddate'])){
        	$select->where('r.date <= ?',$search['enddate']);
            unset($search['enddate']);
        }

        if($search)
        {
        	foreach($search AS $key=>$value){
        		$select->where("r.$key = '$value' ");
        	}
        }
       
        $select->order('r.date desc');                   
        
        return $select;
    }
    
    public function getAllByEmployee($employeeid){
        $select = $this->select()
                        ->from($this->_name)
                        ->where('employee = ?',$employeeid)
                        ->order('date desc');
        $result = $this->fetchAll($select);
        return $result;
    }
    
     public function getAllByEmployeeDate($employeeid,$start,$end=false){
     	$start = date('Y-m-d',strtotime($start));
        $days = date('t',strtotime($start)) - 1;
        
        if(!$end){
     	  $endDate = date('Y-m-d',strtotime($start." +$days days"));
        } else {
            $endDate = date('Y-m-d',strtotime($end));
        }

        $select = $this->select()
                        ->from($this->_name)
                        ->where('employee = ?',$employeeid)
                        ->where('date >= ?',$start)
                        ->where('date <= ?',$endDate)
                        ->order("STR_TO_DATE(date, '%Y-%m-%d %h:%i:%s') asc");
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
    
     function deleteMultiple($ids){
		$idArray = explode(",",$ids);
		foreach($idArray AS $id){
			if(trim($id) <> ''){
		 		$this->delete('id='.$id);
		 	}
		}
       
        return true;
    }
    
}
