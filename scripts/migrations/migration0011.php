<?
class Migration0011 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE substitutes ADD COLUMN `certified` varchar(255)";
		$this->query($sql);
		$sql = "ALTER TABLE substitutes ADD COLUMN `ssn` varchar(255)";
		$this->query($sql);
	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


