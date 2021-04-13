<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);
$_SESSION["edit"]=false;
include "Axis_Header.php";

if(!isset($_GET['keyword'])){
	mvs("Crawl_Search.php");
}


?>

<script>

	function clear_input(){
		$("select[name=duration]").val("0").attr("selected","true");
		$("input[name=keyword]").val("");
		$("input[name=subscribed]").val("0");
		$("select[name=lang]").val("none").attr("selected","true");;
		$("#option1").attr("checked","true")



	}
</script>



<div style="
 margin:20px;
  -webkit-box-shadow: 1px 1px 7px 0 #6e6e6e;
  -moz-box-shadow: 3px 3px 7px 0 #6e6e6e;
  box-shadow: 1px 3px 7px 0 #6e6e6e;
">
	<div style="padding:20px;">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h1 font-weight-bold ml-4">조건 검색</h1>
    </div>
	<form action="<?=SELF?>" type="GET">
		<div class="form-row">
			<div class="col">
				<div class="input-group mb-3 input-group-lg">
					<div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">정렬 기준</label>
					</div>
					<select class="custom-select" id="sort">
						<option hidden selected>Choose...</option>
						<option value="1">1주일</option>
						<option value="2">1달</option>
						<option value="3">1년</option>
					</select>
				</div>
			</div>
			<div class="col">
				<div class="input-group mb-3 input-group-lg">
					<div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">언어</label>
					</div>
					<select class="custom-select" name="lang">
						<option selected value="none">전체</option>
						<option value="en" <?=$_GET['lang']=="en"?'selected':''?>>미국</option>
						<option value="ko" <?=$_GET['lang']=="ko"?'selected':''?>>한국</option>
						<option value="jp" <?=$_GET['lang']=="jp"?'selected':''?>>일본</option>
					</select>
				</div>
			</div>
			<div class="col">
				<div class="btn-group btn-group-toggle pl-5 ml-5" data-toggle="buttons">
					<label class="btn-lg btn btn-outline-primary">
						<input type="radio" name="subscribed" id="option1" value="0"
						<?=$_GET['subscribed']==0?'checked':''?>> 전체
					</label>
					<label class="btn-lg btn btn-outline-success">
						<input type="radio" name="subscribed" id="option2" value="1"
						<?=$_GET['subscribed']==1?'checked':''?>> 기조회
					</label>
					<label class="btn-lg btn btn-outline-danger">
						<input type="radio" name="subscribed" id="option3" value="2"
						<?=$_GET['subscribed']==2?'checked':''?>> 미조회
					</label>
				</div>	
			</div>
		</div>

		<div class="form-row">
			<div class="col">
				<div class="input-group input-group-lg">
					<div class="input-group-prepend">
						<span class="input-group-text">검색어</span>
					</div>
					<input
						type="text"
						class="form-control"
						aria-label="Sizing example input"
						aria-describedby="inputGroup-sizing-lg" value="<?=$_GET['keyword']?>" name="keyword"></div>
			</div>
			<div class="col">
				<div class="input-group mb-3 input-group-lg">
					<div class="input-group-prepend">
						<label class="input-group-text" for="inputGroupSelect01">기간</label>
					</div>
					<select class="custom-select" name="duration">
						<option selected value="0">전체</option>
						<option value="1" <?=$_GET['duration']==1?'selected':''?>>1주일</option>
						<option value="2" <?=$_GET['duration']==2?'selected':''?>>1달</option>
						<option value="3" <?=$_GET['duration']==3?'selected':''?>>1년</option>
					</select>
				</div>
			</div>
		</div>
		<div class="form-row mt-3 ">
			<div class="col-9">
				<button type="submit" class="btn btn-primary col" >검색</button>
			</div>
			<div class="col">
				<button type="button" class="btn btn-secondary col" onclick="clear_input()">초기화</button>
			</div>
		</div>
	</form>

	</div>
<?

#################
#query processing
#################

#duration date init
$today = time();
$month = mktime(0,0,0,2,1,2000)-mktime(0,0,0,1,1,2000);
$week = mktime(0,0,0,1,14,2000)-mktime(0,0,0,1,7,2000);
$year = mktime(0,0,0,1,1,2001)-mktime(0,0,0,1,1,2000);


$fq = array(
	'custom' => array(
		'query' => '*:*',
	),
);

$get = "?keyword=".$_GET['keyword'];

if($_GET['duration']){
	if($_GET['duration']=='0'){$duration="*";}
	elseif($_GET['duration']=='1'){$duration=date("Y-m-d",$today-$week).'T'.date("h:i:s",$today-$week).'Z';}
	elseif($_GET['duration']=='2'){$duration=date("Y-m-d",$today-$month).'T'.date("h:i:s",$today-$month).'Z';}
	else{$duration=date("Y-m-d",$today-$year).'T'.date("h:i:s",$today-$year).'Z';}

	
	$fq['custom']['query'].=' AND creationdate:['.$duration.' TO NOW]&NOW='.$today;
	$get.="&duration=".$_GET['duration'];
}else{
}

if($_GET['subscribed']){
	if($_GET['subscribed']==0){
		//전체
	}elseif($_GET['subscribed']==1){
		//기조회
		$fq['custom']['query'].=' AND usr_sub:'.$Mem->uid;
	}else{
		//미조회
		$fq['custom']['query'].=' AND !usr_sub:'.$Mem->uid;
	}
	$get.="&subscribed=".$_GET['subscribed'];
}else{
}

if($_GET['lang']){
	if($_GET['lang']=='none'){
		//skip
	}else{
		$fq['custom']['query'].=' AND language:'.$_GET['lang'];
	}
}else{

}

$select = array(
    'query'         => "keywords:*".$_GET['keyword'].
						"* OR title:*".$_GET['keyword'].
						"* OR contents:*".$_GET['keyword']."*"
						//." AND item_id:664941 "
						,
    'start'         => 0,
    'rows'          => 5,
    'fields'        => array('*'),
    'sort'          => array('creationdate' => 'desc'),
    'filterquery' => $fq,
);

$paging = solr_paging($Mem->gps,$select,10,10,'',$get);

$Mem->gps->modify($paging[0],$Mem->uid);


?>
	<div class="mt-5">
		<div class="row">
			<h2 class="h2 col text-sm-left ml-5">
			<?=$_GET['keyword']?'"'.$_GET['keyword'].'"':''?> 검색결과 (<?=$paging[0]->getNumFound()?> 건)</h2>
		</div>
		<div id="table">
			<?foreach($paging[0] as $doc){?>
			<div class="comp_out round_shadow" id="line"  onclick="go('Crawl_Edit.php?item_id=<?=$doc['item_id']?>')">
				<div class="f30 hidden center">Edit</div>
				<div class="row">
					<div class="bold f20 col text-left" id="title"><?=$doc['title'][0]?></div>
					<div class="col text-right pl-5">
						<?if(in_array($Mem->uid,$doc['usr_sub'])){?>
							<div style="margin-left:90%;margin-top:20px;width:20px;height:20px;border-radius:50%;background:#28a745;"></div>
						<?}else{?>
							<div style="margin-left:90%;margin-top:20px;width:20px;height:20px;border-radius:50%;background:#dc3545;"></div>
						<?}?>
					</div>
				</div>
				<div class="comp_in cut" style="width:80%"><?=$doc['summary'][0]?></div>
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