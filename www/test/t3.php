<?

error_reporting(E_ALL);	ini_set("display_errors", 1);

include "Axis_Header.php";

require_once "../_Class/PHPExcel/IOFactory.php";

 require_once "../_Class/PHPExcel.php";

//


if($_FILES["FILE_EXCEL"]["tmp_name"]){
				$upload_excel_file=uploadFile($Mem->data["temp"],$_FILES["FILE_EXCEL"]["tmp_name"],$_FILES["FILE_EXCEL"]["name"]);



				$objPHPExcel = new PHPExcel();



				$filename = $Mem->data["temp"].$upload_excel_file; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.
				move_uploaded_file($_FILES["FILE_EXCEL"]["tmp_name"], $filename);
				try {

				  // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.

					$objReader = PHPExcel_IOFactory::createReaderForFile($filename);

					// 읽기전용으로 설정

					$objReader->setReadDataOnly(false);

					// 엑셀파일을 읽는다
					$objExcel = $objReader->load($filename);


					$sheetCount = $objExcel->getSheetCount();

					// 첫번째 시트를 선택


				for($k=0; $k < $sheetCount; $k++){


					$objExcel->setActiveSheetIndex($k);

					$objWorksheet = $objExcel->getActiveSheet();

					$rowIterator = $objWorksheet->getRowIterator();

          foreach ($rowIterator as $row) { // 모든 행에 대해서

							   $cellIterator = $row->getCellIterator();

							   $cellIterator->setIterateOnlyExistingCells(false);

					}

					$maxRow = $objWorksheet->getHighestRow();
					$z = $Mem->q("select CTY_NM from nt_countrys");
					$ze = $z->fetchAll();
		  //2번째부터 시작.
		  $cont= 0;
			for ($i = 3 ; $i <= $maxRow ; $i++) {
            if($objWorksheet->getCell('B' . $i)->getValue()){
				
				$t = 0;
				while($t<count($ze)){
					if($objWorksheet->getCell('B' . $i)->getValue()===$ze[$t]["CTY_NM"]){
						$cont=1;
						$t++;
						continue;
					}
					$t++;
				}
				if($cont==1){
					$cont=0;
					continue;
				}
				$Mem->q("insert into nt_countrys (CTY_NM) values (?)",$objWorksheet->getCell('B' . $i)->getValue());
				$e = $Mem->q("select idx from nt_countrys where CTY_NM like ?",$objWorksheet->getCell('B' . $i)->getValue())->fetch();
				$Mem->q("insert into nt_continents (CONTI_NAME , CTYID) values (?,?)",array($objWorksheet->getCell('A' . $i)->getValue(),$e["idx"]));

			   /*
				업로드 시 게시글 table인 nt_document_list와 국가 list인 cty_list에 동시 insert를 진행
				cty_list의 경우 nt_countrys
				


/*
								$date_type= (PHPExcel_Style_NumberFormat::toFormattedString(($objWorksheet->getCell('F' . $i)->getValue()), 'YYYY-MM-DD'));
								if(strlen($date_type)==7){	$date_type.="-01";	}
								if(strlen($date_type)==10) $Mem->q("update nt_document_list set DC_DT_COLLECT=? where IDX=? ",array(datec($date_type),$PID));


								 if($TID=$Mem->qs("Select IDX from nt_country_list where COUNTRY_NM=? ",trim($objWorksheet->getCell('G' . $i)->getValue()))){

								 }else{
									$Mem->q("insert into nt_country_list(COUNTRY_NM,DT) values(?,?) ",array(trim($objWorksheet->getCell('G' . $i)->getValue()),mktime()));
									$TID=$Mem->insertId();
								 }

									$Mem->q("insert into nt_document_country_list(PID,TID) values(?,?) ",array($PID,$TID));
*/

              }
					 }
					 print_r($cont);
					 echo "<br>";

				}




				} catch (exception $e) {
					echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';

				}

//파일 커버 업로드 하는 내용

}

function DT_trans($date){
    if(!$date){return 0;}
    $val = explode('.',$date);
    if(!$val[0]){return 0;}
    else{
        if(strlen($val[0])<4){
            return 0;
        }else{
            $y = $val[0];
        }
    }
    if(!$val[1]){return mktime(0,0,0,0,0,$y);}
    else{
        if(strlen($val[1])<2){
            $m="0".$val[1];
        }else{
            $m = $val[1];
        }
    }
    if(!$val[2]){return mktime(0,0,0,$m,0,$y);}
    else{
        if(strlen($val[2])<2){
            $d="0".$val[2];
        }else{
            $d = $val[2];
        }
    }
    return mktime(0,0,0,$m,$d,$y);
}

/*

				$Q=$Mem->q("select * from nt_document_list ");
				for($m=0; $m < $Q->rowCount(); $m++){
$r=$Q->fetch();
if(strlen($r["DC_DT_COLLECT_STR"])==7){
	$r["DC_DT_COLLECT_STR"].="-01";
}




	if(strlen($r["DC_DT_COLLECT_STR"])==10) $Mem->q("update nt_document_list set DC_DT_COLLECT=? where IDX=? ",array(datec($r["DC_DT_COLLECT_STR"]),$r["IDX"]));
*/




?>
<?	

	?>
<div class="round_box1" >
<div class="title1">데이터 엑셀등록</div>
<div style="line-height:40px;" >DB제출용 포맷에 맞추어진 엑셀파일을 업로드해주세요.</div>
<div style="line-height:40px;" ><b>현재 등록된 데이터 : <?=$Mem->qs("Select count(*) from nt_document_list where STAT < 9 ")?></b></div>
<form action="" enctype="multipart/form-data" method="post" >

	<input type="file" name="FILE_EXCEL"> <input type="submit" class="button1" value="엑셀적용" >
</form>
</div>


</div>
