<?

require_once "/home/knlab/_Class/Database.php";

Class Politica extends Database {
    
	public $DB,$sshuser,$sshpass,$host;
    
    function __construct(){
        $this->sshuser="knlab";
        $this->sshpass="Knlab1!8*3#";
        $this->host="1.214.203.131";
        //shell_exec("ssh -p 8322 -o StrictHostKeyChecking=no -fNg -L 3307:127.0.0.1:3306 ".$this->sshuser."@".$this->host." sleep 60 >> logfile; ".$this->sshpass);

        $this->Database($user="root",$pw="Company!23",$host="127.0.0.1",$port="3307",$dbname="politica");
    }
}


?>