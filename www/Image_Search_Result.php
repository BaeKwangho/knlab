<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);
$_SESSION["edit"]=false;
include "_h_img.php";

if(!isset($_GET['keyword'])){
	mvs("Image_Search.php");
}

$params=[
	'scroll'=>'30s',
	'size'=>50,
	'client' => [
        'timeout' => 10,       
        'connect_timeout' => 10
	],
	'body' => [
		'query' => [
			'match' => [
				'caption' => $_GET['keyword']
			]
		]
	]
];
$obj = $Mem->es->img_search($params);
$_SESSION["scroll"]=$obj["scroll_id"];
$_SESSION["hash"] = array();
?>
<div class="c5" style="margin-top:123px;">
	<form id="scroll_id">
		<input type="hidden" name="scroll_id" value="<?=$obj["scroll_id"]?>">
	</form>

	<div style="display:flex;">
		<div id="article" style="padding: 0px 20px; display:inline-block;width:100%"></div>
		<div id="poster" style="position:sticky;width:50%;height:90vh;top:125px;display:none;background:rgba(0,0,0,.87);overflow-y: scroll;"></div>
	</div>
</div>
<div class="btn-floating" style='background:#ff3300'onclick="load_more()">더보기
</div>

<script>
$.ajax({
		url:'components/crawl_list.php',
		type: 'POST',
		dataType : "html",
		data:$('#scroll_id').serialize(),
		success: function(result, textStatus, xhr){
			$('#article').append(result);
		},error: function(xhr, textStatus, errorThrown) {
			console.log(xhr,textStatus,errorThrown); 
		}
	});
function load_more(){
	$.ajax({
			url:'components/crawl_list.php',
			type: 'POST',
			async:false,
			dataType : "html",
			data:$('#scroll_id').serialize(),
			success: function(result, textStatus, xhr){
				$('#article').append(result);
			},error: function(xhr, textStatus, errorThrown) {
                console.log(xhr,textStatus,errorThrown); 
            }
		});
}

//스크롤 바닥 감지
window.onscroll = function(e) {
    //추가되는 임시 콘텐츠
    //window height + window scrollY 값이 document height보다 클 경우,
    if((window.innerHeight + window.scrollY) > document.body.offsetHeight) {
		$.ajax({
			url:'components/crawl_list.php',
			type: 'POST',
			async:false,
			dataType : "html",
			data:$('#scroll_id').serialize(),
			success: function(result, textStatus, xhr){
				$('#article').append(result);
			},error: function(xhr, textStatus, errorThrown) {
                console.log(xhr,textStatus,errorThrown); 
            }
		});
		//article에 추가되는 콘텐츠를 append
    }
};

function show_poster(num,src){
	if($('#poster').css('display')==='none'){
		$('#poster').css('display','inline-block');
		$('#article').css('width','50%');
		$.ajax({
			url:'components/crawl_list.php',
			type: 'POST',
			async:false,
			data:{
				item_id:num,
				image_path:src
			},
			success: function(result){
				$('#poster').html(result);
			},error: function(xhr, textStatus, errorThrown) {
                console.log(xhr,textStatus,errorThrown); 
            }
		})
	}else{
		$.ajax({
			url:'components/crawl_list.php',
			type: 'POST',
			data:{
				item_id:num,
				image_path:src
			},
			success: function(result){
				$('#poster').html(result);
			},error: function(xhr, textStatus, errorThrown) {
                console.log(xhr,textStatus,errorThrown); 
            }
		})
	}
}

function hide_poster(){
	$('#poster').css('display','none');
	$('#article').css('width','100%');
}
</script>