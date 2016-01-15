<?php
/**
* Base controller from which all other controllers extend
*/
class Swift_Controller_Action extends Zend_Controller_Action
{


	public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setRequest($request)
             ->setResponse($response)
             ->_setInvokeArgs($invokeArgs);
        $this->_helper = new Zend_Controller_Action_HelperBroker($this);
        $this->initView();
       
    }
    
   

	 public function initView()
    {
    	
    	//init session
    	$storage = new Zend_Auth_Storage_Session();
        $this->session = $storage->read();
        if($this->session){
        	$this->_me = $this->session;
        	$this->view->user = $this->_me;
        }
        $settingsModel = new Settings;
        $offset =  date('Z')/60/60;
        $settingsModel->query("SET time_zone = '$offset:00'");
        
        $this->view->currentRoute =Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        
        $siteSettingsModel = new SiteSettings;
        $this->view->siteSettings = $siteSettingsModel->getById(1);
        
        $this->init();
   }
    
    
   

}

