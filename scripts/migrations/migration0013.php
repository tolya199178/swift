<?
class Migration0013 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE site_settings ADD COLUMN `maxDate` varchar(255)";
		$this->query($sql);

	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


