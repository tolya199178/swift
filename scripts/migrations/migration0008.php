<?
class Migration0008 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE employees ADD COLUMN cp_id varchar(255)";
		$this->query($sql);
		
	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


