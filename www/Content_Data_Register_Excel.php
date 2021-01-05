<?

//error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_head.php";

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
          //2번째부터 시작.
			for ($i = 2 ; $i <= $maxRow ; $i++) {
            if($objWorksheet->getCell('F' . $i)->getValue()){
				/*
				$Large = $i행 'B'열의 값, 대분류
				$Medium = $i행 'C'열의 값, 중분류
				$Small = $i행 'D'열의 값, 소분류
				$code = 현재 행의 주제분류코드 부여

				대,중,소 차례로 맞는 키워드를 nt_categorys에서 select하여 검색한 후
				코드를 부여. (ex. $code = 19 -> 1911 -> 191112)
				만약 맞는 키워드가 없다면, nt_categorys의 최하단 카테고리 바로 다음 생성
				(ex. (대)보건복지노동 / (중)사회복지 / (소)지자체정책 의 경우,
							20				2013			-
					$code = 20				2013		검색 시 없으므로 생성
														=>최하단이 201323일 시,
														  201324로 생성. default는 10.
				)

				*/
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
				$date = mktime();
                //$val=Labelbinarizer($objWorksheet->getCell('B' . $i)->getValue(),$objWorksheet->getCell('C' . $i)->getValue(),$objWorksheet->getCell('D' . $i)->getValue());
				//echo $val;
				
				$Mem->q("insert into nt_document_list (DC_TITLE_OR, DC_TITLE_KR,DC_KEYWORD ,DC_TYPE, DC_COUNTRY ,DC_SMRY_KR,DC_DT_COLLECT_STR,DC_DT_WRITE,DC_DT_REGI,DC_URL_LOC,DC_AGENCY,DC_MEMO1,DC_MEMO2,DC_CODE,DC_CONTENT,DC_PAGE,DC_CAT) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ",
				array(
					$objWorksheet->getCell('K' . $i)->getValue(), //DC_TITLE_OR , 원제목
					$objWorksheet->getCell('J' . $i)->getValue(), //DC_TITLE_KR , 한글제목
					$objWorksheet->getCell('E' . $i)->getValue(), //DC_KEYWORD , 키워드
					$objWorksheet->getCell('G' . $i)->getValue(), //DC_TYPE , 유형분류
					$objWorksheet->getCell('I' . $i)->getValue(), //DC_COUNTRY , 국가
					"",//$objWorksheet->getCell('J' . $i)->getValue(), //DC_SMRY_KR , 요약
					"",//$objWorksheet->getCell('K' . $i)->getValue(), //DC_DT_COLLECT_STR, 이건 뭔데;
					DT_trans($objWorksheet->getCell('N' . $i)->getValue()), //DC_DT_WRITE , 발간일
					//								 (PHPExcel_Style_NumberFormat::toFormattedString(($objWorksheet->getCell('K' . $i)->getValue()), 'YYYY-MM-DD')),
					$date, //원래 mktime() //DC_DT_REGI
					//								 $objWorksheet->getCell('C'.$i)->getHyperlink()->getUrl(),
					//								 $objWorksheet->getCell('C'.$i)->getHyperlink()->getUrl(),

						$objWorksheet->getCell('P' . $i)->getValue(), //DC_URL_LOC , URL 정보
						$objWorksheet->getCell('M' . $i)->getValue(), //DC_AGENCY , 발행기관
					$objWorksheet->getCell('R' . $i)->getValue(), //메모1 , 첨부파일 hwp
					$objWorksheet->getCell('S' . $i)->getValue(), //메모2 , 표지파일 gif
					$code, // DC_CODE, 코드.. 나의 코드 , 대중소 분류된 코드 기입
					$objWorksheet->getCell('L' . $i)->getValue(), //DC_CONTENT , 내용
					$objWorksheet->getCell('O' . $i)->getValue(), //DC_PAGE , 페이지수
					$objWorksheet->getCell('F' . $i)->getValue(), //DC_CAT , 특수분류

				)

			   );
			   

			   /*
				업로드 시 게시글 table인 nt_document_list와 국가 list인 cty_list에 동시 insert를 진행
				cty_list의 경우 nt_countrys 에서 idx를 가져와 cty_list에 넣어준다.
				','를 기준으로 explode하여 각 국가를 분리한다.
				*/
				$PID=$Mem->insertId();
				$conStr = str_replace(' ','',$objWorksheet->getCell('I' . $i)->getValue());
				$conStr = str_replace('/',',',$conStr);
				$conStr= explode(",",$conStr);
				foreach($conStr as $con){
					$coq = $Mem->q("select * from nt_countrys where CTY_NM = '".$con."'")->fetch();
					if(!is_null($coq["IDX"])){
						$Mem->q("insert into nt_document_cty_list (PID, CTYID, DC_DT_REGI, DC_DT_MODI) values (?,?,?,?)",array(
							$PID, $coq["IDX"], $date, $date
						));
					}
				}


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
					echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';

				}

//파일 커버 업로드 하는 내용
}elseif($_FILES["FILE_COVER"]["tmp_name"]){
	for($i=0;$i<count($_FILES["FILE_COVER"]["tmp_name"]);$i++){
		$fname = $_FILES["FILE_COVER"]["name"][$i];
		$tmp_name = $_FILES["FILE_COVER"]["tmp_name"][$i];
		$type = $_FILES["FILE_COVER"]["type"][$i];
		$size = $_FILES["FILE_COVER"]["size"][$i];
		$t=$Mem->q("select IDX from nt_document_list where DC_MEMO2 like ? and STAT < 9",array($fname));

		if($t->rowCount()){
			$k=$t->fetch();
			$idx = $k["IDX"];
			$r=explode(".",$fname);
			$l = explode(" ", microtime());
			$k = explode(".",$l[0]);
			$name=$l[1].$k[1].".".$r[1];
			move_uploaded_file($tmp_name,$Mem->data["cover"].$name);
			$Mem->q("insert into nt_document_file_list(PID,FILE_NAME,FILE_PATH,FILE_TYPE,FILE_EXT,FILE_SIZE,FILE_DT) values(?,?,?,?,?,?,?) ",array($idx,$fname,$name,1,$type,$size,mktime()));
		}else{
			echo "[".$fname."] is not registered in document list";
			continue;
		}
	}
	unset($_FILES["FILE_COVER"]);
}elseif($_FILES["FILE_MODIFY"]["tmp_name"]){
	$upload_excel_file=uploadFile($Mem->data["temp"],$_FILES["FILE_MODIFY"]["tmp_name"],$_FILES["FILE_MODIFY"]["name"]);
	$objPHPExcel = new PHPExcel();
	$filename = $Mem->data["temp"].$upload_excel_file; // 읽어들일 엑셀 파일의 경로와 파일명을 지정한다.
	move_uploaded_file($_FILES["FILE_MODIFY"]["tmp_name"], $filename);
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
			//2번째부터 시작.
			$i =2;
			while($objWorksheet->getCell('J' . $i)->getValue()){
				$Mem->q("select idx from nt_document_list where DC_TITLE_KR like ? ",
				$objWorksheet->getCell('J' . $i)->getValue());
				$i++;

			}
			$i =2;
			while($objWorksheet->getCell('J' . $i)->getValue()){
				if($pname = $objWorksheet->getCell('J' . $i)->getValue()){
					
					$PID = $Mem->q("select idx from nt_document_list where DC_TITLE_KR like '".$pname."'");
					$tta =$PID->fetch();
					$PID = $tta["idx"];
					$Large=$objWorksheet->getCell('B' . $i)->getValue();
					$Medium=$objWorksheet->getCell('C' . $i)->getValue();
					$Small=$objWorksheet->getCell('D' . $i)->getValue();
					if(is_null($Large)){continue;}
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
					$date = mktime();
					
					$document_array = array();
					$document_where ="update nt_document_list SET ";
					if($objWorksheet->getCell('K' . $i)->getValue()){
						$document_where.="DC_TITLE_OR = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('K' . $i)->getValue())); 
					}if($objWorksheet->getCell('J' . $i)->getValue()){
						$document_where.="DC_TITLE_KR = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('J' . $i)->getValue())); 
					}if($objWorksheet->getCell('E' . $i)->getValue()){
						$document_where.="DC_KEYWORD = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('E' . $i)->getValue())); 
					}if($objWorksheet->getCell('G' . $i)->getValue()){
						$document_where.="DC_TYPE = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('G' . $i)->getValue())); 
					}if($objWorksheet->getCell('I' . $i)->getValue()){
						$document_where.="DC_COUNTRY = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('I' . $i)->getValue())); 
					}if($objWorksheet->getCell('N' . $i)->getValue()){
						$document_where.="DC_DT_WRITE = ? ,";
						array_push($document_array,strval(DT_trans($objWorksheet->getCell('N' . $i)->getValue()))); 
					}if($date){
						$document_where.="DC_DT_REGI = ? ,";
						array_push($document_array,strval($date)); 
					}if($objWorksheet->getCell('P' . $i)->getValue()){
						$document_where.="DC_URL_LOC = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('P' . $i)->getValue())); 
					}if($objWorksheet->getCell('M' . $i)->getValue()){
						$document_where.="DC_AGENCY = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('M' . $i)->getValue())); 
					}if($objWorksheet->getCell('Q' . $i)->getValue()){
						$document_where.="DC_MEMO1 = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('Q' . $i)->getValue())); 
					}if($objWorksheet->getCell('R' . $i)->getValue()){
						$document_where.="DC_MEMO2 = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('R' . $i)->getValue())); 
					}if($code){
						$document_where.="DC_CODE = ? ,";
						array_push($document_array,strval($code)); 
					}if($objWorksheet->getCell('O' . $i)->getValue()){
						$document_where.="DC_PAGE = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('O' . $i)->getValue())); 
					}if($objWorksheet->getCell('F' . $i)->getValue()){
						$document_where.="DC_CAT = ? ,";
						array_push($document_array,strval($objWorksheet->getCell('F' . $i)->getValue())); 
					}if($objWorksheet->getCell('I' . $i)->getValue()){
						$conStr = str_replace(' ','',$objWorksheet->getCell('I' . $i)->getValue());
						$conStr = str_replace('/',',',$conStr);
						$conStr= explode(",",$conStr);
						$stat = 0;

						foreach($conStr as $con){
							$coq = $Mem->q("select * from nt_countrys where CTY_NM = '".$con."'")->fetch();
								if(!is_null($coq["IDX"])){
									if($stat==0){$stat=1;$Mem->q("delete from nt_document_cty_list where PID=?",$PID);}
									$Mem->q("insert into nt_document_cty_list (PID, CTYID, DC_DT_REGI, DC_DT_MODI) values (?,?,?,?)",array(
										$PID, $coq["IDX"], $date, $date));
								}
						}
					}
					$document_where.="stat = 1 where idx = ?";
					array_push($document_array,$PID);
					$Mem->q($document_where,$document_array);
					/*
					$ttt= $Mem->q("update nt_document_list SET DC_TITLE_OR = '".$objWorksheet->getCell('K' . $i)->getValue()."',
					DC_TITLE_KR = '".$objWorksheet->getCell('J' . $i)->getValue()."',
					DC_KEYWORD = '".$objWorksheet->getCell('E' . $i)->getValue()."',
					DC_TYPE = '".$objWorksheet->getCell('G' . $i)->getValue()."',
					DC_COUNTRY = '".$objWorksheet->getCell('I' . $i)->getValue()."',
					DC_SMRY_KR = '1',
					DC_DT_COLLECT_STR = '',
					DC_DT_WRITE = '".DT_trans($objWorksheet->getCell('N' . $i)->getValue())."',
					DC_DT_REGI = '".$date."',
					DC_URL_LOC = '".$objWorksheet->getCell('P' . $i)->getValue()."',
					DC_AGENCY = '".$objWorksheet->getCell('M' . $i)->getValue()."',
					DC_MEMO1 = '".$objWorksheet->getCell('Q' . $i)->getValue()."',
					DC_MEMO2 = '".$objWorksheet->getCell('R' . $i)->getValue()."',
					DC_CODE = '".$code."',
					DC_CONTENT = '".$objWorksheet->getCell('L' . $i)->getValue()."',
					DC_PAGE = '".$objWorksheet->getCell('O' . $i)->getValue()."',
					DC_CAT = '".$objWorksheet->getCell('F' . $i)->getValue()."'
					where IDX = '".$PID."'");*/
					$i++;
				}
			}
		}
	}catch (exception $e) {
		echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';

	}
}

function DT_trans($date){
    if(!$date){return 0;}
    $val = explode('.',$date);
    if(!$val[0]){return 0;}
    else{
		if(mktime($val[0])==FALSE){return 0;}
        if(strlen($val[0])<4){
            return 0;
        }else{
            $y = $val[0];
        }
    }
    if(!$val[1]){return mktime(0,0,0,1,1,$y);}
    else{
        if(strlen($val[1])<2){
            $m="0".$val[1];
        }else{
            $m = $val[1];
        }
    }
    if(!$val[2]){return mktime(0,0,0,$m,1,$y);}
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

<div class="round_box1" >
<div class="title1">데이터 엑셀등록</div>
<div style="line-height:40px;" >DB제출용 포맷에 맞추어진 엑셀파일을 업로드해주세요.</div>
<div style="line-height:40px;" ><b>현재 등록된 데이터 : <?=$Mem->qs("Select count(*) from nt_document_list where STAT < 9 ")?></b></div>
<form action="" enctype="multipart/form-data" method="post" >

	<input type="file" name="FILE_EXCEL"> <input type="submit" class="button1" value="엑셀적용" >
</form>
</div>


<div class="round_box1" >
<div class="title1">파일 데이터 등록</div>
<div style="line-height:40px;" >DB제출용 포맷에 지정된 이미지 파일을 업로드해주세요. (최대 100개)</div>
<div style="line-height:40px;" ><b>현재 등록된 이미지 데이터 : <?=$Mem->qs("Select count(*) from nt_document_file_list where STAT < 9 and FILE_TYPE = 1")?></b></div>
<form action="" enctype="multipart/form-data" method="post" >

	<input type="file" name="FILE_COVER[]" multiple> <input type="submit" class="button1" value="파일적용" >
</form>
</div>

<div class="round_box1" >
<div class="title1">데이터 수정등록</div>
<div style="line-height:40px;" >DB제출용 포맷에 맞추어진 엑셀파일을 업로드해주세요.</div>
<div style="line-height:40px;" ><b>현재 등록된 데이터 : <?=$Mem->qs("Select count(*) from nt_document_list where STAT < 9 ")?></b></div>
<form action="" enctype="multipart/form-data" method="post" >

	<input type="file" name="FILE_MODIFY"> <input type="submit" class="button1" value="엑셀적용" >
</form>
</div>
