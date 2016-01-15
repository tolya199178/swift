<?
class Migration0009 extends Settings{

	public function up(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `site_settings` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `minDate` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		)";
		$this->query($sql);
		
	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


