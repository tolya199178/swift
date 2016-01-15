<?
class Migration0004 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE substitutes add column location varchar(255)";
		$this->query($sql);
		
			
	}
	
	
	public function down(){
		
		$sql = "DROP TABLE users";
		$this->query($sql);
		
	}

}


