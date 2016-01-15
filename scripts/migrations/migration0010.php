<?
class Migration0010 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE records ADD COLUMN `acct` varchar(255)";
		$this->query($sql);
		
	}
	
	
	public function down(){
		
	//	$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


