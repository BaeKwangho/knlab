<? 
 include "_h.php"; 

if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"]; 
if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;
 
if($_GET["RESET"]) unset($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]]);


if(!is_array($_SESSION["SET_CATEGORY"])) $_SESSION["SET_CATEGORY"]=array();
if(!is_Array($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]])) $_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]]=array();



//for($i=0; $i < sizeof($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]]); $i++){
	foreach($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]] as $key => $val){
		if($_SESSION["SET_CATEGORY"][$_SESSION["CTYPE"]][$key]){
			
?>
<?=$Mem->qs("select CT_NM from nt_categorys where IDX=? ",$key)?>

	<? } } ?>
