<? include "_head.php"; 

if($_POST["SEARCH"]){

if(!is_array($_SESSION["SEARCH"])) $_SESSION["SEARCH"]=array();

$_SESSION["SEARCH"]=$_POST;

mvs(SELF);
exit;
}

if($_GET["search_reset"]) unset($_SESSION["SEARCH"]);


if($_GET["CID"])	$_SESSION["SEARCH"]["CID"]=$_GET["CID"];
if($_SESSION["SEARCH"]["CID"]){
	$_GET["CID"]=$_SESSION["SEARCH"]["CID"]; 
}

?>
<div style=";">
	

<form action="" method="post" >
<input type="hidden" name="SEARCH" value="1">
<input type="hidden" name="CID" value="<?=$_GET["CID"]?>">

<div style="height:auto; overflow:hidden; ">
	
	
	<div class="header_box1" style="float:left;width: calc( 33.3% - 2px); border-right:solid 2px #EEE; ; " >대분류선택</div>
	
	<div class="header_box1" style="float:left;width: calc( 33.3% - 2px); border-right:solid 2px #EEE; ; " >중분류선택</div>
	
	<div class="header_box1" style="float:left;width: calc( 33.3% - 2px); border-right:solid 2px #EEE; ; " >소분류선택</div>
</div>
<div class="clear"></div>

<div style="height:auto; overflow:hidden;">
	
	
	<div class="" style="float:left;width: calc( 33.3% - 2px); border-right:solid 2px #EEE; height:150px;overflow-y:scroll; background-color:#FFF; " >
 
			<table cellpadding="0" cellspacing="0" border="0" class="table_info1" style="" > 
				<? $n=0; $Q=$Mem->q("Select * from nt_category_list where length(CODE)=2 and STAT < 9 order by CT_NM asc "); while($r=$Q->fetch()){ $n++; ?>


				<tr> 
					<td   class="<?=substr($_GET["CID"],0,2)==$r["CODE"]?"uid":""?>"  style="cursor:pointer;" onclick="go('<?=SELF?>?CID=<?=$r["CODE"]?>');"><?=$r["CT_NM"]?></td> 
				</tr>

				<? } ?>
			</table>

</div>
			
	<div class="" style="float:left;width: calc( 33.3% - 2px); border-right:solid 2px #EEE; height:150px;overflow-y:scroll; background-color:#FFF; " >
 

					<table cellpadding="0" cellspacing="0" border="0" class="table_info1" style="" > 
			<? if(strlen($_GET["CID"])>=2){ ?>


						<? $n=0; $Q=$Mem->q("Select * from nt_category_list where length(CODE)=4 and  CODE like ? and STAT < 9 order by CT_NM asc ",substr($_GET["CID"],0,2)."%"); 
						if($Q->rowCount()==0){
						?> 
						<tr>
							<td colspan="3"  style="height:150px;" >등록된 카테고리가 없습니다.<Br>중분류 카테고리를 등록해주세요.</td>
						</tr>
							<?
						}
						while($r=$Q->fetch()){ $n++; ?>


						<tr> 
							<td  class="<?=substr($_GET["CID"],0,4)==$r["CODE"]?"uid":""?>" style="cursor:pointer;" onclick="go('<?=SELF?>?CID=<?=$r["CODE"]?>');"><?=$r["CT_NM"]?></td> 
						</tr>

						<? } ?>
			<? }else{ ?>
					
					
						<tr>
							<td colspan="3"  style="height:150px;" >대분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table>
</div>
			 
	<div class="" style="float:left;width: calc( 33.3% - 2px); border-right:solid 2px #EEE; height:150px; overflow-y:scroll;background-color:#FFF; " >
 
 
					<table cellpadding="0" cellspacing="0" border="0" class="table_info1" style="" > 
			<? if(strlen($_GET["CID"])>=4){ ?>


						<? $n=0; $Q=$Mem->q("Select * from nt_category_list where length(CODE)=6 and  CODE like ? and STAT < 9 order by CT_NM asc ",substr($_GET["CID"],0,4)."%"); 
										if($Q->rowCount()==0){
												?> 
						<tr>
							<td colspan="3"  style="height:150px;" >등록된 카테고리가 없습니다.<Br>중분류 카테고리를 등록해주세요.</td>
						</tr>
						<?
						}
						while($r=$Q->fetch()){ $n++; ?>


						<tr> 
							<td  class="<?=substr($_GET["CID"],0,6)==$r["CODE"]?"uid":""?>"   style="cursor:pointer;" onclick="go('<?=SELF?>?CID=<?=$r["CODE"]?>');"><?=$r["CT_NM"]?></td> 
						</tr>

						<? } ?>
			<? }else{ ?>
					
					
						<tr>
							<td colspan="3"  style="height:150px;" >중분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table>
</div>



</div>
<div style="border-bottom:solid 2px #999;"></div>
<table class="table_view" cellpadding="0" cellspacing="0" border="0" >

	<tr>
		<th>주제분류</th>

		<td colspan="3" ><div id="category_list1">3s</div></td>
	</tr>
	<tr>
		<th>국가분류</th>
		<td colspan="3" >
			
  	<? $n=0; $Q=$Mem->q("Select * from nt_categorys where STAT < 9 and TYPE=3 order by CT_NM asc "); while($r=$Q->fetch()){  ?>

		<div style="float:left;width:150px;line-height:30px;" >
		<label  style=";">
			<input type="checkbox" name="KEY_COUNTRY[]" value="<?=$r["IDX"]?>" <?=in_array($r["IDX"],$_SESSION["SEARCH"]["KEY_COUNTRY"])?"checked":""?> ><?=$r["CT_NM"]?>
		</label>
		</div>

	<?  $n++;  } ?>
		</td>
	</tr> 
	<tr>
		<th>유형분류</th>
		<td colspan="3" >
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

<table cellpadding="0" cellspacing="0" border="0" class="table_info" >
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
	<? if($Mem->class>8){ ?>	<col  style="width:120px;" /><? } ?>
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
		<? if($Mem->class>8){ ?>	<th>설정</th><? } ?>
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

if($_SESSION["SEARCH"]["KEY_PAGE"]){	 $where_str.=" and a.DC_PAGE like ? "; array_push($where_array,"%".$_SESSION["SEARCH"]["KEY_PAGE"]."%");			}





$where_country="";
$where_country_key=""; 
$where_country_distance="";


for($i=0;  $i < sizeof($_SESSION["SEARCH"]["KEY_COUNTRY"]); $i++){
	$where_country_key.=$_SESSION["SEARCH"]["KEY_COUNTRY"][$i].",";
}

if(sizeof($_SESSION["SEARCH"]["KEY_COUNTRY"])){ 
	$where_table.=" , nt_document_country_list b ";
	$where_country=" and b.PID=a.IDX and b.TID in( ".substr($where_country_key,0,-1).") ";
}




 


$where_category="";
$where_category_key=""; 

/*
 if($_SESSION["SEARCH"]["CID"]){
	$where_table.=" , nt_document_category_list c ,nt_category_list d ";
	if(strlen($_SESSION["SEARCH"]["CID"])==2){

	}

	if(strlen($_SESSION["SEARCH"]["CID"])==4){

	}
	if(strlen($_SESSION["SEARCH"]["CID"])==6){

	}
		$where_category=" and c.PID=a.IDX and c.CID=d.IDX and d.CODE like '".$_SESSION["SEARCH"]["CID"]."%'";

}
*/


echo $where_table;

$re=paging("select DISTINCT(a.IDX) as idxss , a.*  from nt_document_list a  ".$where_table." where   a.STAT < 9   ".$where_str.$where_country.$where_category, $where_array,20,20,"a.IDX desc ");

 

for($i=0; $i < $re[0]->rowCount(); $i++){ $n++;
		$r=$re[0]->fetch();

?>
	<tr>
		<td title="<?=$r["IDX"]?>"> <label ><input type="checkbox"><?=$re[1]--?></label></td>
		<tds>
			<? 
	$QC=$Mem->q("select a.* from nt_country_list a, nt_document_country_list b where b.PID=? and b.TID=a.IDX  ",$r["IDX"]);

while($rs=$QC->fetch()){
echo $rs["COUNTRY_NM"];
echo "<BR>";

}
				?>

		</td>
		<td><?=$r["DC_TYPE"]?></td>
		<td><?=$r["DC_AGENCY"]?></td>
		<td class="uid" style="text-align:left;padding:10px;" > <div><span onclick="window.open('Content_Data_View.php?PID=<?=$r["IDX"]?>','data_view','width=900,height=900,scrollbars=1');"><?=$r["DC_TITLE_OR"]?></span></div></td>
		<td class="uid" style="text-align:left;padding:10px"  >  <div> <span onclick="window.open('Content_Data_View.php?PID=<?=$r["IDX"]?>','data_view','width=900,height=900,scrollbars=1');"><?=$r["DC_TITLE_KR"]?></span></div></td>
		<td><?=$r["DC_PAGE"]?></td>
		<td>
				
		</td>
		<td> 
		<? if( $r["DC_URL_LOC"]){			echo "<img src='/images/icon_link.png' style='width:20px;cursor:pointer;'  onclick=\"window.open('".$r["DC_URL_LOC"]."','_blank');\" >";} ?>
		</td>
		<td>
				<?=$r["DC_DT_COLLECT"]?date("Y-m-d",$r["DC_DT_COLLECT"]):"-"?>
		</td>
		<td>
				<?=$r["DC_HIT"]?>
		</td>
			<? if($Mem->class>8){ ?>
		<td><input type="button" value="삭제" class="button1" ><input type="button" value="수정" class="button1" ></td>
<? } ?>
	</tr>
	<? } ?>
</table>
	<? if($Mem->class>8){ ?>
<input type="button" class="button1" value="전체선택" >
<input type="button" class="button1" value="선택삭제" >
<? } ?>
<!-- <input type="button" class="button1" value="분류재지정" >
 -->

<?=$re[2]?>


<script>
	getup('Content_Data_Category_List.php?CTYPE=1','category_list1'); 

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


 
</script>