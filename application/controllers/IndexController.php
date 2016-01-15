<?php

class IndexController extends Swift_Controller_Action
{

    public function init(){
      
    }

	/**
	* Homepage
	*/
    public function indexAction(){
   		
        
    }
    
    
    public function loginAction(){
		    $goto = $this->getRequest()->getParam('goto');
			    if(@$this->_me){
			    	if($goto){
			    		$this->_redirect("/$goto");
			    	} else {
			    		$this->redirect("/track");
			    	}
			    }
			    $this->view->goto = $goto;
		    	if($this->getRequest()->isPost()){
			    	//Get form data from post array
			        $data = $this->_request->getPost();
			        if($data['email'] == '' || $data['password'] == ''){
			        	 $this->view->error = "Please provide your email address and password.";
			        	 return false;
			        }
			    	//Log user in to session 
			        $users = new Users;
			        $auth = Zend_Auth::getInstance();
			        $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'users');
			        $authAdapter->setIdentityColumn('email')
			                    ->setCredentialColumn('password');
			        $authAdapter->setIdentity($data['email'])
			                    ->setCredential(sha1($data['password']));
			        $result = $auth->authenticate($authAdapter);
			        if($result->isValid()){
			        	Zend_Session::rememberMe(31536000);
			        	
			        	$credentials = base64_encode(serialize(array('email'=>$data['email'],'password'=>sha1($data['password'])))); 
			        	         
		            	//Set login cookie
		            	setcookie('autl',$credentials,time()+31536000,'','.'.$_SERVER['HTTP_HOST']);
		            	
		            	
		            
			            $storage = new Zend_Auth_Storage_Session();
			            $storage->write($authAdapter->getResultRowObject());
			            $this->_redirect($data['goto']);
			        } else {
			            $this->view->error = "Invalid email or password. Please try again.";
			        } 
		        
		        }

    }
    
    public function logoutAction(){
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
		$this->_redirect('/');

    }


	
    
}

