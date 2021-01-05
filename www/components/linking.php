<?
include "../_h.php";

if($_POST["LINK_DEL"]==1){
	$r=$Mem->qr("select DC_LINK from nt_document_list where idx like ?",$_POST["PID"]);
	$link=explode(' ',$r["DC_LINK"]);
	if($link[0]){
		if($_POST["DC_LINK"]){
			for($j=0;$j<count($_POST["DC_LINK"]);$j++){
                $_SESSION["UPDATE_LIST"][$_POST["DC_LINK"][$j]]=0;
			}
		}
	}else{
    }
    for($i=0;$i<count($link);$i++){
        if($_SESSION["UPDATE_LIST"][$link[$i]]==1){
            $l=$Mem->qr("select DC_TITLE_KR,DC_DT_WRITE from nt_document_list where idx like ?",$link[$i]);
                
            ?>
            <div style="padding:10px;float:left;width:50%-30px;line-height:10px;"><label style=";">
            <input type="checkbox" id="link_list" name="DC_LINK[]" value="<?=$link[$i]?>" checked>
            <span onclick="window.open('Content_Data_View.php?PID=<?=$link[$i]?>','data_view','width=900,height=900,scrollbars=1');">
            <?=$l["DC_TITLE_KR"]?> / <?=date("Y-m-d",$l["DC_DT_WRITE"])?>
            </label></div>
            <?			
        }

    }
    unset($_POST["DC_LINK"]);
    $_POST["LINK_DEL"]=0;
	exit;
}
if($_POST["list"]){
	for($i=0;$i<count($_POST["list"]);$i++){
		if($_SESSION["UPDATE_LIST"][$_POST["list"][$i]]==0){
            $_SESSION["UPDATE_LIST"][$_POST["list"][$i]]=1;
            $l=$Mem->qr("select DC_TITLE_KR,DC_DT_WRITE from nt_document_list where idx like ?",$_POST["list"][$i]);
			?>
			<div style="padding:10px;float:left;width:50%-30px;line-height:10px;"><label style=";">
						<input type="checkbox" name="DC_LINK[]" value="<?=$_POST["list"][$i]?>"  checked>
						<span onclick="window.open('Content_Data_View.php?PID=<?=$_POST["list"][$i]?>','data_view','width=900,height=900,scrollbars=1');">
						<?=$l["DC_TITLE_KR"]?> / <?=date("Y-m-d",$l["DC_DT_WRITE"])?>
						</label>
			</div>
		<?
        }else{
        }
        
       
    }
    
    unset($_POST["list"]);
	exit;
}

?>