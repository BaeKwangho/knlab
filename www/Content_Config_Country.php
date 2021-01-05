<? include "_head.php"; 

if(sizeof($_POST)){

if($_POST["country_name"]){

$_POST["DT"]=mktime();
$_POST["COUNTRY_NM"]=$_POST["country_name"];


$Mem->insertPost("nt_country_list");

mvs(SELF);
exit;
}


}


if($_GET["remove"]){

$Mem->q("update nt_country_list set `STAT`=9 where IDX=? ",$_GET["remove"]);

mvs(SELF);
exit;
}

?>

<div>
	<div class="content_title1">국가설정</div>

	<div class="round_box1" >
		

<div>
<table cellpadding="0" cellspacing="0" border="0" class="table_info1" >
<tr>
	<th>순번</th>
	<th>국가명</th>
	<th>등록일</th>
	<th>설정</th>
</tr>
	<? $n=0; $Q=$Mem->q("Select * from nt_country_list where STAT < 9 order by COUNTRY_NM asc "); while($r=$Q->fetch()){ $n++; ?>


	<tr>
		<td><?=$n?></td>
		<td><?=date("Y-m-d H:i",$r["DT"])?></td>
		<td><?=$r["COUNTRY_NM"]?></td>
		<td><input type="button" class="button1" value="수정" onclick="Dialog('Content_Config_Country_Modify.php?PID=<?=$r["IDX"]?>',300,150);"><input type="button" class="button1" value="삭제" onclick="if(confirm('삭제하시겠습니까?')){ go('<?=SELF?>?remove=<?=$r["IDX"]?>');		}" ></td>
	</tr>

	<? } ?>
</table>


</div>

	<div style="text-align:right;padding:10px;" ><form action="<?=SELF?>" method="post" onsubmit="return check();"" >
	
	<input type="text" class="input_text1" id="country_name"  name="country_name" > 
	<input type="submit" class="buttonb" value="등록">	
	

	
	</form>
	
	</div>
	<div>
		

	</div>


	</div>
</div>

<script>
	function check(){
		if($('#country_name').val().length==0){
			alert('국가 이름을 입력해주세요');
			$('#country_name').focus();
			return false;
		}

		return true;

}



			$('#country_name').focus();
</script>