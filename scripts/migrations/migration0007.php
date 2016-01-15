<?
class Migration0007 extends Settings{

	public function up(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `records` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `date` varchar(255) NOT NULL,
		  `employee` varchar(255) NOT NULL,
			`location` varchar(255) NOT NULL,
			`substitute` varchar(255) NOT NULL,
			`reason` varchar(255) NOT NULL,
			`notes` text NOT NULL,
			`percent` varchar(255) NOT NULL,
			`leave_form` varchar(255) NOT NULL,
			`user` varchar(255) NOT NULL,
			`date_created` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		)";
		$this->query($sql);
		
	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


