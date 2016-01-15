<?php

class Zend_View_Helper_UserName {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function userName($id) {
    	
    		$usersModel = new Users;
			$user = $usersModel->getById($id);
				
				

    	
    	return $user->name;
    }
}
?>
