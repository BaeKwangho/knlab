<?
include "../_h.php";
if($_GET["CONTI_MORE"]){
?>
    <div >
        <div style ="width:198px;" class="header_box1"><?=$_GET["CONTI_MORE"]?> 상세보기</div>
        <div style="float:left;width:198px;height:678px;border-right:solid 2px #AAA; overflow-y:scroll;">
            <?
            $countrys=$Mem->q("select a.* from nt_countrys a join nt_continents b on a.IDX = b.CTYID where b.CONTI_NAME=? and a.CTY_PRI = 1",$_GET["CONTI_MORE"]);
            while($r = $countrys->fetch()){
            ?>
            <p>
                <label>
                    <input type="checkbox" name="DC_COUNTRY[]" value="<?=$r["CTY_NM"]?>"></input>
                    <?=$r["CTY_NM"]?>
                </label>
            </p>
            <?
            }
            
            ?>
        </div>
    </div>
    <?   
}
?>