<? 
include "Axis_Header.php"; ?>

<div class="btn-floating" onclick="go('Crawl_Content.php?crawl=1')">크롤 데이터
</div>

<?
$time = time();


//error_reporting(E_ALL);	ini_set("display_errors", 1);

//select * from (select * from nt_document_list where a.DC_CODE like 15 or a.DC_CODE like 16 or a.DC_CODE like 1611 or a.DC_CODE like 1612 or a.DC_CODE like 1613 or a.DC_CODE like 24 or a.DC_CODE like 2410 or a.DC_CODE like 241012 or a.DC_CODE like 241017) where a.DC_CODE like 15
//$r=$Mem->q("select a.* from (".$_SESSION["CODE_ARRAY"].") a where t.DC_CODE like 15");
//var_dump($r);
  if($_SESSION["AUTH"]["MID"]=="2410"){
    if($_GET["CID"]){$Cname=$Mem->qs("Select CT_NM from nt_categorys where CODE like ? and TYPE=1",$_GET["CID"]);
    }else{
      //$Cname="미지정";
      $Cname=$Mem->qs("Select CT_NM from nt_categorys where CODE like ? and TYPE=1",$_SESSION["AUTH"]["MID"]);
    }
    $place="join nt_document_code_list b on b.pid=a.idx where a.STAT<9";
    $item=array();
    $word_where="";
    if($_GET["CID"]){
      $place.=" and b.CODE like ?";
      array_push($item,$_GET["CID"]."%");
    }else{
      $place.=" and b.CODE like ?";
      array_push($item,$_SESSION["AUTH"]["MID"]."%");
    }
    $desc = "a.DC_DT_WRITE desc";
    if($_GET["TYPE"]){
      $place.=" and a.DC_TYPE like ?";
      array_push($item,"%".$_GET["TYPE"]."%");
      if($_GET["TYPE"]==="글로벌동향"){
        $desc = "a.DC_DT_REGI desc";
      }
    }
    
    if($_GET["WORD"]){
      $item2=array();
      $word_where.="(select a.* from nt_document_list a where";
      $word_where.=" a.DC_TITLE_OR like ?";array_push($item2,"%".$_GET["WORD"]."%");
      $word_where.=" or a.DC_TITLE_KR like ?";array_push($item2,"%".$_GET["WORD"]."%");
      $word_where.=" or a.DC_SMRY_KR like ?";array_push($item2,"%".$_GET["WORD"]."%");
      $word_where.=" or a.DC_KEYWORD like ?";array_push($item2,"%".$_GET["WORD"]."%");
      $word_where.=" or a.DC_CONTENT like ?";array_push($item2,"%".$_GET["WORD"]."%");
      $word_where.=" or a.DC_COUNTRY like ?)";array_push($item2,"%".$_GET["WORD"]."%");
      if($item[0]){array_push($item2,$item[0]);}if($item[1]){array_push($item2,$item[1]);}
  
      $re=paging("select DISTINCT(a.IDX) as idxss, a.* from ".$word_where." a ".$place,$item2,20,20,"a.DC_DT_WRITE desc");
    }else{
      $re=paging("select DISTINCT(a.IDX) as idxss, a.* from nt_document_list a ".$place,$item,20,20,"a.DC_DT_WRITE desc");
    }
    /*
    if($_GET["CID"]&&$_GET["TYPE"]){
      array_push($items,$_GET["CID"]."%");
      array_push($items,"%".$_GET["TYPE"]."%");
      $re=paging("select DISTINCT(a.IDX) as idxss, a.* from ".$_SESSION["AUTH"]["CODE_QUERY"]." a where a.DC_CODE like ? and a.DC_TYPE like ? ",$items,20,20,"a.DC_DT_WRITE desc");
    }elseif($_GET["CID"]&&!$_GET["TYPE"]){
      $re=paging("select DISTINCT(a.IDX) as idxss, a.* from ".$_SESSION["AUTH"]["CODE_QUERY"]." a where a.DC_CODE like ? ",array($_GET["CID"]."%"),20,20,"a.DC_DT_WRITE desc");
    }elseif(!$_GET["CID"]&&$_GET["TYPE"]){
      $re=paging("select DISTINCT(a.IDX) as idxss, a.* from nt_document_list a where a.DC_TYPE like ? ",array("%".$_GET["TYPE"]."%"),20,20,"a.DC_DT_WRITE desc");
    }else{
      exit;
    */
    ?>
    <div class="div_single" style="border-bottom:1px solid #000;"><?=$Cname?> (<?=$re[3]?>)</div>
    <?
    for($i=0; $i < $re[0]->rowCount(); $i++){
        $r=$re[0]->fetch();
        $img=$Mem->qs("select FILE_PATH from nt_document_file_list where PID like ? and FILE_TYPE like 1 and STAT<9",array($r["IDX"]));
    ?>
    <table class="table_list" >
      <tr>
        <th rowspan="4" id="table_image" style="min-width:200px;width: 200px;"><img src='<?=$Mem->data["cover"].$img?>' width="100px"></img></th>
        <td colspan="4" onclick="go('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>')" style="font-weight:bold; font-size:18px; padding-top:10px; height:30px;"><?=$r["DC_TITLE_KR"]?></td>
      </tr>
      <tr>
        <td colspan="4" style="width:500px;color:blue;font-size:14px; height:20px; padding-bottom:20px;"><?=$r["DC_TITLE_OR"]?></td>
      </tr>
      <tr>
        <td style="text-align:left;font-size:12px;color:green;  width:200px; padding-left:10px;border-right:1px solid #000"><?=$r["DC_AGENCY"]?></td>
        <td style="text-align:center;font-size:12px;color:green; width:100px;padding-left:10px;border-right:1px solid #000"><?=DT_show($r["DC_DT_WRITE"]);?></td>
        <td style="text-align:left;font-size:12px;color:green; width:200px; padding-left:20px;"><?=$r["DC_PAGE"]?> pages</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="4" style="width:600px;cursor:pointer;color:blue;font-size:11px; padding-left:10px;"onclick="window.open('<?=$r["DC_URL_LOC"]?>','_blank');"><?=$r["DC_URL_LOC"]?></td>
      </tr>
    </table >


    <?}?>
    <div style="padding-top:30px;"><?=$re[2]?></div>
<?}elseif($_SESSION["AUTH"]["MID"]=="2411"){
    //초록 배너를 클릭 시 초기화 해주지만 아래 검색 조건에 하나도 들어가는게 없으니.. 만들어줘야할듯.
    if($_GET["TYPE"]!=$_SESSION["SEARCH"]["TYPE"]){unset($_SESSION["SEARCH"]);}
    if($_GET["country"]){$_SESSION["SEARCH"]["COUNTRY"]=$_GET["country"];}
    if($_GET["date"]){$_SESSION["SEARCH"]["DATE"]=$_GET["date"];}
    if($_GET["list"]){$_SESSION["SEARCH"]["LIST"]=$_GET["list"];}
    if($_GET["doctype"]){$_SESSION["SEARCH"]["DOCTYPE"]=$_GET["doctype"];}
    if($_GET["TYPE"]){$_SESSION["SEARCH"]["TYPE"]=$_GET["TYPE"];}
    if($_GET["TYPE"]=="아카이브"){$_SESSION["SEARCH"]["COUNTRY"]="";$_SESSION["SEARCH"]["DOCTYPE"]="";}
    if($_GET["TYPE"]=="레퍼런스"){$_SESSION["SEARCH"]["DATE"]="";}
    if(!$_GET["TYPE"]||$_GET["TYPE"]=="발간물"||$_GET["TYPE"]=="글로벌동향"){$_SESSION["SEARCH"]["DOCTYPE"]="";}
    if($_GET["CONTID"]){$_SESSION["SEARCH"]["CONTID"]=$_GET["CONTID"];$_SESSION["SEARCH"]["COUNTRY"]="";}
    
    
    $where_state="a.STAT<9";
    $where_values=array();
    ?>
    <table class="table_list">
      <tr>
          <td class="div_single">국제의료</td><td style="width:40%"class="div_single2"><?=!$_GET["TYPE"]?">  신규등록자료":">  ".$_GET["TYPE"];?></td>
        <form name="category"action="<?=SELF?>" method="get">
        <td>
          <?if(!$_SESSION["SEARCH"]["TYPE"]||$_SESSION["SEARCH"]["TYPE"]==="발간물"||$_SESSION["SEARCH"]["TYPE"]==="글로벌동향"||$_SESSION["SEARCH"]["TYPE"]==="레퍼런스"){
            ?><td class="div_select">
            국가
            <select name="country" onChange="submit()">
                <?//국가 불러오기. nt_countrys와 nt_continents 결합
                
                if($_SESSION["SEARCH"]["CONTID"]){
                  $cont = $Mem->q("select a.* from nt_countrys a join nt_continents b on a.IDX = b.CTYID where b.CONTI_NAME like '".$_SESSION["SEARCH"]["CONTID"]."'");
                  
                  ?><option value=""><?=!$_SESSION["SEARCH"]["COUNTRY"]?"전체":$_SESSION["SEARCH"]["COUNTRY"]?></option>
                  <?
                  while($tt=$cont->fetch()){
                    ?>
                      <option value="<?=$tt["CTY_NM"]?>"><?=$tt["CTY_NM"]?></option>
                    <?
                  }

                }else{

                
                ?>
                <option value=""><?=!$_SESSION["SEARCH"]["COUNTRY"]?"전체":$_SESSION["SEARCH"]["COUNTRY"]?></option>
                <option value="전체">전체</option>
                <option value="미국">미국</option>
                <option value="일본">일본</option>
                <option value="중국">중국</option>
                <option value="영국">영국</option>
                <option value="프랑스">프랑스</option>
                <option value="유럽">유럽</option>
                <option value="캐나다">캐나다</option>
                <?}?>
            </select>
            </td>
          <?}else{?>
            <td style="width:150px"></td>
          <?}?>
          <?if(!$_SESSION["SEARCH"]["TYPE"]||$_SESSION["SEARCH"]["TYPE"]==="발간물"||$_SESSION["SEARCH"]["TYPE"]==="글로벌동향"||$_SESSION["SEARCH"]["TYPE"]==="아카이브"){?>
            <td class="div_select">
            기간
            <select name="date" onChange="submit()">
                <option value=""><?=!$_SESSION["SEARCH"]["DATE"]?"전체":$_SESSION["SEARCH"]["DATE"]?></option>
                <option value="전체">전체</option>
                <option value="1주일">1주일</option>
                <option value="1개월">1개월</option>
                <option value="6개월">6개월</option>
                <option value="1년">1년</option>
            </select>
            </td>
          <?}elseif($_SESSION["SEARCH"]["TYPE"]==="레퍼런스"){?>
            <td class="div_select">
            유형
            <select name="doctype" onChange="submit()">
                <option value=""><?=!$_SESSION["SEARCH"]["DOCTYPE"]?"전체":$_SESSION["SEARCH"]["DOCTYPE"]?></option>
                <option value="전체">전체</option>
                <option value="전략">전략</option>
                <option value="정책동향">정책동향</option>
                <option value="산업동향">산업동향</option>
                <option value="보고서">보고서</option>
                <option value="통계백서">통계백서</option>
                <option value="규제지침">규제지침</option>
            </select>
            </td>
          <?}?>
            <td class="div_select">
            목록
            <select name="list" onChange="submit()">
                <option value="" selected disabled hidden><?=!$_SESSION["SEARCH"]["LIST"]?"20":$_SESSION["SEARCH"]["LIST"]?></option>
                <option value=20>20</option>
                <option value=50>50</option>
                <option value=100>100</option>
            </select>
            </td>
        </td>
        </form>
        
      </tr>
    </table>
      <?
        if($_SESSION["SEARCH"]["DATE"]){
          $time = time();
          if($_SESSION["SEARCH"]["DATE"]==="1주일"){$time=$time-(7*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
          if($_SESSION["SEARCH"]["DATE"]==="1개월"){$time=$time-(30*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
          if($_SESSION["SEARCH"]["DATE"]==="6개월"){$time=$time-(182*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
          if($_SESSION["SEARCH"]["DATE"]==="1년"){$time=$time-(365*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
        }
        if($_SESSION["SEARCH"]["LIST"]){
          $list=$_SESSION["SEARCH"]["LIST"];
        }else{
          $list=20;
        }
        if($_SESSION["SEARCH"]["COUNTRY"]==="전체"||!$_SESSION["SEARCH"]["COUNTRY"]){
        }else{
          $where_state.=" and a.DC_COUNTRY like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["COUNTRY"]."%");
        }
        if($_SESSION["SEARCH"]["DOCTYPE"]==="전체"||!$_SESSION["SEARCH"]["DOCTYPE"]){
        }else{
          $where_state.=" and a.DC_TYPE like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["DOCTYPE"]."%");
        }
        $orderby="a.DC_DT_WRITE desc";

        if($_SESSION["SEARCH"]["TYPE"]==="글로벌동향"||$_SESSION["SEARCH"]["TYPE"]==="발간물"||$_SESSION["SEARCH"]["TYPE"]==="레퍼런스"){
          $where_state.=" and a.DC_CAT like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["TYPE"]."%");
        }elseif($_SESSION["SEARCH"]["TYPE"]==="아카이브"){
        }else{
          $where_state.=" and a.DC_CAT not like ?";
          array_push($where_values,"%아카이브%");
          $orderby="a.DC_DT_REGI desc";
        }

        if($_GET["WORD"]){
          #$where_state.=" and a.DC_TITLE_KR like ? or a.DC_TITLE_OR like ? ";
          #array_push($where_values,"%".$_GET["WORD"]."%");
          #array_push($where_values,"%".$_GET["WORD"]."%");
          $word_where.="(select k.* from nt_document_list k where";
          $word_where.=" k.DC_TITLE_OR like '"."%".$_GET["WORD"]."%'";
          $word_where.=" or k.DC_TITLE_KR like '"."%".$_GET["WORD"]."%'";
          $word_where.=" or k.DC_SMRY_KR like '"."%".$_GET["WORD"]."%'";
          $word_where.=" or k.DC_KEYWORD like '"."%".$_GET["WORD"]."%'";
          $word_where.=" or k.DC_CONTENT like '"."%".$_GET["WORD"]."%'";
          $word_where.=" or k.DC_COUNTRY like '"."%".$_GET["WORD"]."%') a";
        }else{
          $word_where="nt_document_list a";
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
          $where_state.=" and c.CODE like ?";
          array_push($where_values,$_SESSION["AUTH"]["MID"]."%");
          $re=paging("select DISTINCT(a.IDX) as idxss, a.* from nt_document_cty_list b 
          join ".$word_where." on b.PID = a.IDX 
          join nt_document_code_list c on c.pid=a.idx 
          where b.CTYID in
          (select a.CTYID from nt_continents a join nt_countrys b on a.CTYID = b.IDX ".$conti.")
          and ".$where_state,$where_values,$list,20,$orderby);
        }else{
          $where_state.=" and c.CODE like ?";
          array_push($where_values,$_SESSION["AUTH"]["MID"]."%");
          $re=paging("select DISTINCT(a.IDX) as idxss, a.* from ".$word_where." 
          join nt_document_code_list c on c.pid=a.idx
          where ".$where_state,$where_values,$list,20,$orderby);
        }
        /*
        CONTID가 있는 경우 paging에 들어가는 쿼리문을 직접적으로 바꾸어서 전송한다 (모듈을 아직 안만들었으므로...)
        paging은 모든 get type의 parameter가 session에 저장된 뒤 그것을 검수하고 추가하는 방식이다.
        그렇기에 사용하지 않거나 새로운 get방식의 검색이 진행될 때 기존의 session을 잘 관리 해주어야겠다.
        
        */
    if(!$_SESSION["SEARCH"]["TYPE"]||$_SESSION["SEARCH"]["TYPE"]==="글로벌동향"){
      for($i=0; $i < $re[0]->rowCount(); $i++){
          $r=$re[0]->fetch();
          
      ?>
      <table class="table_view_list" style="background-color:<?=$i%2==1?"white":"rgba(242,242,242)"?>">
        <tr>
          <?if(!$_SESSION["SEARCH"]["TYPE"]){
        }?>
          <td id="title" colspan="3" onclick="go('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>')" style="cursor:pointer;font-weight:bold; font-size:14px; padding-top:10px; "><?=$r["DC_TITLE_KR"]?>
          <?if($r["DC_DT_REGI"]>($time-(7*24*60*60))){?>
                 <div class="button_tag">new</div></td>
            <?}?>
          </td>
        </tr>
        <tr>
          <td style="color:blue;font-size:13px; max-width:700px;padding-right:30px;"><?=$r["DC_TITLE_OR"]?></td>
          <td style="text-align:center;font-size:12px;color:green; max-width:190px; padding-left:20px;padding-right:20px;border-right:1px solid #000;border-left:1px solid #000"><?=$r["DC_AGENCY"]?></td>
          <td style="text-align:center;font-size:12px;color:green; width:100px;padding-left:10px;"><?=DT_show($r["DC_DT_WRITE"]);?></td>
        </tr>
        <tr>
        <td colspan="3" style="font-size:13px; color:gray;">카테고리 : [<?=$r["DC_TYPE"]?>] , [<?=trim($r["DC_COUNTRY"],',')?>]</td>
        </tr>
      </table>
      <?}?>
    <?}elseif($_SESSION["SEARCH"]["TYPE"]==="발간물"){
      for($i=0; $i < $re[0]->rowCount(); $i++){
        $r=$re[0]->fetch();
      ?>
      <table class="table_view_list" style="background-color:<?=$i%2==1?"white":"rgba(242,242,242)"?>">
      <tr>
        <?  $img=$Mem->qs("select FILE_PATH from nt_document_file_list where PID like ? and FILE_TYPE like 1 and STAT<9",array($r["IDX"]));
        ?><th rowspan="4" id="table_image" style="width: 170px; "><img src='<?=$Mem->data["cover"].$img?>' style="height:110px ;max-width:150px;width:auto "></img></th>
        <td id="title" colspan="3" onclick="go('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>')" style="cursor:pointer;font-weight:bold; font-size:15px; padding-top:15px;"><?=$r["DC_TITLE_KR"]?>
        <?if($r["DC_DT_REGI"]>($time-(7*24*60*60))){?>
                 <div class="button_tag">new</div></td>
            <?}?>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="color:blue;font-size:13px; width:1000px;height:20px; "><?=$r["DC_TITLE_OR"]?></td>
      </tr>
      <tr>
        <td style="text-align:left;font-size:12px;color:green;  width:200px; padding-left:10px;border-right:1px solid #000"><?=$r["DC_AGENCY"]?></td>
        <td style="text-align:center;font-size:12px;color:green; width:100px;padding-left:10px;border-right:1px solid #000"><?=DT_show($r["DC_DT_WRITE"]);?></td>
        <td style="text-align:left;font-size:12px;color:green; width:200px; padding-left:20px;"><?=$r["DC_PAGE"]?> pages</td>
      </tr>
      <tr>
        <td colspan="4" style="color:blue;font-size:12px; width:500px;height:20px;padding-left:10px;padding-bottom:10px; "><?=$r["DC_URL_LOC"]?></td>
      </tr>
      </table >
      <?}?>
    <?}elseif($_SESSION["SEARCH"]["TYPE"]==="레퍼런스"){?>
        <table style="table-layout:fixed;width:1200px; padding-bottom:10px; border-bottom:1px solid #000">
          <tr>
            <td class="table_head"style="width:12%">국가</td>
            <td class="table_head"style="width:19%">유형</td>
            <td class="table_head"style="width:35%">내용</td>
            <td class="table_head">타이틀</td>
          </tr>
    <?
        for($i=0; $i < $re[0]->rowCount(); $i++){
          $r=$re[0]->fetch();
    ?>
          <tr>
          <td class="table_content"style="width:250px"><?=trim($r["DC_COUNTRY"],',')?></td>
            <td class="table_content"style="width:250px"><?=$r["DC_TYPE"]?></td>
            <td class="table_content"style="width:250px"><?=$r["DC_TITLE_KR"]?></td>
            <td class="table_content" style="width:100px;"><a onclick=window.open("<?=$r["DC_URL_LOC"]?>")><?=$r["DC_TITLE_OR"]?></a></td>
          </tr>
        <?}?>
        </table>

    <?}elseif($_SESSION["SEARCH"]["TYPE"]==="아카이브"){?>
        <table style="table-layout:fixed;width:1200px; padding-bottom:10px; border-bottom:1px solid #000">
          <tr>
            <td class="table_head"style="width:5%">No</td>
            <td class="table_head"style="width:55%">Title</td>
            <td class="table_head"style="width:25%">Publisher</td>
            <td class="table_head"style="width:8%">Date_pub</td>
            <td class="table_head"style="width:7%">Pages</td>
          </tr>
    <?  
        for($i=0; $i < $re[0]->rowCount(); $i++){
          $r=$re[0]->fetch();
    ?>
          <tr>
            <td class="table_content" style="text-align:center"><?=$re[1]-$i?></td>
            <td class="table_content"><a onclick="window.open('<?=$r["DC_URL_LOC"]?>','_blank');"><?=$r["DC_TITLE_OR"]?></a>
            <?if($r["DC_CONTENT"]){?>
                 <div class="button_tag" onclick="window.open('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>',600,300);">요약</div></td>
            <?}?>
            <td class="table_content"><?=$r["DC_AGENCY"]?></td>
            <td class="table_content"><?=DT_show($r["DC_DT_WRITE"]);?></td>
            <td class="table_content" style="text-align:right;"><?=$r["DC_PAGE"]?></td>
          </tr>
        <?}?>
        </table>
    <?}?>
    </table>
    <div style="padding-top:30px;"><?=$re[2]?></div>
    <?
  }elseif($_SESSION["AUTH"]["MID"]=="2413"){
    if($_GET["TYPE"]!=$_SESSION["SEARCH"]["TYPE"]){unset($_SESSION["SEARCH"]);}
    if($_GET["country"]){$_SESSION["SEARCH"]["COUNTRY"]=$_GET["country"];}
    if($_GET["date"]){$_SESSION["SEARCH"]["DATE"]=$_GET["date"];}
    if($_GET["list"]){$_SESSION["SEARCH"]["LIST"]=$_GET["list"];}
    if($_GET["doctype"]){$_SESSION["SEARCH"]["DOCTYPE"]=$_GET["doctype"];}
    if($_GET["TYPE"]){$_SESSION["SEARCH"]["TYPE"]=$_GET["TYPE"];}
    if($_GET["TYPE"]=="아카이브"){$_SESSION["SEARCH"]["COUNTRY"]="";$_SESSION["SEARCH"]["DOCTYPE"]="";}
    if($_GET["TYPE"]=="레퍼런스"){$_SESSION["SEARCH"]["DATE"]="";}
    if($_GET["CONTID"]){$_SESSION["SEARCH"]["CONTID"]=$_GET["CONTID"];$_SESSION["SEARCH"]["COUNTRY"]="";}
    
    
    $where_state="a.STAT<9";
    $where_values=array();
    ?>
    <table class="table_list">
      <tr>
          <td class="div_single">재료과학</td><td style="width:40%"class="div_single2"><?=!$_GET["TYPE"]?">  신규등록자료":">  ".$_GET["TYPE"];?></td>
        <form name="category"action="<?=SELF?>" method="get">
        <td>
          <?if($_SESSION["SEARCH"]["TYPE"]!=="아카이브"){
            ?><td class="div_select">
            국가
            <select name="country" onChange="submit()">
                <option value=""><?=!$_SESSION["SEARCH"]["COUNTRY"]?"전체":$_SESSION["SEARCH"]["COUNTRY"]?></option>
                <option value="전체">전체</option>
                <option value="미국">미국</option>
                <option value="일본">일본</option>
                <option value="중국">중국</option>
                <option value="영국">영국</option>
                <option value="프랑스">프랑스</option>
                <option value="유럽">유럽</option>
                <option value="캐나다">캐나다</option>
            </select>
            </td>
          <?}else{?>
            <td style="width:150px"></td>
          <?}?>
          <?if($_SESSION["SEARCH"]["TYPE"]!=="아카이브"&&$_SESSION["SEARCH"]["TYPE"]!=="레퍼런스"){?>
            <td class="div_select">
            등록일
            <select name="date" onChange="submit()">
                <option value=""><?=!$_SESSION["SEARCH"]["DATE"]?"전체":$_SESSION["SEARCH"]["DATE"]?></option>
                <option value="전체">전체</option>
                <option value="1주일">1주일</option>
                <option value="1개월">1개월</option>
                <option value="6개월">6개월</option>
                <option value="1년">1년</option>
            </select>
            </td>
          <?}
            if($_SESSION["SEARCH"]["TYPE"]!=="아카이브"){
            $types=$Mem->q("select distinct(DC_TYPE) from nt_document_list where DC_CODE like '2413%'");
            ?>
            <td class="div_select">
            유형
            <select name="doctype" onChange="submit()">
                <option value=""><?=!$_SESSION["SEARCH"]["DOCTYPE"]?"전체":$_SESSION["SEARCH"]["DOCTYPE"]?></option>
                <option value="전체">전체</option>
              <?while($t=$types->fetch()){?>
                <option value="<?=$t["DC_TYPE"]?>"><?=$t["DC_TYPE"]?></option>
              <?}?>
          </select>
            </td>
        <?}
          if($_SESSION["SEARCH"]["TYPE"]!=="아카이브"){
          ?>
            <td class="div_select">
            목록
            <select name="list" onChange="submit()">
                <option value="" selected disabled hidden><?=!$_SESSION["SEARCH"]["LIST"]?"20":$_SESSION["SEARCH"]["LIST"]?></option>
                <option value=20>20</option>
                <option value=50>50</option>
                <option value=100>100</option>
            </select>
            </td>
          <?}?>
        </td>
        </form>
        
      </tr>
    </table>
      <?
        if($_SESSION["SEARCH"]["DATE"]){
          $time = time();
          if($_SESSION["SEARCH"]["DATE"]==="1주일"){$time=$time-(7*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
          if($_SESSION["SEARCH"]["DATE"]==="1개월"){$time=$time-(30*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
          if($_SESSION["SEARCH"]["DATE"]==="6개월"){$time=$time-(182*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
          if($_SESSION["SEARCH"]["DATE"]==="1년"){$time=$time-(365*24*60*60); $where_state.=" and a.DC_DT_WRITE>=?";array_push($where_values,$time);}
        }
        if($_SESSION["SEARCH"]["LIST"]){
          $list=$_SESSION["SEARCH"]["LIST"];
        }else{
          $list=20;
        }
        if($_SESSION["SEARCH"]["COUNTRY"]==="전체"||!$_SESSION["SEARCH"]["COUNTRY"]){
        }else{
          $where_state.=" and a.DC_COUNTRY like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["COUNTRY"]."%");
        }
        if($_SESSION["SEARCH"]["DOCTYPE"]==="전체"||!$_SESSION["SEARCH"]["DOCTYPE"]){
        }else{
          $where_state.=" and a.DC_TYPE like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["DOCTYPE"]."%");
        }
        $orderby="a.DC_DT_WRITE desc";

        if($_SESSION["SEARCH"]["TYPE"]==="동향자료"||$_SESSION["SEARCH"]["TYPE"]==="발간물"||$_SESSION["SEARCH"]["TYPE"]==="정책자료"){
          $where_state.=" and a.DC_CAT like ?";
          if($_SESSION["SEARCH"]["TYPE"]==="동향자료"){
            array_push($where_values,"동향");
          }else{
            array_push($where_values,"%".$_SESSION["SEARCH"]["TYPE"]."%");
          }
        }elseif($_SESSION["SEARCH"]["TYPE"]==="아카이브"){
        }elseif($_SESSION["SEARCH"]["TYPE"]==="레퍼런스"){
          $where_state.=" and a.DC_CAT like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["TYPE"]."%");
          $orderby="a.DC_DT_REGI desc";
        }

        if($_GET["WORD"]){
          $where_state.=" and a.DC_TITLE_KR like ?";
          array_push($where_values,"%".$_GET["WORD"]."%");
        }
        if($_SESSION["SEARCH"]["CONTID"]){
          $where_state.=" and a.DC_COUNTRY like ?";
          array_push($where_values,"%".$_SESSION["SEARCH"]["CONTID"]."%");
        }
          $where_state.=" and c.CODE like ?";
          array_push($where_values,$_SESSION["AUTH"]["MID"]."%");
          $re=paging("select DISTINCT(a.IDX) as idxss, a.* from nt_document_list a
          join nt_document_code_list c on c.pid = a.idx
          where ".$where_state,$where_values,$list,20,$orderby);

        /*
        CONTID가 있는 경우 paging에 들어가는 쿼리문을 직접적으로 바꾸어서 전송한다 (모듈을 아직 안만들었으므로...)
        paging은 모든 get type의 parameter가 session에 저장된 뒤 그것을 검수하고 추가하는 방식이다.
        그렇기에 사용하지 않거나 새로운 get방식의 검색이 진행될 때 기존의 session을 잘 관리 해주어야겠다.
        
        */
    if(!$_SESSION["SEARCH"]["TYPE"]||$_SESSION["SEARCH"]["TYPE"]==="동향자료"){
      for($i=0; $i < $re[0]->rowCount(); $i++){
          $r=$re[0]->fetch();
          
      ?>
      <table class="table_view_list" style="background-color:<?=$i%2==1?"white":"rgba(242,242,242)"?>">
        <tr>
          <?if(!$_SESSION["SEARCH"]["TYPE"]){
        }?>
          <td id="title" colspan="3" onclick="go('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>')" style="cursor:pointer;font-weight:bold; font-size:14px; padding-top:10px; "><?=$r["DC_TITLE_KR"]?>
          <?if($r["DC_DT_REGI"]>($time-(7*24*60*60))){?>
                 <div class="button_tag">new</div></td>
            <?}?>
          </td>
        </tr>
        <tr>
          <td style="color:blue;font-size:13px; max-width:700px;padding-right:30px;"><?=$r["DC_TITLE_OR"]?></td>
          <td style="text-align:center;font-size:12px;color:green; max-width:190px; padding-left:20px;padding-right:20px;border-right:1px solid #000;border-left:1px solid #000"><?=$r["DC_AGENCY"]?></td>
          <td style="text-align:center;font-size:12px;color:green; width:100px;padding-left:10px;"><?=DT_show($r["DC_DT_WRITE"]);?></td>
        </tr>
        <tr>
        <td colspan="3" style="font-size:13px; color:gray;">카테고리 : [<?=$r["DC_TYPE"]?>] , [<?=$r["DC_COUNTRY"]?>]</td>
        </tr>
      </table >
      <?}?>
    <?}elseif($_SESSION["SEARCH"]["TYPE"]==="발간물"||$_SESSION["SEARCH"]["TYPE"]==="정책자료"){
      for($i=0; $i < $re[0]->rowCount(); $i++){
        $r=$re[0]->fetch();
      ?>
      <table class="table_view_list" style="background-color:<?=$i%2==1?"white":"rgba(242,242,242)"?>">
      <tr>
        <?  $img=$Mem->qs("select FILE_PATH from nt_document_file_list where PID like ? and FILE_TYPE like 1 and STAT<9",array($r["IDX"]));
        ?><th rowspan="4" id="table_image" style="width: 170px; "><img src='<?=$Mem->data["cover"].$img?>' style="height:110px ;max-width:150px;width:auto "></img></th>
        <td id="title" colspan="3" onclick="go('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>')" style="cursor:pointer;font-weight:bold; font-size:15px; padding-top:15px;"><?=$r["DC_TITLE_KR"]?>
        <?if($r["DC_DT_REGI"]>($time-(7*24*60*60))){?>
                 <div class="button_tag">new</div></td>
            <?}?>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="color:blue;font-size:13px; width:1000px;height:20px; "><?=$r["DC_TITLE_OR"]?></td>
      </tr>
      <tr>
        <td style="text-align:left;font-size:12px;color:green;  width:200px; padding-left:10px;border-right:1px solid #000"><?=$r["DC_AGENCY"]?></td>
        <td style="text-align:center;font-size:12px;color:green; width:100px;padding-left:10px;border-right:1px solid #000"><?=DT_show($r["DC_DT_WRITE"]);?></td>
        <td style="text-align:left;font-size:12px;color:green; width:200px; padding-left:20px;"><?=$r["DC_PAGE"]?> pages</td>
      </tr>
      <tr>
        <td colspan="4" style="color:blue;font-size:12px; width:500px;height:20px;padding-left:10px;padding-bottom:10px; "><?=$r["DC_URL_LOC"]?></td>
      </tr>
      </table >
      <?}?>
    <?}elseif($_SESSION["SEARCH"]["TYPE"]==="레퍼런스"){?>
        <table style="table-layout:fixed;width:1200px; padding-bottom:10px; border-bottom:1px solid #000">
          <tr>
            <td class="table_head"style="width:12%">국가</td>
            <td class="table_head"style="width:19%">유형</td>
            <td class="table_head"style="width:35%">내용</td>
            <td class="table_head">원문 제목</td>
          </tr>
    <?
        for($i=0; $i < $re[0]->rowCount(); $i++){
          $r=$re[0]->fetch();
    ?>
          <tr>
          <td class="table_content"style="width:250px"><?=trim($r["DC_COUNTRY"],',')?></td>
            <td class="table_content"style="width:250px"><?=$r["DC_TYPE"]?></td>
            <td class="table_content"style="width:250px"><?=$r["DC_TITLE_KR"]?></td>
            <td class="table_content" style="width:100px;"><a onclick=window.open("<?=$r["DC_URL_LOC"]?>")><?=$r["DC_TITLE_OR"]?></a></td>
          </tr>
        <?}?>
        </table>

    <?}elseif($_SESSION["SEARCH"]["TYPE"]==="아카이브"){?>
        <table style="table-layout:fixed;width:1200px; padding-bottom:10px; border-bottom:1px solid #000">
          <tr>
            <td class="table_head"style="width:5%">No</td>
            <td class="table_head"style="width:55%">Title</td>
            <td class="table_head"style="width:25%">Publisher</td>
            <td class="table_head"style="width:8%">Date_pub</td>
            <td class="table_head"style="width:7%">Pages</td>
          </tr>
    <?  
        for($i=0; $i < $re[0]->rowCount(); $i++){
          $r=$re[0]->fetch();
    ?>
          <tr>
            <td class="table_content" style="text-align:center"><?=$re[1]-$i?></td>
            <td class="table_content"><a onclick="window.open('<?=$r["DC_URL_LOC"]?>','_blank');"><?=$r["DC_TITLE_OR"]?></a>
            <?if($r["DC_CONTENT"]){?>
                 <div class="button_tag" onclick="window.open('Content_viewPost.php?view=1&IDX=<?=$r["IDX"]?>',600,300);">요약</div></td>
            <?}?>
            <td class="table_content"><?=$r["DC_AGENCY"]?></td>
            <td class="table_content"><?=DT_show($r["DC_DT_WRITE"]);?></td>
            <td class="table_content" style="text-align:right;"><?=$r["DC_PAGE"]?></td>
          </tr>
        <?}?>
        </table>
    <?}?>
    </table>
    <div style="padding-top:30px;"><?=$re[2]?></div>
    <?
  }
?>          
