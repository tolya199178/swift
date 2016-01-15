<?php

class Zend_View_Helper_locationName {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function locationName($locationid) {
    	
    		$locationsModel = new Locations;
			$location = $locationsModel->getById($locationid);
				
				
			if(!$location){
				$locationName = "Not Set";
			} else {
				$locationName = $location->name;
			}
    	
    	return $locationName;
    }
}
?>
