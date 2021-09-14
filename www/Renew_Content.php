<? 
$_GET['temp']=true;
include "Axis_Header.php"; 

if($_SESSION["AUTH"]["MID"]=="2411"){
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
      $params = [
        'index' => 'politica_service',
        "from"=> 5,
        "size"=> 20,
        'body'  => [
            'query' => [
                'bool' => [
                    'must' => $must_array,
                    'should' =>$should_array
                ],
            ]
        ]
    ];
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
            <td class="table_content" style="text-align:center"><?=$re[1]+$i+1?></td>
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