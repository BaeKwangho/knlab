<? include "_head.php"; 
	if($Mem->class< 8){
		mvs("Content_view.php");
	}else{
	}
?>

<?
 //echo json_encode($_SERVER);
if($_SESSION["TYPE2"]==1){
?>
<div style="max-width:1200px; margin:0 auto; ">

<div class="round_box2" >
	<div class="title3">리포트</div>

</div>


<div class="round_box2" >
	<div class="title3">정책동향</div>

</div>

<div class="round_box2" >
	<div class="title3">산업동향</div>

</div>

<div class="round_box2" >
	<div class="title3">기술동향</div>

</div>

</div>
<? }


if($_SESSION["TYPE2"]==3){ include "Content_Data_List.php";		}


if($_SESSION["TYPE2"]==4){ include "Content_Data_List.php";		}


if($_SESSION["TYPE2"]==5){ include "Content_Data_List.php";		}

if($_SESSION["TYPE2"]==6){ include "Content_Data_Reference.php";		}

?>
