<? include "Axis_Header.php"; 

if($_GET["remove"]){
$Mem->q("update nt_user_list set STAT=9 where IDX=? ",array($_GET["remove"]));
mvs(SELF);
}
?>

<div class="round_box1" >
<div class="title1" >사용자 관리</div>

	
	<div>
		<table cellpadding="0" cellspacing="0" border="0"  class="table_info" >
			<tr>
				<th>순번</th>
				<th>사용자 ID</th>
				<th>이름</th>
				<th>소속</th>
				<th>직위</th>
				<th>이메일</th>
				<th>연락처</th>
				<th>최근로그인</th>
				<th>주제분류</th>
				<!--<th>유형분류</th>
				<th>국가분류</th>-->
				<th>설정</th>
			</tr>

			<? $n=0;	$Q=$Mem->q("select * from nt_user_list where IDX > 0 and STAT  < 9 "); while($r=$Q->fetch()){ $n++; ?>
<tr>
	<td><?=$n?></td>
	<td><?=$r["USER_ID"]?></td>
	<td><?=$r["USER_NAME"]?></td>
	<td><?=$r["USER_COMPANY"]?></td>
	<td><?=$r["USER_POSITION"]?></td>
	<td><?=$r["USER_EMAIL"]?></td>
	<td><?=$r["USER_TEL"]?></td>
	<td><?=$r["DT_LG"]?date("Y-m-d H:i:s",$r["DT_LG"]):"-"?></td>
	<td><input type="button" class='button1' value="<?=$Mem->qs("select count(*) from nt_categorys_auth_list where UID=? and TYPE=1 ",$r["IDX"])?$Mem->qs("select count(*) from nt_categorys_auth_list where UID=? and TYPE=1 ",$r["IDX"])."개 적용":"전체적용"?>" onclick="user_category_select(1,<?=$r["IDX"]?>);" ></td>
	<!--<td><input type="button" class='button1' value="<?=$Mem->qs("select count(*) from nt_categorys_auth_list where UID=? and TYPE=2 ",$r["IDX"])?$Mem->qs("select count(*) from nt_categorys_auth_list where UID=? and TYPE=2 ",$r["IDX"])."개 적용":"전체적용"?>" onclick="user_category_select(2,<?=$r["IDX"]?>);" ></td>
	<td><input type="button" class='button1' value="<?=$Mem->qs("select count(*) from nt_categorys_auth_list where UID=? and TYPE=3 ",$r["IDX"])?$Mem->qs("select count(*) from nt_categorys_auth_list where UID=? and TYPE=3 ",$r["IDX"])."개 적용":"전체적용"?>" onclick="user_category_select(3,<?=$r["IDX"]?>);" ></td>-->
	<td><input type="button" class="button1" value="삭제" onclick="if(confirm('이 사용자를 삭제하시겠습니까?')){	go('<?=SELF?>?remove=<?=$r["IDX"]?>'); }"> <input type="button" class="button1" value="수정" onclick="Dialog('Content_User_Modify.php?PID=<?=$r["IDX"]?>',500,300);"></td>
</tr>
				<? } ?>
		</table>

<input type="button" class="buttonb" value="사용자 등록" onclick="Dialog('Content_User_Register.php',500,300);" >
	</div>

</div>


<script>
	
function user_category_select(num,uid){
	Dialog('Content_User_Category_Select.php?CTYPE='+num+'&UID='+uid,700,650);
}


</script>