<?

//error_reporting(E_ALL);ini_set("display_errors", 1);

include "/home/knlab/_Class/Member.php"; $Mem=new Member();

if(!$Mem->user["uid"]){			mvs("Member_Login.php");	exit;		}

if($_GET["WORD"]){ $_SESSION["SEARCH"]["WORD"]=$_GET["WORD"];}
if($_SESSION["SEARCH"]["WORD"]){$_GET["WORD"]=$_SESSION["SEARCH"]["WORD"];}

if($_GET["search_reset"]){ unset($_SESSION["SEARCH"]);unset($_GET);}
if($_GET["reset_type"]){ unset($_SESSION["SEARCH"]["TYPE"]);unset($_GET["TYPE"]);}

if($_GET["CID"])	$_SESSION["SEARCH"]["CID"]=$_GET["CID"];
if($_SESSION["SEARCH"]["CID"]){
	$_GET["CID"]=$_SESSION["SEARCH"]["CID"];
}
if($_GET["TYPE"])	$_SESSION["SEARCH"]["TYPE"]=$_GET["TYPE"];
if($_SESSION["SEARCH"]["TYPE"]){
	$_GET["TYPE"]=$_SESSION["SEARCH"]["TYPE"];
}

	if(!$_SESSION["AUTH"]){//auth_list에서 사용자에 부여된 권한 조회 및 사용
	  $uid=$Mem->q("select CID from nt_categorys_auth_list where UID=?",$Mem->uid);
		if($uid->rowCount()==0){
			$_SESSION["AUTH"]["CODE_QUERY"]="nt_document_list";
			$_SESSION["AUTH"]["CODE_ARRAY"]="nt_categorys";
		}else {
		  $code_query="(select a.* from nt_document_list a where";
			$code_array="(select a.* from nt_categorys a where";
		  for($i=0;$uid->rowCount()>$i;$i++){
			$r=$uid->fetch();
		    if($i==0){
			  $q=$Mem->qs("select CODE from nt_categorys where IDX=?",$r[0]);
			  if(!$q){continue;}
		      $code_query.=" a.DC_CODE like ";
		      $code_query.=$q;
					$code_array.=" a.CODE like ";
					$code_array.=$q;
		      continue;
			}
			$q=$Mem->qs("select CODE from nt_categorys where IDX=?",$r[0]);
			if(!$q){continue;}
		    $code_query.=" or a.DC_CODE like ";
		    $code_query.=$q;
				$code_array.=" or a.CODE like ";
				$code_array.=$q;
		  }
		  $_SESSION["AUTH"]["CODE_QUERY"]=$code_query.")";
			$_SESSION["AUTH"]["CODE_ARRAY"]=$code_array.")";
		}
	}
$Q=$Mem->q("select t.* from ".$_SESSION["AUTH"]["CODE_ARRAY"]." t where t.TYPE=1 and length(t.CODE)=4 and t.STAT<9");
$value=0;
	
if($Q->rowCount()){ //이쪽라인에서 고의적으로 중분류 코드를 분리
	while($r=$Q->fetch()){
		if(substr($r["CODE"],0,4)=="2410") {$value="2410";$_SESSION["AUTH"]["MID"]="2410";}
		if(substr($r["CODE"],0,4)=="2411") {$value="2411";$_SESSION["AUTH"]["MID"]="2411";}
		if(substr($r["CODE"],0,4)=="2413") {$value="2411";$_SESSION["AUTH"]["MID"]="2413";}
	}
}



?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <link rel="stylesheet" href="/_style.css">

	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="/_script.js" ></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script type="text/javascript" src="/js/treeview.min.js"></script>

  <title>Document</title>
 </head>
 <body class="gray">

 <div id="overlay" class="web_dialog_overlay"></div>
 <div id="overlay1" class="web_dialog_overlay1"></div>
<div id="web_dialog" class="web_dialog_content"  ></div>
<div id="web_dialog1" class="web_dialog_content"  ></div>

<? if(!$Close){ ?>

<div style="min-width:1200px;background-color:#FFF; height:auto; overflow:hidden;">
<div class="main_wrap"  >
<table cellpadding="0" cellspacing="0" border="0"  style="width:100%;">
	<tr>
		<td style="min-width:300px;"><img src="/images/logo3.jpg" alt="" style="height:70px;margin-top:10px;margin-bottom:10px;cursor:pointer;" onclick="" style=";">	 </td>
		<td style="text-align:center;" >
	<? if($Mem->class<8){?>
		<form action="<?=SELF?>" method="GET">
	<input type="text" name="WORD" value="<?=$_SESSION["SEARCH"]["WORD"]?>" required="required" style="border:solid 4px #2f5597; background-color:#FFF; font-size:16px;padding:5px;margin-right:6px;"><input type="submit"  class="button1" value="검색" style="padding-left: 10px;height:38px; width:60px;font-size:14px;"> <!--<input type="button"  class="button1" value="상세검색" style="height:38px; width:90px;font-size:14px;" onclick="Dialog('Content_Data_Search.php',900,800);">-->
		</form>
	<?}?>
		</td>
    <td style="font-size:25px; padding:10px;" >
      <? ?>
    </td>
		<td style="min-width:300px;text-align:right;">
			<b><?=$Mem->name?></b> 안녕하세요. <input type="button" class="button1" value="로그아웃" onclick="go('Member_Logout.php');">
		</td>
	</tr>
</table>
 </div>
</div>

<?}?>