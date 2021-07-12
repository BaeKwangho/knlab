<? 

$_SESSION['crawl']=TRUE;
include "Axis_Header.php"; ?>

<div class="btn-floating" style='background:#ff3300'onclick="go('Content_view.php?crawl=0')">정제 데이터
</div>

<?
if($_GET["CONTID"]){$_SESSION["SEARCH"]["CONTID"]=$_GET["CONTID"];$_SESSION["SEARCH"]["COUNTRY"]="";}
if($_GET["TYPE"]!=$_SESSION["SEARCH"]["TYPE"]){unset($_SESSION["SEARCH"]);}

if(!isset($_SESSION["SEARCH"]["TYPE"])){
    $type='*';
}else{
    $type=$_SESSION["SEARCH"]["TYPE"];
}
if($_SESSION["SEARCH"]["CONTID"]){
    $conti = "where a.CONTI_NAME like "; 
    if($_SESSION["SEARCH"]["CONTID"]==="유럽/CIS"){
      $conti.= "'유럽' or a.CONTI_NAME like 'CIS'";
    }elseif($_SESSION["SEARCH"]["CONTID"]==="중동/아프리카"){
      $conti.= "'중동' or a.CONTI_NAME like '아프리카'";
    }elseif($_SESSION["SEARCH"]["CONTID"]==="북미/중남미"){
      $conti.= "'북미' or a.CONTI_NAME like '남미'";
    }elseif($_SESSION["SEARCH"]["CONTID"]==="글로벌"){
      $conti.= "'글로벌'";
    }else{
      $conti.= "'아시아'";
    }
    $state = "select b.CTY_NM from nt_continents a join nt_countrys b on a.CTYID = b.IDX ".$conti.'';
    $result = $Mem->q($state);
    $country = '(';
    foreach($result as $doc){
        $country.='DC_COUNTRY:'.$doc[0].' OR ';
    }
    $country = substr($country,0,-3).')';
  }
$select = array(
    'query'         => 'DC_CODE:'.$_SESSION["AUTH"]["MID"].
                        "* AND DC_CAT:".$type." AND ".$country,
    'start'         => 0,
    'rows'          => 1,
    'fields'        => array('*'),
    'sort'          => array('DC_DT_COLLECT' => 'asc'),
    'filterquery' => array(
        'custom' => array(
            'query' => '',
        ),
    ),
);

$re = solr_paging($Mem->docs,$select,5,10);

    ?>
    <div class="div_single" style="border-bottom:1px solid #000;"><?=$Cname?> (<?=$re[3]?>)</div>
    <?
    foreach($re[0] as $r){
        $img=$Mem->qs("select FILE_PATH from nt_document_file_list where PID like ? and FILE_TYPE like 1 and STAT<9",array($r["IDX"]));
    ?>
    <table class="table_list" >
      <tr>
        <th rowspan="4" id="table_image" style="min-width:200px;width: 200px;"><img src='<?=$Mem->data["cover"].$img?>' width="100px"></img></th>
        <td colspan="4" onclick="go('Content_viewPost.php?crawl=1&id=<?=$r["id"]?>')" style="font-weight:bold; font-size:18px; padding-top:10px; height:30px;"><?=$r["DC_TITLE_KR"][0]?></td>
      </tr>
      <tr>
        <td colspan="4" style="width:500px;color:blue;font-size:14px; height:20px; padding-bottom:20px;"><?=$r["DC_TITLE_OR"][0]?></td>
      </tr>
      <tr>
        <td style="text-align:left;font-size:12px;color:green;  width:200px; padding-left:10px;border-right:1px solid #000"><?=$r["DC_AGENCY"][0]?></td>
        <td style="text-align:center;font-size:12px;color:green; width:100px;padding-left:10px;border-right:1px solid #000"><?=DT_show($r["DC_DT_WRITE"][0]);?></td>
        <td style="text-align:left;font-size:12px;color:green; width:200px; padding-left:20px;"><?=$r["DC_PAGE"][0]?> pages</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="4" style="width:600px;cursor:pointer;color:blue;font-size:11px; padding-left:10px;"onclick="window.open('<?=$r["DC_URL_LOC"]?>','_blank');"><?=$r["DC_URL_LOC"][0]?></td>
      </tr>
    </table >


    <?}?>
    <div style="padding-top:30px;"><?=$re[2]?></div>
         
