<? include "Axis_Header.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);

if($_POST["SEARCH"]){

if(!is_array($_SESSION["SEARCH"])) $_SESSION["SEARCH"]=array();

$_SESSION["SEARCH"]=$_POST;

mvs(SELF);
exit;
}
if($_GET["search_reset"]) unset($_SESSION["SEARCH"]);

//CID는 대중소 분류.. get의 cid와 session의 cid에 맞게 값 수정
//20201202 deprecated
/*
if($_GET["CID"])	$_SESSION["SEARCH"]["CID"]=$_GET["CID"];
if($_SESSION["SEARCH"]["CID"]){
	$_GET["CID"]=$_SESSION["SEARCH"]["CID"];
}
*/


?>
<div style=";">

<script language="javascript" type="text/javascript" src="/js/createXMLHttpRequest.js"></script>
<form action="" method="post" >
<input type="hidden" name="SEARCH" value="1">

<div style="height:auto; overflow:hidden; ">


</div>
<div class="clear"></div>

<div style="height:auto; overflow:hidden;">

</div>
<div style="border-bottom:solid 2px #999;"></div>
<table class="table_view" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<th>주제분류</th>
		<td colspan="3" >
			<input type="hidden" name="category_list"><div id="category_list1"> </div></input>
		</td>
	</tr>
	<tr>
		<th>국가분류</th>
		<td>
		<input type="text" class="input_cell"  name="KEY_COUNTRY"  value="<?=$_SESSION["SEARCH"]["KEY_COUNTRY"]?>">

<!-- 기존 라벨형식의 출력 
  	<? $n=0; $Q=$Mem->q("Select * from nt_categorys where STAT < 9 and TYPE=3 order by CT_NM asc "); while($r=$Q->fetch()){  ?>

		<div style="float:left;width:150px;line-height:30px;" >
		<label  style=";">
			<input type="checkbox" name="KEY_COUNTRY[]" value="<?=$r["IDX"]?>" <?=in_array($r["IDX"],$_SESSION["SEARCH"]["KEY_COUNTRY"])?"checked":""?> ><?=$r["CT_NM"]?>
		</label>
		</div>

	<?  $n++;  } ?>
-->
		</td>
		<th>유형분류</th>
		<td colspan="3" ><input type="text" class="input_cell"  name="KEY_TYPE"  value="<?=$_SESSION["SEARCH"]["KEY_TYPE"]?>">
		</td>
	</tr>
	<tr>
		<th>원제목</th>
		<td><input type="text" class="input_cell"  name="KEY_TITLE_OR"  value="<?=$_SESSION["SEARCH"]["KEY_TITLE_OR"]?>"></td>
		<th>한글제목</th>
		<td><input type="text" class="input_cell" name="KEY_TITLE_KR"  value="<?=$_SESSION["SEARCH"]["KEY_TITLE_KR"]?>"></td>
	</tr>
	<tr>
		<th>내용 또는 요약</th>
		<td><input type="text" class="input_cell"  name="KEY_CONTENT"  value="<?=$_SESSION["SEARCH"]["KEY_CONTENT"]?>"></td>
		<th>키워드</th>
		<td><input type="text" class="input_cell"  name="KEY_KEYWORD"  value="<?=$_SESSION["SEARCH"]["KEY_KEYWORD"]?>"></td>
	</tr>
	<tr>
		<th>기관명</th>
		<td><input type="text" class="input_cell" name="KEY_AGENCY"  value="<?=$_SESSION["SEARCH"]["KEY_AGENCY"]?>"></td>
		<th>페이지수</th>
		<td><input type="text" class="input_cell" name="KEY_PAGE"  value="<?=$_SESSION["SEARCH"]["KEY_PAGE"]?>"></td>
	</tr>
	<tr>
		<th>수집일</th>
		<td><input type="text" class="input_text1" style="width:100px;" name="DT_COLLECT_ST" id="DT_COLLECT_ST"  value="<?=$_SESSION["SEARCH"]["DT_COLLECT_ST"]?>"  data-type="DT"  >~<input type="text" class="input_text1" style="width:100px;" name="DT_COLLECT_ED"  id="DT_COLLECT_ED"   value="<?=$_SESSION["SEARCH"]["DT_COLLECT_ED"]?>" data-type="DT"></td>
		<th>작성일</th>
		<td><input type="text" class="input_text1" style="width:100px;" name="DT_WRITE_ST" id="DT_WRITE_ST"  value="<?=$_SESSION["SEARCH"]["DT_WRITE_ST"]?>"  data-type="DT" >~<input type="text" class="input_text1" style="width:100px;" name="DT_WRITE_ED"  id="DT_WRITE_ED"   value="<?=$_SESSION["SEARCH"]["DT_WRITE_ED"]?>" data-type="DT"></td>
	</tr>
</table>
<div style="padding:10px; text-align:center;background-color:#FFF;" ><input type="button" class="button1" value="상세검색초기화" style="height:36px; width:100px;"  onclick="go('<?=SELF?>?search_reset=1');" ><input type="submit" class="buttonb" value="상세검색" onclick="" style="height:36px; width:100px;" ></div>
</form>
</div>
<div style="border-bottom:solid 2px #999;"></div>

<table id="list_table" cellpadding="0" cellspacing="0" border="0" class="table_info" >
<colgroup>
	<col  style="width:80px;" />
	<col  style="width:70px;" />
	<col  style="width:80px;" />
	<col  style="width:150px;" />
	<col   style="width:350px;" />
	<col   />
	<col  style="width:70px;" />
	<col  style="width:70px;" />
	<col  style="width:60px;" />
	<col  style="width:80px;" />
	<col  style="width:70px;" />
	<? if($Mem->class>7){ ?>	<col  style="width:120px;" /><? } ?>
</colgroup>
	<tr>
		<th>순번</th>
		<th>국가</th>
		<th>구분</th>
		<th>기관명</th>
		<th>원제목</th>
		<th>한글제목</th>
		<th>페이지수</th>
		<th>첨부파일</th>
		<th>링크</th>
		<th>수집일</th>
		<th>열람수</th>
		<? if($Mem->class>7){ ?>	<th>설정</th><? } ?>
	</tr>

<?

if($_SESSION["SEARCH"]["KEY_TITLE_OR"]){$KEY_TITLE_OR=$_SESSION["SEARCH"]["KEY_TITLE_OR"];}else{$KEY_TITLE_OR='*';}
if($_SESSION["SEARCH"]["KEY_TITLE_KR"]){$KEY_TITLE_KR=$_SESSION["SEARCH"]["KEY_TITLE_KR"];}else{$KEY_TITLE_KR='*';}
if($_SESSION["SEARCH"]["KEY_KEYWORD"]){$KEY_KEYWORD=$_SESSION["SEARCH"]["KEY_KEYWORD"];}else{$KEY_KEYWORD='*';}
if($_SESSION["SEARCH"]["KEY_CONTENT"]){$KEY_CONTENT=$_SESSION["SEARCH"]["KEY_CONTENT"];}else{$KEY_CONTENT='*';}
if($_SESSION["SEARCH"]["KEY_AGENCY"]){$KEY_AGENCY=$_SESSION["SEARCH"]["KEY_AGENCY"];}else{$KEY_AGENCY='*';}

if($_SESSION["SEARCH"]["DT_COLLECT_ST"]){$DT_COLLECT_ST=$_SESSION["SEARCH"]["DT_COLLECT_ST"];}else{$DT_COLLECT_ST='*';}
if($_SESSION["SEARCH"]["DT_COLLECT_ED"]){$DT_COLLECT_ED=$_SESSION["SEARCH"]["DT_COLLECT_ED"];}else{$DT_COLLECT_ED='*';}

if($_SESSION["SEARCH"]["DT_WRITE_ST"]){$DT_WRITE_ST=$_SESSION["SEARCH"]["DT_WRITE_ST"];}else{$DT_WRITE_ST='*';}
if($_SESSION["SEARCH"]["DT_WRITE_ED"]){$DT_WRITE_ED=$_SESSION["SEARCH"]["DT_WRITE_ED"];}else{$DT_WRITE_ED='*';}

if($_SESSION["SEARCH"]["KEY_PAGE"]){$KEY_PAGE=$_SESSION["SEARCH"]["KEY_PAGE"];}else{$KEY_PAGE='*';}
if($_SESSION["SEARCH"]["KEY_COUNTRY"]){$KEY_COUNTRY=$_SESSION["SEARCH"]["KEY_COUNTRY"];}else{$KEY_COUNTRY='*';}

if($_SESSION["SEARCH"]["KEY_TYPE"]){$KEY_TYPE=$_SESSION["SEARCH"]["KEY_TYPE"];}else{$KEY_TYPE='*';}


$select = array(
    'query'         => 'DC_CODE:'.$_SESSION["SEARCH"]["ITEM"].
                        "* AND DC_CAT:".$KEY_TYPE." AND DC_COUNTRY:".$KEY_COUNTRY.
                        " AND DC_TITLE_OR:".$KEY_TITLE_OR." AND DC_TITLE_KR:".$KEY_TITLE_KR.
                        " AND DC_CONTENT:".$KEY_CONTENT." AND DC_COUNTRY:".$KEY_COUNTRY.
                        " AND DC_CAT:".$KEY_TYPE." AND DC_AGENCY:".$KEY_AGENCY,

    'start'         => 0,
    'rows'          => 1,
    'fields'        => array('*'),
    'sort'          => array('DC_DT_COLLECT' => 'asc'),
    'filterquery' => array(
        'custom' => array(
            'query' => '',
        ),
    ),
);
$re = solr_paging($Mem->docs,$select,20,20);
foreach($re[0] as $r){
?>
<tr id="<?=$r["id"]?>">
    <td title="<?=$r["id"]?>"> <label ><input class="check_idx" type="checkbox" id="list[]" value="<?=$r["id"]?>"><?=$re[1]--?></label></td>
    <tds>
        <?
            // $QC=$Mem->q("select a.* from nt_country_list a, nt_document_country_list b where b.PID=? and b.TID=a.IDX  ",$r["IDX"]);
            // while($rs=$QC->fetch()){
            // echo $rs["COUNTRY_NM"];
            // echo "<BR>";
            //}
            ?>

    </td>
    <td>
	<?
		$countrys = '';
		foreach($r["DC_COUNTRY"] as $con){
			$countrys.= $con.', ';
		}
		$countrys = substr($countrys,0,-2);
		echo $countrys;
	?>
				
	</td>
    <td><?=$r["DC_TYPE"][0]?></td>
    <!--<td><?=$r["DC_AGENCY"][0]?></td>-->
    <td><?=$r["DC_AGENCY"][0]?></td>
    <td class="uid" style="text-align:left;padding:10px;" > <div><span><?=$r["DC_TITLE_OR"][0]?></span></div></td>
    <td class="uid" style="text-align:left;padding:10px"  >  <div> <span><?=$r["DC_TITLE_KR"][0]?></span></div></td>
    <td><?=$r["DC_PAGE"][0]?></td>
    <td>

    </td>
    <td>
    <? if( $r["DC_URL_LOC"][0]){			echo "<img src='/images/icon_arrow2.png' style='width:20px;cursor:pointer;'  onclick=\"window.open('".$r["DC_URL_LOC"][0]."','_blank');\" >";} ?>
    </td>
    <td>
            <?=$r["DC_DT_COLLECT"][0]?date("Y-m-d",$r["DC_DT_COLLECT"][0]):"-"?>
    </td>
    <td>
            <?=$r["DC_HIT"][0]?>
    </td>
        <? if($Mem->class>7){ ?>
    <td>
        <input type="button" value="삭제" class="button1" onclick="test('<?=$r['id']?>');">
        <input type="button" value="수정" class="button1" onclick="window.open('Crawl_Modify.php?id=<?=$r["id"]?>','data_modify','scrollbars=1');">
    </td>
<? } ?>
</tr>
<? }?>
</table>
<? if($Mem->class>7){ ?>
<input type="button" class="button1" value="전체선택" >
<input type="button" class="button1" value="선택삭제" onclick="del_list();">
<? } ?>
<!-- <input type="button" class="button1" value="분류재지정" >
-->
<?= $re[2]?>

<script>
	getup('Content_Data_Category_List.php?TYPES=1','category_list1'); 



function test(idx){
	if(confirm("정말 삭제하시겠습니까?")==true){
		getup('Content_Remove.php?REMOVE_CRAWL='+idx,idx);
		alert("삭제되었습니다.");
	}else{
		
	}
}

//전체 삭제는 check_idx로 받은 리스트를 하나하나 그냥 getup으로 처리시키며 삭제해줌.
function del_list(){
	var idxs=[];
	$.each($(".check_idx:checked"),function(){
		idxs.push($(this).val());
	});
	if(confirm("정말 삭제하시겠습니까?")==true){
		for(var i =0;i<idxs.length;i++){
			getup('Content_Remove.php?REMOVE_DOC='+idxs[i],idxs[i]);
		}
	}
	alert("삭제되었습니다.");
	
}

</script>
