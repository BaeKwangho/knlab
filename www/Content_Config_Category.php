<?  
 if(sizeof($_POST)){ 
	 include "_h.php"; 
 }else{

	 include "Axis_Header.php"; 
 }


if($_GET["CTYPE"]) $_SESSION["CTYPE"]=$_GET["CTYPE"]; 
if(!$_SESSION["CTYPE"]) $_SESSION["CTYPE"]=1;
 

 if($_POST["CID1"]){ 
		$max=$Mem->qs("Select MAX(CODE) from nt_categorys where length(CODE)=2 and TYPE=? ",array($_SESSION["CTYPE"]))*1; 
		if($max==0){	$max=10;	}else{$max+=1;} 
		$Mem->q("insert into nt_categorys(CT_NM,CODE,TYPE) values(?,?,?)  ",array($_POST["CID1"],$max,$_SESSION["CTYPE"])); 
		//	mvs(SELF);exit; 
		$data=array("parent"=>$_POST["CID_PARENT"],"stat"=>"true"); 
		echo json_encode($data); 
		exit;
 }



 if($_POST["CID2"]){ 
		$max=$Mem->qs("Select MAX(CODE) from nt_categorys where length(CODE)=4  and CODE like ? and TYPE=?" ,array($_POST["CID_PARENT"]."%",$_SESSION["CTYPE"]))*1; 
		if($max==0){	$max=$_POST["CID_PARENT"]."10";	}else{$max+=1;} 
		$Mem->q("insert into nt_categorys(CT_NM,CODE,TYPE) values(?,?,?)  ",array($_POST["CID2"],$max,$_SESSION["CTYPE"])); 
	//		mvs(SELF."?CID=".$_POST["CID_PARENT"]);exit;
		$data=array("parent"=>$_POST["CID_PARENT"],"stat"=>"true"); 
		echo json_encode($data); 
		exit;
 
 }

 
 if($_POST["CID3"]){ 
		$max=$Mem->qs("Select MAX(CODE) from nt_categorys where length(CODE)=6  and CODE like ? and TYPE=?" ,array($_POST["CID_PARENT"]."%",$_SESSION["CTYPE"]))*1; 
		if($max==0){	$max=$_POST["CID_PARENT"]."10";	}else{$max+=1;} 
		$Mem->q("insert into nt_categorys(CT_NM,CODE,TYPE) values(?,?,?)  ",array($_POST["CID3"],$max,$_SESSION["CTYPE"])); 
		$data=array("parent"=>$_POST["CID_PARENT"],"stat"=>"true"); 
		echo json_encode($data); 
		exit;
 }




if($_GET["remove_cid1"]){

	$Mem->q("update nt_categorys set `STAT`=9 where IDX=? ",$_GET["remove_cid1"]); 
	mvs(SELF);
exit;
}

?>

	<div class="title1">

 <? 
switch($_SESSION["CTYPE"]){
case 1:
	echo "주제분류";
	break;
case 2:
	echo "유형분류";
	break;
case 3:
	echo "국가분류";
	break;
 }

 ?>
 설정</div>		


<div > 
	<div style="float:left; width: calc( 33.33% - 2px );;border-right:solid 2px #AAA; " id="" ><div class="title2">대분류 </div></div> 
	<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; ;"  id=""><div class="title2">중분류 </div></div> 
	<div style="float:left; width:33.33%;;" id=""><div class="title2">소분류 </div></div> 
</div>


<div class="clear"></div>

<div > 
	<div style="float:left; width: calc( 33.33% - 2px );height:500px;border-right:solid 2px #AAA; " id="cid1" ></div> 
	<div style="float:left; width:calc( 33.33% - 2px ); border-right:solid 2px #AAA; height:500px;"  id="cid2"  ></div> 
	<div style="float:left; width:33.33%;height:500px;" id="cid3" ></div>  
</div>
  

<script>

function c_add1(){



         $.ajax({
            url: "<?=SELF?>", // 목적지
            type: "POST", // POST형식으로 폼 전송
	        timeout: 20000,
			data:$("#cid_add_form1").serialize(),
            success: function(data, textStatus, jqXHR) {  
				getup('Content_Config_Category_Load.php?type=cid1','cid1');
				$('#CID1').focus();

            },
            error: function(xhr, textStatus, errorThrown) {
					msg("전송에 실패했습니다");
            }
        });  
		
}


function c_add2(){


         $.ajax({
            url: "<?=SELF?>", // 목적지
            type: "POST", // POST형식으로 폼 전송
	        timeout: 20000,
			data:$("#cid_add_form2").serialize(),
            success: function(data, textStatus, jqXHR) { 
				var json = $.parseJSON(data);
				getup('Content_Config_Category_Load.php?CID='+json.parent,'cid2');
				$('#CID2').focus();
            },
            error: function(xhr, textStatus, errorThrown) {
					msg("전송에 실패했습니다");
            }
        });  

}

function c_add3(){


         $.ajax({
            url: "<?=SELF?>", // 목적지
            type: "POST", // POST형식으로 폼 전송
	        timeout: 20000,
			data:$("#cid_add_form3").serialize(),
            success: function(data, textStatus, jqXHR) { 
				var json = $.parseJSON(data);
				getup('Content_Config_Category_Load.php?CID='+json.parent,'cid3');
				$('#CID3').focus();
            },
            error: function(xhr, textStatus, errorThrown) {
					msg("전송에 실패했습니다");
            }
        });  

}



	function check(){
		if($('#country_name').val().length==0){
			alert('국가 이름을 입력해주세요');
			$('#country_name').focus();
			return false;
		}

		return true;

}



			$('#country_name').focus();

			getup('Content_Config_Category_Load.php?type=cid1','cid1');
			getup('Content_Config_Category_Load.php?dummy=1','cid2');
			getup('Content_Config_Category_Load.php?dummy=2','cid3');
</script>

