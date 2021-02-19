<?


$_SESSION["edit"]=true;
include "_h_img.php";
$_SESSION["edit"]=false;

$_GET["keyword"]="";
if(!isset($_GET["item_id"])){
    mvs("components/error.php?err_msg=유효하지 않은 Crawl_Data 번호입니다.");
    exit;
}

//error_reporting(E_ALL);	ini_set("display_errors", 1);
if(sizeof($_POST)){
	if(!isset($_POST["DC_CODE"])){
		mvs("components/error.php?err_msg=CODE가 지정되지 않았습니다.");
		exit;
	}
if($_POST["DC_DT_COLLECT"]){	 $_POST["DC_DT_COLLECT"]=datec($_POST["DC_DT_COLLECT"]);		}else{		$_POST["DC_DT_COLLECT"]=0;	}
if($_POST["DC_DT_WRITE"]){ $_POST["DC_DT_WRITE"]=datec($_POST["DC_DT_WRITE"]); 		}else{		$_POST["DC_DT_WRITE"]=0;	}

$_POST["UID"]=$Mem->user["uid"];
$date = mktime();
$Mem->q("insert into nt_document_list (DC_TITLE_OR, DC_TITLE_KR,DC_KEYWORD ,DC_TYPE, DC_COUNTRY ,DC_SMRY_KR,DC_DT_COLLECT_STR,DC_DT_WRITE,DC_DT_REGI,DC_URL_LOC,DC_AGENCY,DC_MEMO1,DC_MEMO2,DC_CODE,DC_CONTENT,DC_PAGE,DC_CAT) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ",
array(
    $_POST["DC_TITLE_OR"], //DC_TITLE_OR , 원제목
    $_POST["DC_TITLE_KR"], //DC_TITLE_KR , 한글제목
    $_POST["DC_KEYWORD"], //DC_KEYWORD , 키워드
    $_POST["DC_TYPE"], //DC_TYPE , 유형분류
    $_POST["DC_COUNTRY"], //DC_COUNTRY , 국가
    "",//$objWorksheet->getCell('J' . $i)->getValue(), //DC_SMRY_KR , 요약
    "",//$objWorksheet->getCell('K' . $i)->getValue(), //DC_DT_COLLECT_STR, 이건 뭔데;
    $_POST["DC_DT_WRITE"], //DC_DT_WRITE , 발간일
    //								 (PHPExcel_Style_NumberFormat::toFormattedString(($objWorksheet->getCell('K' . $i)->getValue()), 'YYYY-MM-DD')),
    $date, //원래 mktime() //DC_DT_REGI
    //								 $objWorksheet->getCell('C'.$i)->getHyperlink()->getUrl(),
    //								 $objWorksheet->getCell('C'.$i)->getHyperlink()->getUrl(),

    $_POST["DC_URL_LOC"], //DC_URL_LOC , URL 정보
    $_POST["DC_AGENCY"], //DC_AGENCY , 발행기관
    $_POST["DC_MEMO1"], //메모1 , 첨부파일 hwp
    $_POST["DC_MEMO2"], //메모2 , 표지파일 gif
    0, // DC_CODE, 코드.. 나의 코드 , 대중소 분류된 코드 기입
    $_POST["DC_CONTENT"], //DC_CONTENT , 내용
    $_POST["DC_PAGE"], //DC_PAGE , 페이지수
    $_POST["DC_CAT"], //DC_CAT , 특수분류

));

$PID=$Mem->insertId();

if($_POST["DC_COUNTRY"]){
	$stat=0;
	$countrys="";
	foreach($_POST["DC_COUNTRY"] as $con){
		echo $con;
		$coq = $Mem->q("select * from nt_countrys where CTY_NM = '".$con."'")->fetch();
			if(!is_null($coq["IDX"])){
				$Mem->q("insert into nt_document_cty_list (PID, CTYID, DC_DT_MODI) values (?,?,?)",array(
					$PID, $coq["IDX"], $date));
				$countrys.=$con.",";
			}
	}
}

$codes = $Mem->qa("select code from nt_document_code_list where pid = ? and stat < 9",$PID)[0];
//등록될 코드가 저장되어 있었는지에 대해
foreach($_POST["DC_CODE"] as $code) {
    if(in_array($code, $codes)) {
        continue;
    }
    else {
        $Mem->q("insert into nt_document_code_list (pid,code,stat,uid,dt_write) values (?,?,?,?,?)", array($PID,$code,0,$Mem->user["uid"],$date));
    }
}
//저장된 코드가 남을건지에 대해
foreach($codes as $code) {
    if(in_array($code, $_POST["DC_CODE"])) {}
    else {
        $Mem->q("delete from nt_document_code_list where pid = ? and code = ?",array($PID,$code));
    }
}
unset($_SESSION["UPDATE_LIST"]);
mvs("Content_Data_View.php?PID=".$PID);
exit;


$_SESSION["TMP_CORVER"]=array();
$_SESSION["TMP_DOCUMENT"]=array();
$_SESSION["TMP_ATTECH"]=array();
 
$Mem->q("delete from nt_categorys_join_list where PID=? and TYPE=? ",array($_POST["PID"],1));
foreach($_SESSION["SET_CATEGORY"][1] as $key => $val){
	if($_SESSION["SET_CATEGORY"][1][$key]){
		$Mem->q("insert into nt_categorys_join_list(PID,CID,TYPE) values(?,?,?)",array($_POST["PID"],$key,1));

	}
}

 $Mem->q("delete from nt_categorys_join_list where PID=? and TYPE=? ",array($_POST["PID"],2));
foreach($_SESSION["SET_CATEGORY"][2] as $key => $val){
	if($_SESSION["SET_CATEGORY"][2][$key]){
		$Mem->q("insert into nt_categorys_join_list(PID,CID,TYPE) values(?,?,?)",array($_POST["PID"],$key,2));

	}
}

 $Mem->q("delete from nt_categorys_join_list where PID=? and TYPE=? ",array($_POST["PID"],3));
foreach($_SESSION["SET_CATEGORY"][3] as $key => $val){
	if($_SESSION["SET_CATEGORY"][3][$key]){
		$Mem->q("insert into nt_categorys_join_list(PID,CID,TYPE) values(?,?,?)",array($_POST["PID"],$key,3));

	}
}



/*
$Mem->q("delete from nt_document_country_list where PID=? ",$_POST["PID"]);
for($i=0; $i < sizeof($_SESSION["SEL_COUNTRY"]);$i++){
	$Mem->q("insert into nt_document_country_list(PID,TID) values(?,?)",array($_POST["PID"],$_SESSION["SEL_COUNTRY"][$i]));
}



$Mem->q("delete from nt_document_category_list where PID=? ",$_POST["PID"]);
foreach($_SESSION["CID_LIST"] as $key => $val){ 
	$CID=$Mem->qs("select IDX from nt_category_list where CODE=? ",array($val)); 
	$Mem->q("insert into nt_document_category_list(PID,CID) values(?,?)",array($_POST["PID"],$CID)); 
}
*/
unset($_SESSION["UPDATE_LIST"]);
mvs("Content_Data_View.php?PID=".$_POST["PID"]);

exit;
}else{
	unset($_POST);
}

$params=[
	'client' => [
        'timeout' => 10,       
        'connect_timeout' => 10
	],
	'body' => [
		'size' => 1000,
		'query' => [
			'match' => [
				'item_id' => $_GET['item_id']
			]
		]
	]
];
$es_imgs = $Mem->es->img_search($params)['images'];
$solr_res = $Mem->solr->search('item_id:"'.$_GET['item_id'].'"')['result'][0];

?>
<script type="text/javascript" src="Editor2/ckeditor.js"></script>

<script type="text/javascript">
//<![CDATA[
function LoadPage() {
    CKEDITOR.replace('DC_CONTENT');
	CKEDITOR.config.extraPlugins = 'footnotes';
}

function FormSubmit(f) {
    CKEDITOR.instances.contents.updateElement();
	if(f.contents.value == "") {
		alert("내용을 입력해 주세요.");
		return false;
	}
    alert(f.contents.value);
    
	// 전송은 하지 않습니다.
    return false;
}

</script>



<div style="width=70%">
    <div class="title1x">데이터 등록진행</div>
    <div>
        <form action="<?=SELF?>" method="post" >
            <table
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="width:100%;"
                class="table_view">

                <tr>
                    <th>원제목</th>
                    <td colspan="3"><input type="text" class="input_cell" name="DC_TITLE_OR" id="" value="<?=$solr_res['title']?>"></td>
                </tr>

                <tr>
                    <th>한글제목</th>
                    <td colspan="3"><input type="text" class="input_cell" name="DC_TITLE_KR" id=""></td>
                </tr>
                <tr>
                    <th>한글요약</th>
                    <td colspan="3"><input type="text" class="input_cell" name="DC_SMRY_KR" id=""></td>
                </tr>
                <tr>
                    <th>국가선택<br>
                        <input
                            type="button"
                            class='button1'
                            value="설정"
                            onclick="register_country_select();"><input
                            type="button"
                            class='button1'
                            value="초기화"
                            onclick="$('#country_select_list').empty();"></th>
                    <td colspan="3">
                        <div id="country_select_list"></div>
                    </td>
                </tr>
                <tr>
                    <th>분류선택
                        <br><input
                            type="button"
                            class='button1'
                            value="변경"
                            onclick="register_category_select();"><input
                            type="button"
                            class='button1'
                            value="초기화"
                            onclick="$('#category_select_list').empty();"></th>
                    <td colspan="3">
                        <div id="category_select_list"><input type="hidden" name="DC_CODE" id="DC_CODE"></div>
                    </td>
                </tr>
                <tr>
                    <th>수집일시</th>
                    <td><input
                        type="text"
                        class="input_cell"
                        placeholder="0000-00-00"
                        name="DC_DT_COLLECT"
                        id="DC_DT_COLLECT" value="<?=$solr_res['created_at'][0]?>"></td>
                    <th>작성일</th>
                    <td><input
                        type="text"
                        class="input_cell"
                        placeholder="0000-00-00"
                        name="DC_DT_WRITE"
                        id="DC_DT_WRITE"></td>
                </tr>
                <tr>
                    <th>문서위치URL</th>
                    <td><input type="text" class="input_cell" name="DC_URL_LOC" id="DC_URL_LOC" value="<?=$solr_res['url']?>"></td>
                    <th>문서안내URL</th>
                    <td><input type="text" class="input_cell" name="DC_URL_EXP" id="DC_URL_EXP"></td>
                </tr>

                <tr>
                    <th>구분</th>
                    <td><input type="text" class="input_cell" name="DC_TYPE" id="DC_TYPE"></td>
                    <th>페이지수</th>
                    <td><input type="text" class="input_cell" name="DC_PAGE" id="DC_PAGE" value="<?=$solr_res['pages'][0]?>"></td>
                </tr>
                <tr>
                    <th>기관명</th>
                    <td><input type="text" class="input_cell" name="DC_AGENCY" id="DC_AGENCY" value="<?=$solr_res['host'][0]?>"></td>
                    <th>검색키워드</th>
                    <td><input type="text" class="input_cell" name="DC_KEYWORD" id="DC_KEYWORD" value="<?=$solr_res['keywords'][0]?>"></td>
                </tr>

                <tr>
                    <th>내용</th>
                    <td colspan="3">

                        <textarea
                            style="display:none;"
                            name="DC_CONTENT"
                            id="DC_CONTENT"
                            class="input_cell">
                            <? foreach($solr_res['contents'] as $content){
                                echo $content;
                            }?></textarea>

                    </td>
                </tr>
                <tr style="">
				<th >표지파일</th>
				<td colspan="3"  >
                <div style="height:200px;overflow-y: scroll;" id="crawl_cover_list">
				<?
				foreach($es_imgs as $doc){
					echo "<div style='float:left;text-align:center;margin:10px;' >";
					echo	"<img width='100px' src='".$doc['image_path']."' class='temp_file' >";
					echo "</a></div>";
				}
				 ?>
                 </div>
				<!--<div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="cover_upload" style="display:none;"  onchange="file_corver_add();" ></td> -->
				<div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="cover_upload" style="display:none;"  onchange="easySetting();" ></td> 

			    </tr>
			
			<tr style="height:100px;">
				<th>첨부문서<br><input type="button" class="button1" value="파일추가" onclick="$('#file_document_upload').click();;"><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=2&reset=1','file_document_list');" ></th>
				<td colspan="3" >
								<? /*
				while($rs=$QS->fetch()){
					echo "<div style='float:left;text-align:center;margin:10px;'  ><a href=\"".$Mem->data["document"].$rs["FILE_PATH"]."\" target=\"_blank\" >";
					if(@is_array(getimagesize($Mem->data["document"].$rs["FILE_PATH"]))){
						echo	"<img src='".$Mem->data_url["document"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"];
					}else{
						echo "<img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"];
					}
								echo '<label  style="color:red;"><input type="checkbox" name="FILES[]" value="'.$rs["IDX"].'">삭제</label>';
					echo "</a></div>";
				 } */
				 ?>

				<div id="file_document_list"></div> <input type="file" id="file_document_upload" name="" multiple  style="display:none;" onchange="file_document_add();" ></td> 
			</tr>

            </table>

            <div style="text-align:right;padding:10px;text-align:center;">

                <input
                    type="submit"
                    class="buttonb"
                    value="등록"
                    style="height:40px; width:200px;">

            </div>
        </form>
    </div>
</div>

<script>

	LoadPage();

	//주제 불러오기
	function load_category($category){
		postup('components/selection.php','category_select_list',$category);
	}
	load_category($('input[name^=PCODE]').serialize());

	//국가 불러오기
	function load_country($country){
		postup('components/selection.php','country_select_list',$country);
	}
	load_country($('input[name^=PCOUNTRY]').serialize());

	//주제 설정하기
	function register_category_select(){
		Dialog('Content_Data_Search.php?category=1',800,255);
	}

	//국가 설정하기
	function register_country_select(){
		Dialog('Content_Data_Country.php',600,700);
	}

function sel_cover($obj){
    console.dir($obj);

    console.dir(base64);
    $('#file_corver_list').html($obj);
    $('#file_corver_upload').html($obj);
}


/******************************
***중복 미허용시 이전 카테고리***
******************************/
/*
function register_category_select_reset(num){

//	Dialog('Content_Data_Register_Set_Category2.php',800,600);
//	Dialog('Content_Data_Category_Select.php?CTYPE=2',700,650);

getup('Content_Data_Category_Select_List.php?RESET=1&CTYPE='+num,'category'+num);
}


function register_category_select_refresh(){
getup('Content_Data_Category_Select_List.php?CTYPE=1','category1');
getup('Content_Data_Category_Select_List.php?CTYPE=2','category2');
getup('Content_Data_Category_Select_List.php?CTYPE=3','category3');

//	Dialog('Content_Data_Register_Set_Category2.php',800,600);	
//	Dialog('Content_Data_Category_Select.php?CTYPE=2',700,650);

}

register_category_select_refresh();
*/

//링크 검색하기
function LinkFinder(){
	$.ajax({
		url: 'Content_Data_Search.php',
		processData: false,
		contentType: false,
		type:'POST',
		success:function(result){
			Dialog('Content_Data_Search.php',800,750);
		}
	})
}

//커버 변경하기
function easySetting(){
	var change = $('#changeCover');
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
					change.css('visibility','hidden');
				}

		}); 
}

/* 이전 커버 등록
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
*/

//첨부 문서 등록
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

/* 연관문서? 이전 버전
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
			alert('d');
					getup('Content_Data_Register_File_List.php?type=3','file_attech_list');
				}
		}); 
}
*/

// 파일 및 표지 불러오기 (1회 실행)
getup('Content_Data_Register_File_List.php?type=1','file_corver_list');
getup('Content_Data_Register_File_List.php?type=2','file_document_list');
getup('Content_Data_Register_File_List.php?type=3','file_attech_list');

/*
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

<?//=$Mem->data_url["temp"]?>