<?
class Migration0005 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE substitutes add column active int(11) default '0' ";
		$this->query($sql);
		
			
	}
	
	
	public function down(){
		
		//$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


