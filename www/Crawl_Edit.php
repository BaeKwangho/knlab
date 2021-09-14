<?


include "Axis_Header.php";

//error_reporting(E_ALL);	ini_set("display_errors", 1);
if(sizeof($_POST)){
	if(!isset($_POST["DC_CODE"])){
		mvs("components/error.php?err_msg=CODE가 지정되지 않았습니다.");
		exit;
	}
    if(!isset($_POST["DC_COUNTRY"])){
		mvs("components/error.php?err_msg=COUNTRY가 지정되지 않았습니다.");
		exit;
	}
if($_POST["DC_DT_COLLECT"]){	 $_POST["DC_DT_COLLECT"]=datec($_POST["DC_DT_COLLECT"]);		}else{		$_POST["DC_DT_COLLECT"]=0;	}
if($_POST["DC_DT_WRITE"]){ $_POST["DC_DT_WRITE"]=datec($_POST["DC_DT_WRITE"]); 		}else{		$_POST["DC_DT_WRITE"]=0;	}


$_POST["UID"]=$Mem->user["uid"];
$date = mktime();
$params = array();
if($_POST['DC_CODE']){$params['dc_code']=$_POST['DC_CODE'];}
if($_POST['DC_DT_COLLECT']){$params['dc_dt_collect']=date('c',$_POST['DC_DT_COLLECT']);}
if($_POST['DC_DT_WRITE']){$params['dc_dt_write']=date('c',$_POST['DC_DT_WRITE']);}
if($_POST['DC_LINK']){$params['dc_link']=$_POST['DC_LINK'];}else{$params['dc_link']="0";}
if($_POST['DC_COUNTRY']){$params['dc_country']=$_POST['DC_COUNTRY'];}else{$params['dc_country']="NULL";}
if($_POST['DC_TITLE_OR']){$params['dc_title_or']=$_POST['DC_TITLE_OR'];}else{$params['dc_title_or']="NULL";}
if($_POST['DC_TITLE_KR']){$params['dc_title_kr']=$_POST['DC_TITLE_KR'];}else{$params['dc_title_kr']="NULL";}
if($_POST['DC_CONTENT']){$params['dc_content']=$_POST['DC_CONTENT'];}else{$params['dc_content']="NULL";}
if($_POST['DC_URL_LOC']){$params['dc_url_loc']=$_POST['DC_URL_LOC'];}else{$params['dc_url_loc']="NULL";}
if($_POST['DC_AGENCY']){$params['dc_publisher']=$_POST['DC_AGENCY'];}else{$params['dc_publisher']="NULL";}
if($_POST['DC_PAGE']){$params['dc_page']=$_POST['DC_PAGE'];}else{$params['dc_page']="NULL";}
if($_POST['DC_TYPE']){$params['dc_type']=$_POST['DC_TYPE'];}else{$params['dc_type']="NULL";}
if($_POST['DC_TYPE']){$params['dc_cat']=$_POST['DC_TYPE'];}else{$params['dc_cat']="NULL";}
if($_POST['DC_KEYWORD']){$params['dc_keyword']=$_POST['DC_KEYWORD'];}else{$params['dc_keyword']="NULL";}
if($_POST['DC_SMRY_KR']){$params['dc_smry_kr']=$_POST['DC_SMRY_KR'];}else{$params['dc_smry_kr']="NULL";}
if($_POST['DC_HIT']){$params['dc_hit']=$_POST['DC_HIT'];}else{$params['dc_hit']="0";}
if($_POST['DC_COVER']){$params['dc_cover']=$_POST['DC_COVER'];}else{$params['dc_cover']="NULL";}
if($_POST['DC_URL_LOC']){

    $params['dc_cover']=$_POST['DC_COVER'];
}else{
    $params['dc_cover']="NULL";
}

if($_POST['ITEM_ID']){$params['item_id']=$_POST['ITEM_ID'];}
$params['is_crawled']=true;
$params['stat']=0;


if(!isset($_POST["DC_DT_WRITE"])){
    $params['dc_dt_write'] = date('c',$date);
}

$index=[
	'index' => 'politica_service',
	'body'=>$params
];
$index['refresh']=true;
$Mem->es->index($index);
$date = date('c',$date);
$Mem->poli->q('update collected_item set submit_status=1, submit_time='.$date.' where item_id='.$_POST['ITEM_ID']);

mvs("components/error.php?err_msg=성공적으로 등록되었습니다.&back=-2");
exit;

if(!isset($_GET["item_id"])){
    mvs("components/error.php?err_msg=유효하지 않은 Crawl_Data 번호입니다.");
    exit;
}

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


//기등록된 자료가 있는지 확인 후, 있으면 페이지 종료
$params=[
    'index' => 'politica_service',
    'body' => [
        'query' => [
            'match' => [
                'item_id' => $_GET['item_id']
            ]
        ]
    ]
];
$already = $Mem->es->document_search($params)['result'];
if(count($already)){
    mvs("components/error.php?err_msg=이미 등록된 자료입니다.");
    exit;
}

$solr_imgs = $Mem->gps->thumbnail('item_id:"'.$_GET['item_id'].'"');
$solr_res = $Mem->gps->search('item_id:"'.$_GET['item_id'].'"')['result'][0];
$Mem->gps->modify($solr_res,$Mem->uid,'viewer');

$fq = array(
	'custom' => array(
		'query' => 'item_id:"'.$_GET['item_id'].'"',
	),
);

$select = array(
  'query'         => "*:*",
  'start'         => 0,
  'rows'          => 5,
  'fields'        => array('*'),
  'sort'          => array('creationdate' => 'desc'),
  'filterquery' => $fq,
);
$result = $Mem->gps->select($select);

$Mem->gps->modify($result,$Mem->uid,'usr_edit');
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
            <input type="hidden" name="ITEM_ID" value="<?=$solr_res['item_id']?>">
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
                        id="DC_DT_WRITE" value="<?=conv_solr_time(mktime())?>"></td>
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
                <div class="row" style="padding:10px 10px;height:400px;overflow-y: scroll;" id="crawl_cover_list">
                <div class="col-xs-6 col-md-3">
				<?
                $img_loc= $Mem->gps->thumbnail("item_id:".$solr_res['item_id']);
                $thumbnails = $Mem->nas->get_image_from_folder($img_loc);
                if($thumbnails){
                    ?><div class="btn-group" data-toggle="buttons"><?
                    foreach($thumbnails as $img){
                    ?>
                    <label class="btn">
                        <div >
                        <?=$img[0]?>
                        </div>
                        <input type="radio" name="DC_COVER" id="DC_COVER" autocomplete="off" value="<?=$img[1]?>">
                    </label>
                    <?
                    }
                    ?></div><?
                }else{
                    echo "썸네일 이미지가 추출되지 않았습니다.";
                }
				 ?>
                 </div>
                 </div>
				<!--<div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="cover_upload" style="display:none;"  onchange="file_corver_add();" ></td> -->
				<div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="cover_upload" style="display:none;"  onchange="easySetting();" ></td> 

			    </tr>
			<!--
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
                -->
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