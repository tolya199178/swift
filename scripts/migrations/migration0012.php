<?
class Migration0012 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE employees ADD COLUMN `paid` varchar(255)";
		$this->query($sql);

	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


