<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_head.php";

require_once "../_Class/PHPExcel/IOFactory.php";

 require_once "../_Class/PHPExcel.php";

//



if($_FILES["FILE_EXCEL"]["tmp_name"]){
				$upload_excel_file=uploadFile($Mem->data["temp"],$_FILES["FILE_EXCEL"]["tmp_name"],$_FILES["FILE_EXCEL"]["name"]);



				$objPHPExcel = new PHPExcel();



				$filename = 'Data/temp/'.$upload_excel_file; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.
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
          //아; 일단 2부터 시작하게 해두자;
					for ($i = 2 ; $i <= $maxRow ; $i++) {
            if($objWorksheet->getCell('H' . $i)->getValue()){
                $Large=$objWorksheet->getCell('B' . $i)->getValue();
                $Medium=$objWorksheet->getCell('C' . $i)->getValue();
                $Small=$objWorksheet->getCell('D' . $i)->getValue();
                $l=$Mem->q("Select * from nt_categorys where CT_NM like ? and length(CODE)=2 and TYPE=1",$Large."%");

                if(($l->rowCount())==0){
                  $lmax=$Mem->qs("Select MAX(CODE) from nt_categorys where length(CODE)=2 and TYPE=1")*1;
                  if($lmax==0){	$lmax=10;	}else{$lmax+=1;}
              		$Mem->q("insert into nt_categorys(CT_NM,CODE,TYPE) values(?,?,1)  ",array($Large,$lmax));$code=$lmax;
                  $m=$Mem->q("Select * from nt_categorys where CT_NM like ? and CODE like ? and TYPE=1",array($Medium."%",$code."%"));
                }else{
                  while($lr=$l->fetch()){if(strlen($lr["CODE"])==2){$code=$lr["CODE"];break;}}
                  $m=$Mem->q("Select * from nt_categorys where CT_NM like ? and CODE like ? and TYPE=1",array($Medium."%",$code."%"));
                }
                if($m->rowCount()==0){
                  $mmax=$Mem->qs("Select MAX(CODE) from nt_categorys where length(CODE)=4 and TYPE=1 and CODE like ?",array($code."%"))*1;
                	if($mmax==0){	$mmax=$code."10";	}else{$mmax+=1;}
              		$Mem->q("insert into nt_categorys(CT_NM,CODE,TYPE) values(?,?,1)  ",array($Medium,$mmax));$code=$mmax;
                  $s=$Mem->q("Select * from nt_categorys where CT_NM like ? and CODE like ? and TYPE=1",array($Small."%",$code."%"));
                }else{
                  while($mr=$m->fetch()){if(strlen($mr["CODE"])==4&&substr($mr["CODE"],0,2)==$code){$code=$mr["CODE"];break;}}
                  $s=$Mem->q("Select * from nt_categorys where CT_NM like ? and CODE like ? and TYPE=1",array($Small."%",$code."%"));
                }
                if($s->rowCount()==0){
                  $smax=$Mem->qs("Select MAX(CODE) from nt_categorys where length(CODE)=6 and TYPE=1 and CODE like ?",array($code."%"))*1;
                  if($smax==0){	$smax=$code."10";	}else{$smax+=1;}
              		$Mem->q("insert into nt_categorys(CT_NM,CODE,TYPE) values(?,?,1)  ",array($Small,$smax));$code=$smax;
                }else{
                  while($sr=$s->fetch()){if(strlen($sr["CODE"])==6&&substr($sr["CODE"],0,4)==$code){$code=$sr["CODE"];break;}}
                }
                //$val=Labelbinarizer($objWorksheet->getCell('B' . $i)->getValue(),$objWorksheet->getCell('C' . $i)->getValue(),$objWorksheet->getCell('D' . $i)->getValue());
                //echo $val;
                 $Mem->q("insert into nt_document_list (DC_TITLE_OR, DC_TITLE_KR,DC_KEYWORD ,DC_TYPE, DC_COUNTRY ,DC_SMRY_KR,DC_DT_COLLECT_STR,DC_DT_WRITE,DC_URL_LOC,DC_AGENCY,DC_MEMO1,DC_MEMO2,DC_CODE,DC_CONTENT,DC_PAGE) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ",
								 array(
								 $objWorksheet->getCell('I' . $i)->getValue(), //DC_TITLE_OR , 원제목
								 $objWorksheet->getCell('H' . $i)->getValue(), //DC_TITLE_KR , 한글제목
                   $objWorksheet->getCell('E' . $i)->getValue(), //DC_KEYWORD , 키워드
                $objWorksheet->getCell('F' . $i)->getValue(), //DC_TYPE , 유형분류
                $objWorksheet->getCell('G' . $i)->getValue(), //DC_COUNTRY , 국가
								 "",//$objWorksheet->getCell('J' . $i)->getValue(), //DC_SMRY_KR , 요약
								 "",//$objWorksheet->getCell('K' . $i)->getValue(), //DC_DT_COLLECT_STR, 이건 뭔데;
                DT_trans($objWorksheet->getCell('L' . $i)->getValue()), //DC_DT_WRITE , 발간일
//								 (PHPExcel_Style_NumberFormat::toFormattedString(($objWorksheet->getCell('K' . $i)->getValue()), 'YYYY-MM-DD')),
								 //time(), //원래 mktime() //DC_DT_REGI
//								 $objWorksheet->getCell('C'.$i)->getHyperlink()->getUrl(),
//								 $objWorksheet->getCell('C'.$i)->getHyperlink()->getUrl(),

									 $objWorksheet->getCell('N' . $i)->getValue(), //DC_URL_LOC , URL 정보
									 $objWorksheet->getCell('K' . $i)->getValue(), //DC_AGENCY , 발행기관
								 $objWorksheet->getCell('P' . $i)->getValue(), //메모1 , 첨부파일 hwp
								 $objWorksheet->getCell('Q' . $i)->getValue(), //메모2 , 표지파일 gif
								 $code, // DC_CODE, 코드.. 나의 코드 , 대중소 분류된 코드 기입
								 $objWorksheet->getCell('J' . $i)->getValue(), //DC_CONTENT , 내용
								 $objWorksheet->getCell('M' . $i)->getValue(), //DC_PAGE , 페이지수
								 )

               );
								 $PID=$Mem->insertId();



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


				}




				} catch (exception $e) {
          echo $e;
					echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';

				}


}

function DT_trans($date){
  if(!$date){return 0;}
  $val = str_replace('.','',$date);
  if(strlen($val)<5){
    $y = substr($val,0,4);
    $result = mktime(0,0,0,0,0,$y);
  }elseif(strlen($val)<7){
    $y = substr($val,0,4);
    $m = substr($val,4,2);
    $result = mktime(0,0,0,$m,0,$y);
  }else{
    $y = substr($val,0,4);
    $m = substr($val,4,2);
    $d = substr($val,6);
    $result = mktime(0,0,0,$m,$d,$y);
  }
  return $result;
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

<div class="round_box1" >
<div class="title1">데이터 엑셀등록</div>
<div style="line-height:40px;" >DB제출용 포맷에 맞추어진 엑셀파일을 업로드해주세요.</div>
<div style="line-height:40px;" ><b>현재 등록된 데이터 : <?=$Mem->qs("Select count(*) from nt_document_list where STAT < 9 ")?></b></div>
<form action="" enctype="multipart/form-data" method="post" >

	<input type="file" name="FILE_EXCEL"> <input type="submit" class="button1" value="엑셀적용" >
</form>
</div>
<!--

<div class="round_box1" >
<div class="title1">파일 데이터 등록</div>
<div style="line-height:40px;" >DB제출용 포맷에 맞추어진 엑셀파일을 업로드해주세요.</div>
<div style="line-height:40px;" ><b>현재 등록된 데이터 : <?=$Mem->qs("Select count(*) from nt_document_list where STAT < 9 ")?></b></div>
<form action="" enctype="multipart/form-data" method="post" >

	<input type="file" name="FILE_EXCEL"> <input type="submit" class="button1" value="엑셀적용" >
</form>
</div>
-->