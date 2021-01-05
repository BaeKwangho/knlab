
<table id="list_table" cellpadding="0" cellspacing="0" border="0" class="table_ex" >
<colgroup>
	<col  style="width:10%;" />
	<col  style="width:75%;" />
	<col  style="width:15%;" />
</colgroup>
	<tr>
		<th>순번</th>
		<th>한글제목</th>
		<th>수집일</th>
	</tr>
<?
//error_reporting(E_ALL);	ini_set("display_errors", 1);

include "../_h.php";
if($_POST["SEARCH"]){
    $_SESSION["SEARCH"]=$_POST;
}
	
$n=0;
$where_str=" where a.IDX > 0";
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
$where_code="(select * from nt_document_list where ";
$code_array=array();
if($_SESSION["SEARCH"]["ITEM"]){
	$max=count($_SESSION["SEARCH"]["ITEM"]);
	if($max>1){
		$lenmax=strlen($_SESSION["SEARCH"]["ITEM"][$max-1]);
		for($i=$max-1;$i>=0;$i--){
			if(strlen($_SESSION["SEARCH"]["ITEM"][$i])!=$lenmax){
				break;
			}else{
				
				//$where_category=" and c.PID=a.IDX and c.CID=d.IDX and d.CODE like '".$_SESSION["SEARCH"]["CID"]."%'";
				$where_code.=" DC_CODE like ".$_SESSION["SEARCH"]["ITEM"][$i]." or ";
				//$where_category=" and a.DC_CODE like ?'".$_SESSION["SEARCH"]["CID"]."'";
				//$where_category=$_SESSION["SEARCH"]["CID"];
			}
		}

	}else{
		$where_code.="DC_CODE like '".$_SESSION["SEARCH"]["ITEM"]."%' or ";
	}
}
$where_code.="IDX <0 ) a";
$_Ajax=array();
$_Ajax["div"]="list_table";
//$re=paging("select DISTINCT(a.IDX) as idxss , a.*  from nt_document_list a where   a.STAT < 9  and a.DC_CODE like ?'".$where_str."'.", array($where_category."%"),20,20,"a.IDX desc ");
$re=paging("select DISTINCT(a.IDX) as idxss , a.* from  ".$where_code."".$where_str, $where_array,6,10,"a.IDX desc ",$_POST);
if($re[0]->rowCount()==0){
	echo "검색 결과가 없습니다.";
}else{
for($i=0; $i < $re[0]->rowCount(); $i++){ $n++;
		$r=$re[0]->fetch();
?>

<tr style="height:10px;"id="<?=$r["IDX"]?>">
<td style="text-align:center;width:10px;" title="<?=$r["IDX"]?>"> <label ><input type="checkbox" name="list[]" value="<?=$r["IDX"]?>"><?=$re[1]--?></label></td>
	
</td>
<td style="text-align:left;padding:2px 10px 2px 10px;"  >  <div> <span onclick="window.open('Content_Data_View.php?PID=<?=$r["IDX"]?>','data_view','width=900,height=900,scrollbars=1');"><?=$r["DC_TITLE_KR"]?></span></div></td>

<td style="text-align:center;"><?=$r["DC_DT_WRITE"]?date("Y-m-d",$r["DC_DT_WRITE"]):"-"?></td>

</tr>
<? } ?>
</table>
</form>
<!-- <input type="button" class="button1" value="분류재지정" >
 -->
<div style="width:800px;">
	<div id="ne" style="float:left;width:70%;">
	<?=$re[2]?>
	</div>
	<div style="float:right;width:30%;text-align:center;">
	<input type="button" class="buttonb" value="선택추가" onclick="assign();" style="height:36px; width:100px;margin:20px;" ></div>
	</div>
</div>
<?}?>
