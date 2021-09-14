
<?
include "../_h.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);

if(!isset($_POST["scroll_id"])){
	$params=[
		'index' => 'caption',
		'body'  => [
			'query' => [
				'match' => [
					'image_path' => $_POST['image_path']
				]
			]
		]
	];
	$doc = $Mem->es->search($params)['result'][0];
	$solr_res = $Mem->gps->search('item_id:"'.$doc['item_id'].'"')['result'][0];
	$keywords = '';
	foreach($doc['wiki_category'] as $val){
		$keywords.=$val.', ';
	}
	$keywords = substr($keywords,0,-2);
?>
	<div class="close" onclick="hide_poster()"></div>
	<div style="margin-top:50px;">
		<img class='img' style="width:80% !important; height:auto !important; "  src="<?=$_POST['image_path']?>">
	</div>
	<div class='img' style="color:white; height:auto;width:80%;border-bottom:1px solid white; padding-bottom:10px">
		<div style="display:inline-block">
			<div class="f14 "><span style="color:gray">Image ID : </span><?=$doc['item_id']?></div>
			<div class="f14 "><span style="color:gray">Source ID : </span><?=$doc['item_id']?></div>
			<div class="f14 "><span style="color:gray">Source Title : </span><?=$solr_res['title']?></div>
			<div class="f14 "><span style="color:gray">Publisher : </span><?=$doc['host'][0]?></div>
			<div class="f14 "><span style="color:gray">Pub date : </span><?=$solr_res['creationdate'][0]?></div>
			<div class="f14 "><span style="color:gray">Type of Source : </span><?=$solr_res['fileExtension'][0]?></div>
			<div class="f14 "><span style="color:gray">Image Title :</span> <?=$doc['caption'][0]?></div>
			<div class="f14 "><span style="color:gray">Image url : </span><?=$doc['image_path']?></div>
			<div class="f14 "><span style="color:gray">Source url : </span><?=$solr_res['url']?></div>
			<div class="f14 "><span style="color:gray">Domain : </span><?=$solr_res['host'][0]?></div>
			<div class="f14 "><span style="color:gray">Keyword : </span><?=$keywords?></div>
		</div>
	</div>
	<div style="margin-left:20px;">
<?
	$params=[
		'index' => 'caption',
		'body'  => [
			'query' => [
				'match' => [
					'title' => $doc['title'][0]
				],
			],
		]
	];
	$docs = $Mem->es->search($params);
	foreach($docs['result'] as $doc){?>
		<div class='frame' >
				<div class='img_frame'>
				<!--es-->
					<img class='img' style="cursor:pointer;max-width:500px" src="<?=$doc['image_path']?>" onclick="show_poster(<?=$doc['item_id']?>,'<?=$doc['image_path']?>');">
					<div class='script' onclick="window.open('<?=$solr_res['url']?>', '_blank').focus()">
						<!--solr-->
						<div class='f14' style="cursor:pointer;color:white;">
							<?=$doc['caption'][0]?>
						</div>
					</div>
				</div>
			</div>
	<?}?>
	
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
				<img class='img' style="cursor:pointer;max-width:500px" src="<?=$doc['image_path']?>" onclick="show_poster(<?=$doc['item_id']?>,'<?=$doc['image_path']?>');">
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