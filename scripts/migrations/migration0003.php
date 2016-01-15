<?
class Migration0003 extends Settings{

	public function up(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `employees` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `firstname` varchar(255) NOT NULL,
		  `lastname` varchar(255) NOT NULL,
		  `location` varchar(255) NOT NULL,
		  `position` varchar(255) NOT NULL,
		  `account_number` varchar(255) NOT NULL,
		  `date_created` TIMESTAMP DEFAULT NOW(),
		  PRIMARY KEY (`id`)
		)";
		$this->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS `substitutes` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `secondary_id` varchar(255) NOT NULL,
		  `firstname` varchar(255) NOT NULL,
		  `lastname` varchar(255) NOT NULL,
		  `payrate` varchar(255) NOT NULL,
		  `date_created` TIMESTAMP DEFAULT NOW(),
		  PRIMARY KEY (`id`)
		)";
		$this->query($sql);
		
	}
	
	
	public function down(){
		
		$sql = "DROP TABLE users";
		$this->query($sql);
		
	}

}


