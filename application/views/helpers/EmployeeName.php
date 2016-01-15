<?php

class Zend_View_Helper_employeeName {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function employeeName($employeeid) {
    	
    		$employeesModel = new Employees;
			$employee = $employeesModel->getById($employeeid);
				
				
			if(!$employee){
				$employeeName = "Not Set";
			} else {
				$employeeName = $employee->lastname.", ".$employee->firstname;
			}
    	
    	return $employeeName;
    }
}
?>
