<? 
include "_h.php"; 
 

 if(Sizeof($_POST)){
	
	if($_POST["USER_PASSWD1"]&&$_POST["USER_PASSWD2"]&&$_POST["USER_PASSWD1"]==$_POST["USER_PASSWD2"]){

		$_POST["USER_PASSWD"]=$Mem->qs("Select password(?) ",array($_POST["USER_PASSWD1"]));
		$Mem->q("update nt_user_list set USER_PASSWD=? where IDX=?",array($_POST["USER_PASSWD"],$_POST["PID"]));
	}

$Mem->q("update nt_user_list set  USER_CLASS=:USER_CLASS,  USER_COMPANY=:USER_COMPANY, USER_POSITION=:USER_POSITION, USER_TEL=:USER_TEL, USER_EMAIL=:USER_EMAIL,  USER_NAME=:USER_NAME where IDX=:IDX  ", array(":IDX"=>$_POST["PID"],":USER_CLASS"=>$_POST["USER_CLASS"],":USER_COMPANY"=>$_POST["USER_COMPANY"],":USER_POSITION"=>$_POST["USER_POSITION"],":USER_TEL"=>$_POST["USER_TEL"],":USER_EMAIL"=>$_POST["USER_EMAIL"],":USER_NAME"=>$_POST["USER_NAME"]));

//$Mem->insertPost("nt_user_list");
exit;
 }
 

$r=$Mem->qr("Select * from nt_user_list where IDX=? ",$_GET["PID"]);


?>
<div>
	<div class='box_title1'>사용자 등록</div>
	<div style="text-align:center; margin-top:10px;">
	<form action="" name="user_add_form" id="user_add_form" onsubmit="country_modify(); return false;" >
	<input type="hidden" name="PID" value="<?=$_GET["PID"]?>">
<table cellpadding="0" cellspacing="0" border="0"  class="table_view" >
	<tr>		
		<th>사용자 ID</th>
		<td colspan="3" class="uid"><?=$r["USER_ID"]?></td>
	</tr>
	<Tr>
		<th>비밀번호</th>
		<td><input type="password" class="input_cell" name="USER_PASSWD1" id="USER_PASSWD1"   ></td>
		<th>비밀번호 재입력</th>
		<td><input type="password" class="input_cell" name="USER_PASSWD2" id="USER_PASSWD2"  ></td>
	</tr>
	
	<tr>
		<th>사용자유형</th>
		<td>
			
			
		<label  ><input type="radio" name="USER_CLASS" value="1" <?=$r["USER_CLASS"]==1?"checked":""?>  >일반사용자</label>
<label  ><input type="radio" name="USER_CLASS" value="8" <?=$r["USER_CLASS"]==9?"checked":""?>  >작업자</label>
<label  ><input type="radio" name="USER_CLASS" value="9" <?=$r["USER_CLASS"]==9?"checked":""?>  >관리자</label>

		</td>
		<th>사용자이름</th>
		<td><input type="text" class="input_cell" name="USER_NAME" value="<?=$r["USER_NAME"]?>" ></td>
	</tr>
	<Tr>
		<th>소속</th>
		<td><input type="text" class="input_cell" name="USER_COMPANY"  value="<?=$r["USER_COMPANY"]?>" ></td>
		<th>직위</th>
		<td><input type="text" class="input_cell" name="USER_POSITION"  value="<?=$r["USER_POSITION"]?>" ></td>
	</tr>
	<Tr>
		<th>이메일주소</th>
		<td><input type="text" class="input_cell" name="USER_EMAIL"  value="<?=$r["USER_EMAIL"]?>" ></td>
		<th>연락처</th>
		<td><input type="text" class="input_cell" name="USER_TEL"  value="<?=$r["USER_TEL"]?>" ></td>
	</tr>
</table>

<div style="text-align:center;padding:10px;" >
<input type="button" class='button1' value='창닫기'  style="height:40px; width:100px;" onclick="DialogHides();" >
<input type="button" class='buttonb' value='사용자정보수정'  style="height:40px; width:100px;" onclick="user_modify();">
</div>
	</form>

	</div>
</div>



<script>
function user_modify(){
	  

         $.ajax({
            url: "<?=SELF?>", // 목적지
            type: "POST", // POST형식으로 폼 전송
	        timeout: 20000,
			data:$("#user_add_form").serialize(),
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

