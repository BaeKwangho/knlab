<? include "_h.php";



if($_GET["remove_code"]){	$Mem->q("update nt_categorys set `STAT`=9 where CODE like ? ",$_GET["remove_code"]."%");		 		}
//그러니까... 카테고리 비활성화를 stat=9로 바꿔서 나타낸다는 것


if(!$_GET["dummy"]){
if(strlen($_GET["CID"])==0){
?>

 			<form action="<?=SELF?>" method="post" id="cid_add_form1" onsubmit=" c_add1(); return false;" >
				<input type="text" class="input_text1" id="CID1_input"  name="CID1" >
				<input type="submit" class="buttonb" value="등록">
			</form>
			<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
								<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th>
						<th style='width:80px;' >설정</th>
					</tr>
				<? $n=0; $Q=$Mem->q("Select * from nt_categorys where length(CODE)=2 and TYPE=? and STAT < 9 order by PR asc ,  CT_NM asc ",$_SESSION["CTYPE"]); while($r=$Q->fetch()){ $n++; ?>


				<tr>
					<td><?=$n?></td>
					<td   class="<?=substr($_GET["CID"],0,2)==$r["CODE"]?"uid":""?>"  style="cursor:pointer;" onclick="getup('<?=SELF?>?CID=<?=$r["CODE"]?>','cid2'); getup('<?=SELF?>?CID=<?=$r["CODE"]?>&dummy=1','cid3');"><?=$r["CT_NM"]?></td>
					<td>
						<img src="/images/icon_modify.png" alt="" class="img_icon" onclick="Dialog('Content_Config_Category_Modify.php?PID=<?=$r["IDX"]?>',300,150);" >
						<img src="/images/icon_trash.png" alt="" class="img_icon"  onclick="if(confirm('삭제하시겠습니까?')){ getup('<?=SELF?>?CID=<?=$_GET["CID"]?>&remove_code=<?=$r["CODE"]?>','cid1');		}"  >
					</td>
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
 			<form action="<?=SELF?>" method="post" id="cid_add_form2" onsubmit=" c_add2(); return false;" >
				<input type="hidden" name="CID_PARENT" id="CID_PARENT" value="<?=$_GET["CID"]?>" >
				<input type="text" class="input_text1" id="CID2_input"  name="CID2" >
				<input type="submit" class="buttonb" value="등록">
			</form>
			<? }	 ?>

					<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
					<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th>
						<th style='width:80px;' >설정</th>
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
							<td  class="<?=substr($_GET["CID"],0,4)==$r["CODE"]?"uid":""?>" style="cursor:pointer;" onclick="getup('<?=SELF?>?CID=<?=$r["CODE"]?>','cid3');"><?=$r["CT_NM"]?></td>
              <td>
								<img src="/images/icon_modify.png" alt="" class="img_icon" onclick="Dialog('Content_Config_Category_Modify.php?PID=<?=$r["IDX"]?>',300,150);" >
								<img src="/images/icon_trash.png" alt="" class="img_icon"  onclick="if(confirm('삭제하시겠습니까?')){ getup('<?=SELF?>?CID=<?=$_GET["CID"]?>&remove_code=<?=$r["CODE"]?>','cid2');		}"  >
							</td>
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
 			<form action="<?=SELF?>" method="post" id="cid_add_form3" onsubmit=" c_add3(); return false;" >
				<input type="hidden" name="CID_PARENT" id="CID_PARENT" value="<?=$_GET["CID"]?>" >
				<input type="text" class="input_text1" id="CID3"  name="CID3" >
				<input type="submit" class="buttonb" value="등록">
			</form>
			<? } ?>
					<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
					<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th>
						<th style='width:80px;' >설정</th>
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
							<td  class="<?=substr($_GET["CID"],0,6)==$r["CODE"]?"uid":""?>"   style="cursor:pointer;"  ><?=$r["CT_NM"]?></td>
							<td>
								<img src="/images/icon_modify.png" alt="" class="img_icon" onclick="Dialog('Content_Config_Category_Modify.php?PID=<?=$r["IDX"]?>',300,150);" >
								<img src="/images/icon_trash.png" alt="" class="img_icon"  onclick="if(confirm('삭제하시겠습니까?')){ getup('<?=SELF?>?CID=<?=$_GET["CID"]?>&remove_code=<?=$r["CODE"]?>','cid3');		}"  >
							</td>
						</tr>

						<? } ?>
			<? }else{ ?>


						<tr>
							<td colspan="3"  style="height:200px;" >중분류에서 선택된 항목이 없습니다.</td>
						</tr>
			<? } ?>

					</table>


		<? } }else{  ?>
 			<form action="<?=SELF?>" method="post" onsubmit="return check1();"" >
				<input type="text" class="input_text1" id="CID1"  name="CID1" >
				<input type="submit" class="buttonb" value="등록">
			</form>
			<table cellpadding="0" cellspacing="0" border="0" class="table_info" style="" >
								<tr>
						<th style='width:50px;' >순번</th>
						<th>카테고리이름</th>
						<th style='width:80px;' >설정</th>
					</tr>
				</table>
			<? } ?>
