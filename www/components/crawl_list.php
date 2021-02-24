<?
include "/home/knlab/_Class/Member.php"; $Mem=new Member();

	$obj = $Mem->es->page_test($_SESSION["scroll"],null);
	print_r($obj);
	foreach($obj['images'] as $doc){
		try{
			$solr_res = $Mem->solr->search('item_id:"'.$doc['item_id'].'"')['result'][0];
			//print_r($solr_res[0]['title']);
		}catch(Exception $e){
			continue;
		}
		echo("
			<div class='frame' onclick='go('Crawl_Edit.php?item_id=".$doc['item_id']."')'>
    			<div class='img_frame'>
        <!--es-->
        <img class='img' src='".$doc['image_path']."'>
        <div class='script'>
            <!--solr-->
            <div class='shortcut f14'>
                ".$solr_res['title']."
            </div>
            <div class='caption'>");
                foreach($doc['caption'] as $caption){
                
						$cap = highlight_words($caption,$_GET['keyword'],array('#000000'));
						echo $cap;
						
                echo ',';
                }
            echo("</div>
        </div>
    </div>
</div>
		");
	}
	exit;

?>