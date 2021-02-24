<?
//error_reporting(E_ALL);	ini_set("display_errors", 1);
include "Axis_Header.php";

 $r=$Mem->qr("Select * from nt_document_list where IDX=? ",$_GET["PID"]);
 $Cname=$Mem->qr("Select CT_NM from nt_categorys where CODE like ? and TYPE=1",$r["DC_CODE"]);

$Mem->q("update nt_document_list set DC_HIT=? where IDX=? ",array($r["DC_HIT"]+1,$r["IDX"]));
$Mem->q("insert into nt_document_access_list(UID,PID,DT) values(?,?,?) ",array($Mem->user["uid"],$r["IDX"],mktime()));

?>

<body onresize="parent.resizeTo(1300,1300)" onload="parent.resizeTo(1300,1300)">

<div style="max-width:1000px; margin:0 auto;">


	<div class="title3"><?=$r["DC_TITLE_KR"]?></div>
	<div class="line1" ></div>
	<div>
		<form action="<?=SELF?>" method="post" onsubmits="return check();" >
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;" class="table_view3" >

			<tr>
        <th>원제목</th>
				<td colspan="3"><?=$r["DC_TITLE_OR"]?></td>
			</tr>

			<tr>
        <th>발행기관</th>
				<td width="400"><?=$r["DC_AGENCY"]?>  </td>
        <th>발 행 일</th>
        <td width="400"><?=date("Y.m.d",$r["DC_DT_WRITE"])?>  </td>
			</tr>
			<tr>
        <th>발행면수</th>
        <td width="400"><?=$r["DC_PAGE"]?>  </td>
        <th>대상국가</th>
        <td width="400"><?=$r["DC_COUNTRY"]?>  </td>
				<!--<td colspan="3" style="line-height:40px;"><?=$r["DC_SMRY_KR"]?> </td>-->
			</tr>
			<tr>
        <th>유형분류</th>
        <td width="400"><?=$r["DC_TYPE"]?>  </td>
        <th>주제분류</th>
        <td width="350"><?=$Cname[0]?>  </td>
		<td>
		</td>
		</tr>

      <tr>
        <th>키워드</th>
        <td colspan="3"><?=$r["DC_KEYWORD"]?>  </td>
      </tr>
      <tr>
        <th>URL</th>
        <td colspan="3"><a href=<?=$r["DC_URL_LOC"]?> target="_blank"><?=$r["DC_URL_LOC"]?>  </a></td>
		</tr>
		<tr>
		<th>한글 요약</th>
			<td>
			<?=$r["DC_SMRY_KR"]?>
			</td>
		</tr>
      <tr>
        <th>본문내용</th>
				<td colspan="3" ><?=$r["DC_CONTENT"]?></td>
			</tr>
			<tr>
				<!--<td colspan="3" >

				<?/*
				$QS=$Mem->q("select * from nt_document_file_list where PID=? and FILE_TYPE=1 and STAT < 9",$r["IDX"]);
				while($rs=$QS->fetch()){

					if(is_image($Mem->data["temp"].$rs["FILE_PATH"])){
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data_url["temp"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"]."</div>";
					}else{
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"]."</div>";
					}
				 } ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" >
<?
				$QS=$Mem->q("select * from nt_document_file_list where PID=? and FILE_TYPE=2 and STAT < 9",$r["IDX"]);
				while($rs=$QS->fetch()){

					if(is_image($Mem->data["temp"].$rs["FILE_PATH"])){
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data_url["temp"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"]."</div>";
					}else{
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"]."</div>";
					}
				 } ?>

				</td>
			</tr>
			<tr>
				<td colspan="3" >


				<?
				$QS=$Mem->q("select * from nt_document_file_list where PID=? and FILE_TYPE=3  and STAT < 9 ",$r["IDX"]);
				while($rs=$QS->fetch()){

					if(is_image($Mem->data["temp"].$rs["FILE_PATH"])){
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data_url["temp"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"]."</div>";
					}else{
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"]."</div>";
					}
				 } */?>

				</td>
			</tr>
				-->
		<tr>
		<th>연관링크</th>
		<td colspan="3">
		<?
			$link=explode(' ',$r["DC_LINK"]);
			if($link[0]){
				for($i=0;$i<count($link);$i++){
					$l=$Mem->qr("select DC_TITLE_KR,DC_DT_WRITE from nt_document_list where idx like ?",$link[$i]);
					
			?>
			<div style="padding:10px;float:left;width:50%-30px;line-height:10px;cursor:pointer;">
			<span onclick="window.open('Content_Data_View.php?PID=<?=$link[$i]?>','data_view','width=900,height=900,scrollbars=1');">
			<?=$l["DC_TITLE_KR"]?> / <?=date("Y-m-d",$l["DC_DT_WRITE"])?>
			</div>
	<?	
				}
			}else{
			}
		?>
		</td>
		</tr>





		</table>

	<div class="line1" ></div>


	<div style="text-align:right;padding:10px;text-align:center;" >
	<? if($Mem->class>=0){ ?>
<!--
	<input type="button" class="button1" value="삭제하기" style="height:40px; width:200px;" onclick="window.close();">

	<input type="button" class="button1" value="수정하기" style="height:40px; width:200px;" onclick="go('Content_Data_Modify.php?PID=<?=$r["IDX"]?>');">	 -->
<? } ?>
<!-- 	<input type="button" class="button1" value="인쇄하기" style="height:40px; width:200px;" onclick="window.print();">	 -->
<!-- 	<input type="button" class="buttonb" value="돌아가기" style="height:40px; width:200px;" onclick="window.close();">

 -->

	</div>

	</form>

	</div>
</div>

<script>

function register_country_select(){
	Dialog('Content_Data_Register_Set_Country.php',600,400);
}


function register_category_select(){
	Dialog('Content_Data_Register_Set_Category.php',900,600);
}


function file_corver_add(){
		var i=0;
		var formData = new FormData();
		var myfiles = document.getElementById("file_corver_upload");
		var files = myfiles.files;

		for (i = 0; i < files.length; i++) {
		formData.append('FILE_CORVER[]' , files[i]);
		}


		$.ajax({
		url: '<?=SELF?>',
				processData: false,
				contentType: false,
				data: formData,
				type: 'POST',
				success: function(result){
					getup('Content_Data_Register_File_List.php?type=1','file_corver_list');
				}
		});
}
function file_document_add(){
		var i=0;
		var formData = new FormData();
		var myfiles = document.getElementById("file_document_upload");
		var files = myfiles.files;

		for (i = 0; i < files.length; i++) {
		formData.append('FILE_DOCUMENT[]' , files[i]);
		}


		$.ajax({
		url: '<?=SELF?>',
				processData: false,
				contentType: false,
				data: formData,
				type: 'POST',
				success: function(result){
					getup('Content_Data_Register_File_List.php?type=2','file_document_list');
				}
		});


}



function file_attech_add(){

		var i=0;
		var formData = new FormData();
		var myfiles = document.getElementById("file_attech_upload");
		var files = myfiles.files;

		for (i = 0; i < files.length; i++) {
		formData.append('FILE_ATTECH[]' , files[i]);
		}


		$.ajax({
		url: '<?=SELF?>',
				processData: false,
				contentType: false,
				data: formData,
				type: 'POST',
				success: function(result){
					getup('Content_Data_Register_File_List.php?type=3','file_attech_list');
				}
		});



}





  $( "#DC_DT_COLLECT" ).datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
    yearSuffix: '년'
  });


  $( "#DC_DT_WRITE" ).datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
    yearSuffix: '년'
  });

</script>
