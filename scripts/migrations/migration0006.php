<?
class Migration0006 extends Settings{

	public function up(){
		
		$sql = "ALTER TABLE substitutes change column active active int(11) default '1'  ";
		$this->query($sql);
		
			
	}
	
	
	public function down(){
		
		//$sql = "DROP TABLE users";
	//	$this->query($sql);
		
	}

}


