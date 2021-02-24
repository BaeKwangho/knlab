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
	<col  style="width:90px;" />
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
		<th>IDX</th>
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




	$n=0;
$where_str=" and a.IDX > 0";
$where_table="";
$where_array=array();

if($_SESSION["SEARCH"]["KEY_TITLE_OR"]){	 $where_str.=" and a.DC_TITLE_OR like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_TITLE_OR"]."%");			}
if($_SESSION["SEARCH"]["KEY_TITLE_KR"]){	 $where_str.=" and a.DC_TITLE_KR like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_TITLE_KR"]."%");			}
if($_SESSION["SEARCH"]["KEY_KEYWORD"]){	 $where_str.=" and a.DC_KEYWORD like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_KEYWORD"]."%");			}
if($_SESSION["SEARCH"]["KEY_CONTENT"]){	 $where_str.=" and ( a.DC_CONTENT like ? or a.DC_SMRY_KR like ? )"; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_CONTENT"]."%"); array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_CONTENT"]."%");	  	}
if($_SESSION["SEARCH"]["KEY_AGENCY"]){	 $where_str.=" and a.DC_AGENCY like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_AGENCY"]."%");			}

if($_SESSION["SEARCH"]["DT_COLLECT_ST"]){	 $where_str.=" and a.DC_DT_COLLECT >= ? "; array_push($where_array,datec($_SESSION["SEARCH"]["DT_COLLECT_ST"]));			}
if($_SESSION["SEARCH"]["DT_COLLECT_ED"]){	 $where_str.=" and a.DC_DT_COLLECT <= ? ";  array_push($where_array,datec($_SESSION["SEARCH"]["DT_COLLECT_ED"]));	}

if($_SESSION["SEARCH"]["DT_WRITE_ST"]){	 $where_str.=" and a.DC_DT_WRITE >=? "; array_push($where_array,datec($_SESSION["SEARCH"]["DT_WRITE_ST"]));			}
if($_SESSION["SEARCH"]["DT_WRITE_ED"]){	 $where_str.=" and a.DC_DT_WRITE <=? ";  array_push($where_array,datec($_SESSION["SEARCH"]["DT_WRITE_ED"]));	}

if($_SESSION["SEARCH"]["KEY_PAGE"]){	 $where_str.=" and a.DC_PAGE<=? "; array_push($where_array,$_SESSION["SEARCH"]["KEY_PAGE"]);			}
if($_SESSION["SEARCH"]["KEY_COUNTRY"]){	 $where_str.=" and a.DC_COUNTRY like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_COUNTRY"]."%");			}

if($_SESSION["SEARCH"]["KEY_COUNTRY"]){	 $where_str.=" and a.DC_COUNTRY like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_COUNTRY"]."%");			}
if($_SESSION["SEARCH"]["KEY_TYPE"]){	 $where_str.=" and a.DC_TYPE like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_TYPE"]."%");			}





$where_country="";
$where_country_key="";
$where_country_distance="";


/* 라벨 형식 국가 분류
if(is_array($_SESSION["SEARCH"]["KEY_COUNTRY"]) && sizeof($_SESSION["SEARCH"]["KEY_COUNTRY"]) <= 0){
	for($i=0;  $i < sizeof($_SESSION["SEARCH"]["KEY_COUNTRY"]); $i++){
		$where_country_key.=$_SESSION["SEARCH"]["KEY_COUNTRY"][$i].",";
	}
}
if(is_array($_SESSION["SEARCH"]["KEY_COUNTRY"]) && sizeof($_SESSION["SEARCH"]["KEY_COUNTRY"]) <= 0){
	$where_table.=" , nt_document_country_list b ";
	$where_country=" and b.PID=a.IDX and b.TID in( ".substr($where_country_key,0,-1).") ";
}
*/






$where_category="";
$where_category_key="";


if($_SESSION["SEARCH"]["ITEM"]){
	$where_table.=" join nt_document_code_list b on b.pid = a.idx ";
	$where_str.=" and b.CODE like ? "; array_push($where_array,$_SESSION["SEARCH"]["ITEM"]."%");
}

//$re=paging("select DISTINCT(a.IDX) as idxss , a.*  from nt_document_list a where   a.STAT < 9  and a.DC_CODE like ?'".$where_str."'.", array($where_category."%"),20,20,"a.IDX desc ");
$re=paging("select DISTINCT(a.IDX) as idxss , a.*  from nt_document_list a  ".$where_table." where   a.STAT < 9   ".$where_str, $where_array,20,20,"a.IDX desc ");
echo debug_print_backtrace();
for($i=0; $i < $re[0]->rowCount(); $i++){ $n++;
		$r=$re[0]->fetch();

?>
	<tr id="<?=$r["IDX"]?>">
		<td title="<?=$r["IDX"]?>"> <label ><input class="check_idx" type="checkbox" id="list[]" value="<?=$r["IDX"]?>"><?=$re[1]--?></label></td>
		<tds>
			<?
				// $QC=$Mem->q("select a.* from nt_country_list a, nt_document_country_list b where b.PID=? and b.TID=a.IDX  ",$r["IDX"]);

				// while($rs=$QC->fetch()){
				// echo $rs["COUNTRY_NM"];
				// echo "<BR>";
				//}
				?>

		</td>
		<td><?=$r["IDX"]?></td>
		<td><?=$r["DC_COUNTRY"]?></td>
		<td><?=$r["DC_TYPE"]?></td>
		<!--<td><?=$r["DC_AGENCY"]?></td>-->
		<td><?=$r["DC_AGENCY"]?></td>
		<td class="uid" style="text-align:left;padding:10px;" > <div><span onclick="window.open('Content_Data_View.php?PID=<?=$r["IDX"]?>','data_view','width=900,height=900,scrollbars=1');"><?=$r["DC_TITLE_OR"]?></span></div></td>
		<td class="uid" style="text-align:left;padding:10px"  >  <div> <span onclick="window.open('Content_Data_View.php?PID=<?=$r["IDX"]?>','data_view','width=900,height=900,scrollbars=1');"><?=$r["DC_TITLE_KR"]?></span></div></td>
		<td><?=$r["DC_PAGE"]?></td>
		<td>

		</td>
		<td>
		<? if( $r["DC_URL_LOC"]){			echo "<img src='/images/icon_arrow2.png' style='width:20px;cursor:pointer;'  onclick=\"window.open('".$r["DC_URL_LOC"]."','_blank');\" >";} ?>
		</td>
		<td>
				<?=$r["DC_DT_COLLECT"]?date("Y-m-d",$r["DC_DT_COLLECT"]):"-"?>
		</td>
		<td>
				<?=$r["DC_HIT"]?>
		</td>
			<? if($Mem->class>7){ ?>
		<td>
			<input type="button" value="삭제" class="button1" onclick="test(<?=$r["IDX"]?>);">
			<input type="button" value="수정" class="button1" onclick="window.open('Content_Data_Modify.php?PID=<?=$r["IDX"]?>','data_modify','scrollbars=1');">
		</td>
<? } ?>
	</tr>
	<? } ?>
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


  $( '[data-type="DT"]' ).datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
    yearSuffix: '년'
  });

function test(idx){
	if(confirm("정말 삭제하시겠습니까?")==true){
		getup('Content_Remove.php?REMOVE_DOC='+idx,idx);
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
