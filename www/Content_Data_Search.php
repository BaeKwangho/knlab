<?
/* 사용처 
1. Content_Data_Modify.php - 주제 설정하기
*/


include "_h.php"; 

//error_reporting(E_ALL);	ini_set("display_errors", 1);

if($_POST["SEARCH"]){

	if(!is_array($_SESSION["SEARCH"])) $_SESSION["SEARCH"]=array();
	
	$_SESSION["SEARCH"]=$_POST;
	
	}
	if($_GET["search_reset"]) unset($_SESSION["SEARCH"]);
	
	//CID는 대중소 분류.. get의 cid와 session의 cid에 맞게 값 수정
	if($_GET["CID"])	$_SESSION["SEARCH"]["CID"]=$_GET["CID"];
	if($_SESSION["SEARCH"]["CID"]){
		$_GET["CID"]=$_SESSION["SEARCH"]["CID"];
	}
?>

<form action="" method="post" > 
<input type="hidden" name="SEARCH" value="1">
<table  style=""class="table_view" cellpadding="0" cellspacing="0" border="0" >
	
	<?
	//카테고리 변수를 받는 것은 수정 혹은 단일 데이터 등록에서의 작업, form 태그를 추가하여
	//아래 script의 함수에서 값을 전달할 수 있도록 함. 그리고 이 값은 기존에 있던 페이지의 #category_select_list에 저장된다.
	if($_GET["category"]){?>
		<form id="category" action="" method="post">
			<tr>
				<th>주제분류<br><input type="button" class='button1' value="선택초기화" onclick="getup('Content_Data_Category_List.php?TYPES=1&RESET=1&FORM=1','category_list1');"></th>

				<td><input type="hidden" name="category_list"><div id="category_list1"> </div></input></td>
			</tr>
			<tr>
				<td colspan="3" style="border-radius: 5px 5px 5px 5px; width:100%">
					<div style="float:right;display:inline-block">
					<input class="buttonb" type="button" value="창닫기" onclick="DialogHides();">
					<!--name=ITEM 코드 추가-->
					<input class="buttonb" type="button" value="추가" onclick="sel_cat();">
					</div>
				</td>
			</tr>
		</form>
	<?}else{?>
		<tr>
		<th>주제분류<br><input type="button" class='button1' value="선택초기화" onclick="getup('Content_Data_Category_List.php?TYPES=1&RESET=1&FORM=1','category_list1');"></th>

		<td colspan="3" ><div id="category_list1"></div></td>
	</tr>
	<!--
	<tr>
		<th>국가분류<Br><input type="button" class='button1' value="선택초기화" onclick="getup('Content_Data_Category_List.php?TYPES=3&RESET=1','category_list3');"></th>
		<td colspan="3" ><div id="category_list3"></div></td>
	</tr>
	
	<tr>
		<th>유형분류<br><input type="button" class='button1' value="선택초기화" onclick="getup('Content_Data_Category_List.php?TYPES=2&RESET=1','category_list2');"></th>
		<td colspan="3" ><div id="category_list2"></div></td>
	</tr> 
	<tr>
	-->
		<th>원제목</th>
		<td><input type="text" class="input_cell"  name="KEY_TITLE_OR"  value="<?=$_SESSION["SEARCH"]["KEY_TITLE_OR"]?>"></td> 
		<th>한글제목</th>
		<td><input type="text" class="input_cell" name="KEY_TITLE_KR"  value="<?=$_SESSION["SEARCH"]["KEY_TITLE_KR"]?>"></td>
	</tr>
	<tr>
		<th>내용 또는 요약</th>
		<td><input type="text" class="input_cell"  name="KEY_CONTENT"  value="<?=$_SESSION["SEARCH"]["KEY_CONTENT"]?>"></td>
		<th>키워드</th>
		<td><input type="text" class="input_cell"  name="KEY_KEYWORD"  value="<?=$_SESSION["SEARCH"]["KEY_KEYWORD"]?>"></td>
	</tr> 
	<tr>
		<th>기관명</th>
		<td><input type="text" class="input_cell" name="KEY_AGENCY"  value="<?=$_SESSION["SEARCH"]["KEY_AGENCY"]?>"></td>
		<th>페이지수</th>
		<td><input type="text" class="input_cell" name="KEY_PAGE"  value="<?=$_SESSION["SEARCH"]["KEY_PAGE"]?>"></td>
	</tr>
	<tr>
		<th>수집일</th>
		<td><input type="text" class="input_text1" style="width:100px;" name="DT_COLLECT_ST" id="DT_COLLECT_ST"  value="<?=$_SESSION["SEARCH"]["DT_COLLECT_ST"]?>"  data-type="DT"  >~<input type="text" class="input_text1" style="width:100px;" name="DT_COLLECT_ED"  id="DT_COLLECT_ED"   value="<?=$_SESSION["SEARCH"]["DT_COLLECT_ED"]?>" data-type="DT"></td>
		<th>작성일</th>
		<td><input type="text" class="input_text1" style="width:100px;" name="DT_WRITE_ST" id="DT_WRITE_ST"  value="<?=$_SESSION["SEARCH"]["DT_WRITE_ST"]?>"  data-type="DT" >~<input type="text" class="input_text1" style="width:100px;" name="DT_WRITE_ED"  id="DT_WRITE_ED"   value="<?=$_SESSION["SEARCH"]["DT_WRITE_ED"]?>" data-type="DT"></td>
	</tr> 
</table>
<div style="padding:10px; text-align:center;background-color:#FFF;" >
<input type="button" class="button1" value="창닫기" style="height:36px; width:100px;"  onclick="DialogHides();setup('Content_Data_Category_List.php?RESET=1')" >
<input type="button" class="button1" value="상세검색초기화" style="height:36px; width:100px;"  onclick="getup('<?=SELF?>?search_reset=1');" >
<input type="button" class="buttonb" value="상세검색" onclick="selfcheck();" style="height:36px; width:100px;" ></div>
</form>
<form id="link_data" action="" method="post">
<table id="list_table" cellpadding="0" cellspacing="0" border="0" class="table_ex" >
<colgroup>
	<col  style="width:10%;" />
	<col  style="width:75%;" />
	<col  style="width:15%;" />
</colgroup>
	<tr>
		<th>순번</th>
		<th>한글제목</th>
		<th>수집일</th>
	</tr>
<?
	}

?>
<script>

	//주제 분류 테이블 로딩
	getup('Content_Data_Category_List.php?TYPES=1','category_list1'); 
	
	
	function selfcheck(){
		$.ajax({
			url:'components/paging.php',
			type:'POST',
			data:$('form').serialize(),
			success:function(result){
				$(document).ready(function(){
					$('#list_table').html(result);
				});
			}
		})
	}
	
	function assign(){
		var links = $('#link_data').serialize();
		$.ajax({
			url:'components/linking.php',
			type:'POST',
			data:links,
			success:function(result){
				$('#link_area').append(result);
			},
            error: function(request,status,error) {
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			}
		});
		DialogHides();
	}

	//단일 데이터 분류,국가 등을 설정할 때 다이얼로그에서 등록을 하는 함수.
	//보다시피 selection.php에서 해당 다이얼로그의 form으로 등록된(그러니까, 다이얼로그에서 선택한) 값들을
	//기존에 있던 페이지로 넘겨주는 역할(이미 완료된 html 태그를 jquery로 바꿈으로써 실행)

	//주제 선택 후 추가
	function sel_cat(){
		$.ajax({
			url:'components/selection.php',
			type:'post',
			data:$('form').serialize(),
			success:function(result){
				$('#category_select_list').append(result);
			},
            error: function(xhr, textStatus, errorThrown) {
                alert(errorThrown); 
            }
		})
		DialogHides();
	}

	
</script>