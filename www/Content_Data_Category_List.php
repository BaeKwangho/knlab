<?
/* 사용처
1. Content_Data_Search.php / 'category_list1' - 주제 분류 테이블 로딩, 초기화
*/

include "_h.php";

//if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"]; 
//if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;

if($_GET["RESET"]) unset($_SESSION["SET_CATEGORY_SEARCH"][$_GET["TYPES"]]);

?> 
 <div>
	
 
	<div class="clear"></div>
	<div >
		<table class="table_info">
			<th>카테고리이름</th> 
			<th>카테고리이름</th> 
			<th>카테고리이름</th>
		</table>
	</div>
	<div > 
		<div style="float:left; width: calc( 33.33% - 2px );height:200px;border-right:solid 2px #AAA; overflow-y:scroll;" id="cid1<?=$_GET["TYPES"]?>" ></div> 
		<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; height:200px;overflow-y:scroll;"  id="cid2<?=$_GET["TYPES"]?>"  ></div> 
		<div style="float:left; width:33.33%;height:200px;overflow-y:scroll;" id="cid3<?=$_GET["TYPES"]?>" ></div>
		<input type="hidden" id="ITEM" name="ITEM" value="<?=$_GET["CID"]?>">
	</div>
	
</div>
	<div class="clear"></div> 
<script>
	//카테고리 리스트 띄우기
	getup('Content_Config_Category_Load_Data.php?type=cid1&TYPES=<?=$_GET["TYPES"]?>','cid1<?=$_GET["TYPES"]?>');
	getup('Content_Config_Category_Load_Data.php?dummy=1&TYPES=<?=$_GET["TYPES"]?>','cid2<?=$_GET["TYPES"]?>');
	getup('Content_Config_Category_Load_Data.php?dummy=2&TYPES=<?=$_GET["TYPES"]?>','cid3<?=$_GET["TYPES"]?>');
</script>