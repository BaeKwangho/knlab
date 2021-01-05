<?php include "_head.php"; ?>

 
<div style="text-align:center;padding:20px;" >
	
</div>


<div style=";" class="round_box1" >
	
 

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



$re=paging("select DISTINCT(a.IDX) as idxss , a.*  from nt_document_list a  ".$where_table." where   a.STAT < 9   ".$where_str.$where_country.$where_category, $where_array,20,20,"a.IDX desc ");

 

for($i=0; $i < $re[0]->rowCount(); $i++){ $n++;
		$r=$re[0]->fetch();

?>
	<tr>
		<td title="<?=$r["IDX"]?>"> <label ><input type="checkbox"><?=$re[1]--?></label></td>
		<td>
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


<?php include "_foot.php"; ?>