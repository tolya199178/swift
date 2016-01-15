<?php
class AdminController extends Swift_Controller_Action
{

    public function init(){
    	if($this->_me->admin <> 1){
      		$this->_redirect("/track");
      	}
    }

	/**
	* Page
	*/
    public function usersAction(){
   		$usersModel = new Users;
   		
   		if($this->getRequest()->isPost()){
   			$data = $this->getRequest()->getPost();
   			
   			if(@$data['method'] == 'create'){
   				//CREATE NEW USER
   				unset($data['method']);
   				
   				if($data['email'] == '' || $data['password'] == ''){
   					$this->view->error = "Please complete all fields.";
   					$this->view->data = $data;
   				} else {
   				
   					$data['password'] = sha1($data['password']);
   					$usersModel->insert($data);
   					$this->view->success = "New User Created.";
   				}
   			}
   			if(@$data['method'] == 'update'){
   				//UPDATE USER
   				unset($data['method']);
   				if($data['password'] == ''){
   					unset($data['password']);
   				} else {
   				$data['password'] = sha1($data['password']);
   				}
   				$usersModel->updateRecord($data['id'],$data);
   				$this->view->success = "User Record Updated.";
   			}
   			if(@$data['method'] == 'delete'){
   				//DELETE USER
   				$where = "id=".$data['id'];
   				$usersModel->delete($where);
   			}
   		}
   		
   		
   		$users = $usersModel->getAll();
   		
   		$page=$this->_getParam('page',1);
	    $paginator = Zend_Paginator::factory($users);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

   		
   		$this->view->users = $paginator;
   		
   		$locationsModel = new Locations;
   		$this->view->locations = $locationsModel->getAll();
        
    }
    
     public function locationsAction(){
   			$locationsModel = new Locations;
   		
   		if($this->getRequest()->isPost()){
   			$data = $this->getRequest()->getPost();
   			
   			if(@$data['method'] == 'create'){
   				//CREATE NEW Location
   				unset($data['method']);
   				
   				if($data['name'] == ''){
   					$this->view->error = "Please Provide a Name for the New Location.";
   					$this->view->data = $data;
   				} else {
   					$locationsModel->insert($data);
   					$this->view->success = "New Location Created.";

   				}
   			}
   			if(@$data['method'] == 'update'){
   				//UPDATE Location
   				unset($data['method']);
   				 $locationsModel->updateRecord($data['id'],$data);
   				$this->view->success = "User Record Updated.";
   			}
   			if(@$data['method'] == 'delete'){
   				//DELETE Location
   				$where = "id=".$data['id'];
   				$locationsModel->delete($where);
   			}
   		
   		}
   		
   		$locations = $locationsModel->getAll();

   		$page=$this->_getParam('page',1);
	    $paginator = Zend_Paginator::factory($locations);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

   		
   		$this->view->locations = $paginator;

        
    }
    
    public function employeesAction(){
    	$locationsModel = new Locations;
    	$this->view->locations = $locationsModel->getAll();
    	
    	$employeesModel = new Employees;
    	if($this->getRequest()->isPost()){
   			$data = $this->getRequest()->getPost();
   			
   			if(@$data['method'] == 'create'){
   				//CREATE NEW Location
   				unset($data['method']);
   				
   				if($data['firstname'] == ''){
   					$this->view->error = "Please Provide a Name for the New Employee.";
   					$this->view->data = $data;
   				}  else if($data['lastname'] == ''){
   					$this->view->error = "Please Provide a Name for the New Employee.";
   					$this->view->data = $data;
   				} else if($data['position'] == ''){
   					$this->view->error = "Please Provide a Position for the New Employee.";
   					$this->view->data = $data;
   				} else {
   					$employeesModel->insert($data);
   					$this->view->success = "New Employee Created.";

   				}
   			}
   			
   			if(@$data['method'] == 'update'){
   				//UPDATE Location
   				unset($data['method']);
   				 $employeesModel->updateRecord($data['id'],$data);
   				$this->view->success = "Employee Record Updated.";
   			}
   			if(@$data['method'] == 'delete'){
   				//DELETE Location
   				$where = "id=".$data['id'];
   				$employeesModel->delete($where);
   				$this->view->success = "Employee Removed.";
   			}
   			
   		}

      $term = $this->getRequest()->getParam('term',false);
   		
   		$employees = $employeesModel->getAll($term);
   		
   		$page=$this->_getParam('page',1);
	    $paginator = Zend_Paginator::factory($employees);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

   		
   		$this->view->employees = $paginator;

   		
    }
    
    public function substitutesAction(){
       	$locationsModel = new Locations;
    	$this->view->locations = $locationsModel->getAll();
    	
    	$substitutesModel = new Substitutes;
    	if($this->getRequest()->isPost()){
   			$data = $this->getRequest()->getPost();
   			
   			if(@$data['method'] == 'create'){
   				//CREATE NEW Location
   				unset($data['method']);
   				
   				if($data['firstname'] == ''){
   					$this->view->error = "Please Provide a Name for the New Substitute.";
   					$this->view->data = $data;
   				}  else if($data['lastname'] == ''){
   					$this->view->error = "Please Provide a Name for the New Substitute.";
   					$this->view->data = $data;
   				} else if($data['payrate'] == ''){
   					$this->view->error = "Please Provide a Payrate for the New Substitute.";
   					$this->view->data = $data;
   				} else {
   					$substitutesModel->insert($data);
   					$this->view->success = "New Substitute Created.";

   				}
   			}
   			
   			if(@$data['method'] == 'update'){
   				//UPDATE Location
   				unset($data['method']);
   				 $substitutesModel->updateRecord($data['id'],$data);
   				$this->view->success = "Substitute Record Updated.";
   			}
   			if(@$data['method'] == 'delete'){
   				//DELETE Location
   				$where = "id=".$data['id'];
   				$substitutesModel->delete($where);
   				$this->view->success = "Substitute Removed.";
   			}
   			
   		}
   		
      $term = $this->getRequest()->getParam('term',false);
   		$substitutes = $substitutesModel->getAllTotal($term);
   		
   		$page=$this->_getParam('page',1);
	    $paginator = Zend_Paginator::factory($substitutes);
	    $paginator->setItemCountPerPage(20);
	    $paginator->setCurrentPageNumber($page);

   		
   		$this->view->substitutes = $paginator;

   		
    }
    

	public function recordsAction(){
		$recordsModel =  new Records;

		
		$records = $recordsModel->getAll($sort,$sorttype);
		
		$page=$this->_getParam('page',1);
	    $paginator = Zend_Paginator::factory($records);
	    $paginator->setItemCountPerPage(100);
	    $paginator->setCurrentPageNumber($page);
	    
	    $this->view->records = $paginator;
	}
    
}
