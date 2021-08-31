
<?


/* 사용처
1. Content_Document_List.php - 수정 기능
*/

$Close=true;
include "_head.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);

if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"]; 
if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;

if(!is_array($_SESSION["TMP_CORVER"])) $_SESSION["TMP_CORVER"]=array();
if(!is_array($_SESSION["TMP_DOCUMENT"])) $_SESSION["TMP_DOCUMENT"]=array();
if(!is_array($_SESSION["TMP_ATTECH"])) $_SESSION["TMP_ATTECH"]=array();

if(!is_array($_SESSION["SET_CATEGORY"])) $_SESSION["SET_CATEGORY"]=array();
if(!is_Array($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]])) $_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]]=array();

if($_FILES["FILE_CORVER"]["tmp_name"]){
		for($i=0; $i <sizeof($_FILES["FILE_CORVER"]["tmp_name"]); $i++){
			if($_FILES["FILE_CORVER"]["tmp_name"][$i]){
				$file_corver=uploadFile($Mem->data["temp"],$_FILES["FILE_CORVER"]["tmp_name"][$i],$_FILES["FILE_CORVER"]["name"][$i]);
				if(file_exists($Mem->data["temp"].$file_corver)){
					array_push($_SESSION["TMP_CORVER"],array("name"=>$_FILES["FILE_CORVER"]["name"][$i],"file"=>$file_corver,"size"=>$_FILES["FILE_CORVER"]["size"][$i],"ext"=>$_FILES["FILE_CORVER"]["type"][$i]));
				}
			}
		}
		exit;
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
		exit;
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
		exit;
}

if(sizeof($_POST)){
	$date = mktime();

    /*
	$curDoc = $Mem->qr("select * from nt_document_list where idx = ?",$_POST["PID"]);
	$doc_img = $Mem->q("select * from nt_document_image_list where pid = ?",$_POST["PID"]);
	if(isset($doc_img)){
		while($img = $doc_img->fetch()){
			if(!strpos($_POST["DC_CONTENT"],$img["IMG_NAME"])){
				// 기존 post image로 등록된 파일이 DC_CONTENT 전송 내용에 없을 시
				unlink($img["IMG_NAME"]);
				$Mem->q("delete from nt_document_image_list where PID = ? and IMG_NAME = ?",
				array($_POST["PID"],$img["IMG_NAME"]));
			}
		}
	}
	//print_r($doc_img);

    if(isset($_SESSION["post_file"])){
        foreach($_SESSION["post_file"] as $file){
            if(!strpos($_POST["DC_CONTENT"],$file["real_name"])){
				// Data/post/에 생성된 이미지가 사용되지 않았을 경우 삭제
                unlink($file["real_name"]);
            }else{
				// Data/post/에 생성된 이미지가 사용되었을 경우 등록
				$Mem->q("insert into nt_document_image_list (pid,dt,uid,img_name,img_path) values (?,?,?,?,?)",
				array($_POST["PID"],$date,$Mem->user["uid"],$file["real_name"],$file["real_path"]));
			}
		}
		unset($_SESSION["post_file"]);
    }
    */
    $_POST["UID"]=$Mem->user["uid"];

    $data = array();
    if($_POST['DC_CODE']){$data['DC_CODE']=$_POST['DC_CODE'];}
    if($_POST['DC_DT_COLLECT']){$data['DC_DT_COLLECT']=datec($_POST['DC_DT_COLLECT']);}
    if($_POST['DC_DT_WRITE']){$data['DC_DT_WRITE']=datec($_POST['DC_DT_WRITE']);}
    if($_POST['DC_LINK']){$data['DC_LINK']=$_POST['DC_LINK'];}
    if($_POST['DC_COUNTRY']){$data['DC_COUNTRY']=$_POST['DC_COUNTRY'];}
    if($_POST['DC_TITLE_OR']){$data['DC_TITLE_OR']=array($_POST['DC_TITLE_OR']);}
    if($_POST['DC_TITLE_KR']){$data['DC_TITLE_KR']=array($_POST['DC_TITLE_KR']);}
    if($_POST['DC_CONTENT']){$data['DC_CONTENT']=array($_POST['DC_CONTENT']);}
    if($_POST['DC_URL_LOC']){$data['DC_URL_LOC']=array($_POST['DC_URL_LOC']);}
    if($_POST['DC_AGENCY']){$data['DC_AGENCY']=array($_POST['DC_AGENCY']);}
    if($_POST['DC_PAGE']){$data['DC_PAGE']=array($_POST['DC_PAGE']);}
    if($_POST['DC_TYPE']){$data['DC_TYPE']=$_POST['DC_TYPE'];}
	if($_POST['DC_TYPE']){$data['DC_CAT']=$_POST['DC_TYPE'];}
    if($_POST['DC_KEYWORD']){$data['DC_KEYWORD']=array($_POST['DC_KEYWORD']);}
    if($_POST['DC_SMRY_KR']){$data['DC_SMRY_KR']=array($_POST['DC_SMRY_KR']);}


$_POST["PID"] = $_POST["ITEM_ID"];
if($_SESSION["TMP_CORVER"]){
	$lastnum=sizeof($_SESSION["TMP_CORVER"])-1;
	@copy($Mem->data["temp"].$_SESSION["TMP_CORVER"][$lastnum]["file"],$Mem->data["cover"].$_SESSION["TMP_CORVER"][$lastnum]["file"]);

	for($i=0; $i < sizeof($_SESSION["TMP_CORVER"]); $i++){
		@unlink($Mem->data["temp"].$_SESSION["TMP_CORVER"][$i]["file"]);
	}
	$Mem->q("update nt_crawl_file_list set STAT = 9 where PID = ? and STAT = 0",$_POST["PID"]);
	$Mem->q("insert into nt_crawl_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT,STAT) values(?,?,?,?,?,?,?,?) ",array($_POST["PID"],$_SESSION["TMP_CORVER"][$lastnum]["name"],$_SESSION["TMP_CORVER"][$lastnum]["file"],1,$_SESSION["TMP_CORVER"][$lastnum]["ext"],$_SESSION["TMP_CORVER"][$lastnum]["size"],mktime(),0));
}
if($_SESSION["TMP_DOCUMENT"]){
	$docArray = $curDoc["DC_MEMO1"];
	for($i=0; $i < sizeof($_SESSION["TMP_DOCUMENT"]); $i++){
		@copy($Mem->data["temp"].$_SESSION["TMP_DOCUMENT"][$i]["file"],$Mem->data["document"].$_SESSION["TMP_DOCUMENT"][$i]["file"]);
		@unlink($Mem->data["temp"].$_SESSION["TMP_DOCUMENT"][$i]["file"]);
		$Mem->q("insert into nt_crawl_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT) values(?,?,?,?,?,?,?) ",array($_POST["PID"],$_SESSION["TMP_DOCUMENT"][$i]["name"],$_SESSION["TMP_DOCUMENT"][$i]["file"],2,$_SESSION["TMP_DOCUMENT"][$i]["ext"],$_SESSION["TMP_DOCUMENT"][$i]["size"],mktime()));
		$docArray= $docArray."/".$_SESSION["TMP_DOCUMENT"][$i]["name"];
	}
}
for($i=0; $i < sizeof($_SESSION["TMP_ATTECH"]); $i++){
 	$Mem->q("insert into nt_crawl_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT) values(?,?,?,?,?,?,?) ",array($_POST["PID"],$_SESSION["TMP_ATTECH"][$i]["name"],$_SESSION["TMP_ATTECH"][$i]["file"],3,$_SESSION["TMP_ATTECH"][$i]["ext"],$_SESSION["TMP_ATTECH"][$i]["size"],mktime()));
}

for($i=0 ; $i < sizeof($_POST["FILES"]); $i++){
		$rs=$Mem->qr("select * from nt_crawl_file_list where IDX=? ",$_POST["FILES"][$i]);
		//
		$Mem->q("update nt_crawl_file_list set Stat=9 where IDX=? ",$_POST["FILES"][$i]);
	@copy($Mem->data["document"].$rs["FILE_PATH"],$Mem->data["remove"].$rs["FILE_PATH"]);
	@unlink($Mem->data["document"].$rs["FILE_PATH"]);
}


$Mem->docs->multi_modify($_POST['id'],$data);

$_SESSION["TMP_CORVER"]=array();
$_SESSION["TMP_DOCUMENT"]=array();
$_SESSION["TMP_ATTECH"]=array();

unset($_SESSION["UPDATE_LIST"]);
echo "<script> opener.parent.location.reload(); self.close(); </script>";

}else{
	unset($_POST);
}

 
if($_GET["id"]){

$select = array(
    'query'         => 'id:'.$_GET['id'],
    'start'         => 0,
    'rows'          => 1,
    'fields'        => array('*'),
    'sort'          => array('DC_DT_COLLECT' => 'asc'),
    'filterquery' => array(
        'custom' => array(
            'query' => '',
        ),
    ),
);
    
$doc = $Mem->docs->select($select);
foreach($doc as $r){

}

 $_SESSION["SEL_COUNTRY"]=array();
}

 /*
$QC=$Mem->q("select a.* from nt_country_list a, nt_document_country_list b where b.PID=? and b.TID=a.IDX  ",$r["IDX"]);

while($rs=$QC->fetch()){


 	array_push($_SESSION["SEL_COUNTRY"],$rs["IDX"]);
}



 $_SESSION["CID_LIST"]=array();

$QC=$Mem->q("select b.* from nt_document_category_list a, nt_category_list b where b.IDX=a.CID and a.PID=?  ",$r["IDX"]);

while($rs=$QC->fetch()){ 
array_push($_SESSION["CID_LIST"],$rs["CODE"]);
}

  
 }
*/




//echo $Mem->updateStr("nt_document_list");

?>

<script type="text/javascript" src="./Editor2/ckeditor.js"></script>

<script type="text/javascript">
//<![CDATA[
function LoadPage() {
    CKEDITOR.replace('DC_CONTENT_EDITOR');
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


<div>


	<div class="title1">문서 수정 : <?=$r["DC_TITLE_OR"][0]?></div>
	<div>
		<?
		$codes = $r['DC_CODE'];
		foreach($codes as $code){
		?>
			<input type="hidden" id="PCODE[]" name="PCODE[]" value="<?=$code?>">
		<?	
		}
		$org_countrys = $r['DC_COUNTRY'];
		foreach($org_countrys as $org_country){
		?>
			<input type="hidden" id="PCOUNTRY[]" name="PCOUNTRY[]" value="<?=$org_country?>">
		<?	
		}
		?>
		<form action="<?=SELF?>" method="post" onsubmit=" CKEDITOR.instances.contents.updateElement(); return true;"" >
		<input type="hidden" name="id" value="<?=$r["id"]?>">
		<input type="hidden" name="ITEM_ID" value="<?=$r["ITEM_ID"]?>">
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;" class="table_view" >
		
			<tr>
				<th>원제목</th>
				<td colspan="3" ><input type="text" class="input_cell" name="DC_TITLE_OR" id="" value="<?=$r["DC_TITLE_OR"][0]?>"  ></td> 
			</tr>
			
			<tr>
				<th>한글제목</th>
				<td colspan="3" ><input type="text" class="input_cell" name="DC_TITLE_KR" id="" value="<?=$r["DC_TITLE_KR"][0]?>"  ></td> 
			</tr> 
			<tr>
				<th>한글요약</th>
				<td colspan="3" ><input type="text" class="input_cell" name="DC_SMRY_KR" id="" value="<?=$r["DC_SMRY_KR"][0]?>"  ></td> 
			</tr>

			
			<tr>
				<th>주제분류 <br><input type="button" class='buttonb' value="선택" onclick="register_category_select();"><input type="button" class='button1' value="초기화" onclick="load_category($('input[name^=PCODE]').serialize());"><input type="button" class='button1' value="비우기" onclick="$('#category_select_list').text('');"></th>
				<td colspan="3" >
					<div id="category_select_list" >
					</div>
				</td> 
			</tr>
			
			<tr>
				<th>국가분류<br> <input type="button" class='buttonb' value="선택" onclick="register_country_select();"><input type="button" class='button1' value="초기화" onclick="load_country($('input[name^=PCOUNTRY]').serialize());"><input type="button" class='button1' value="비우기" onclick="$('#country_select_list').text('');"></th>
				<td colspan="3" >
					<div id="country_select_list" >
					</div>
				</td>
			</tr>
			<!--
			<tr>
				<th>유형분류 <br><input type="button" class='buttonb' value="선택" onclick="register_category_select(2);"><input type="button" class='button1' value="초기화" onclick="register_category_select_reset(2)"></th>
				<td colspan="3" ><div id="category2" ></div></td> 
			</tr>
			-->
			<tr>
				<th>수집일시</th>
				<td><input type="text" class="input_cell" placeholder="0000-00-00" name="DC_DT_COLLECT" id="DC_DT_COLLECT" value="<?=$r["DC_DT_COLLECT"][0]?date("Y-m-d",$r["DC_DT_COLLECT"][0]):""?>" ></td>
				<th>작성일</th>
				<td><input type="text" class="input_cell"  name="DC_DT_WRITE" id="DC_DT_WRITE"   value="<?=$r["DC_DT_WRITE"][0]?date("Y-m-d",$r["DC_DT_WRITE"][0]):""?>"    ></td>
			</tr>
			<tr>
				<th>문서위치URL</th>
				<td><input type="text" class="input_cell"  name="DC_URL_LOC" id="DC_URL_LOC"  value="<?=$r["DC_URL_LOC"][0]?>"  ></td>
				<th>문서안내URL</th>
				<td><input type="text" class="input_cell"  name="DC_URL_EXP" id="DC_URL_EXP"  value="<?=$r["DC_URL_EXP"][0]?>"  ></td>
			</tr>

			<tr>
				<th>구분</th>
				<td><input type="text" class="input_cell"  name="DC_TYPE" id="DC_TYPE"  value="<?=$r["DC_TYPE"][0]?>"  ></td> 
				<th>페이지수</th>
				<td><input type="text" class="input_cell"  name="DC_PAGE" id="DC_PAGE" value="<?=$r["DC_PAGE"][0]?>"   ></td> 
			</tr>
			<tr>
				<th>기관명</th>
				<td><input type="text" class="input_cell"  name="DC_AGENCY" id="DC_AGENCY" value="<?=$r["DC_AGENCY"][0]?>"   ></td> 
				<th>검색키워드</th>
				<td><input type="text" class="input_cell"  name="DC_KEYWORD" id="DC_KEYWORD"  value="<?=$r["DC_KEYWORD"][0]?>"  ></td> 
			</tr>
 
			<tr>
				<th>내용</th>
				<td colspan="3" >
 
					<textarea name="DC_CONTENT" id="DC_CONTENT_EDITOR"  class="input_cell" ><?=$r["DC_CONTENT"][0]?></textarea>

				</td> 
				
			</tr>

			<tr style="height:100px;">
				<th>링크
				<br><input type="button" class="button1" value="검색" onclick="LinkFinder();<?unset($_SESSION["UPDATE_LIST"]);?>">
				<br><input type="button" class="button1" value="선택삭제" onclick="$('#LINK_DEL').val(1);postup('components/linking.php','link_area',$('input[name=DC_LINK]').serialize())">
				<input type="text" style="display:none;" id="LINK_DEL" name="LINK_DEL" value="0">			
				</th>
				<td id="link_area" colspan="3">
					
						<?
							$link=explode(' ',$r["DC_LINK"]);
							if($link[0]){
								for($i=0;$i<count($link);$i++){
									$l=$Mem->qr("select DC_TITLE_KR,DC_DT_WRITE from nt_document_list where idx like ?",$link[$i]);
									
						?>
						<div style="padding:10px;float:left;width:50%-30px;line-height:10px;"><label style=";">
						<input type="checkbox" id="link_list" name="DC_LINK[]" value="<?=$link[$i]?>" checked >
						<span onclick="window.open('Content_Data_View.php?PID=<?=$link[$i]?>','data_view','width=900,height=900,scrollbars=1');">
						<?=$l["DC_TITLE_KR"]?> / <?=date("Y-m-d",$l["DC_DT_WRITE"])?>
						</label></div>
						<?			$_SESSION["UPDATE_LIST"][$link[$i]]=1;
								}
							}else{
							}
						?>
					
				</td>

			</tr>

			<tr style="height:100px;">
				<th>표지파일<br><input id="changeCover" type="button" class="button1" value="변경" onclick="$('#file_corver_upload').click();" ><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=1&reset=1','file_corver_list');$('#changeCover').css('visibility','visible');" ></th>
				<td colspan="3" >
				<?
				
                $QS=$Mem->q("select * from nt_crawl_file_list where PID=? and FILE_TYPE=1 and STAT < 9 ",$r["ITEM_ID"]);
				while($rs=$QS->fetch()){
					echo "<div style='float:left;text-align:center;margin:10px;'  ><a href=\"".$Mem->data["cover"].$rs["FILE_PATH"]."\" target=\"_blank\" >";
					echo	"<img src='".$Mem->data["cover"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"];
					echo "</a></div>";
				}
				?>
				<!--<div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="cover_upload" style="display:none;"  onchange="file_corver_add();" ></td> -->
				<div id="file_corver_list"></div> <input type="file" id="file_corver_upload" name="cover_upload" style="display:none;"  onchange="easySetting();" ></td> 

			</tr>
			
			<tr style="height:100px;">
				<th>첨부문서<br><input type="button" class="button1" value="파일추가" onclick="$('#file_document_upload').click();;"><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=2&reset=1','file_document_list');" ></th>
				<td colspan="3" >
								<? 
				$QS=$Mem->q("select * from nt_crawl_file_list where PID=? and FILE_TYPE=2 and STAT < 9 ",$r["ITEM_ID"]);
				while($rs=$QS->fetch()){

		
					echo "<div style='float:left;text-align:center;margin:10px;'  ><a href=\"".$Mem->data["document"].$rs["FILE_PATH"]."\" target=\"_blank\" >";
					if(@is_array(getimagesize($Mem->data["document"].$rs["FILE_PATH"]))){
						echo	"<img src='".$Mem->data_url["document"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"];
					}else{
						echo "<img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"];
					}
								echo '<label  style="color:red;"><input type="checkbox" name="FILES[]" value="'.$rs["IDX"].'">삭제</label>';
					echo "</a></div>";
				 } 
				 ?>

				<div id="file_document_list"></div> <input type="file" id="file_document_upload" name="" multiple  style="display:none;" onchange="file_document_add();" ></td> 
			</tr>
			<!--
			<tr>
				<th>참조파일<br><input type="button" class="button1" value="파일추가" onclick="$('#file_attech_upload').click();"><br><input type="button" class="button1" value="초기화" onclick="getup('Content_Data_Register_File_List.php?type=3&reset=1','file_attech_list');" ></th>
				<td colspan="3" >
								<?/* 
				$QS=$Mem->q("select * from nt_crawl_file_list where PID=? and FILE_TYPE=3 and STAT < 9 ",$r["ITEM_ID"]);
				while($rs=$QS->fetch()){

		
					echo "<div style='float:left;text-align:center;margin:10px;'  ><a href=\"".$Mem->data_url["temp"].$rs["FILE_PATH"]."\" target=\"_blank\" >";
					if(is_image($Mem->data["temp"].$rs["FILE_PATH"])){
						echo	"<img src='".$Mem->data_url["temp"].$rs["FILE_PATH"]."' class='temp_file' ><br>".$rs["FILE_NAME"];
					}else{
						echo "<img src='/images/files.png' class='temp_file'><br>".$rs["FILE_NAME"];
					}
								echo '<label  style="color:red;"><input type="checkbox" name="FILES[]" value="'.$rs["IDX"].'">삭제</label>';
					echo "</a></div>";
				 } */
				 ?>

				<div id="file_attech_list"></div> <input type="file" id="file_attech_upload" name="" multiple style="display:none;"  onchange="file_attech_add();" ></td> 
			</tr>
			-->




		</table>


		
	<div style="text-align:right;padding:10px;text-align:center;" >
	 
	<input type="submit" class="buttonb" value="수정" style="height:40px; width:200px;">	
	

	
	</div>
	
	</form>

	</div>
</div>
<script>
window.onbeforeunload = function () {
	$.ajax({
		url: 'components/modify_who.php?EDIT=false&PID='+$('[name="id"]').attr('value'),
		processData: false,
		contentType: false,
		type:'GET',
		success:function(result){
		}
	});
	return "really?";
};



	ModifyWho(true);
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
function ModifyWho(edit){
	$.ajax({
		url: 'components/modify_who.php?EDIT='+edit+'&PID='+$('[name="id"]').attr('value'),
		processData: false,
		contentType: false,
		type:'GET',
		success:function(result){
			var result_value = JSON.parse(result);
			if(result_value.act===0){
				location.replace('components/error.php?err_msg=누군가 작업중입니다.');
				ModifyWho(false);
			}
		}
	})
}

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

	console.dir($('#file_corver_upload'));
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


</script>

<?//=$Mem->data_url["temp"]?>