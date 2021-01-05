<?

 ob_start();
include "/home/knlab/_Class/Database.php";
include "/home/knlab/_Class/Elastic.php";
include "/home/knlab/_Class/Solr.php";

Class Member extends Database {

	var $uid,$pid , $name,$position, $CODE,$DOMAIN;
	var $auth;
	var $data,$data_url;
	var $class;
	var $es;
	var $solr;

	function  __construct  ($default=false){
			@session_start();
			$this->Database($default);
			$this->es=new Elastic($default);
			$this->solr =new Solr($default);

 			$this->data=array();
			$this->data["temp"]="Data/temp/";
			$this->data["remove"]="Data/remove/";

			$this->data["cover"]="Data/Cover/";
			$this->data["document"]="Data/Document/";
			$this->data["attach"]="Data/Attach/";



			$this->data_url["temp"]="Data/temp/";
			$this->data_url["cover"]="Data/Cover/";
			$this->data_url["document"]="Data/Document/";
			$this->data_url["attach"]="Data/Attach/";


				if(strlen(trim($_POST["USER_ID"]))>0 &&strlen(trim($_POST["USER_PASSWD"]))>0){
					$_POST["USER_ID"]=trim($_POST["USER_ID"]);
					$_POST["USER_PASSWD"]=trim($_POST["USER_PASSWD"]);

					$this->Login($_POST["USER_ID"],$_POST["USER_PASSWD"]);
				}


				 if($_SESSION["user"]){

					$User=explode("%02D",$_SESSION["user"]);

					$Time=$this->qs("select DT_LG  from nt_user_list where idx=? and STAT < 9 ",array($User[0]));
 					$Str=$User[0]."%02D".md5($Time.$User[0]);

						if($Str==$_SESSION["user"]){

							$_User=$this->qr("select * from nt_user_list where idx=? and STAT < 9 ",array($User[0]));

							$this->user["uid"]=$_User["IDX"];
							$this->user["name"]=$_User["USER_NAME"];
							$this->user["name"]=$_User["USER_NAME"];

							$this->uid=$_User["IDX"];
							$this->class=$_User["USER_CLASS"];
							$this->name=$_User["USER_NAME"];


						}
				}







		}


	function Login($id,$pass,$Method=1){

			if($_SESSION["user"]||$_SESSION["member"]){
						@header("location:/index.php");
						exit;
			}else{

						$Q=$this->q("select * from nt_user_list where User_ID=? and User_Passwd=password(?) and STAT < 9 ",array($id,$pass));
						if($Q->rowCount()>0){
							$row=$Q->fetch();

							if($_POST["SET_ID"]){	setcookie("save_id", $id, time() + 86400 * 30); }

							$current_time=mktime();
								$_SESSION["user"]=$row["IDX"]."%02D".md5($current_time.$row["IDX"]);

							$this->q("update nt_user_list set DT_LG=? where IDX=? and STAT < 9 ",array($current_time,$row["IDX"]));


							if($_GET['url']){
								@header("location:".urldecode($_GET['url']));	exit;
							}else{

 								@header("location:/index.php");
                							exit;
							}
						}else{
							$this->msg("로그인실패","등록되지 않은 아이디 이거나 비밀번호가 일치하지 않습니다.",SELF);
							exit;
						}
			}


	}

}

?>
