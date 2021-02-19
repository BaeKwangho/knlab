<?
include "../_h_img.php";
error_reporting(E_ALL);	ini_set("display_errors", 1);
if($_GET["err_msg"]){
	$msg = $_GET["err_msg"];
}else{
	$msg = "알 수 없는 오류 발생";
}

?>

<div class="c5">
	<div class="logo">
		<img src="/images/logo3.jpg" style="height:120px;" onclick="" style=";">
	</div>
	<div class="bar">
		<?=$msg?>
		<span id="msg"></span>
    </div>
</div>


<script>

if(document.referrer){	
	$('#msg').html("3초 후 이전 페이지로 돌아갑니다.");
	setTimeout(() => {
		window.history.back();
	}, 3000);
}else{
	$('#msg').html("3초 후 종료됩니다.");
	setTimeout(() => {
		close();
	}, 3000);
}


</script>