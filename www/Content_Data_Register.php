<? include "Axis_Header.php";


if(!is_array($_SESSION["TMP_CORVER"])) $_SESSION["TMP_CORVER"]=array();
if(!is_array($_SESSION["TMP_DOCUMENT"])) $_SESSION["TMP_DOCUMENT"]=array();
if(!is_array($_SESSION["TMP_ATTECH"])) $_SESSION["TMP_ATTECH"]=array();


if($_FILES["FILE_CORVER"]["tmp_name"]){
		for($i=0; $i <sizeof($_FILES["FILE_CORVER"]["tmp_name"]); $i++){
			if($_FILES["FILE_CORVER"]["tmp_name"][$i]){
				$file_corver=uploadFile($Mem->data["temp"],$_FILES["FILE_CORVER"]["tmp_name"][$i],$_FILES["FILE_CORVER"]["name"][$i]);
				if(file_exists($Mem->data["temp"].$file_corver)){
					array_push($_SESSION["TMP_CORVER"],array("name"=>$_FILES["FILE_CORVER"]["name"][$i],"file"=>$file_corver,"size"=>$_FILES["FILE_CORVER"]["size"][$i],"ext"=>$_FILES["FILE_CORVER"]["type"][$i]));
				}
			}
		}
}



if($_FILES["FILE_DOCUMENT"]["tmp_name"]){
		for($i=0; $i <sizeof($_FILES["FILE_DOCUMENT"]["tmp_name"]); $i++){
			if($_FILES["FILE_DOCUMENT"]["tmp_name"][$i]){
				$file_corver=uploadFile($Mem->data["temp"],$_FILES["FILE_DOCUMENT"]["tmp_name"][$i],$_FILES["FILE_DOCUMENT"]["name"][$i]);
				if(file_exists($Mem->data["temp"].$file_corver)){
					array_push($_SESSION["TMP_DOCUMENT"],array("name"=>$_FILES["FILE_DOCUMENT"]["name"][$i],"file"=>$file_corver,"size"=>$_FILES["FILE_DOCUMENT"]["size"][$i],"ext"=>$_FILES["FILE_DOCUMENT"]["type"][$i]));
				}
			}
		}
}


if($_FILES["FILE_ATTECH"]["tmp_name"]){
		for($i=0; $i <sizeof($_FILES["FILE_ATTECH"]["tmp_name"]); $i++){
			if($_FILES["FILE_ATTECH"]["tmp_name"][$i]){
				$file_corver=uploadFile($Mem->data["temp"],$_FILES["FILE_ATTECH"]["tmp_name"][$i],$_FILES["FILE_ATTECH"]["name"][$i]);
				if(file_exists($Mem->data["temp"].$file_corver)){
					array_push($_SESSION["TMP_ATTECH"],array("name"=>$_FILES["FILE_ATTECH"]["name"][$i],"file"=>$file_corver,"size"=>$_FILES["FILE_ATTECH"]["size"][$i],"ext"=>$_FILES["FILE_ATTECH"]["type"][$i]));
				}
			}
		}
}

if(sizeof($_POST)){
print_r($_POST);

if($_POST["DC_DT_COLLECT"])$_POST["DC_DT_COLLECT"]=datec($_POST["DC_DT_COLLECT"]);
if($_POST["DC_DT_WRITE"])$_POST["DC_DT_WRITE"]=datec($_POST["DC_DT_WRITE"]);

$_POST["UID"]=$Mem->user["uid"];

$_POST["DT_REGI"];

$_POST["PID"]=$Mem->insertId();


for($i=0; $i < sizeof($_SESSION["TMP_CORVER"]); $i++){
	$Mem->q("insert into nt_document_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT) values(?,?,?,?,?,?,?) ",array($_POST["PID"],$_SESSION["TMP_CORVER"][$i]["name"],$_SESSION["TMP_CORVER"][$i]["file"],1,$_SESSION["TMP_CORVER"][$i]["ext"],$_SESSION["TMP_CORVER"][$i]["size"],mktime()));
 }

for($i=0; $i < sizeof($_SESSION["TMP_DOCUMENT"]); $i++){
	$Mem->q("insert into nt_document_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT) values(?,?,?,?,?,?,?) ",array($_POST["PID"],$_SESSION["TMP_DOCUMENT"][$i]["name"],$_SESSION["TMP_DOCUMENT"][$i]["file"],2,$_SESSION["TMP_DOCUMENT"][$i]["ext"],$_SESSION["TMP_DOCUMENT"][$i]["size"],mktime()));
}

for($i=0; $i < sizeof($_SESSION["TMP_ATTECH"]); $i++){
 	$Mem->q("insert into nt_document_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT) values(?,?,?,?,?,?,?) ",array($_POST["PID"],$_SESSION["TMP_ATTECH"][$i]["name"],$_SESSION["TMP_ATTECH"][$i]["file"],3,$_SESSION["TMP_ATTECH"][$i]["ext"],$_SESSION["TMP_ATTECH"][$i]["size"],mktime()));
}










exit;
}




?>


<script type="text/javascript" src="./js/HuskyEZCreator.js" charset="utf-8"></script>

<div>


	<div class="title1x">데이터 등록진행</div>
	<div>
		<form action="<?=SELF?>" method="post" onsubmits="return check();" >
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;" class="table_view" >

			<tr>
				<th>원제목</th>
				<td colspan="3" ><input type="text" class="input_cell" name="DC_TITLE_OR" id=""></td>
			</tr>

			<tr>
				<th>한글제목</th>
				<td colspan="3" ><input type="text" class="input_cell" name="DC_TITLE_KR" id=""></td>
			</tr>
			<tr>
				<th>한글요약</th>
				<td colspan="3" ><input type="text" class="input_cell" name="DC_SMRY_KR" id=""></td>
			</tr>
			<tr>
				<th>국가선택<br> <input type="button" class='button1' value="설정" onclick="register_country_select();"><input type="button" class='button1' value="초기화" onclick="$('#country_select_list').empty();"></th>
				<td colspan="3" ><div id="country_select_list" ></div></td>
			</tr>
			<tr>
				<th>분류선택 <br><input type="button" class='button1' value="변경" onclick="register_category_select();"><input type="button" class='button1' value="초기화" onclick="$('#category_select_list').empty();"></th>
				<td colspan="3" ><div id="category_select_list" ><input type="hidden" name="DC_CODE" id="DC_CODE"></div></td>
			</tr>
			<tr>
				<th>수집일시</th>
				<td><input type="text" class="input_cell" placeholder="0000-00-00" name="DC_DT_COLLECT" id="DC_DT_COLLECT"></td>
				<th>작성일</th>
				<td><input type="text" class="input_cell"  placeholder="0000-00-00" name="DC_DT_WRITE" id="DC_DT_WRITE"  ></td>
			</tr>
			<tr>
				<th>문서위치URL</th>
				<td><input type="text" class="input_cell"  name="DC_URL_LOC" id="DC_URL_LOC"  ></td>
				<th>문서안내URL</th>
				<td><input type="text" class="input_cell"  name="DC_URL_EXP" id="DC_URL_EXP"  ></td>
			</tr>

			<tr>
				<th>구분</th>
				<td><input type="text" class="input_cell"  name="DC_TYPE" id="DC_TYPE"  ></td>
				<th>페이지수</th>
				<td><input type="text" class="input_cell"  name="DC_PAGE" id="DC_PAGE"  ></td>
			</tr>
			<tr>
				<th>기관명</th>
				<td><input type="text" class="input_cell"  name="DC_AGENCY" id="DC_AGENCY"  ></td>
				<th>검색키워드</th>
				<td><input type="text" class="input_cell"  name="DC_KEYWORD" id="DC_KEYWORD"  ></td>
			</tr>

			<tr>
				<th>내용</th>
				<td colspan="3" >

					<textarea style="height:100px;" name="DC_CONTENT" id="DC_CONTENT" style="display:none;" class="input_cell" ></textarea>

				</td>
			</tr>
			<tr>
				<th>표지파일<br><input id="cover" type="button" class="button1" value="파일추가" onclick="$('#file_corver_upload').click();" ><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=1&reset=1','file_corver_list');$('#cover').css('visibility','visible');" ></th>
				<td colspan="3" > <div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="" multiple style="display:none;"  onchange="file_corver_add();" ></td>
			</tr>
			<tr>
				<th>첨부문서<br><input type="button" class="button1" value="파일추가" onclick="$('#file_document_upload').click();;"><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=2&reset=1','file_document_list');" ></th>
				<td colspan="3" > <div id="file_document_list"></div> <input type="file" id="file_document_upload" name="" multiple  style="display:none;" onchange="file_document_add();" ></td>
			</tr>
			<!--
			<tr>
				<th>참조파일<br><input type="button" class="button1" value="파일추가" onclick="$('#file_attech_upload').click();"><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=3&reset=1','file_attech_list');" ></th>
				<td colspan="3" > <div id="file_attech_list"></div> <input type="file" id="file_attech_upload" name="" multiple style="display:none;"  onchange="file_attech_add();" ></td>
			</tr>
			-->




		</table>



	<div style="text-align:right;padding:10px;text-align:center;" >

	<input type="submit" class="buttonb" value="등록" style="height:40px; width:200px;">



	</div>

	</form>

	</div>
</div>
<script>


function register_country_select(){
	//Dialog('Content_Data_Register_Set_Country.php',600,400);
	Dialog('Content_Data_Country.php',600,700);
}


function register_category_select(){
	Dialog('Content_Data_Search.php?category=1',800,255);
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
				},
				complete: function(result){
					$('#cover').css('visibility','hidden');
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

/*
getup('Content_Data_Register_File_List.php?type=1','file_corver_list');
getup('Content_Data_Register_File_List.php?type=2','file_document_list');
getup('Content_Data_Register_File_List.php?type=3','file_attech_list');


getup('Content_Data_Register_Select.php','country_select_list');
getup('Content_Data_Register_Sel_Category.php','category_select_list');
*/


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
<!--<script type="text/javascript" src="NaverEditor/js/service/HuskyEZCreator.js" charset="utf-8"></script>
-->
<script type="text/javascript">
var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: document.getElementById("DC_CONTENT"),
	sSkinURI: "NaverEditor/SmartEditor2Skin.html",
	htParams : {bUseToolbar : true,
		fOnBeforeUnload : function(){
			//alert("아싸!");
		}
	}, //boolean
	fOnAppLoad : function(){
		//예제 코드
		//oEditors.getById["DC_CONTENT"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
	},
	fCreator: "createSEditor2"
});

function pasteHTML() {
	var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
	oEditors.getById["DC_CONTENT"].exec("PASTE_HTML", [sHTML]);
}

function showHTML() {
	var sHTML = oEditors.getById["DC_CONTENT"].getIR();
	alert(sHTML);
}

function submitContents(elClickedObj) {
	oEditors.getById["DC_CONTENT"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

	// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("DC_CONTENT").value를 이용해서 처리하면 됩니다.

	try {
		elClickedObj.form.submit();
	} catch(e) {}
}

function setDefaultFont() {
	var sDefaultFont = '궁서';
	var nFontSize = 24;
	oEditors.getById["DC_CONTENT"].setDefaultFont(sDefaultFont, nFontSize);
}
</script>
