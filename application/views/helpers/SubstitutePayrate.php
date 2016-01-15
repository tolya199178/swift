<?php

class Zend_View_Helper_SubstitutePayrate {

    public $view;

    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function substitutePayrate($substituteid) {
    		$payrate = '';
    		
    		$substitute = new Substitutes();    		
			$subs = $substitute->getById($substituteid);
			
			if($subs)
				$payrate = $subs->payrate;
			
    	return $payrate;
    }
}
?>
