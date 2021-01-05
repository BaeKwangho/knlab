<? include "_h.php"; 

if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"]; 
if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;


if(!is_array($_SESSION["SET_CATEGORY"])) $_SESSION["SET_CATEGORY"]=array();
if(!is_Array($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]])) $_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]]=array();

if($_GET["SET_ID"]&&$_GET["SET_TYPE"]){
	$_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]][$_GET["SET_ID"]]=($_GET["SET_TYPE"]=="true"?1:0);
}


//print_R($_SESSION["SET_CATEGORY"]);


if(!$_GET["dummy"]){
if(strlen($_GET["CID"])==0){
?> 
			<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
								<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th> 
					</tr>
				<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=2 and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",$_SESSION["CTYPE"]); while($r=$Q->fetch()){ $n++; ?>


				<tr>
					<td><?=$n?></td>
					<td   class="<?=substr($_GET["CID"],0,2)==$r["CODE"]?"uid":""?>"  style="cursor:pointer;" onclick="getup('<?=SELF?>?CID=<?=$r["CODE"]?>','cid2'); getup('<?=SELF?>?CID=<?=$r["CODE"]?>&dummy=1','cid3');">
<input type="checkbox" style="" name="" onclick="setup('<?=SELF?>?SET_ID=<?=$r["IDX"]?>&SET_TYPE='+this.checked);"  <?=$_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]][$r["IDX"]]?"checked":""?> >
				<?=$r["CT_NM"]?></td>
				
				</tr>

				<? } ?>
			</table>

					<script>
						$('#CID1_input').focus();
					</script>

<? }
				
if(strlen($_GET["CID"])==2){
				
?>

			<? if(strlen($_GET["CID"])>=2){ ?> 
			<? }	 ?>

					<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
					<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th> 
					</tr>
			<? if(strlen($_GET["CID"])>=2){ ?>


						<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=4 and  CODE like ?  and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",array(substr($_GET["CID"],0,2)."%",$_SESSION["CTYPE"])); 
						if($Q->rowCount()==0){
						?> 
						<tr>
							<td colspan="3"  style="height:200px;" >등록된 카테고리가 없습니다.<Br>중분류 카테고리를 등록해주세요.</td>
						</tr>
							<?
						}
						while($r=$Q->fetch()){ $n++; ?>


						<tr>
							<td><?=$n?></td>
							<td  class="<?=substr($_GET["CID"],0,4)==$r["CODE"]?"uid":""?>" style="cursor:pointer;" onclick="getup('<?=SELF?>?CID=<?=$r["CODE"]?>','cid3');">
<input type="checkbox" style="" name="" onclick="setup('<?=SELF?>?SET_ID=<?=$r["IDX"]?>&SET_TYPE='+this.checked);"  <?=$_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]][$r["IDX"]]?"checked":""?>  >
						<?=$r["CT_NM"]?></td>

						</tr>

						<? } ?>
			<? }else{ ?>
					
					
						<tr>
							<td colspan="3"  style="height:200px;" >대분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table>

					<script>
						$('#CID2_input').focus();
					</script>



	<? } 
	
	
if(strlen($_GET["CID"])==4){
	?>

			<? if(strlen($_GET["CID"])>=4){ ?> 
			<? } ?>
					<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
					<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th> 
					</tr>
			<? if(strlen($_GET["CID"])>=4){ ?> 

						<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=6 and  CODE like ?  and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",array(substr($_GET["CID"],0,4)."%",$_SESSION["CTYPE"])); 
						if($Q->rowCount()==0){
						?> 
						<tr>
							<td colspan="3"  style="height:200px;" >등록된 카테고리가 없습니다.<Br>소분류 카테고리를 등록해주세요.</td>
						</tr>
						<?
						}
						while($r=$Q->fetch()){ $n++; ?>  
						<tr>
							<td><?=$n?></td>
							<td  class="<?=substr($_GET["CID"],0,6)==$r["CODE"]?"uid":""?>"   style="cursor:pointer;"  >
<input type="checkbox" style="" name="" onclick="setup('<?=SELF?>?SET_ID=<?=$r["IDX"]?>&SET_TYPE='+this.checked);"  <?=$_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]][$r["IDX"]]?"checked":""?>  >
						<?=$r["CT_NM"]?></td>

						</tr>

						<? } ?>
			<? }else{ ?>
					
					
						<tr>
							<td colspan="3"  style="height:200px;" >중분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table>


		<? } }else{  ?> 
			<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
								<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th>
						<th style='width:80px;' >설정</th>
					</tr>
				</table>
			<? } ?>