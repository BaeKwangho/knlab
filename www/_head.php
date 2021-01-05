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

<? 
if($Mem->class < 8 ){ //위쪽 레이어 작성.

if($_SESSION["AUTH"]["MID"]=="2410"){?>

<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:200px; font-size:20px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&search_reset=1')"><?if($_SESSION["AUTH"]["MID"]){$Q=$Mem->q("select CT_NM from nt_categorys where CODE like ?",$_SESSION["AUTH"]["MID"]);$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["reset_type"]=="1"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&reset_type=1')">최근자료</td>
	<td class="<?=$_GET["TYPE"]=="전략"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=전략')">전략</td>
    <td class="<?=$_GET["TYPE"]=="정책동향"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=정책동향')">정책동향</td>
    <td class="<?=$_GET["TYPE"]=="산업동향"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=산업동향')">산업동향</td>
    <td class="<?=$_GET["TYPE"]=="보고서"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=보고서')">보고서</td>
    <td class="<?=$_GET["TYPE"]=="통계백서"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=통계백서')">통계백서</td>
    <td class="<?=$_GET["TYPE"]=="규제지침"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=규제지침')">규제지침</td>
  </table>
</div>

<?}elseif($_SESSION["AUTH"]["MID"]=="2411"){?>

	<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:200px; background-color:green;font-size:20px;border-bottom:2px solid green" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&search_reset=1')"><?if($_SESSION["AUTH"]["MID"]){$Q=$Mem->q("select CT_NM from nt_categorys where CODE like ?",$_SESSION["AUTH"]["MID"]);$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["reset_type"]=="1"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&reset_type=1')">신규등록자료</td>
	<td class="<?=$_GET["TYPE"]=="글로벌동향"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=글로벌동향')">글로벌동향</td>
    <td class="<?=$_GET["TYPE"]=="발간물"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=발간물')">발간물</td>
    <td class="<?=$_GET["TYPE"]=="레퍼런스"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=레퍼런스')">레퍼런스</td>
    <td class="<?=$_GET["TYPE"]=="아카이브"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=아카이브')">아카이브</td>
  </table>
</div>

<?}elseif($_SESSION["AUTH"]["MID"]=="2413"){?>
	<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:200px; background-color:#00ccff;font-size:20px;border-bottom:2px solid #00ccff" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&search_reset=1')"><?if($_SESSION["AUTH"]["MID"]){$Q=$Mem->q("select CT_NM from nt_categorys where CODE like ?",$_SESSION["AUTH"]["MID"]);$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["TYPE"]=="동향자료"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=동향자료')">동향자료</td>
	<td class="<?=$_GET["TYPE"]=="정책자료"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=정책자료')">정책자료</td>
    <td class="<?=$_GET["TYPE"]=="발간물"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=발간물')">발간물</td>
    <td class="<?=$_GET["TYPE"]=="레퍼런스"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=레퍼런스')">레퍼런스</td>
    <td class="<?=$_GET["TYPE"]=="아카이브"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=아카이브')">아카이브</td>
  </table>
</div>
<?}

}else{ ?>

<div style="min-width:1200px;line-height:40px;background-color:#2f5597;text-align:center;padding-top:8px;">
</div>

<? } ?>





<? if($Mem->class < 8 ){ //유저 접근 권한 확인 후 왼쪽 레이어 작성?>

<div>
<table    cellpadding="0" cellspacing="0" border="0"  style="width:100%" >
	<tr>
		<td style="width:200px; min-width:200px;" valign="top" >
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<div class="main_side_menu1">
<ul class="menu_list">


<?
if(!$_SESSION["AUTH"]["MID"]){
		echo "지정된 영역이 없습니다.";
}elseif($_SESSION["AUTH"]["MID"]==="2410"){
		$Q=$Mem->q("select * from nt_categorys where CODE like ? and TYPE=1 and length(CODE)=6 and STAT<9",$_SESSION["AUTH"]["MID"]."%");
		while($r=$Q->fetch()){
			?><li onclick="go('Content_view.php?CID=<?=$r["CODE"]?>')" style="cursor:pointer;
			<?=$_GET["CID"]==$r["CODE"]?"background-color:white":""?>;"><?=$r["CT_NM"]?></li><?		
		}
}elseif($_SESSION["AUTH"]["MID"]==="2411"){
		$contid = array("아시아", "유럽/CIS", "중동/아프리카", "북미/중남미", "글로벌");
		$t = 0;
		while($t<count($contid)){
			?><li onclick="go('Content_view.php?CONTID=<?=$contid[$t]?>')" style="cursor:pointer;
			<?=($_GET["CONTID"]==$contid[$t])||(!$_GET["CONTID"]&&($_SESSION["SEARCH"]["CONTID"]==$contid[$t]))?"background-color:white":""?>;"><?=$contid[$t]?></li><?		
		//헤드의 배너를 누를 시 GET으로 전송하고, 그것을 session에 저장해둠.
		//누를 때 전송을 하므로 이전에 저장된 세션의 CONTID를 고려하여 처리, CONTID가 무시되는 경우 session으로 색인하는 것 활성화
		$t++;
	}

}elseif($_SESSION["AUTH"]["MID"]==="2413"){
	$contid = array("미국", "일본", "중국", "독일", "EU","글로벌");
	$t = 0;
	while($t<count($contid)){
		?><li onclick="go('Content_view.php?CONTID=<?=$contid[$t]?>')" style="cursor:pointer;
		<?=($_GET["CONTID"]==$contid[$t])||(!$_GET["CONTID"]&&($_SESSION["SEARCH"]["CONTID"]==$contid[$t]))?"background-color:white":""?>;"><?=$contid[$t]?></li><?		
	//헤드의 배너를 누를 시 GET으로 전송하고, 그것을 session에 저장해둠.
	//누를 때 전송을 하므로 이전에 저장된 세션의 CONTID를 고려하여 처리, CONTID가 무시되는 경우 session으로 색인하는 것 활성화
	$t++;
}
}
	?>
</ul></div></td>
<td style="vertical-align:top; padding-left:20px;">
<?






?>
<?}else{//유저 접근 권한이 높을 경우, 즉 관리자.?>

<div style="min-width:1200px; ">
<table    cellpadding="0" cellspacing="0" border="0"  style="width:100%;" >
	<tr>
		<td style="width:200px;" valign="top" >
	<div class="main_side_menu1" >
			<ul class="menu_list" >
				<?if($Mem->class===9){?>
					 <li onclick="go('/Content_User_List.php');"  style="cursor:pointer;" >+ 사용자관리</li>
				<?}?>
				<li onclick="go('/Content_Document_List.php?search_reset=1');"  style="cursor:pointer;" >+ 데이터조회</li>
				<li onclick="go('/Content_Data_Register.php');"  style="cursor:pointer;" >+ 단일데이터 등록</li>
				<li onclick="go('/Content_Data_Register_Excel.php');"  style="cursor:pointer;" >+ 대량데이터 등록</li>
				<?if($Mem->class===9){?>
					<li onclick="go('/Content_Config_Category.php?CTYPE=1');"  style="cursor:pointer;" >+ 주제분류설정</li>
				<?}?>
				<!--<li onclick="go('/Content_Config_Continent.php?CTYPE=2');"  style="cursor:pointer;" >+ 국가분류설정</li>-->
				<!--<li onclick="go('/Content_Config_Category.php?CTYPE=3');"  style="cursor:pointer;" >+ 국가분류설정</li>-->
				<li onclick="go('/Member_Logout.php');"  style="cursor:pointer;" >+ 로그아웃</li>
			</ul>
	</div>
</td>

		<td style=";">
<? } ?>
<? } ?>