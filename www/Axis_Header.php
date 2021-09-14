
<? 
include "_head.php";

if($Mem->class < 8 ){ //위쪽 레이어 작성.
	if($_GET["crawl"]){
		$destination = "Crawl_Content.php";
	}else{
		$destination = "Content_view.php";
	}
	if($_GET['temp']){

		$destination = "Renew_Content.php";
	}
	#exit;
if($_SESSION["AUTH"]["MID"]=="2410"){?>

<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:200px; font-size:20px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&search_reset=1')"><?if($_SESSION["AUTH"]["MID"]){$Q=$Mem->q("select CT_NM from nt_categorys where CODE like ?",$_SESSION["AUTH"]["MID"]);$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["reset_type"]=="1"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&reset_type=1&crawl=<?=$_GET["crawl"]?>')">최근자료</td>
	<td class="<?=$_GET["TYPE"]=="전략"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=전략&crawl=<?=$_GET["crawl"]?>')">전략</td>
    <td class="<?=$_GET["TYPE"]=="정책동향"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=정책동향&crawl=<?=$_GET["crawl"]?>')">정책동향</td>
    <td class="<?=$_GET["TYPE"]=="산업동향"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=산업동향&crawl=<?=$_GET["crawl"]?>')">산업동향</td>
    <td class="<?=$_GET["TYPE"]=="보고서"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=보고서&crawl=<?=$_GET["crawl"]?>')">보고서</td>
    <td class="<?=$_GET["TYPE"]=="통계백서"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=통계백서&crawl=<?=$_GET["crawl"]?>')">통계백서</td>
    <td class="<?=$_GET["TYPE"]=="규제지침"?"active":""?>" style="width:14.2%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=규제지침&crawl=<?=$_GET["crawl"]?>')">규제지침</td>
  </table>
</div>

<?}elseif($_SESSION["AUTH"]["MID"]=="2411"){?>

	<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:200px; background-color:green;font-size:20px;border-bottom:2px solid green" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&search_reset=1')"><?if($_SESSION["AUTH"]["MID"]){$Q=$Mem->q("select CT_NM from nt_categorys where CODE like ?",$_SESSION["AUTH"]["MID"]);$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["reset_type"]=="1"?"active":""?>" style="width:16.7%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&reset_type=1&crawl=<?=$_GET["crawl"]?>')">신규등록자료</td>
	<td class="<?=$_GET["TYPE"]=="글로벌동향"?"active":""?>" style="width:16.7%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=글로벌동향&crawl=<?=$_GET["crawl"]?>')">글로벌동향</td>
    <td class="<?=$_GET["TYPE"]=="발간물"?"active":""?>" style="width:16.7%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=발간물&crawl=<?=$_GET["crawl"]?>')">발간물</td>
    <td class="<?=$_GET["TYPE"]=="레퍼런스"?"active":""?>" style="width:16.7%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=레퍼런스&crawl=<?=$_GET["crawl"]?>')">레퍼런스</td>
    <td class="<?=$_GET["TYPE"]=="아카이브"?"active":""?>" style="width:16.7%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=아카이브&crawl=<?=$_GET["crawl"]?>')">아카이브</td>
	<td class="<?=$_GET["TYPE"]=="image"?"active":""?>" style="width:16.7%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=image&crawl=<?=$_GET["crawl"]?>')">이미지 검색</td>
  </table>
</div>

<?}elseif($_SESSION["AUTH"]["MID"]=="2413"){?>
	<div style="height: 50px;background-color:#2f5597;">
  <table class="menu_top">
    <td style="min-width:200px; background-color:#00ccff;font-size:20px;border-bottom:2px solid #00ccff" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&search_reset=1')"><?if($_SESSION["AUTH"]["MID"]){$Q=$Mem->q("select CT_NM from nt_categorys where CODE like ?",$_SESSION["AUTH"]["MID"]);$r=$Q->fetch();}?><?=$r["CT_NM"]? $r["CT_NM"]:"미지정"?></td>
    <td class="<?=$_GET["TYPE"]=="동향자료"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=동향자료&crawl=<?=$_GET["crawl"]?>')">동향자료</td>
	<td class="<?=$_GET["TYPE"]=="정책자료"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=정책자료&crawl=<?=$_GET["crawl"]?>')">정책자료</td>
    <td class="<?=$_GET["TYPE"]=="발간물"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=발간물&crawl=<?=$_GET["crawl"]?>')">발간물</td>
    <td class="<?=$_GET["TYPE"]=="레퍼런스"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=레퍼런스&crawl=<?=$_GET["crawl"]?>')">레퍼런스</td>
    <td class="<?=$_GET["TYPE"]=="아카이브"?"active":""?>" style="width:20%;min-width:100px;" onclick="go('<?=$destination?>?<?=!$_GET["CID"]?"CID=".$_GET["CID"]:""?>&TYPE=아카이브&crawl=<?=$_GET["crawl"]?>')">아카이브</td>
  </table>
</div>
<?}

}else{ ?>

<div style="min-width:1200px;line-height:40px;background-color:#2f5597;text-align:center;padding-top:8px;">
</div>

<? } ?>





<? if($Mem->class < 7 ){ //유저 접근 권한 확인 후 왼쪽 레이어 작성?>

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
			?><li onclick="go('<?=$destination?>?CID=<?=$r["CODE"]?>&crawl=<?=$_GET['crawl']?>')" style="cursor:pointer;
			<?=$_GET["CID"]==$r["CODE"]?"background-color:white":""?>;"><?=$r["CT_NM"]?></li><?		
		}
}elseif($_SESSION["AUTH"]["MID"]==="2411"){
		$contid = array("아시아", "유럽/CIS", "중동/아프리카", "북미/중남미", "글로벌");
		$t = 0;
		while($t<count($contid)){
			?><li onclick="go('<?=$destination?>?CONTID=<?=$contid[$t]?>&crawl=<?=$_GET['crawl']?>')" style="cursor:pointer;
			<?=($_GET["CONTID"]==$contid[$t])||(!$_GET["CONTID"]&&($_SESSION["SEARCH"]["CONTID"]==$contid[$t]))?"background-color:white":""?>;"><?=$contid[$t]?></li><?		
		//헤드의 배너를 누를 시 GET으로 전송하고, 그것을 session에 저장해둠.
		//누를 때 전송을 하므로 이전에 저장된 세션의 CONTID를 고려하여 처리, CONTID가 무시되는 경우 session으로 색인하는 것 활성화
		$t++;
	}

}elseif($_SESSION["AUTH"]["MID"]==="2413"){
	$contid = array("미국", "일본", "중국", "독일", "EU","글로벌");
	$t = 0;
	while($t<count($contid)){
		?><li onclick="go('<?=$destination?>?CONTID=<?=$contid[$t]?>&crawl=<?=$_GET['crawl']?>')" style="cursor:pointer;
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
					<li onclick="go('/Crawl_Search_Result.php');"  style="cursor:pointer;" >+ 크롤데이터 등록</li>
					<li onclick="go('/Crawl_Document_List.php');"  style="cursor:pointer;" >+ 크롤데이터 조회</li>
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

