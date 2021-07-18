<?
include "/home/knlab/_Class/Member.php"; $Mem=new Member();
if(!$Mem->user["uid"]){			mvs("Member_Login.php");	exit;		}

?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <link rel="stylesheet" href="/_style.css">

	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="/_script.js" ></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script type="text/javascript" src="/js/treeview.min.js"></script>

  <title>Document</title>
 </head>
 <body>
 <script>
      $(document).ready(function() {
          $('body').bind('cut copy', function(e) {
              e.preventDefault();
            });
        });
    </script>
 <div id="overlay" class="web_dialog_overlay"></div>
 <div id="overlay1" class="web_dialog_overlay1"></div>
<div id="web_dialog" class="web_dialog_content"  ></div>
<div id="web_dialog1" class="web_dialog_content"  ></div>
<? if(isset($_GET["keyword"])){ ?>
	<div style="position:fixed;min-width:1200px;width:100%;background-color:#FFF; height:auto; overflow:hidden;top:0px;right:0px;">
		<div>
			<div class="main_wrap"  >
				<table cellpadding="0" cellspacing="0" border="0"  style="width:100%;">
					<tr>
						<td style="min-width:300px;"><img src="/images/logo3.jpg" alt="" style="height:70px;margin-top:10px;margin-bottom:10px;cursor:pointer;" onclick="go('Image_Search.php')" style=";">	 </td>
						<td style="width:600px;text-align:center;padding-top:10px;" >
							<div class="bar small">
								<form method="get" action="">
									<input class="searchbar small" type="text" title="Search" name="keyword" value="<?=$_GET['keyword']?>">
									<a href="#">
								</form>
							</div>
						</td>
						<td style="font-size:25px; padding:10px;" >
						</td>
						<td style="min-width:300px;text-align:right;">
							<b><?=$Mem->name?></b> 안녕하세요. <input type="button" class="button1" value="로그아웃" onclick="go('Member_Logout.php');">
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div style="background-color:white;height:30px;width:100%;border-bottom:2px solid #c5cbff;">
		</div>
	</div>
<?}?> 