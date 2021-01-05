<?
include "_h.php";

if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"];
if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;

if($_GET["UID"]) $_SESSION["SET_UID"]=$_GET["UID"];


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

if($_GET["RESET"]){
$Mem->q("delete from nt_categorys_auth_list where UID=? and TYPE=? ",array($_SESSION["SET_UID"],$_SESSION["CTYPE"]));


}

?> 선택<td style="font-size:14px; color:black;">  상위 분류를 꼭 체크하여야 합니다.</td>
</div>

 <div>

	<div >
		<div style="float:left; width: calc( 33.33% - 2px );;border-right:solid 2px #AAA; " id="" ><div class="title2">대분류 </div></div>
		<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; ;"  id=""><div class="title2">중분류 </div></div>
		<div style="float:left; width:33.33%;;" id=""><div class="title2">소분류 </div></div>
	</div>


	<div class="clear"></div>

	<div >
		<div style="float:left; width: calc( 33.33% - 2px );height:500px;border-right:solid 2px #AAA;overflow-y:scroll; " id="cid1" ></div>
		<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; height:500px;overflow-y:scroll;"  id="cid2"  ></div>
		<div style="float:left; width:33.33%;height:500px;overflow-y:scroll;" id="cid3" ></div>
	</div>


</div>

	<div class="clear"></div>
	<div>
	<div style="text-align:center;" ><input type="button" class='button1' value="창닫기" onclick="location.reload();" style="height:40px; width:100px;"> <input type="button" class='buttonb' value="전체적용(선택초기화)" onclick="setup('<?=SELF?>?RESET=1'); alert('초기화되었습니다.');location.reload();" style="height:40px; width:200px;">  </div>


	</div>
<script>


			getup('Content_Config_Category_Load_User.php?type=cid1&CTYPE=<?=$_GET["CTYPE"]?>','cid1');
			getup('Content_Config_Category_Load_User.php?dummy=1','cid2');
			getup('Content_Config_Category_Load_User.php?dummy=2','cid3');

</script>

<?

?>
