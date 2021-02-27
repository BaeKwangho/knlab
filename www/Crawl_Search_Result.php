<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);
$_SESSION["edit"]=false;
include "Axis_Header.php";
?>
<? if(isset($_GET["keyword"])){ ?>
	<div class="bar small">
		<form method="get" action="">
			<input
				class="searchbar small"
				type="text"
				title="Search"
				name="keyword"
				value="<?=$_GET['keyword']?>">
		</form>
	</div>
<?}?> 

<?
if(!isset($_GET['keyword'])){
	mvs("Crawl_Search.php");
}

$select = array(
    'query'         => "keywords:*".$_GET['keyword'].
						"* OR title:*".$_GET['keyword'].
						"* OR contents:*".$_GET['keyword']."*",
    'start'         => 0,
    'rows'          => 5,
    'fields'        => array('*'),
    'sort'          => array('created_at' => 'desc'),
    'filterquery' => array(
        'custom' => array(// or contents:*".$_GET['keyword']."*",
        ),
    ),
);

$paging = solr_paging($Mem->gps,$select,10,10,'',"?keyword=".$_GET['keyword']);

?>
<div class="c5">
	<div class="row bold">
		<text><?=$_GET['keyword']?> 에 대한 검색결과 (<?=$paging[0]->getNumFound()?>건)</text>
	</div>
	<div id="table">
		<?foreach($paging[0] as $doc){?>
		<div class="comp_out round_shadow" id="line"  onclick="go('Crawl_Edit.php?item_id=<?=$doc['item_id']?>')">
			<div class="f30 hidden center">Edit</div>
			<div class="comp_in bold f20" id="title"><?=$doc['title'][0]?></div>
			<div class="comp_in cut"><?=$doc['summary'][0]?></div>
			<div class="comp_in">
				<?foreach($doc['keywords'] as $key){?>
					<div class="bold label white round_blue h20 comp_in" id="caption"><?=$key?></div>
				<?}?>
			</div>
		</div>
		<?}?>
	</div>
	<?=$paging[2]?>
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