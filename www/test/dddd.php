<?
error_reporting(E_ALL);ini_set("display_errors", 1);
//include "/home/knlab/_Class/Member.php"; $Mem=new Member();
//include "/home/knlab/_Class/Elastic.php";
include "/home/knlab/_Class/_Define.php";
include "/home/knlab/_Class/_Lib.php";

$nextrend=new PDO("mysql:host=localhost;port=3306;dbname=nexteli;charset=utf8","root","tony267");
$politica=new PDO("mysql:host=data.knlab.kr;port=3306;dbname=politica;charset=utf8","crawl","crawl12!@");
$nextrend->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$nextrend->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$politica->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$politica->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
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
<script>
alert(DT_show(31536000));
</script>