<?php

class Zend_View_Helper_employeeAcct {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function employeeAcct($employeeid) {
    	
    		$employeesModel = new Employees;
			$employee = $employeesModel->getById($employeeid);
				
				
		
    	
    	return $employee->account_number;
    }
}
?>
