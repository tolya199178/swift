<?php

class Zend_View_Helper_substituteName {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function substituteName($substituteid) {        
        $substitutesModel = new Substitutes;
        $substitute = $substitutesModel->getById($substituteid);

        if(!$substitute){
                $substituteName = "Not Set";
        } else {
                $substituteName = $substitute->lastname.", ".$substitute->firstname;
        }
    	return $substituteName;
    }
}
?>
