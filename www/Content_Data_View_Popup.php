<? 

$Close=true;
include "_head.php";
 

 $r=$Mem->qr("Select * from nt_document_list where IDX=? ",$_GET["PID"]);

 

$Mem->q("update nt_document_list set DC_HIT=? where IDX=? ",array($r["DC_HIT"]+1,$r["IDX"]));
$Mem->q("insert into nt_document_access_list(UID,PID,DT) values(?,?,?) ",array($Mem->user["uid"],$r["IDX"],mktime()));

?>
<div>


	<div class="title1"><?=$r["DC_TITLE_OR"]?></div>
	<div class="line1" ></div>
	<div>
		<form action="<?=SELF?>" method="post" onsubmits="return check();"" >
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;" class="table_view3" >
		<colgroup>
			<col style="width:100px;" >
			<col style="width:350px;" >
			<col style="width:100px;" >
			<col>
		</colgroup>
			<tr>
				<th>원제목</th>
				<td colspan="3" ><?=$r["DC_TITLE_OR"]?></td> 
			</tr>
			
			<tr>
				<th>한글제목</th>
				<td colspan="3" ><?=$r["DC_TITLE_KR"]?>  </td> 
			</tr> 
			<tr>
				<th>한글요약</th>
				<td colspan="3" style="line-height:40px;"><?=$r["DC_SMRY_KR"]?> </td> 
			</tr> 
			<tr>
				<th>국가</th>
				<td colspan="3" >
<? 
$QC=$Mem->q("select a.* from nt_country_list a, nt_document_country_list b where b.PID=? and b.TID=a.IDX  ",$r["IDX"]);

while($rs=$QC->fetch()){
echo "<div class='list_type1'>".$rs["COUNTRY_NM"]."</div>";
 
}
?>

				</td> 
			</tr>
			<tr>
				<th>분류 </th>
				<td colspan="3" >
<? 
$QC=$Mem->q("select b.* from nt_document_category_list a, nt_category_list b where b.IDX=a.CID and a.PID=?  ",$r["IDX"]);

while($rs=$QC->fetch()){ 
echo "<div class='list_type1'>";

echo $Mem->qs("Select CT_NM from nt_category_list where length(CODE)=2 and STAT < 9 and CODE=? ",substr($rs["CODE"],0,2));
	if(strlen($rs["CODE"])>=4) echo " >> ";

	echo $Mem->qs("Select CT_NM from nt_category_list where length(CODE)=4 and STAT < 9 and CODE=? ",substr($rs["CODE"],0,4));
	if(strlen($rs["CODE"])>=6) echo " >> ";

	echo $Mem->qs("Select CT_NM from nt_category_list where length(CODE)=6 and STAT < 9 and CODE=? ",substr($rs["CODE"],0,6));

echo "</div>";

 

}
?>
				</td> 
			</tr>
			<tr>
				<th>수집일시</th>
				<td><?=$r["DC_DT_COLLECT"]?date("Y-m-d",$r["DC_DT_COLLECT"]):""?></td>
				<th>작성일</th>
				<td><?=$r["DC_DT_WRITE"]?date("Y-m-d",$r["DC_DT_WRITE"]):""?> </td>
			</tr>
			<tr>
				<th>문서위치URL</th>
				<td><?=url_auto_link($r["DC_URL_LOC"],true)?></td>
				<th>문서안내URL</th>
				<td><?=url_auto_link($r["DC_URL_EXP"],true)?></td>
			</tr>

			<tr>
				<th>구분</th>
				<td><?=$r["DC_TYPE"]?> </td> 
				<th>페이지수</th>
				<td><?=$r["DC_PAGE"]?></td> 
			</tr>
			<tr>
				<th>기관명</th>
				<td><?=$r["DC_AGENCY"]?></td> 
				<th>검색키워드</th>
				<td><?=$r["DC_KEYWORD"]?></td> 
			</tr>
 
			<tr>
				<th>내용</th>
				<td colspan="3" >
					<?=$r["DC_CONTENT"]?>

				</td> 
			</tr>
			<tr>
				<th>표지파일</th>
				<td colspan="3" > 

				<? 
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
				<th>첨부문서</th>
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
				<th>참조파일</th>
				<td colspan="3" >
					

				<? 
				$QS=$Mem->q("select * from nt_document_file_list where PID=? and FILE_TYPE=3  and STAT < 9 ",$r["IDX"]);
				while($rs=$QS->fetch()){

					if(is_image($Mem->data["temp"].$rs["FILE_PATH"])){
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data_url["temp"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"]."</div>";
					}else{
						echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"]."</div>";
					}
				 } ?>

				</td> 
			</tr>





		</table>

	<div class="line1" ></div>

		
	<div style="text-align:right;padding:10px;text-align:center;" >
	<? if($Mem->class>=0){ ?>

	<input type="button" class="button1" value="삭제하기" style="height:40px; width:200px;" onclick="window.close();">	
	 
	<input type="button" class="button1" value="수정하기" style="height:40px; width:200px;" onclick="go('Content_Data_Modify.php?PID=<?=$r["IDX"]?>');">	
<? } ?>
	<input type="button" class="button1" value="인쇄하기" style="height:40px; width:200px;" onclick="window.print();">	
	<input type="button" class="buttonb" value="창닫기" style="height:40px; width:200px;" onclick="window.close();">	
	

	
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