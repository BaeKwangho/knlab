<? include "Axis_Header.php"; ?>
<?
//error_reporting(E_ALL);	ini_set("display_errors", 1);

//select * from (select * from nt_document_list where a.DC_CODE like 15 or a.DC_CODE like 16 or a.DC_CODE like 1611 or a.DC_CODE like 1612 or a.DC_CODE like 1613 or a.DC_CODE like 24 or a.DC_CODE like 2410 or a.DC_CODE like 241012 or a.DC_CODE like 241017) where a.DC_CODE like 15
//$r=$Mem->q("select a.* from (".$_SESSION["CODE_ARRAY"].") a where t.DC_CODE like 15");
//var_dump($r);


if($_GET["view"]){
  $r=$Mem->qr("select * from nt_document_list where IDX like ?",$_GET["IDX"]);
  //포스트 페이지 부분 category 중복 출력을 위한 검색.
  $cats = $Mem->qa("select code from nt_document_code_list where PID = ? and stat<9",$r["IDX"]);
  $Cnames = array();
  foreach($cats as $cat){
    $cname = $Mem->qs("Select CT_NM from nt_categorys where CODE like ? and TYPE=1",$cat);
    array_push($Cnames,$cname);
  }
  $Mem->q("update nt_document_list set DC_HIT=? where IDX=? ",array($r["DC_HIT"]+1,$r["IDX"]));
  $Mem->q("insert into nt_document_access_list(UID,PID,DT) values(?,?,?) ",array($Mem->user["uid"],$r["IDX"],mktime()));
  $img=$Mem->qs("select FILE_PATH from nt_document_file_list where PID like ? and FILE_TYPE like 1 and STAT<9",array($r["IDX"]));

?>
  <div class="div_single">
    <?//TODO, 대,중분류 표기 필요?
      foreach($Cnames as $cname){
      echo "<".$cname.">";   
    }?>
  </div>
  <table class="view_main">
      <tr>
        <td colspan="4" style="font-weight:bold;font-size:18px;border-top:1px solid #000;"><?=$r["DC_TITLE_KR"]?></td>
        <td rowspan="8" style="vertical-align:top;border:0px; width:200px"><img src='<?=$Mem->data["cover"].$img?>'></img></td>
      </tr>
      <tr><td style="font-weight:bold;font-size:16px;color:blue;border-top:1px solid #000;"colspan="4"><?=$r["DC_TITLE_OR"]?></td></tr>
      <tr>
        <td style="padding-top:0px; padding-bottom:0px;color:green;width:150px;border-right:1px dotted #AAA;"><?=$r["DC_AGENCY"]?></td>
        <td style="padding-top:0px; padding-bottom:0px;color:green;width:70px;border-right:1px dotted #AAA;"><?=$r["DC_DT_WRITE"]?date("Y-m-d",$r["DC_DT_WRITE"]):"-"?></td>
        <td style="padding-top:0px; padding-bottom:0px;color:green;width:70px;border-right:1px dotted #AAA;"><?=$r["DC_PAGE"]?> pages</td>
        <td style="width:30%"></td>
      </tr>
      <tr><td colspan="4" style="cursor:pointer;color:blue;font-size:11px;"onclick="window.open('<?=$r["DC_URL_LOC"]?>','_blank');">[URL]:<?=$r["DC_URL_LOC"]?></td></tr>
      <tr><td colspan="4">카테고리 : [<?=$r["DC_TYPE"]?>] , [<?=trim($r["DC_COUNTRY"],',')?>]</td></tr>
      <tr><td colspan="4" style="font-size:14px;border-top:1px solid #000;"><?=$r["DC_CONTENT"]?></td></tr>
      <tr><td colspan="4" style="border-top:1px solid #000;border-bottom:1px solid #000">관련문서 : <?
        
        $tokens = explode('/',$r["DC_MEMO1"]);
        for($i=1;$i<count($tokens);$i++){
          $doc=$Mem->qs("select FILE_PATH from nt_document_file_list where PID like ? and FILE_NAME like ? and STAT<9",array($_GET["IDX"],$tokens[$i]));
          ?><a style="padding:5px;" href="<?=$Mem->data["document"].$doc?>" download="<?=$tokens[$i]?>"><?=$tokens[$i]?></a><?
        }
      ?></td></tr>
      <tr><td>연관링크</td>
		<td colspan="3">
		<?
			$link=explode(' ',$r["DC_LINK"]);
			if($link[0]){
				for($i=0;$i<count($link);$i++){
					$l=$Mem->qr("select DC_TITLE_KR,DC_DT_WRITE from nt_document_list where idx like ?",$link[$i]);
			?>
			<div style="padding:10px;float:left;width:50%-30px;line-height:10px;cursor:pointer;">
			<span onclick="go('Content_view.php?view=1&IDX=<?=$link[$i]?>','data_view','width=900,height=900,scrollbars=1');">
			<?=$l["DC_TITLE_KR"]?> / <?=date("Y-m-d",$l["DC_DT_WRITE"])?>
			</div>
	<?	
				}
			}
		?>
		</td>
      </tr>
      
<?
}?>