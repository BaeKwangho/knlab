<?

//error_reporting(E_ALL);ini_set("display_errors", 1);
//error_reporting(E_ALL);ini_set("display_errors", 1);

include "../_Class/Member.php"; $Mem=new Member();

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
		      $code_query.=" a.DC_CODE like ";
		      $code_query.=$q;
					$code_array.=" a.CODE like ";
					$code_array.=$q;
		      continue;
		    }
		    $q=$Mem->qs("select CODE from nt_categorys where IDX=?",$r[0]);
		    $code_query.=" or a.DC_CODE like ";
		    $code_query.=$q;
				$code_array.=" or a.CODE like ";
				$code_array.=$q;
		  }
		  $_SESSION["AUTH"]["CODE_QUERY"]=$code_query.")";
			$_SESSION["AUTH"]["CODE_ARRAY"]=$code_array.")";
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
 <body>

 <div id="overlay" class="web_dialog_overlay"></div>
 <div id="overlay1" class="web_dialog_overlay1"></div>
<div id="web_dialog" class="web_dialog_content"  ></div>
<div id="web_dialog1" class="web_dialog_content"  ></div>

<? if(!$Close){ ?>

<div style="background-color:#FFF; height:auto; overflow:hidden;">
<div class="main_wrap"  >
<table cellpadding="0" cellspacing="0" border="0"  style="width:100%;"  >
	<tr>
		<td style="min-width:300px;"><img src="/images/logo3.jpg" alt="" style="height:70px;margin-top:10px;margin-bottom:10px;cursor:pointer;" onclick="" style=";">	 </td>
		<td style="text-align:center;" >
	<? if($Mem->class<8){?>
		<form action="<?=SELF?>" method="GET">
	<input type="text" name="WORD" value="<?=$_SESSION["SEARCH"]["WORD"]?>" required="required" style="border:solid 4px #2f5597; background-color:#FFF; font-size:16px;padding:5px;margin-right:6px;"><input type="submit"  class="button1" value="검색" style="padding-left: 10px;height:38px; width:60px;font-size:14px;"> <input type="button"  class="button1" value="상세검색" style="height:38px; width:90px;font-size:14px;" onclick="Dialog('Content_Data_Search.php',900,800);">
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

<? if($Mem->class < 8 ){ //위쪽 레이어 작성.?>

<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:199px; font-size:20px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&reset_type=1')"><?if($_GET["CID"]){$Q=$Mem->q("select t.CT_NM from ".$_SESSION["AUTH"]["CODE_ARRAY"]." t where t.CODE like ?",$_GET["CID"]."%");$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["reset_type"]=="1"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&reset_type=1')">최근자료</td>
	<td class="<?=$_GET["TYPE"]=="전략"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=전략')">전략</td>
    <td class="<?=$_GET["TYPE"]=="정책동향"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=정책동향')">정책동향</td>
    <td class="<?=$_GET["TYPE"]=="산업동향"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=산업동향')">산업동향</td>
    <td class="<?=$_GET["TYPE"]=="보고서"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=보고서')">보고서</td>
    <td class="<?=$_GET["TYPE"]=="통계백서"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=통계백서')">통계백서</td>
    <td class="<?=$_GET["TYPE"]=="규제지침"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('Content_view.php?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=규제지침')">규제지침</td>
  </table>
</div>

<? }else{ ?>

<div style="line-height:40px;background-color:#2f5597;text-align:center;padding-top:8px;">
</div>

<? } ?>





<? if($Mem->class < 8 ){ //유저 접근 권한 확인 후 왼쪽 레이어 작성?>

<div>
<table    cellpadding="0" cellspacing="0" border="0"  style="" >
	<tr>
		<td style="width:200px; min-width:200px;" valign="top" >
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>


<? if(!$_GET["CID"]&&!$_SESSION["SEARCH"]["CID"]){ //만약 CID가 없으면
  $Q=$Mem->q("select t.* from ".$_SESSION["AUTH"]["CODE_ARRAY"]." t where length(t.CODE)=2 and t.TYPE=1 and t.STAT<9");

?>
<div class="main_side_menu1" >
	<ul class="menu_list">
<?
  while($r=$Q->fetch()){
?>
  <li onclick="go('Content_view.php?CID=<?=$r["CODE"]?>')" style="cursor:pointer;"><?=$r["CT_NM"]?></li>
<?}?>
</label>
</div>
<td>
<? }else{ //CID가 있으면 ?>


<div class="main_side_menu1" >
<?
  if(strlen($_GET["CID"])==2){
    $Q=$Mem->q("select t.* from ".$_SESSION["AUTH"]["CODE_ARRAY"]." t where t.CODE like ? and t.TYPE=1 and length(t.CODE)=4 and t.STAT<9",$_GET["CID"]."%");
		?><div class="left_single" onclick="go('Content_view.php?search_reset=1')">이전 분류 선택</div><?
  }elseif(strlen($_GET["CID"])==4){
    $Q=$Mem->q("select t.* from ".$_SESSION["AUTH"]["CODE_ARRAY"]." t where t.CODE like ? and t.TYPE=1 and length(t.CODE)=6 and t.STAT<9",$_GET["CID"]."%");
		?><div onclick="go('Content_view.php?CID=<?=!substr($_GET["CID"],-0,-2)?0:substr($_GET["CID"],-0,-2)?>')" class="left_single">이전 분류 선택</div><?
  }elseif(strlen($_GET["CID"])==6){
		?><div onclick="go('Content_view.php?CID=<?=!substr($_GET["CID"],-0,-2)?0:substr($_GET["CID"],-0,-2)?>')" class="left_single">이전 분류 선택</div><?
    ?><div style="text-align:center;">세부 분류가 더 없습니다.</div><?
  }?>


    <ul class="menu_list">
<?
  while($r=$Q->fetch()){
?>
<li onclick="go('Content_view.php?CID=<?=$r["CODE"]?>')" style="cursor:pointer;"><?=$r["CT_NM"]?></li>

<? }//이후부터 자료 출력 가능. CID 있을 경우 여기서 카테고리 td종료?>
</ul></div></td>
<td style="vertical-align:top;">
<?
} //CID 있을때 끝.?>
	
<?}else{//유저 접근 권한이 높을 경우, 즉 관리자.?>

	<div style="min-width:1200px; ">
	<table    cellpadding="0" cellspacing="0" border="0"  style="width:100%;" >
		<tr>
			<td style="width:200px;" valign="top" >
	<? if($Mem->class < 8 ){ ?>
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

		<div class="main_side_menu1" >


	<div id="layerTree" style="background-color:#d6d6d6;"></div>
	 <script>
	  // Create tree
	  //

	var xhr= new XMLHttpRequest();
	xhr.open("GET", "/_json_menu_list.php");
	xhr.send();

	xhr.onreadystatechange= function(){
	if(xhr.readyState=== XMLHttpRequest.DONE){
	if(xhr.status== 200){
	let loadedJSON= JSON.parse(xhr.responseText);


	// jsTree 생성
	$('#layerTree').on('changed.jstree', function (e, data) {
	    var i, j, r = [];
	    for(i = 0, j = data.selected.length; i < j; i++) {
	      r.push(data.instance.get_node(data.selected[i]).a_attr.href);
	    }
	    $('#event_result').html('Selected: ' + r.join(', '));
	  }).jstree({
	    "plugins" : [ "wholerow", "changed" ],
	    'core' : { 'data' : loadedJSON }
	});


	$(function () { $('#jstree_demo_div').jstree(); });

	}else{
		alert("fail to load");
	}
	}
	}


	</script>
	 <div id="event_result" style="background-color:#d6d6d6;"></div>
	<!-- 			<ul class="inline2"  >
					<?
					$Q=$Mem->q("select t.* from ".$_SESSION["AUTH"]["CODE_ARRAY"]." t where length(t.CODE)=2 and t.TYPE=1 and t.STAT=0 order by PR asc ");
					while($r=$Q->fetch()){
					?>
					<li>+<?=$r["CT_NM"]?></li>
						<? } ?>
				</ul> -->
		</div>
	<? }else{ ?>

		<div class="main_side_menu1" >


				<ul class="menu_list" >
				 	<li onclick="go('/Content_User_List.php');"  style="cursor:pointer;" >+ 사용자관리</li>
					<li onclick="go('/Content_Document_List.php?search_reset=1');"  style="cursor:pointer;" >+ 데이터조회</li>
					<li onclick="go('/Content_Data_Register.php');"  style="cursor:pointer;" >+ 데이터 등록</li>
					<li onclick="go('/Content_Data_Register_Excel.php');"  style="cursor:pointer;" >+ 데이터 엑셀등록</li>
					<li onclick="go('/Content_Config_Category.php?CTYPE=1');"  style="cursor:pointer;" >+ 주제분류설정</li>
					<li onclick="go('/Content_Config_Category.php?CTYPE=2');"  style="cursor:pointer;" >+ 유형분류설정</li>
					<li onclick="go('/Content_Config_Category.php?CTYPE=3');"  style="cursor:pointer;" >+ 국가분류설정</li>
					<li onclick="go('/Member_Logout.php');"  style="cursor:pointer;" >+ 로그아웃</li>
				</ul>


		</div>

	<? } ?>
	</td>

			<td style=";">
<? } ?>
<? } ?>
