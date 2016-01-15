<?
class Migration0002 extends Settings{

	public function up(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `locations` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
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


