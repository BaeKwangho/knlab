<?
/* 사용처
1.Content_Data_Modify.php - 주제, 국가 불러오기
*/

//error_reporting(E_ALL);	ini_set("display_errors", 1);
    include "../_h.php";
    //저장된 주제분류 PCODE와 새로 주어지는 ITEM
    //ITEM은 Content_Data_Search 에서 생성된 분류코드 ITEM을 통해 여기서 만들어짐.
    if($_POST["ITEM"]||$_POST["PCODE"]){
        if($_POST["PCODE"]){
            $last=$_POST["PCODE"];
        }elseif($_POST["ITEM"]){
            $last=$_POST["ITEM"];
        }else{}

        if(is_array($last)){
            foreach($last as $code){
                show_cat($code,$Mem);
            }
        }else{
            show_cat($last,$Mem);
        }

        /* DEPRECATED
        if(strlen($last)==2){
            $low =$Mem->q("select CT_NM from nt_categorys where CODE like ? and STAT<9",$last)->fetch();
            ?><text onclick="" class="buttonb"><input type="hidden" name="DC_CODE" id="DC_CODE" value='<?=$last?>'><?=$low["CT_NM"]?></input></td><?
        }elseif(strlen($last)==4){
            $low =$Mem->q("select CT_NM from nt_categorys where CODE like ? and STAT<9",substr($last,0,2))->fetch();
            echo "<text style='display:inline-block;' class='buttonb'>".$low["CT_NM"]."</text>";
            $mid =$Mem->q("select CT_NM from nt_categorys where CODE like ? and STAT<9",substr($last,0,4))->fetch();
            ?><text onclick=""class="buttonb"><input type="hidden" name="DC_CODE" id="DC_CODE" value='<?=$last?>'><?=$mid["CT_NM"]?></input></td><?
        }else{
            $low =$Mem->q("select CT_NM from nt_categorys where CODE like ? and STAT<9",substr($last,0,2))->fetch();
            echo "<text style='display:inline-block;' class='buttonb'>".$low["CT_NM"]."</text>";
            $mid =$Mem->q("select CT_NM from nt_categorys where CODE like ? and STAT<9",substr($last,0,4))->fetch();
            echo "<text style='display:inline-block;' class='buttonb'>".$mid["CT_NM"]."</text>";
            $high =$Mem->q("select CT_NM from nt_categorys where CODE like ? and STAT<9",$last)->fetch();
            ?><text onclick=""class="buttonb"><input type="hidden" name="DC_CODE" id="DC_CODE" value='<?=$last?>'><?=$high["CT_NM"]?></input></td><?
        }*/
    }elseif($_POST["DC_COUNTRY"]){
        ?><div style="float:left;width:500px;line-height:30px;"><?
        foreach($_POST["DC_COUNTRY"] as $country){
            ?><label style="cursor:default;">
                <input type="hidden" name="DC_COUNTRY[]" id="DC_COUNTRY[]" value="<?=$country?>"><?=$country.', '?>
            </label><?
        }
        ?></div><?
    }elseif($_POST["PCOUNTRY"]){
        ?><div style="float:left;width:500px;line-height:30px;"><?
        foreach($_POST["PCOUNTRY"] as $country){
            ?><label style="cursor:default;">
                <input type="hidden" name="DC_COUNTRY[]" id="DC_COUNTRY[]" value="<?=$country?>"><?=$country.', '?>
            </label><?
        }
        ?></div><?
    }


//하나의 카테고리 박스를 나타냄
function show_cat($last, $Mem){
    $content=$Mem->q('select * from nt_categorys where CODE like ?',$last)->fetch()["CT_NM"];
    if(strlen($last)==2){
        ?><div class="buttonb" onclick="$(this).remove();"> <!--클릭 시 삭제--> 
            <input type=hidden name='DC_CODE[]' value="<?=$last?>"></input>
            <p style="margin-top:5px;"><?=$content?></p>
        </div><?
    }elseif(strlen($last)==4){
        $large = $Mem->q('select * from nt_categorys where CODE like ?',array(substr($last,0,2)))->fetch()["CT_NM"];
        ?><div class="buttonb" onclick="$(this).remove();" onmouseover="$(this).hover(function(){
                $(this).children('p').text('<?=$large?>')
            }, function(){
                $(this).children('p').text('<?=$content?>')
            })"> <!--마우스 올릴 시 상세 분류-->
            <input type=hidden name='DC_CODE[]' value="<?=$last?>"></input>
            <p style="margin-top:5px;"><?=$content?></p>
        </div><?
    }else{
        $large = $Mem->q('select * from nt_categorys where CODE like ?',array(substr($last,0,2)))->fetch()["CT_NM"];
        $mid = $Mem->q('select * from nt_categorys where CODE like ?',array(substr($last,0,4)))->fetch()["CT_NM"];
        ?><div class="buttonb" onclick="$(this).remove();" onmouseover="$(this).hover(function(){
                    $(this).children('p').text('<?=$large?> > <?=$mid?>')
                }, function(){
                    $(this).children('p').text('<?=$content?>')
                })">
            <!--DC_CODE 중복을 위해 내부 p태그 삽입 및 jquery 수정-->
            <input type=hidden name='DC_CODE[]' value="<?=$last?>"></input>
            <p style="margin-top:5px;"><?=$content?></p>
        </div><?
    }
}

?>
