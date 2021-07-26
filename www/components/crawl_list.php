
<?
include "../_h.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);

if(!isset($_POST["scroll_id"])){
	$params=[
		'index' => 'caption',
		'body'  => [
			'query' => [
				'match' => [
					'item_id' => $_POST['item_id']
				]
			]
		]
	];
	$doc = $Mem->es->search($params)['result'][0];
	$solr_res = $Mem->gps->search('item_id:"'.$doc['item_id'].'"')['result'][0];
?>
	<div class="close" onclick="hide_poster()"></div>
	<div style="margin-top:50px;">
		<img class='img' style="width:80% !important; height:auto !important; "  src="<?=$_POST['image_path']?>">
	</div>
	<div class='img' style="color:white; height:100px; width:80%;border-bottom:1px solid white;">
		<div style="display:inline-block">
			<div>Title : <?=$doc['title'][0]?></div>
			<div>Caption : <?=$doc['caption'][0]?></div>
			<div>URL : <?=$doc['host'][0]?></div>
			<div>TimeStamp : <?=$doc['timestamp']?></div>
			<div>Category : <?=$doc['wiki_category'][0]?></div>
		</div>
	</div>
<?
}else{
	$obj = $Mem->es->page_test($_POST["scroll_id"],null);
	foreach($obj['result'] as $doc){
		try{
			$solr_res = $Mem->gps->search('item_id:"'.$doc['item_id'].'"')['result'][0];
		}catch(Exception $e){
			continue;
		}
	?>
		<div class='frame' >
			<div class='img_frame'>
			<!--es-->
				<img class='img' style="cursor:pointer;" src="<?=$doc['image_path']?>" onclick="show_poster(<?=$doc['item_id']?>,'<?=$doc['image_path']?>');">
				<div class='script' onclick="window.open('<?=$solr_res['url']?>', '_blank').focus()">
					<!--solr-->
					<div class='shortcut f14' style="cursor:pointer;">
						<?=$solr_res['title']?>
					</div>
					<div class='caption' style="cursor:pointer;">
						<?=$solr_res["host"][0]?>
					</div>
				</div>
			</div>
		</div>
	<?}?>
<?}?>