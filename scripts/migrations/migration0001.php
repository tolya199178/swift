<?
class Migration0001 extends Settings{

	public function up(){
		
		$sql = "CREATE TABLE IF NOT EXISTS `users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `email` varchar(255) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `location` varchar(255),
		  `admin` int(11) default 0,
		  `password` text NOT NULL,
		  PRIMARY KEY (`id`)
		)";
		$this->query($sql);
		
	}
	
	
	public function down(){
		
		$sql = "DROP TABLE users";
		$this->query($sql);
		
	}

}


