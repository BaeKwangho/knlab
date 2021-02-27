<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);
$_SESSION["edit"]=false;
include "_h_img.php";

if(!isset($_GET['keyword'])){
	mvs("Image_Search.php");
}

$params=[
	'scroll'=>'30s',
	'client' => [
        'timeout' => 10,       
        'connect_timeout' => 10
	],
	'body' => [
		'size' => 1000,
		'query' => [
			'match' => [
				'caption' => $_GET['keyword']
			]
		]
	]
];
$obj = $Mem->es->img_search($params);
$_SESSION["scroll"]=$obj["scroll_id"];

?>
<div class="c5">
	<div class="row bold">
		<text><?=$_GET['keyword']?> 에 대한 검색결과 (<?=$obj['doc_num']?>건)</text>
	</div>
	<form id="scroll_id">
		<input type="hidden" name="scroll_id" value="<?=$obj["scroll_id"]?>">
	</form>
	<div id="article" style="padding: 0px 20px; display:inline-block">
		<?foreach($obj['images'] as $doc){
			try{
				$solr_res = $Mem->gps->search('item_id:"'.$doc['item_id'].'"')['result'][0];
				//print_r($solr_res[0]['title']);
			}catch(Exception $e){
				continue;
			}
			
		?>
			<div class="frame">
				<div class="img_frame">
					<!--es-->
					<img class="img" src="<?=$doc['image_path']?>">
					<div class="script">
						<!--solr-->
						<div class="shortcut f14">
							<?=$solr_res['title']?>
						</div>
						<div class="caption">
							<?foreach($doc['caption'] as $caption){?>
								<?
								$cap = highlight_words($caption,$_GET['keyword'],array('#000000'));
								echo $cap;
								?>
								<?=','?>
							<?}?>
						</div>
					</div>
				</div>
			</div>
		<?}?>
	</div>
</div>
<!--
<script>
//스크롤 바닥 감지
window.onscroll = function(e) {
    //추가되는 임시 콘텐츠
    //window height + window scrollY 값이 document height보다 클 경우,
    if((window.innerHeight + window.scrollY) > document.body.offsetHeight) {
		$.ajax({
			url:'components/crawl_list.php',
			type: 'POST',
			data:$('#scroll_id').serialize(),
			succes: function(result){
				console.dir(result);
				$('#article').html(result);
			},error: function(xhr, textStatus, errorThrown) {
                console.log(xhr,textStatus,errorThrown); 
            }
		});
		//article에 추가되는 콘텐츠를 append
    }
};

</script>
-->