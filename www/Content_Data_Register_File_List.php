<? include "_h.php"; 

//error_reporting(E_ALL);	ini_set("display_errors", 1);
if($_GET["type"]==1){
if($_GET["reset"]){ 
	for($i=0; $i < sizeof($_SESSION["TMP_CORVER"]); $i++){
		@unlink($Mem->data["temp"].$_SESSION["TMP_CORVER"][$i]["file"]);
	}
	unset($_SESSION["TMP_CORVER"]); 
}
for($i=0; $i < sizeof($_SESSION["TMP_CORVER"]); $i++){
	if(@is_array(getimagesize($Mem->data["temp"].$_SESSION["TMP_CORVER"][$i]["file"]))){
		echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data["temp"].$_SESSION["TMP_CORVER"][$i]["file"]."' class='temp_file' ><br>".$_SESSION["TMP_CORVER"][$i]["name"]."</div>";	
	}else{
		echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$_SESSION["TMP_CORVER"][$i]["name"]."</div>";

	}
} 
}


if($_GET["type"]==2){
if($_GET["reset"]){
	for($i=0; $i < sizeof($_SESSION["TMP_DOCUMENT"]); $i++){
		@unlink($Mem->data["temp"].$_SESSION["TMP_DOCUMENT"][$i]["file"]);
	}
	unset($_SESSION["TMP_DOCUMENT"]);
} 
for($i=0; $i < sizeof($_SESSION["TMP_DOCUMENT"]); $i++){


	if(@is_array(getimagesize($Mem->data["temp"].$_SESSION["TMP_DOCUMENT"][$i]["file"]))){
		echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data_url["temp"].$_SESSION["TMP_DOCUMENT"][$i]["file"]."' class='temp_file' ><br>".$_SESSION["TMP_DOCUMENT"][$i]["name"]."</div>";
	}else{
		echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$_SESSION["TMP_DOCUMENT"][$i]["name"]."</div>";
	}

	}
} 

if($_GET["type"]==3){
if($_GET["reset"]) unset($_SESSION["TMP_ATTECH"]);
for($i=0; $i < sizeof($_SESSION["TMP_ATTECH"]); $i++){

	if(@is_array(getimagesize($Mem->data["temp"].$_SESSION["TMP_ATTECH"][$i]["file"]))){
		echo "<div style='float:left;text-align:center;margin:10px;' ><img src='".$Mem->data_url["temp"].$_SESSION["TMP_ATTECH"][$i]["file"]."' class='temp_file' ><br>".$_SESSION["TMP_ATTECH"][$i]["name"]."</div>";
	}else{
		echo "<div style='float:left;text-align:center;margin:10px;' ><img src='/images/files.png' class='temp_file'><br>".$_SESSION["TMP_ATTECH"][$i]["name"]."</div>";
	}
}
} 
?>
