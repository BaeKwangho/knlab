
<?
//error_reporting(E_ALL);	ini_set("display_errors", 1);
include "_h_img.php";

if(!isset($_GET['keyword'])){
	mvs("Image_Search.php");
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
				'caption' => $_GET['keyword']
			]
		]
	]
];
$obj = $Mem->es->img_search($params);
?>
<div class="c5">
	<div class="row bold">
		<text><?=$_GET['keyword']?> 에 대한 검색결과 (<?=$obj['doc_num']?>건)</text>
	</div>
	<div style="padding: 0px 20px; display:inline-block">
		<?foreach($obj['images'] as $doc){
			try{
				$solr_res = $Mem->solr->search('item_id:"'.$doc['item_id'].'"')['result'][0];
				//print_r($solr_res[0]['title']);
			}catch(Exception $e){
				continue;
			}
			
		?>
			<div class="frame unselectable">
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

