<? include "_h.php"; 



if(!is_array($_SESSION["SET_CATEGORY_SEARCH"])) $_SESSION["SET_CATEGORY_SEARCH"]=array();
if(!is_Array($_SESSION["SET_CATEGORY_SEARCH"][$_GET["TYPES"]])) $_SESSION["SET_CATEGORY_SEARCH"][$_GET["TYPES"]]=array();

if($_GET["SET_ID"]&&$_GET["SET_TYPE"]){
	$_SESSION["SET_CATEGORY_SEARCH"][$_GET["TYPES"]][$_GET["SET_ID"]]=($_GET["SET_TYPE"]=="true"?1:0);
}
 

if(!$_GET["dummy"]){
if(strlen($_GET["CID"])==0){
?>  
			<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" id="CLD" >
								<tr> 
					
					</tr>
				<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=2 and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",$_GET["TYPES"]); while($r=$Q->fetch()){ $n++; ?>

				<tr> 
					<td   class="<?=substr($_GET["CID"],0,2)==$r["CODE"]?"uid":""?>"  style="cursor:pointer;" onclick="getup('<?=SELF?>?CID=<?=$r["CODE"]?>&TYPES=<?=$_GET["TYPES"]?>','cid2<?=$_GET["TYPES"]?>'); 
					getup('<?=SELF?>?CID=<?=$r["CODE"]?>&TYPES=<?=$_GET["TYPES"]?>&dummy=1','cid3<?=$_GET["TYPES"]?>');click_color(this);$('#ITEM').val('<?=$r["CODE"]?>');">
					<!--  $(this).css('background-color', 'rgb(137, 198, 255)');-->

				<?=$r["CT_NM"]?></td>
				
				</tr>

				<? } ?>
			</table> 

<? }
				
if(strlen($_GET["CID"])==2){
				
?>

			<? if(strlen($_GET["CID"])>=2){ ?> 
			<? }	 ?>

					<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" id="CLD" >
					<tr> 
					
					</tr>
			<? if(strlen($_GET["CID"])>=2){ ?>


						<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=4 and  CODE like ?  and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",array(substr($_GET["CID"],0,2)."%",$_GET["TYPES"])); 
						if($Q->rowCount()==0){
						?> 
						<tr>
							<td colspan="3"  style="height:200px;" >등록된 카테고리가 없습니다.</td>
						</tr>
							<?
						}
						while($r=$Q->fetch()){ $n++; ?>


						<tr> 
							<td  class="<?=substr($_GET["CID"],0,4)==$r["CODE"]?"uid":""?>" style="cursor:pointer;" onclick="getup('<?=SELF?>?CID=<?=$r["CODE"]?>&TYPES=<?=$_GET["TYPES"]?>','cid3<?=$_GET["TYPES"]?>');
							click_color(this);$('#ITEM').val('<?=$r["CODE"]?>');"<?=$_SESSION["SET_CATEGORY_SEARCH"][$_GET["TYPES"]][$r["IDX"]]?"active":""?>>

						<?=$r["CT_NM"]?></td>

						</tr>

						<? } ?>
			<? }else{ ?>
					
					
						<tr>
							<td colspan="3"  style="height:200px;" >대분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table> 


	<? } 
	
	
if(strlen($_GET["CID"])==4){
	?>

					<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" id="CLD" >
					<tr> 
					 
					</tr>
			<? if(strlen($_GET["CID"])>=4){ ?> 

						<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=6 and  CODE like ?  and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",array(substr($_GET["CID"],0,4)."%",$_GET["TYPES"])); 
						if($Q->rowCount()==0){
						?> 
						<tr>
							<td colspan="3"  style="height:200px;" >등록된 카테고리가 없습니다.</td>
						</tr>
						<?
						}
						while($r=$Q->fetch()){ $n++; ?>  
						<tr>
							<td  class="<?=substr($_GET["CID"],0,6)==$r["CODE"]?"uid":""?>" onclick="click_color(this);$('#ITEM').val('<?=$r["CODE"]?>');"  style="cursor:pointer;" >

							<?=$r["CT_NM"]?></td>

						</tr>

						<? } ?>
			<? }else{ 
				?>
					
					
						<tr>
							<td colspan="3"  style="height:200px;" >중분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table>


		<? 
		}
	
		
}else{  ?> 
	<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
						<tr> 
			
			</tr>
		</table>
	<? } ?>

<script>
	function click_color($cur){
		$($cur).closest('table').children('tbody').children('tr').children('td').css('background-color', 'white');
		$($cur).css('background-color', 'rgb(137, 198, 255)');
	}
</script>