<? 

include "_h.php"; 
if($_GET["PID"]){
$row=$Mem->qr("select * from nt_categorys where IDX=? and STAT < 9 ",$_GET["PID"]);
}
 
 if($_POST["PID"]&&$_POST["CT_NM"]){
	$Mem->q("update nt_categorys set CT_NM=? where IDX=? ",array($_POST["CT_NM"],$_POST["PID"]));

 }



?>
<div>
	<div class='title1'>카테고리 이름수정</div>
	<div style="text-align:center; margin-top:10px;">
	<form action="" name="modify_country" id="modify_country" onsubmit="country_modify(); return false;" >
	<input type="hidden" name="PID" value="<?=$_GET["PID"]?>">
		<input type="text" class='input_text1' name="CT_NM" id="CT_NM"  style="width:150px;font-size:20px;"  value="<?=$row["CT_NM"]?>">
		<div style="text-align:center;">
			<input type="button" class="button1" value="창닫기" style="width:80px; height:40px;" onclick="DialogHides();">
			<input type="button" class="buttonb" value="수정" style="width:80px; height:40px;" onclick="country_modify();" >
		</div>

	</form>

	</div>
</div>



<script>
function country_modify(){
	  

         $.ajax({
            url: "<?=SELF?>", // 목적지
            type: "POST", // POST형식으로 폼 전송
	        timeout: 20000,
			data:$("#modify_country").serialize(),
            success: function(data, textStatus, jqXHR) { 
			//	Dialog('/Config_Gallery_Category.php',400,600);
			//location.reload();
			location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
					msg("전송에 실패했습니다");
            }
        });  
		
}

</script>

