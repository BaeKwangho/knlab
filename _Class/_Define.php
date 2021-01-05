<?php
$TABLE_WEEKS=array("일","월","화","수","목","금","토");

$TABLE_REPEAT=array("","매일","매주","메월","분기","반기","년간");


//상수 설정을 위한 함수
function domain() {
		$domain	="";
		$r				=explode(".",$_SERVER["SERVER_NAME"]);
		$no_list		=array('new','www');				//서브도메인 필터링

		for($i=0; $i < sizeof($r); $i++){
			if(!in_array($r[$i],$no_list)){
			$domain.=$r[$i].".";
			}
		}
	return substr($domain,0,-1);
}



function getURL($ar=array()){
	$url="";
	while($r=each($_GET)){

	$_GET[$r[0]]=$r[1];

	if(!in_array($r[0],$ar)){
	$url.=$r[0]."=".$r[1]."&";
	}
	}

	if($url){
	$url="?".$url;
	}
return $url;
}



$_PAGE		=explode("/",$_SERVER["PHP_SELF"]);
$_PAGE		=$_PAGE[sizeof($_PAGE)-1];

//$_SERVER[DOCUMENT_ROOT]=str_replace($_SERVER["PHP_SELF"],"",$_SERVER["SCRIPT_FILENAME"]);	//2013-12-16

define("DOMAIN",domain());												//도메인 상수
define("DIR",$_SERVER["DOCUMENT_ROOT"]."/");				//절대위치 설정
define("URL","http://".$_SERVER["SERVER_NAME"]."/");			//홈페이지 주소
define("SELF",$_SERVER["PHP_SELF"]);								//자신파일
define("_GET",getURL());												// GET 파라미터를 포함한 전체 url주소
define("URLS",SELF._GET);
define("SELFS",$_PAGE);
define("PATH", "home/ibss/");
define("UTF8","<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
define("OnlyNum","onkeyup=\"if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;\" style=\"IME-MODE:disabled;\" ");


 $TABLE_WEEK=array('일','월','화','수','목','금','토');

?>
