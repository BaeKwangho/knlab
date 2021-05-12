<?
include "/home/knlab/_Class/_Define.php";
include "/home/knlab/_Class/_Lib.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);

Class Database {

	public $DB,$path,$url,$CODE,$user,$shop;
	function Database($default=false){
		$this->DB=new PDO("mysql:host=127.0.0.1;port=3306;dbname=nexteli;charset=utf8","root","tony267");
		$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 	$this->DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);//추가
		$this->path=array();
		$this->url=array();
		$this->user=array();
	}

	/********* Database Function ************/

	function qa($query,$array=null){
		try {
			$stmt=$this->DB->prepare($query);
			if($array != null){
				if(is_array($array)){
					$stmt->execute($array);
				}else{
					$stmt->execute(array($array));
				}
			}else{
				$stmt->execute($array);
			}
			$rowCnt = $stmt->columnCount();
			$res_array = array_fill(0,$rowCnt,array());
			while($row = $stmt->fetch()){
				for($i=0;$i<$rowCnt;$i++){
					array_push($res_array[$i],$row[$i]);
				}
			}
		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $res_array;
	}

	function q($query,$array=null){
		try {
			$stmt=$this->DB->prepare($query);
			if($array != null){
				if(is_array($array)){
					$stmt->execute($array);
				}else{
					$stmt->execute(array($array));
				}
			}else{
				$stmt->execute($array);
			}

		}catch(Exception $e){
			echo $e->getMessage();
			return null;
		}
		return $stmt;
	}

	# 다음과 같은 방법으로 개선 요망
	/*
	    // 쿼리를 담은 PDOStatement 객체 생성
	    $stmt = $pdo -> prepare("SELECT * FROM girl_group WHERE name = :name");

	    // PDOStatement 객체가 가진 쿼리의 파라메터에 변수 값을 바인드
	    $stmt -> bindValue(":name", "나연");

	    // PDOStatement 객체가 가진 쿼리를 실행
	    $stmt -> execute();
	*/


	function qs($query,$array=null){
		$stmt=$this->DB->prepare($query);
		if($array != null){
			if(is_array($array)){
				$stmt->execute($array);
			}else{
				$stmt->execute(array($array));
			}
		}else{
			$stmt->execute($array);
		}
		return $stmt->fetchColumn();
	}




	function qr($query,$array=null){
		$stmt=$this->DB->prepare($query);
			if($array != null){
				if(is_array($array)){
					$stmt->execute($array);
				}else{
					$stmt->execute(array($array));
				}
			}else{
				$stmt->execute($array);
			}
		return $stmt->fetch();
	}




	function qe($query=null){
		$this->DB->beginTransaction();
		$Query=$this->DB->exec("insert into IBSS_Member_List(UserID,UserPasswd) values('TEST','1234')");
		$this->DB->commit();
	}




	function insertId(){
		return $this->DB->lastInsertId();
	}




	function insert($query,$list=array()){
		try{
			$this->DB->beginTransaction();
			$stmt=$this->DB->prepare($query);
			$stmt->execute($list);
			$this->DB->commit();
		}catch(PDOException $ex){
			$this->DB->rollBack();
			echo $ex->getMessage();
		}
	}





	function updatePost($query,$array=null){
		if($array==null){ $array=$_POST;	}
		$list=$this->field($table);
		$update[0]="insert into $table (";
		$update[1]=" values(";
		$n=0;
		$field_name="";
		$data=array();
		for($i=0; $i < sizeof($list); $i++){
			$field_name=$list[$i][0];
				if(@strlen($array[$field_name])){
					if($list[$i][0]!="idx"&&$list[$i][0]!="uid"){
					$update[0]	 .="`".$list[$i][0]."`,";
					$update[1]	 .=":".$list[$i][0].",";
					$data[":".$list[$i][0].""]="".$_POST["".$list[$i][0].""]."";
					$n++;
 					}
				}
		}
	 	$Query=substr($update[0],0,-1).") ".substr($update[1],0,-1).")";
		$stmt=$this->DB->prepare($Query);
		$stmt->execute($data);
	}





	function updateStr($table,$where=""){
			$list=array();
			$update="";
 			$value="array(";
			$list=$this->field($table);
			$update="update $table set ";
			$n=0;
			for($i=0; $i < sizeof($list); $i++){
 					$update	 .=$list[$i][0]."=:".$list[$i][0].", ";
					$value .="\":".$list[$i][0]."\"=>\$_POST[\"".$list[$i][0]."\"],";
			}
			echo  substr($update,0,-1)." ".substr($where,0,-1)."\", ". substr($value,0,-1)."));";
	}






	function field($table){
			$stmt=$this->DB->prepare("SHOW FULL FIELDS FROM $table ");
			$stmt->execute();
			$field_list=array();
			$field_comment=array();
			while($r=$stmt->fetch()){		array_push($field_list,array($r["Field"],$r["Comment"],$r["Type"]));		}
		return $field_list;
	}





	function createTable($table,$array=null){
		if($array==null){ $array=$_POST;	}else{			}


		@$this->q("drop table ".$table);
		$sql="create table ".$table."( `IDX` INT UNSIGNED NOT NULL AUTO_INCREMENT, `DT_RG` INT UNSIGNED, `DT_UP` INT UNSIGNED, `Status` TINYINT UNSIGNED, ";
		foreach($_POST as $key=>$value){
			if($key !="IDX" && $key !="DT_RG" && $key !="DT_UP" && $key != "Status"){
				$sql.="`".$key."` VARCHAR(100),";
			}
		}
		$sql=substr($sql,0,-1).",  PRIMARY KEY (`IDX`) )";

		$this->q($sql);

	}



	function InsertPost($table,$array=null){
		global $_RESET;

		if($array==null){ $array=$_POST;	}else{			}

		if($_RESET){
				$this->createTable($table,$array);
		}


		$list=$this->field($table);
		$update[0]="insert into $table (";
		$update[1]=" values(";
		$n=0;
		$field_name="";
		$data=array();
		for($i=0; $i < sizeof($list); $i++){
			$field_name=$list[$i][0];
				if(@strlen($array[$field_name])){
					if($list[$i][0]!="idx"&&$list[$i][0]!="uid"){
					$update[0]	 .="`".$list[$i][0]."`,";
					$update[1]	 .=":".$list[$i][0].",";

						if( preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $array["".$list[$i][0].""]) ){
							$data[":".$list[$i][0].""]="".datec($array["".$list[$i][0].""])."";
						}else{
							$data[":".$list[$i][0].""]="".$array["".$list[$i][0].""]."";
						}

					$n++;
 					}
				}
		}
		$Query=substr($update[0],0,-1).") ".substr($update[1],0,-1).")";
		$stmt=$this->DB->prepare($Query);
		print_r($stmt);  
		$stmt->execute($data);
	}







	function InsertStr($table,$array=null){
		$list=$this->field($table);
		$update[0]="insert into $table (";
		$update[1]=" values(";
		$update[2]="\",array(";
		$n=0;
		$field_name="";
		$data=array();
		for($i=0; $i < sizeof($list); $i++){
				$field_name=$list[$i][0];
				$update[0]	 .="`".$list[$i][0]."`,";
				$update[1]	 .=":".$list[$i][0].",";
				$update[2] .="\":".$list[$i][0]."\"=>\$_POST[\"".$list[$i][0]."\"],";
		}
	 	$Query=substr($update[0],0,-1).") ".substr($update[1],0,-1)."),".substr($update[2],0,-1).");";
		 return $Query;
	}















	/******** Common Function ********************/



		function msg($title,$content,$type=""){



	echo "<!DOCTYPE html>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
  <meta name='viewport' content='user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height'>
<style>
body,div,td,tr{ font-size:12px; }
.box_msg{
background: rgba(254,254,254,1);
background: -moz-linear-gradient(top, rgba(254,254,254,1) 0%, rgba(250,250,250,1) 30%, rgba(235,235,235,1) 32%, rgba(250,250,250,1) 100%);
background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(254,254,254,1)), color-stop(30%, rgba(250,250,250,1)), color-stop(32%, rgba(235,235,235,1)), color-stop(100%, rgba(250,250,250,1)));
background: -webkit-linear-gradient(top, rgba(254,254,254,1) 0%, rgba(250,250,250,1) 30%, rgba(235,235,235,1) 32%, rgba(250,250,250,1) 100%);
background: -o-linear-gradient(top, rgba(254,254,254,1) 0%, rgba(250,250,250,1) 30%, rgba(235,235,235,1) 32%, rgba(250,250,250,1) 100%);
background: -ms-linear-gradient(top, rgba(254,254,254,1) 0%, rgba(250,250,250,1) 30%, rgba(235,235,235,1) 32%, rgba(250,250,250,1) 100%);
background: linear-gradient(to bottom, rgba(254,254,254,1) 0%, rgba(250,250,250,1) 30%, rgba(235,235,235,1) 32%, rgba(250,250,250,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fefefe', endColorstr='#fafafa', GradientType=0 );

-webkit-box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.42);
-moz-box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.42);
box-shadow: 2px 2px 5px 0px rgba(0,0,0,0.42);

border-radius: 8px 8px 8px 8px;
-moz-border-radius: 8px 8px 8px 8px;
-webkit-border-radius: 8px 8px 8px 8px;
border: 2px solid #EEE;
padding: 2px 10px 10px 10px;
margin: 0 auto;
width: 400px;
margin-top:10%;
		}
.button1 {
	font-size:12px;
	font-family:Arial;
	font-weight:bold;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
	border-radius:8px;
	border:1px solid #dcdcdc;
	padding:7px 20px;
	text-decoration:none;
	background:-moz-linear-gradient( center top, #ffffff 5%, #f6f6f6 100% );
	background:-ms-linear-gradient( top, #ffffff 5%, #f6f6f6 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f6f6f6');
	background:-webkit-gradient( linear, left top, left bottom, color-stop(5%, #ffffff), color-stop(100%, #f6f6f6) );
	background-color:#ffffff;
	color:#666666;
	display:inline-block;
	text-shadow:1px 1px 0px #ffffff;
 	-webkit-box-shadow:inset 1px 1px 0px 0px #ffffff;
 	-moz-box-shadow:inset 1px 1px 0px 0px #ffffff;
 	box-shadow:inset 1px 1px 0px 0px #ffffff;

	cursor:pointer;
}.button1:hover {
	background:-moz-linear-gradient( center top, #f6f6f6 5%, #ffffff 100% );
	background:-ms-linear-gradient( top, #f6f6f6 5%, #ffffff 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f6f6f6', endColorstr='#ffffff');
	background:-webkit-gradient( linear, left top, left bottom, color-stop(5%, #f6f6f6), color-stop(100%, #ffffff) );
	background-color:#f6f6f6;
}.button1:active {
	position:relative;
	top:1px;
}
</style>
</head>
<body>";


$button="<input type='button' class='button1' value='확인' onclick=\"location.href='".$type."';\" >";



switch($type){

	case 1:
		$button="<input type='button' class='button1' value='확인' onclick=\"history.go(-1)';\" >";
		break;
	case 2:
		$button="<input type='button' class='button1' value='확인' onclick=\"window.close();\" >";
		break;
	case 3:
		$button="<input type='button' class='button1' value='확인' onclick=\"opener.document.location.reload(); window.close();\" >";
		break;
 }




echo "
<div class=box_msg >
<div><span style='font-size:14px;font-weight:bold;line-height:30px;'>$title</span>  <span style='color:#AAA;' >".DOMAIN."</span></div>
<div style='text-align:center;padding-top:5px;' >$content</div>
<div style='text-align:center;'>".$button."</div>
</div>";



echo "</body></html>";

		}





	function log($data){

		if(!$this->q("insert into `_log`(DT,data,page) values(unix_timestamp(now()),?,?) ",array(print_R($data,true),$_SERVER["SCRIPT_NAME"]))){
			$this->q("CREATE TABLE `_log` (  `IDX` int(10) unsigned NOT NULL AUTO_INCREMENT,  `DT` int(10) unsigned DEFAULT NULL,  `data` text,  `page` varchar(50) DEFAULT '',  PRIMARY KEY (`IDX`)) ENGINE=InnoDB AUTO_INCREMENT=44699 DEFAULT CHARSET=utf8;");
		}


	}





}

?>
