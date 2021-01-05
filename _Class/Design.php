<?

include "/home/knlab/_Class/Member.php"; 


Class Design extends Member {



	var $layout;

	function  __construct  ($default=false){  
		Member::__construct();

		
		$this->path["layout"]="/home/ibss_shop/shop_pub/_Layout/";
 		$this->path["content"]="/home/ibss_shop/shop_pub/_Content/";
 		$this->path["skin"]="/home/ibss_shop/shop_pub/_SKIN/";


		if($default==false)	$this->getLayout();

	} 

	function getLayout(){

echo '<!doctype html>'.
'<html lang="en">'.
'<head>'.
'<meta charset="UTF-8"> '.
'<meta name="Author" content="">'.
'<meta name="Keywords" content="">'.
'<meta name="Description" content="">'.
'<title>Document</title>'.
'<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>'.
'<script src="https://code.jquery.com/jquery-1.12.4.js"></script>'.
'<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>'.
'		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">'.
'<script src="/_script.js"></script>'.
'<link rel="stylesheet" href="/_style.css">'.
'<style>';

echo $this->qs("select `Style` from ibss_design_style_list where `DFT`=1 ");
echo '</style>'.
'</head>'.
'<body>'.
' <div id="overlay" class="web_dialog_overlay"></div> '.
'<div id="web_dialog" class="web_dialog_content"  ></div>';




			$__Z=null;
 

	 
			$__ZQ=$this->q("select * from ibss_design_content_list where FID=?   and GR='TP'  ", array(FID));  for($__Z=0; $__Z  < $__ZQ->rowCount(); $__Z++){  $__ZR=$__ZQ->fetch(); 
				if($__ZR["File_Name"]&&file_exists($this->path["content"].$__ZR["File_Name"]))	include  $this->path["content"].$__ZR["File_Name"];
			} 


			
			$row=$this->qr("select * from ibss_design_skin_list where PAGE=? ",SELF);
			if($row["SKIN"]&&file_exists($this->path["skin"].$row["SKIN"])){
				include $this->path["skin"].$row["SKIN"];
 			}

 			

 
			$__ZQ=$this->q("select * from ibss_design_content_list where FID=?   and GR='BT'  ", array(FID));  for($__Z=0; $__Z  < $__ZQ->rowCount(); $__Z++){  $__ZR=$__ZQ->fetch(); 
				if($__ZR["File_Name"]&&file_exists($this->path["content"].$__ZR["File_Name"]))	include  $this->path["content"].$__ZR["File_Name"];
			} 




 
		echo ' </body>
		</html>';

	}


	function getDeliveryPrice($price){


		$row=$this->qr("select * from ibss_delivery_method_list where IDX > 0 ");

		$result=array();


		if($this->shop["delivery_price"]){

			switch($this->shop["delivery_price"]){

				case 1:
						$result['price']=0;	
						$result["msg"]="무료배송";
					break;
				case 2:
						$result['price']=$row["Delivery_Price"];	
						$result["msg"]=($this->qs("select `NAME` from `_ibss`.ibss_variable_data where `PID`='101' and `VALUE`=? ", $row["Delivery_Method"]))."  ".number_format($row["Delivery_Price"])."원 "; 
					break;
				case 3:
						if( $price  >= $this->shop["delivery_free"]){
					
							$result['price']=0;	
							$result["msg"]=number_format($this->shop["delivery_free"])."원 이상 구매시 무료배송";
						}else{
							$result['price']=$row["Delivery_Price"];	



							$result["msg"]=($this->qs("select `NAME` from `_ibss`.ibss_variable_data where `PID`='101' and `VALUE`=? ", $row["Delivery_Method"]))."  ".$row["Delivery_Price"]."원 ";

						}
					break;
			}
		}



		return $result;
	}

}

 ?>