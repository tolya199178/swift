<?


$commands[] = array('command'=>'backupDB','description'=>'Backup database');


function backupDB(){
	makeRed("Backup Database? This will overwrite any previous backups!");
	echo "(Y/N): ";
	$data = FOPEN("php://stdin", "rb");
	$input=''; 
	WHILE (1==1) {
	   $chunk = FREAD($data, 1);
	   IF ($chunk == "\n" || $chunk == "\r") BREAK;
	   $input .= $chunk;
	}
	FCLOSE($data);
 
	if(strtolower(@$input) == 'y'){
		echo "Getting Credentials from application.ini...\n";
		$application = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/configs/application.ini'
		);
		$bootstrap = $application->getBootstrap();
	
	    $options = $bootstrap->getOptions();
	    
	    $db = $options['resources']['db']['params'];
		echo "Database Backing Up. Please be patient, this could take a while...";
		sleep(1); echo "."; sleep(1); echo ".";sleep(1);echo ".";
		echo "\n";
		exec("mysqldump -u ".$db['username']." -p".$db['password']." ".$db['dbname']." > ".APPLICATION_PATH."/../data/dbbackup.sql",$output);
		
		makeGreen("DONE!");
		echo "\n\n";
	} else {
		echo "Operation Cancelled.\n";
	}

}