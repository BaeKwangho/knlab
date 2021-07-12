
<?

  $num = include "../_Class/Member.php"; 
  $Mem=new Member();
  //error_reporting(E_ALL);ini_set("display_errors", 1);
  session_destroy();
?>


<!doctype html>

<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title> </title>
  <link rel="stylesheet" href="_style.css">
  <style>

	body, html {
  height: 100%;
}

.bg {
  /* The image used */
  background-image: url("images/login_back.jpg");

  /* Full height */
  height: 100%;

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

 </style>
 </head>
 <body class="bg" >

 <div class="login_box" >
		 <form action="<?=SELF?>" method='post' >
			<div style="width:400px;margin:0 auto;">
					<img src="images/logo4.png" alt="" style="height:100px;" >

				<div style="width:350px; margin:0 auto; ">
					<table cellpadding="0" cellspacing="0" border="0"  class="table_login" style="float:left;">
						<tr>
							<th style="text-align:left;" >아이디</th>
							<td><input type="text" class="input_text1" style="width:120px;" name="USER_ID" value="<?=$_COOKIE["save_id"]?>"  ></td>
						</tr>
						<tr>
							<th style="text-align:left;" >비밀번호</th>
							<td><input type="password" class="input_text1" style="width:120px;" name="USER_PASSWD"></td>
						</tr>
					</table>
					<input type="submit" class="button1" value="로그인" style="height:60px;width:100px;float:left;">
									<div class="clear"></div>
<label  > <input type="checkbox"  name="SET_ID" value="1"  <?=strlen($_COOKIE["save_id"])?"checked":""?>>아이디 저장</label>
				</div>

			</div>

		</form>
    <div>
    <div style="padding: 20px 0px;width:50%;float:left;text-align:center;"><a href="http://knlab.co.kr/?act=info.page&pcode=sub3_1">서비스 소개</a></div> <div style="padding: 20px 5px;width:40%;float:left">powerd by <a href="http://knlab.co.kr">KN Lab.Inc</a></div>
    </div>
 </div>

 </body>
</html>
