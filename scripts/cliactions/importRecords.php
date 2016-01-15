<?

$commands[] = array('command'=>'importRecords','description'=>'Import Records from CSV');


function importRecords(){
	$csvPath = APPLICATION_PATH."/../data/imports.csv";
	
	$contents = file_get_contents($csvPath);
	$lines = explode("\n",$contents);
	$i = 0;
	
	$recordsModel = new Records;
	
	foreach($lines AS $line){
		if($i > 0){
		$data = explode(",",$line);
		
		
		$substitutesModel = new Substitutes;
		$substitute = $substitutesModel->getBySecondaryId($data[13]);
		
		
		
		$locationsModel = new Locations;
		$location = $locationsModel->getByName($data[4]);
		
		$nameSegments = explode("|",$data[3]);
		
		$lastName = $nameSegments[0];
		$firstName = $nameSegments[1];
		
		$employeesModel = new Employees;
		$employee = $employeesModel->getByName($firstName,$lastName);
		

		
		if($data[9] == 'FALSE' || $data[9] == FALSE){
			$leaveForm = 0;
		} else {
			$leaveForm = 1;
		}
		
		if(!$substitute){
			$subid = '129';
		} else {
			$subid = $substitute->id;
		}
		
		$recordData = array(
			'date' =>date('Y-m-d',strtotime($data[1])),
			'employee'=>@$employee->id,
			'location'=>@$location->id,
			'substitute'=>$subid,
			'reason'=>$data[6],
			'notes'=>$data[7],
			'percent'=>str_replace("%","",$data[8]),
			'leave_form'=>$leaveForm,
			'user'=>6,
			'acct'=>$data[14]
		);
		
		try{
		$recordsModel->insert($recordData);
		} catch (Exception $e){
			echo $e->getMessage();
		}
	
		
		print_r($recordData);
		}
		$i++;
		
	}
	

}