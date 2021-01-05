<?php
include "/home/knlab/_Class/Database.php"; 


Class Member extends Database {

	var $uid,$pid , $name,$position, $CODE,$DOMAIN;


	function  __construct  ($default=false){  
			@session_start(); 
			$this->Database($default); 

 

				$this->path["photo"]="/home/efms/public_html/Data/Photo/";
				$this->url["photo"]="http://admin.mobischool.co.kr/Data/";
 
				$this->SysDir();


				if($_SESSION["user"]){
					$User=explode("%02D",$_SESSION["user"]);

					$Time=$this->qs("select DT_LG  from ibss_service_member_list where idx=? ",$User[0]);
					$Str=$User[0]."%02D".md5($Time.$User[0]);

						if($Str==$_SESSION["user"]){
							$_User=$this->qr("select * from ibss_service_member_list where idx=? ",$User[0]);
							$this->user["uid"]	=$_User["IDX"];
							$this->user["gid"]	=$_User["GID"];      

							$this->user["pid"]=$_User["IDX"];


							$this->uid=$_User["IDX"];
							$this->pid=$_User["PID"];

							$this->name=$_User["MB_NAME"];
							$this->position=$_User["MB_POST"];



							$DB_Row=$this->qr("select * from ibss_service_connect_list where IDX=?" ,$this->pid); 
					
							$this->DB=new PDO("mysql:host=".$DB_Row["DB_HOST"].";port=".$DB_Row["DB_PORT"].";dbname=".$DB_Row["DB_NAME"].";charset=utf8",$DB_Row["DB_USER"],$DB_Row["DB_PASS"]); 
							$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
							$this->CODE=$DB_Row["DB_NAME"];
							$this->DOMAIN=$DB_Row["DOMAIN"];

							$this->SysDir();


							

						}else{ 
							session_destroy();
							exit;
						} 
				} else if($_SESSION["member"]){


					$User=explode("%02D",$_SESSION["member"]);

					$Time=$this->qs("select DT_LG  from ibss_member_user_list where idx=? ",$User[0]);
					$Str=$User[0]."%02D".md5($Time.$User[0]);

						if($Str==$_SESSION["member"]){

							$_User=$this->qr("select * from ibss_member_user_list where idx=? ",$User[0]);

							$this->user["uid"]=$_User["IDX"];
							$this->user["name"]=$_User["User_Name"];
	
						}
				}





		}


	function SysDir(){ 

			if($this->CODE&&$this->DOMAIN){


				$this->url["goods"]="http://".$this->DOMAIN."/Goods/".$this->CODE."/";
				$this->url["goods_small"]="http://".$this->DOMAIN."/Goods/".$this->CODE."/small/";
				$this->url["goods_middle"]="http://".$this->DOMAIN."/Goods/".$this->CODE."/middle/";
				$this->path["goods"]="/home/ibss_shop/shop_pub/Goods/".$this->CODE."/"; 
				$this->path["goods_small"]="/home/ibss_shop/shop_pub/Goods/".$this->CODE."/small/"; 
				$this->path["goods_middle"]="/home/ibss_shop/shop_pub/Goods/".$this->CODE."/middle/"; 
				$this->path["goods_original"]="/home/ibss_shop/shop_pub/Goods/".$this->CODE."/small/"; 


				$this->url["data"]="http://".$this->DOMAIN."/Data/".$this->CODE."/";
				$this->path["data"]="/home/ibss_shop/shop_pub/Data/".$this->CODE."/"; 



				if(!file_exists($this->path["goods"])){	 
						 mkdir($this->path["goods"]);		
						chmod($this->path["goods"],0707); 
						mkdir($this->path["goods"]."small/");		
						chmod($this->path["goods"]."small/",0707); 
						
						mkdir($this->path["goods"]."middle/");		
						chmod($this->path["goods"]."middle/",0707); 

						mkdir($this->path["goods"]."app/");		
						chmod($this->path["goods"]."app/",0707); 			
						
						mkdir($this->path["goods"]."original/");		
						chmod($this->path["goods"]."original/",0707); 		
				} 

				

				if(!file_exists($this->path["data"])){	 
						 mkdir($this->path["data"]);		
						chmod($this->path["data"],0707); 
				} 


			}  

	}

	function Work($type,$msg,$UUID=0){
		$this->q("insert into ibss_member_work_history(UID,DT,MSG,TYPE,PID) values(?,unix_timestamp(now()),?,?,?) ",array($this->user["uid"],$msg,$type,$UUID));
	}

	function Works($type,$msg,$Reason,$UUID=0){
		$this->q("insert into ibss_member_work_history(UID,DT,msg,TYPE,PID,REASON) values(?,unix_timestamp(now()),?,?,?,?) ",array($this->user["uid"],$msg,$type,$UUID,$Reason));
	}


/*
	function AppAuth($code,$id,$pass,$fcm=""){


		$result=array();

		$Q=$this->q("select * from ibss_service_connection_list where CODE=? ",$code);

		if($Q->rowCount()>0){
			$row=$Q->fetch();
 			try{ 
				$this->DB=new PDO("mysql:host=".$row["DB_HOST"].";port=".$row["DB_PORT"].";dbname=".$row["DB_NAME"].";charset=utf8",$row["DB_USER"],$row["DB_PASS"]); 
				$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
										
				$Q=$this->q("select * from ibss_memeber_list where User_ID=? and User_Passwd=password(?) ",array($id,$pass));

				if($Q->rowCount()>0){
					$row=$Q->fetch();
					$current_time=mktime(); 
					$_SESSION["user"]=$row["IDX"]."%02D".md5($current_time.$row["IDX"]);
					$this->q("update ibss_memeber_list set DT_LG=? , SIDS=?,FCM=? where IDX=? ",array($current_time,session_id(),$fcm,$row["IDX"]));
					$this->q("insert into ibss_member_login_list(UID,DT,MTD,IP_ADDR) values(?,?,?,?) ", array($row["IDX"],time(),$Method,$_SERVER["REMOTE_ADDR"])); 
					
					$result=array("status"=>true,"msg"=>"","SIDS"=>session_id(),"UID"=>$row["IDX"],"User_Name"=>$row["User_Name"],"User_Position"=>$row["User_Position"],"User_Part"=>$row["User_Part"],"User_Permission"=>$row["User_Permission"]);
					 
				}else{
						$result=array("status"=>false,"msg"=>"사용자 정보가 유효하지 않습니다. 비밀번호 또는 아이디를 확인해주세요.");
 				}
			}catch(Exception $e){ 
 				$result=array("status"=>false,"msg"=>"서버 접속 에러가 발생했습니다.");
			}
 		}else{
			//$this->msg("접근에러","도메인이 유효하지 않습니다.");
				$result=array("status"=>false,"msg"=>"인증정보가 유효하지 않습니다.");
		}

	return $result; 
	}
*/

	function AppAuth($id,$pass,$uuid,$fcm=""){

 
 			try{  

				$Q=$this->q("select * from ibss_service_member_list where User_ID=? and User_Passwd=password(?) ",array($id,$pass));
				if($Q->rowCount()>0){

					$row=$Q->fetch(); 
					$current_time=mktime();   
					$SID=session_id();
					$this->q("update ibss_service_member_list set DT_LG=? , SID=? ,UUID=?, FCM=? where IDX=? ",array($current_time,$SID,$uuid,$fcm,$row["IDX"]));

					$result=array("PID"=>$row["PID"],"status"=>true,"msg"=>"","SID"=>$SID,"UID"=>$row["IDX"],"User_Name"=>$row["User_Name"],"User_Position"=>$row["User_Position"],"User_Part"=>$row["User_Part"],"User_Permission"=>$row["User_Permission"]);
				}else{
					$result=array("status"=>false,"msg"=>"사용자 정보가 유효하지 않습니다. 비밀번호 또는 아이디를 확인해주세요.");
				} 


			}catch(Exception $e){ 
 				$result=array("status"=>false,"msg"=>"서버 접속 에러가 발생했습니다.");
			}



	return $result; 
	}




	function AppUserAuth($id,$pass,$uuid,$fcm=""){

 
 			try{  

				$Q=$this->q("select * from ibss_member_user_list where User_ID=? and User_Passwd=password(?) ",array($id,$pass));
				if($Q->rowCount()>0){

					$row=$Q->fetch(); 
					$current_time=mktime();   
					$SID=session_id();
					$this->q("update ibss_member_user_list set DT_LG=? , SID=? ,UUID=?, FCM=? where IDX=? ",array($current_time,$SID,$uuid,$fcm,$row["IDX"]));

					$result=array("PID"=>$row["PID"],"status"=>true,"msg"=>"","SID"=>$SID,"UID"=>$row["IDX"],"User_Name"=>$row["User_Name"],"User_Position"=>$row["User_Position"],"User_Part"=>$row["User_Part"],"User_Permission"=>$row["User_Permission"]);
				}else{
					$result=array("status"=>false,"msg"=>"사용자 정보가 유효하지 않습니다. 비밀번호 또는 아이디를 확인해주세요.");
				} 


			}catch(Exception $e){ 
 				$result=array("status"=>false,"msg"=>"서버 접속 에러가 발생했습니다.");
			}



	return $result; 
	}






	function AppAdminCheck($uid,$pid,$sid,$uuid){
 

		if($_SESSION["ADMIN_AUTH_INFO"] && ( !$uid || !$pid || !$sid || !$uuid )){
			$data=explode("%02D",$_SESSION["ADMIN_AUTH_INFO"]);

			$uid=$data[0];
			$pid=$data[1];
			$sid=$data[2];
			$uuid=$data[3];
 
		}


				
		if($this->qs("select IDX from ibss_service_member_list where IDX=? and PID=? and SID=? and UUID=? ",array($uid,$pid,$sid,$uuid))){

				$_SESSION["ADMIN_AUTH_INFO"]=$uid."%02D".$pid."%02D".$sid."%02D".$uuid;


				$_User=$this->qr("select * from ibss_service_member_list where idx=? ",$uid);
				$this->user["uid"]	=$_User["IDX"];
				$this->user["gid"]	=$_User["GID"];      

				$this->user["pid"]=$_User["IDX"];


				$this->uid=$_User["IDX"];
				$this->pid=$_User["PID"];

				$this->name=$_User["MB_NAME"];
				$this->position=$_User["MB_POST"];

 

				$DB_Row=$this->qr("select * from ibss_service_connect_list where IDX=?" ,$this->pid); 
		
				$this->DB=new PDO("mysql:host=".$DB_Row["DB_HOST"].";port=".$DB_Row["DB_PORT"].";dbname=".$DB_Row["DB_NAME"].";charset=utf8",$DB_Row["DB_USER"],$DB_Row["DB_PASS"]); 
				$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$this->CODE=$DB_Row["DB_NAME"];
				$this->DOMAIN=$DB_Row["DOMAIN"];

				$this->SysDir(); 
		} 
	}




	function AppUser($code,$uid,$sid){


		$result=array();

		$Q=$this->q("select * from ibss_service_connection_list where CODE=? ",$code);

		if($Q->rowCount()>0){
			$row=$Q->fetch();
 			try{ 
				$this->DB=new PDO("mysql:host=".$row["DB_HOST"].";port=".$row["DB_PORT"].";dbname=".$row["DB_NAME"].";charset=utf8",$row["DB_USER"],$row["DB_PASS"]); 
				$this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
										
				$Q=$this->q("select * from ibss_memeber_list where IDX=? and SIDS=? ",array($uid,$sid));

				$this->CODE=$row["CODE"];

				$this->SysDir();

				if($Q->rowCount()==0){ 
						return  $result=array("status"=>false,"msg"=>"사용자 정보가 유효하지 않습니다. 비밀번호 또는 아이디를 확인해주세요.");
 				}
			}catch(Exception $e){ 
 				return $result=array("status"=>false,"msg"=>"서버 접속 에러가 발생했습니다.");
			}
 		}else{
			//$this->msg("접근에러","도메인이 유효하지 않습니다.");
				return $result=array("status"=>false,"msg"=>"인증정보가 유효하지 않습니다.");
		}
 
	}



		function loginAjax($id,$pass){

				$Q=$this->q("select * from ibss_member_user_list where user_id=? and user_passwd=password(?) ",array($id,$pass));
				if($Q->rowCount()>0){
					$row=$Q->fetch();

					$current_time=mktime(); 
					$_SESSION["member"]=$row["IDX"]."%02D".md5($current_time.$row["IDX"]);


					$this->q("update ibss_member_user_list set DT_LG=? where IDX=? ",array($current_time,$row["IDX"]));





	 			$CART_DATA=json_decode($this->qs("select `CART` from ibss_member_user_cart_list where UID=? ",array($row["IDX"])),true);

				//$_SESSION["CART"]=array_merge($_SESSION["CART"],$cart_data);

				foreach($CART_DATA as $key => $val ){
					$_SESSION["CART"][$key]=$val;
				}





				if($this->qs("select count(*) from ibss_member_user_cart_list where UID=? ",$row["IDX"])){
					$this->q("update ibss_member_user_cart_list set `CART`=? where `UID`=? ",array( json_encode($_SESSION["CART"],true),$row["IDX"]));
				}else{
					$this->q("insert into ibss_member_user_cart_list(`UID`,`CART`) values(?,?) ",array($row["IDX"], json_encode($_SESSION["CART"],true)));
				} 


			





						return  json_encode(array("status"=>true,"msg"=>"서버 접속 에러가 발생했습니다."),true);

				}else{
						return json_encode(array("status"=>false,"msg"=>"아이디 또는 비밀번호가 일치하지 않습니다."),true);
				}


		}


	function login($id,$pass,$Method=1){ 

			if($_SESSION["user"]||$_SESSION["member"]){
						@header("location:/index.php");
						exit;
			}else{

				if(DOMAIN !="admin.onmart.kr"){


						$Q=$this->q("select * from ibss_member_user_list where User_ID=? and User_Passwd=password(?) ",array($id,$pass));
						if($Q->rowCount()>0){
							$row=$Q->fetch();

							$current_time=mktime(); 
							$_SESSION["member"]=$row["IDX"]."%02D".md5($current_time.$row["IDX"]);

							$this->q("update ibss_member_user_list set DT_LG=? where IDX=? ",array($current_time,$row["IDX"]));



							
							if($_GET['url']){
								@header("location:".urldecode($_GET['url']));	exit;
							}else{
								@header("location:/");									exit; 
							}
						}else{
							$this->msg("로그인실패","등록되지 않은 아이디 이거나 비밀번호가 일치하지 않습니다.",SELF);
							exit;
						}



				}else{



						$Q=$this->q("select * from ibss_service_member_list where User_ID=? and User_Passwd=password(?) ",array($id,$pass));
						if($Q->rowCount()>0){
							$row=$Q->fetch();

							$current_time=mktime(); 
							$_SESSION["user"]=$row["IDX"]."%02D".md5($current_time.$row["IDX"]);

							$this->q("update ibss_service_member_list set DT_LG=? where IDX=? ",array($current_time,$row["IDX"]));


							
							if($_GET['url']){
								@header("location:".urldecode($_GET['url']));	exit;
							}else{
								@header("location:/");									exit; 
							}
						}else{
							$this->msg("로그인실패","등록되지 않은 아이디 이거나 비밀번호가 일치하지 않습니다.",SELF);
							exit;
						}

				}


			}


	}

}

?>


