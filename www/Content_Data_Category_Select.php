<? 
if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"]; 
if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;


?>
<div class="title1">
	<? 
	
switch($_SESSION["CTYPE"]){
case 1:
	echo "주제분류";
	break;
case 2:
	echo "유형분류";
	break;
case 3:
	echo "국가분류";
	break;
 }
?> 선택
</div>
 <div>
	
	<div > 
		<div style="float:left; width: calc( 33.33% - 2px );;border-right:solid 2px #AAA; " id="" ><div class="title2">대분류 </div></div> 
		<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; ;"  id=""><div class="title2">중분류 </div></div> 
		<div style="float:left; width:33.33%;;" id=""><div class="title2">소분류 </div></div> 
	</div>


	<div class="clear"></div>

	<div > 
		<div style="float:left; width: calc( 33.33% - 2px );height:500px;border-right:solid 2px #AAA; " id="cid1" ></div> 
		<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; height:500px;"  id="cid2"  ></div> 
		<div style="float:left; width:33.33%;height:500px;" id="cid3" ></div>  
	</div>
	  

</div>

	<div class="clear"></div>
	<div>
		<input type="button" class='button1' value="창닫기" onclick="register_category_select_refresh(); DialogHides();">
	
	</div>
<script>
	

			getup('Content_Config_Category_Load_Sel.php?type=cid1&CTYPE=<?=$_GET["CTYPE"]?>','cid1');
			getup('Content_Config_Category_Load_Sel.php?dummy=1','cid2');
			getup('Content_Config_Category_Load_Sel.php?dummy=2','cid3');

</script>