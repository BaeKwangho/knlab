
<?php

function conv_solr_time($time){
	$duration=date("Y-m-d",$time).'T'.date("h:i:s",$time).'Z';
	return $duration;
}

function highlight_word( $content, $word, $color ) {
    $replace = '<b style="font-weight:bolder">' . $word . '</b>'; // create replacement
    $content = str_replace( $word, $replace, $content ); // replace content

    return $content; // return highlighted data
}

function highlight_words( $content, $words, $colors ) {
    $color_index = 0; // index of color (assuming it's an array)
	$inc_word = false;

	// loop through words
	if(is_array($words)){
		foreach( $words as $word ) {
			if(strpos($content,$word)===false){
				$inc_word = false;
			}else{
				$inc_word = true;
				$content = highlight_word( $content, $word, $colors[$color_index] ); // highlight word
				$color_index = ( $color_index + 1 ) % count( $colors ); // get next color index
			}
		}
	}else{
		if(strpos($content,$words)===false){
			$inc_word = false;
		}else{
			$inc_word = true;
			$content = highlight_word( $content, $words, $colors[$color_index] ); // highlight word
			$color_index = ( $color_index + 1 ) % count( $colors ); // get next color inde
		}
	}
    return $content; // return highlighted data
}


function DT_show($date){
	if($date==0){
		return "-";
	}else{
		$nowString = date("Y-m-d",$date);
		if((substr($nowString,5,2)=='01')&&(substr($nowString,8,2)=='01')){
			return substr($nowString,0,4);
		}
		return $nowString;
	}
}

function num($number){
	$num=preg_replace('/[^0-9]/', '', $number);
return $num;

}

function bytes($size){

	if($size>=1099511627776){
		return ceil($size/1099511627776)."Tbyte";
	}elseif($size>=(1024*1024*1024)){
		return ceil($size/1073741824)."Gbyte";
	}elseif($size>=(1024*1024)){
		return ceil($size/1048576)."Mbyte";
	}elseif($size>=(1024)){
		return ceil($size/1024)."Kbyte";
	}else{
		return $size."byte";
	}




}


function sign($cost){
	$str="";
	if($cost >0){	$str="<span style=\"color:blue\">+".number_format($cost);	}
	if($cost ==0){	$str="<span style=\"color:gray\">".number_format($cost);	}
	if($cost < 0){	$str="<span style=\"color:red\">".number_format($cost);		}
	$str.="</span>";
	return $str;
}


function uid($uid,$var="User_Name"){
	global $Mem;
	$value="";
	if($uid>0){
		if(strlen($var)==0){	$var="Device_Name";	}
		$value=$Mem->qs("Select ".$var." from ibss_member_list where IDX= ? ",$uid);
	}
	return $value;
}



function lid($LID,$length){
	global $Mem;
	$value="";
	if(strlen($LID) >= $length){
		$value=$Mem->qs("Select name from ibss_location_list where code= ?  ",substr($LID,0,$length));
	}

 	return $value;
}


function line_num($number,$set=0){
	$num=preg_replace('/[^0-9]/', '', $number);


	$local=array('02','03','03','03','04','04','04','05','06','07','08');
	$cate=substr($num,0,2);


		//지역번호 해당시
		if(in_array($cate,$local)){

			if($cate=="02"){
				$nums[0]=substr($num,0,2);
				$nums[1]=substr($num,2,strlen($num)-6);
			}else{
				$nums[0]=substr($num,0,3);
				$nums[1]=substr($num,3,strlen($num)-7);
			}
			$nums[2]=substr($num,-4);
		}elseif($cate=="01"){
			$nums[0]=substr($num,0,3);
			$nums[1]=substr($num,3,strlen($num)-7);
			$nums[2]=substr($num,-4);
		}else	if($cate=="15"){
			$nums[1]=substr($num,0,strlen($num)-4);
			$nums[2]=substr($num,-4);
		}else{
			$nums=$num;
			$no_local=true;

		}


	if(!$set){
		return $nums[0].(($nums[0]&&$nums[1])?"-":"").(($nums[1])?"".$nums[1]:"").(($nums[2])?"-".$nums[2]:"");
	}else{
		return $nums;
	}
}


function uploadFile($path,$tmp_name,$name ,$set=true){
	$r=explode(".",$name);

	if(in_array($r[1],array('jsp','php','htm','html','js'))){
		return false;
	}else{
		if($set){
			$l = explode(" ", microtime());
			$k = explode(".",$l[0]);
			$name=$l[1].$k[1].".".$r[1];
		}
		move_uploaded_file($tmp_name,$path.$name);
		return $name;
	}
}




/*
function upload($file,$path,$extension=array(),$limit_size=0){

	$ext_temp=explode(".",$_FILES[$file][name]);
	$file_ext=strtolower($ext_temp[sizeof($ext_temp)-1]);


	$file_array=array();

	if(in_array($file_ext,array('jsp','php','htm','html','js'))){

		}else{

			if(in_array($file_ext,$extension)|| sizeof($extension)==0){

				$original_name=$_FILES[$file][name];
				$l = explode(" ", microtime());
				$k = explode(".",$l[0]);
				$_FILES[$file][name]=$l[1].$k[1].".".$file_ext;

				if(($limit_size&& $limit_size>=$_FILES[$file][size])|| $limit_size==0){
					if(move_uploaded_file($_FILES[$file][tmp_name],$path.$_FILES[$file][name])){
						$file_array[file]=$_FILES[$file][name];;
						$file_array[name]=$original_name;
						$file_array[size]=$_FILES[$file][size];
						$file_array[ext]=$file_ext;

					}
				}

			}
		}

	return $file_array;

}

*/



function file_size($size){

if($size>1024) $byte=ceil($size/1024)." Kbyte";
if($size>(1024*1024)) $byte=ceil(($size/1024)/1024).".".ceil($size/1024)." Mbyte";
if($size>(1024*1024*1024)) $byte=ceil((($size/1024)/1024)/1024)." Gbyte";

return $byte;

}



function		mv($url)			{		header("location:$url");		}
function		mvs($url)		{			echo "<script>document.location.href='$url';</script>";		}




// 하단 네비 바
function navi_bars($total,$list_n,$page_n,$hrzt,$param){

/*

total		: 전체 게시물수
list_n		: 한페이지에 출력할 페이지 수
page		: blank
dv			: blank
img		: 좌우측 이미지 화살표 배열값
param	: 파라미터 추가
*/

global $_Ajax;
$number_list="";
$auto_div=0;

$PHP_SELF=substr($_SERVER["PHP_SELF"],1,strlen($_SERVER["PHP_SELF"]));
if(!$_Ajax["param"]){$_Ajax["param"]="GETOP=0";}else{
	$_Ajax["param"]="GETOP=1";
	$_Ajax["param"].=$param;
}
if(!$param){ $param="?";	}else{	$param.="&";	}

$ico[0]="<<";
$ico[1]="<";
$ico[2]=">";
$ico[3]=">>";



// 전에 목록 바로 가기 시작
if(is_array($_Ajax) && $_Ajax["div"]){ $number_list.="<ul class='navi_bar' >"; }else{		$number_list.="<div class='navi_bar' >";		}
//navi_bar가 원래 ul로 지정되어 있었지만 css에 구현물이 없기에 div기준 바로 구현
//$auto_div=$_GET["div"];
$_GET["div"]=floor(($_GET["page"]-1)/$hrzt)+1;

//$_GET["div"]=$auto_div;

if($_GET["div"]){	$k=($hrzt * ($_GET["div"]-1))+1;}else{	$k=1;	$_GET["div"]=1;}
$is_page=(int)(($_GET["div"])*$hrzt)-$hrzt;
$next_page = ($_GET["div"] * $hrzt)+1;
if($_GET["div"] > 1 || !$_GET["div"]){


if(is_array($_Ajax) &&$_Ajax["div"]){
$number_list.="<li onclick=\"getup('".$PHP_SELF."?".$_Ajax["param"]."&page=1&div=".$_GET["div"]."','".$_Ajax["div"]."');\" style=cursor:hand >$ico[0]</li>&nbsp;".
"<li onclick=\"getup('".$PHP_SELF."?".$_Ajax["param"]."&page=$is_page&div=".($_GET["div"]-1)."','".$_Ajax["div"]."');\" style=cursor:hand >$ico[1]</li>";
}else{
$number_list.="<a href=".$_SERVER["PHP_SELF"].$param."page=1>$ico[0]</a>&nbsp;<a href=".$_SERVER["PHP_SELF"].$param."page=".$is_page."&div=".($_GET["div"]-1).">$ico[1]</a>&nbsp;";
}



}

// 번호 시작 부
for($i=$k; $i < $k+$hrzt; $i++){

$i_1=$i;

if(is_array($_Ajax) && $_Ajax["div"]){
	if($_GET["page"]==$i){
	$number_list.="<li class=\"active\" onclick=\" getup('".$PHP_SELF."?".$_Ajax["param"]."&page=$i&div=".$_GET["div"]."','".$_Ajax["div"]."');\"  >$i_1</li>";
	}else{
		$number_list.="<li  onclick=\" getup('".$PHP_SELF."?".$_Ajax["param"]."&page=$i&div=".$_GET["div"]."','".$_Ajax["div"]."');\"  >$i_1</li>";
	}

}else{
	if($_GET["page"]==$i){
		$number_list.="<a class=\"active\" href=".$_SERVER["PHP_SELF"].$param."page=$i&div=".$_GET["div"].">$i_1</a>&nbsp;";
	}else{
		$number_list.="<a href=".$_SERVER["PHP_SELF"].$param."page=$i&div=".$_GET["div"].">$i_1</a>&nbsp;";
	}
}

if(($total-($i*$list_n)) <=0) { break;}
}

// 뒷 부분 시작
if(($total-($_GET["div"]*$hrzt*$list_n)) > 0) {
$last_page=(int)($total/$list_n)+1;
$last_div=(int)($last_page/$hrzt)+1;


if(is_array($_Ajax) && $_Ajax["div"]){
$number_list.="<li  onclick=\"  getup('".$PHP_SELF."?".$_Ajax["param"]."&page=".$next_page."&div=".($_GET["div"]+1)."','".$_Ajax["div"]."');\" style='cursor:pointer;' >$ico[2]</li>".
"<li   onclick=\"getup('".$PHP_SELF."?".$_Ajax["param"]."&page=$last_page&div=".($last_div)."','".$_Ajax["div"]."');\" style='cursor:pointer;' >$ico[3] </li></ul>";

}else{
$number_list.="<a href=".$_SERVER["PHP_SELF"].$param."page=".$next_page."&div=".($_GET["div"]+1).">$ico[2]</a>&nbsp;<a href=".$_SERVER["PHP_SELF"].$param."page=$last_page&div=$last_div>$ico[3]</a></div>";

}





}

if(is_array($_Ajax) && $_Ajax["div"]){ $number_list.="</ul>"; }else{		$number_list.="</div>";		}





return $number_list;
}



function paging($query,$options=array(),$b,$c,$opt="IDX desc",$param=""){

	global $Mem;
	global $_Ajax;
	global $parameter;
	$param="?".$parameter.$param;
	$print_n=$b;
	$print_horizontal =$c;
//	$total=mysql_num_rows(mysql_query($query)); // 전체 목록수 출력
	$DB=$Mem->DB;

	$stmt=$DB->prepare($query);
	$stmt->execute($options);


//	$total=$stmt->fetchColumn();
//	$rows= $stmt->fetchAll();
// 	$total= count($rows);
 $total= $stmt->rowCount();

	$paging=array();



	if(array_key_exists("page",$_GET)){
		if($_GET['page']){
			$start=($_GET['page']-1)*$print_n;
			$end=(($_GET['page']-1)*$print_n)+$print_n;
		}else{
			$_GET['page']=1;
			$start=0;
			$end=$print_n;
		}
	}else{

			$_GET['page']=1;
			$start=0;
			$end=$print_n;
	}

	if(!$opt) $opt="idx desc";

	//echo $query." order by $opt limit $start , $print_n";
	$stmts=$DB->prepare($query." order by $opt limit $start , $print_n");
	$stmts->execute($options);
	$paging[0]=$stmts;
	$paging[1]=($total-$start);
	$paging[2]=navi_bars($total,$print_n,$_GET['page'],$print_horizontal,$param);
	$paging[3]=$total;



/*
	$paging[0]=mysql_query($query." order by $opt limit $start , $print_n");
	$paging[1]=($total-$start);
	$paging[2]=navi_bars($total,$print_n,$_GET['page'],$print_horizontal,$param);
*/

	return $paging;
}


function solr_paging($DB,$query,$b,$c,$opt="IDX desc",$param=""){
	$result = $DB->select($query);
	$print_n=$b;
	$print_horizontal =$c;
	$total = $result->getNumFound();

	$paging=array();

	if(array_key_exists("page",$_GET)){
		if($_GET['page']){
			$start=($_GET['page']-1)*$print_n;
			$end=(($_GET['page']-1)*$print_n)+$print_n;
		}else{
			$_GET['page']=1;
			$start=0;
			$end=$print_n;
		}
	}else{

			$_GET['page']=1;
			$start=0;
			$end=$print_n;
	}

	if(!$opt) $opt="idx desc";

	$query['start'] = $start;
	$query['rows'] = $print_n;

	$result = $DB->select($query);

	$paging[0]=$result;
	$paging[1]=($total-$start);
	$paging[2]=navi_bars($total,$print_n,$_GET['page'],$print_horizontal,$param);

	return $paging;
}





function paging2($DB,$query,$options=array(),$b,$c,$opt="IDX desc",$param=""){

 	global $_Ajax;
	global $parameter;

	 $parameter;

	$param="?".$parameter.$param;
	$print_n=$b;
	$print_horizontal =$c;
//	$total=mysql_num_rows(mysql_query($query)); // 전체 목록수 출력
//	$DB=$DB;

	$stmt=$DB->prepare($query);
	$stmt->execute($options);


//	$total=$stmt->fetchColumn();
//	$rows= $stmt->fetchAll();
// 	$total= count($rows);
 $total= $stmt->rowCount();

	$paging=array();



	if(array_key_exists("page",$_GET)){
		if($_GET['page']){
			$start=($_GET['page']-1)*$print_n;
			$end=(($_GET['page']-1)*$print_n)+$print_n;
		}else{
			$_GET['page']=1;
			$start=0;
			$end=$print_n;
		}
	}else{

			$_GET['page']=1;
			$start=0;
			$end=$print_n;
	}

	if(!$opt) $opt="idx desc";


	$stmts=$DB->prepare($query." order by $opt limit $start , $print_n");
	$stmts->execute($options);
	$paging[0]=$stmts;
	$paging[1]=($total-$start);
	$paging[2]=navi_bars($total,$print_n,$_GET['page'],$print_horizontal,$param);



/*
	$paging[0]=mysql_query($query." order by $opt limit $start , $print_n");
	$paging[1]=($total-$start);
	$paging[2]=navi_bars($total,$print_n,$_GET['page'],$print_horizontal,$param);
*/

	return $paging;
}








function img_size($a1,$a2,$b1,$b2){

$a1; //입력된 원본이미지 가로사이즈
$a2; //입력된 원본이미지 세로사이즈

$b1; //변경될 가로사이즈
$b2; //변경될 세로사이즈


$size[0]; //정렬된 가로 사이즈
$size[1]; //정렬된 세로 사이즈



if($a1 > $b1 ){
$r1			=($a1-$b1)/($a1/100);
$size[0]	=$b1;
$size[1]	=$a2-(($a2/100)*$r1);
}elseif( $a2 > $b2){
$r1			=($a2-$b2)/($a2/100);
$size[0]	=$a1-(($a1/100)*$r1);
$size[1]	=$b2;
}

if($size[1] > $b2 ){
$r1			=($size[1]-$b2)/($size[1]/100);
$size[0]	=$size[0]-(($size[0]/100)*$r1);
$size[1]	=$b2;
}elseif($size[0] > $b1){
$r1			=($size[0]-$b1)/($size[0]/100);
$size[0]	=$b1;
$size[1]	=$size[1]-(($size[1]/100)*$r1);
}


if(!$size[0]) $size[0]=$a1;
if(!$size[1]) $size[1]=$a2;

$size[0]=ceil($size[0]);
$size[1]=ceil($size[1]);

return $size;
}















function img($file,$width=0,$height=0,$opt=""){

if($width==0){	$widths="";			}else{	$widths="width=".$width;	}
if($height==0){	$heights="";	}else{	$heights="width=".$height;	}

return "<img src=$file $widths $heights border=0 $opt>";

}




function img_resize($file,$width,$height){

	$ext=strtolower($file);
	$ext_array=explode(".",$ext);
	$ext=$ext_array[sizeof($ext_array)-1];

	if($ext=="jpg"||$ext=="jpeg"){		$img=imagecreatefromjpeg($file);			}
	if($ext=="gif"){			$img=imagecreatefromgif($file);			}
//	if($ext=="bmp"){			$img=imagecreatefromwbmp($file);			}
	if($ext=="png"){			$img=imagecreatefrompng($file);			}

	$s=@getimagesize($file);

	$im=@imagecreatetruecolor($width,$height);
	@imagecopyresized ( $im, $img, 0, 0, 0, 0, $width, $height ,$s[0],$s[1]);



	if($ext=="jpg"||$ext=="jpeg"){			imagejpeg($im,$file,100);		}
	if($ext=="gif"){				imagegif($im,$file);				}
//	f($ext=="bmp"){			imagejpeg($im,$file,100);		}
	if($ext=="png"){			 imagepng($im,$file,100);		}
}


function img_resize2($file,$width,$height){

	$ext=strtolower($file);
	$ext_array=explode(".",$ext);
	$ext=$ext_array[sizeof($ext_array)-1];

	if($ext=="jpg"||$ext=="jpeg"){		$img=imagecreatefromjpeg($file);			}
	if($ext=="gif"){			$img=imagecreatefromgif($file);			}
//	if($ext=="bmp"){			$img=imagecreatefromwbmp($file);			}
	if($ext=="png"){			$img=imagecreatefrompng($file);			}

	$s=@getimagesize($file);
	$ss=img_size($s[0],$s[1],$width,$height);
	$im=@imagecreatetruecolor($ss[0],$ss[1]);
	@imagecopyresized ( $im, $img, 0, 0, 0, 0, $ss[0], $ss[1] ,$s[0],$s[1]);


	if($ext=="jpg"||$ext=="jpeg"){			imagejpeg($im,$file,100);		}
	if($ext=="gif"){				imagegif($im,$file);				}
//	f($ext=="bmp"){			imagejpeg($im,$file,100);		}
	if($ext=="png"){			 imagepng($im,$file);		}
}





function img_stamp($file,$stamp,$x=0,$y=0,$opt=60){

	global $preview;



	$r=explode(".",$stamp);
	$ext=$r[1];
	$ext=strtolower($ext);

	$im=imagecreatefromjpeg($file);

	if($ext=="png")	$stp=imagecreatefrompng($stamp);
	if($ext=="jpg")		$stp=imagecreatefromjpeg($stamp);
	if($ext=="gif")		$stp=imagecreatefromgif($stamp);
//	if($ext=="png")		$stp=imagecreatefrompng($stamp);

	$s=getimagesize($stamp);
	$s1=getimagesize($file);

	imageantialias($stp,true);
	imageantialias($im,true);


	if($x==0) $x=$s1[0]-$s[0];
	if($y==0) $y=$s1[1]-$s[1];



	imagecopymerge ( $im, $stp, $x, $y, 0, 0, $s[0], $s[1], $opt);


	if($preview){
	imagejpeg($im);
	}else{
	imagejpeg($im,$file,100);
	}


}



function img_text($file){

header('Content-Type: image/jpeg');
	$im=imagecreatefromjpeg($file);
	$s1=getimagesize($file);

	$im_font = imagecreatetruecolor($s1[0], $s1[1]);

	$transparent=imagecolorallocatealpha($im_font,255,255,255,80);
	imagefill( $im_font, 0, 0, $transparent );

	$text_color = ImageColorAllocate ($im_font, 255, 255, 255);
	$list= ImageTTFText ($im, 54, 0, 100, 100, $transparent, "BonvenoCF-Light.otf","copyright 2006");


  //	imagecopymerge ( $im, $im_font, 0, 0, 0, 0, $s1[0], $s1[1], 50);
//	imagejpeg($im,$file,100);
imagejpeg($im);
imagedestroy($im);

}


function img_stamp2($file){


$im_img=imagecreatefromjpeg($file);
$s1=getimagesize($file);
$im = imagecreatetruecolor($s1[0], $s1[1]);
$im_temp = imagecreatetruecolor($s1[0], $s1[1]);




imagecopymerge ( $im, $im_img, 0, 0, 0, 0, $s1[0], $s1[1], 100);


$query=q("select * from ibss_water_mark_list ");

$text_height_check=0;
for($i=0; $i < mysql_num_rows($query); $i++){
	$row=mysql_fetch_array($query);

	$size=$s1[0]*($row[size]/1000);
	$row[color]=str_replace("#","",$row[color]);

	$R= base_convert(substr($row[color],0,2),16,10);
	$G= base_convert(substr($row[color],2,2),16,10);
	$B= base_convert(substr($row[color],4,2),16,10);

	$transparent=imagecolorallocatealpha($im,$R,$G,$B,$row[opt]*1.27);
	$text_check=ImageTTFText ($im_temp,$size, 0,$row[x],$row[y], $transparent, $row[font],$row[msg]);


	$percent_x=$s1[0]*(((int)str_replace("%","",$row[x]))/100);
	$left=$percent_x-($text_check[2]/2);

	$percent_y=$s1[1]*(((int)str_replace("%","",$row[y]))/100);
	$top=$percent_y+(($text_check[3]/2));


	if($top<$text_height_check) $top+=(($text_height_check-$top));



	ImageTTFText ($im,$size, 0,$left,$top, $transparent, $row[font],$row[msg]);
	$text_height_check=$text_check[3]+$top;





}


imagejpeg($im,$file,100);
imagedestroy($im_temp);
imagedestroy($im_img);
imagedestroy($im);

}




function img_shop_resize($file,$width,$height){

	$ext=strtolower($file);
	$ext_array=explode(".",$ext);
	$ext=$ext_array[sizeof($ext_array)-1];

	if($ext=="jpg"||$ext=="jpeg"){		$img=imagecreatefromjpeg($file);			}
	if($ext=="gif"){			$img=imagecreatefromgif($file);			}
	if($ext=="png"){			$img=imagecreatefrompng($file);			}

	$s=getimagesize($file);
	$ss=img_size($s[0],$s[1],$width,$height);
	$im=imagecreatetruecolor($ss[0],$ss[1]);

	if($ext=="jpg"||$ext=="jpeg"){
		imagecopyresampled($im, $img, 0, 0, 0, 0,  $ss[0], $ss[1] ,$s[0],$s[1]);
		imagejpeg($im,$file,100);
	}
	if($ext=="gif"){
		imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
		imagealphablending($im, false);
		imagesavealpha($im, true);
		imagecopyresampled($im, $img, 0, 0, 0, 0,  $ss[0], $ss[1] ,$s[0],$s[1]);
		imagegif($im,$file);

	}
	if($ext=="png"){
		imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
		imagealphablending($im, false);
		imagesavealpha($im, true);
		imagecopyresampled($im, $img, 0, 0, 0, 0,  $ss[0], $ss[1] ,$s[0],$s[1]);
		imagepng($im,$file);
	}


}



function img_shop_water_mark($file,$title,$font,$font_size=38,$font_alpha=30,$domain=""){
	$im=imagecreatefrompng($file);
	$s1=getimagesize($file);
	$im_font = imagecreatetruecolor($s1[0], $s1[1]);
	$transparent=imagecolorallocatealpha($im_font,255,255,255,$font_alpha);
	imagefill( $im_font, 0, 0, $transparent );
	$text_color = ImageColorAllocate ($im_font, 255, 255, 255);
	$check= ImageTTFText ($im_font, $font_size, 0, 10, $s1[1]/2, $transparent-10, $font,$title);
	$list= ImageTTFText ($im, $font_size, 0, ($s1[0]-$check[2])/2, ((($s1[1]-$check[3])/2)+($check[3]/2)), $transparent-10, $font,$title);

	if(strlen($domain)){

		$list2= ImageTTFText ($im, 15, 0, ($s1[0]-$check[2])/2, ((($s1[1]-$check[3])/2)+($check[3]/2))+20, $transparent-10, $font,$domain);

	}


	imagecolortransparent($im, imagecolorallocatealpha($im, 0, 0, 0, 127));
	imagealphablending($im, false);
	imagesavealpha($im, true);
	imagecopyresampled($im, $img, 0, 0, 0, 0,  $ss[0], $ss[1] ,$s[0],$s[1]);
	imagepng($im,$file);
	imagedestroy($im);
}






function sela($array,$value=""){
	$str="";
	while($r=each($array)){
	$str.="<option value=\"".$r[1]."\" ".($r[1]==$value?"selected":"")." >".$r[1]."</option>\n";
	}
	return $str;
}

function sel($min,$max,$value=""){
for($i=$min; $i <=$max;  $i++){

$str.="<option value=\"".$i."\" ".($value==$i?"selected":"").">$i</option>\n";

}
return $str;
}



function selas($array,$value=""){
	$str="";
	while($r=each($array)){
	$str.="<option value=\"".$r[0]."\" ".($r[0]==$value?"selected":"")." >".$r[1]."</option>\n";
	}
	return $str;
}


function selas2($array,$value=""){
	$str="";
	while($r=each($array)){
	$str.="<option value=\"".$r[0]."\" ".($r[1]==$value?"selected":"")." >".$r[1]."</option>\n";
	}
	return $str;
}



function cell($var_type,$value,$type=0){
$data=array();
if($type){


}else{
	return qs("select  style  from ibss_variable where type=$var_type  and `var`=$value ");
}
}


 function Color($target,$data){
	global $Mem;
	return  $Mem->qs("select `Color` from  `_ibss`.ibss_variable_data  where `PID`=? and `VALUE`=? ",array($target,$data));

 }



 function VRM($target,$data,$display=0,$style="",$option=""){

	global $Mem;
	if(!$style) $style="input_cell";

	if($display){

		switch($display){
			case 1:
					echo  "<select name='".$Mem->qs("select CODE from `_ibss`.ibss_variable_list where idx=? ",$target)."' class='".$style."' >\n";
					$Q=$Mem->q("select * from `_ibss`.ibss_variable_data where pid=? order by priority asc ",$target);
					while($rows=$Q->fetch()){	echo "<option value='".$rows["VALUE"]."' ".($rows["VALUE"]==$data?"selected":"").">".$rows["NAME"]."</option>\n";	}
					echo "</select>";
				break;

				case 2:
					$name=$Mem->qs("select code from `_ibss`.ibss_variable_list where idx=?",$target );
					$q=$Mem->q("select * from `_ibss`.ibss_variable_data where PID=? order by priority asc, name asc ",$target );
					$i=0;
					echo "<ul class='inline2' >";
					while($rows=$q->fetch()){
						$i++;
						echo "<li><input type='radio' name='".$name."'  value='".$rows["VALUE"]."' id='".$name.$i."'  ".($rows["VALUE"]==$data?"checked":"")." ".($rows["Defaluts"]==1?"checked":"")." ><label for='".$name.$i."'>".$rows["NAME"]."</label></li>";
					}
					echo "</ul>";
					break;


			case 3:
					$name=$Mem->qs("select code from `_ibss`.ibss_variable_list where idx=? ",$target );
					$q=$Mem->q("select * from `_ibss`.ibss_variable_data where pid=? order by priority asc, name asc ",$target);
					$i=0;

					echo "<ul class='inline2' >";
					while($rows=$q->fetch()){

						$i++;
						echo "<li><input type='checkbox' name='".$name."[]'  value='".$rows["VALUE"]."' id='".$name.$i."'  ".(($rows["VALUE"]==$data||$_GET["EQ_Type".$rows["VALUE"]])?"checked":"")."  ></li><li  onclick='checks(\"".$name.$i."\");' >".$rows["NAME"]."</span></li>";
					}

					echo "</ul>";

				break;

		}
	}else{
			 $row=$Mem->qr("select * from  `_ibss`.`ibss_variable_data`  where `PID`=? and `VALUE`=? ",array($target,$data));  	return  $row["NAME"];
	}
 }






 function VR($target,$data,$display=0,$style="",$option=""){

	global $Mem;
	if(!$style) $style="input_cell";

	if($display){

		switch($display){
			case 1:
				echo  "<select name='".$Mem->qs("select code from ibss_variable_list where idx=? ",$target)."' class='".$style."' >\n";
				$Q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc ",$target);
				while($rows=$Q->fetch()){
					echo "<option value='".$rows["Value"]."' ".($rows["Value"]==$data?"selected":"").">".$rows["Name"]."</option>\n";
				}
				echo "</select>";
				break;

				case 2:
					$name=$Mem->qs("select code from ibss_variable_list where idx=?",$target );
					$q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc, name asc ",$target );
					$i=0;
					echo "<ul class='inline2' >";
					while($rows=$q->fetch()){
						$i++;
						echo "<li><input type='radio' name='".$name."'  value='".$rows["Value"]."' id='".$name.$i."'  ".($rows["Value"]==$data?"checked":"")." ".($rows[defaults]==1?"checked":"")." ><label for='".$name.$i."'>".$rows["Name"]."</label></li>";
					}
					echo "</ul>";
					break;


			case 3:
					$name=$Mem->qs("select code from ibss_variable_list where idx=? ",$target );
					$q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc, name asc ",$target);
					$i=0;

					while($rows=$q->fetch()){

						$i++;
						echo "<li><input type='checkbox' name='".$name."[]'  value='".$rows["Value"]."' id='".$name.$i."'  ".(($rows["Value"]==$data||$_GET["EQ_Type".$rows["Value"]])?"checked":"")."  ></li><li  onclick='checks(\"".$name.$i."\");' >".$rows["Name"]."</span></li>";
					}


				break;

		}
	}else{
			 $row=$Mem->qr("select * from  ibss_variable_data  where pid=? and value=? ",array($target,$data));  	return  $row["Name"];
	}
 }







 function variable($target,$data,$display=0,$style="",$option=""){

	global $Mem;
	if(!$style) $style="input_cell";

	if($display){

		switch($display){
			case 1:
				echo  "<select name='".$Mem->qs("select code from ibss_variable_list where idx=? ",$target)."' class='".$style."' >\n";
				$Q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc ",$target);
				while($rows=$Q->fetch()){
					echo "<option value='".$rows["Value"]."' ".($rows["Value"]==$data?"selected":"").">".$rows["Name"]."</option>\n";
				}
				echo "</select>";
				break;

				case 2:
					$name=$Mem->qs("select code from ibss_variable_list where idx=?",$target );
					$q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc, name asc ",$target );
					$i=0;
					echo "<ul class='inline2' >";
					while($rows=$q->fetch()){
						$i++;
						echo "<li><input type='radio' name='".$name."'  value='".$rows["Value"]."' id='".$name.$i."'  ".($rows["Value"]==$data?"checked":"")." ".($rows[defaults]==1?"checked":"")." ><label for='".$name.$i."'>".$rows["Name"]."</label></li>";
					}
					echo "</ul>";
					break;


			case 3:
					$name=$Mem->qs("select code from ibss_variable_list where idx=? ",$target );
					$q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc, name asc ",$target);
					$i=0;

					while($rows=$q->fetch()){

						$i++;
						echo "<li><input type='checkbox' name='".$name."[]'  value='".$rows["Value"]."' id='".$name.$i."'  ".(($rows["Value"]==$data||$_GET["EQ_Type".$rows["Value"]])?"checked":"")."  ></li><li  onclick='checks(\"".$name.$i."\");' >".$rows["Name"]."</span></li>";
					}


				break;
	/*
			case 2:
					$name=qs("select code from ibss_variable_list where idx=$target ");
					$q=q("select * from ibss_variable_data where pid=$target order by priority asc, name asc ");
					$i=0;
					echo "<ul class='inline2' >";
					while($rows=qa($q)){
						$i++;
						echo "<li><input type='radio' name='".$name."'  value='$rows[value]' id='".$name.$i."'  ".($rows[value]==$data?"checked":"")." ".($rows[defaults]==1?"checked":"")." ></li><li  onclick='checks(\"".$name.$i."\");' >$rows[name]</span></li>";
					}
					echo "</ul>";
				break;


	*/

		}



	}else{

	 $row=$Mem->qr("select * from  ibss_variable_data  where pid=? and value=? ",array($target,$data));
		return  $row["Name"];

	}



 }





 function variable2($target,$data,$display=0,$style="",$option=""){

	global $Mem;
	if(!$style) $style="input_cell";

	if($display){

		switch($display){
			case 1:
				echo  "<select name='".$Mem->qs("select code from ibss_variable_list where idx=? ",$target)."' class='".$style."' >\n";
				$Q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc ",$target);
				while($rows=$Q->fetch()){
					echo "<option value='".$rows["Value"]."' ".($rows["Value"]==$data?"selected":"").">".$rows["Name"]."</option>\n";
				}
				echo "</select>";
				break;


			case 3:
					$name=$Mem->qs("select code from ibss_variable_list where idx=? ",$target );
					$q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc, name asc ",$target);
					$i=0;

					while($rows=$q->fetch()){

						$i++;
						echo "<li><input type='checkbox' name='".$name."[]'  value='".$rows["Value"]."' id='".$name.$i."'  ".(($rows["Value"]==$data||$_GET["EQ_Type".$rows["Value"]])?"checked":"")."  ></li><li  onclick='checks(\"".$name.$i."\");' >".$rows["Name"]."</span></li>";
					}


				break;

		}



	}else{

	 $row=$Mem->qr("select * from  ibss_variable_data  where pid=? and value=? ",array($target,$data));
		return  "<span style='".$row["style"]."' >".$row["Name"]."</span>";

	}



 }





 function variable3($target,$data,$display=0,$style="",$option=""){

	global $Mem;
	if(!$style) $style="input_cell";

	if($display){

		switch($display){
			case 1:
				echo  "<select name='".$Mem->qs("select code from ibss_variable_list where idx=? ",$target)."' class='".$style."' >\n";
				$Q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc ",$target);
				while($rows=$Q->fetch()){
					echo "<option value='".$rows["Value"]."' ".($rows["Value"]==$data?"selected":"").">".$rows["Name"]."</option>\n";
				}
				echo "</select>";
				break;


			case 3:
					$name=$Mem->qs("select code from ibss_variable_list where idx=? ",$target );
					$q=$Mem->q("select * from ibss_variable_data where pid=? order by priority asc, name asc ",$target);
					$i=0;

					while($rows=$q->fetch()){

						$i++;
						echo "<li><input type='checkbox' name='".$name."[]'  value='".$rows["Value"]."' id='".$name.$i."'  ".(($rows["Value"]==$data||$_GET["EQ_Type".$rows["Value"]])?"checked":"")."  ></li><li  onclick='checks(\"".$name.$i."\");' >".$rows["Name"]."</span></li>";
					}


				break;

		}



	}else{

	 $row=$Mem->qr("select * from  ibss_variable_data  where pid=? and value=? ",array($target,$data));
		return  $row["Name"];

	}



 }



/*

 function variables($target,$data,$display=0){

$string="";
if($display){

	switch($display){
		case 1:
			$string.="<select name='".qs("select code from `ibss`.ibss_variable_list where idx=$target ")."' >\n";
			$q=q("select * from `ibss`.ibss_variable_data where pid=$target order by `value` asc ");
			while($rows=qa($q)){
				$string.="<option value='$rows[value]' ".($rows[value]==$data?"selected":"")." ".($rows[disabled]?"disabled":"").">$rows[name]\n";

			}
			$string.="</select>";
			break;


		case 2:
			$name=qs("select code from `ibss`.ibss_variable_list where idx=$target ");
			$q=q("select * from ibss_variable_data where pid=$target order by priority asc, name asc ");
			$i=0;
			$string.="<ul class='inline2' >";
			while($rows=qa($q)){
				$i++;
				$string.="<li><input type='radio' name='".$name."'  value='$rows[value]' id='".$name.$i."'  ".($rows[value]==$data?"checked":"")." ".($rows[defaults]==1?"checked":"")." ></li><li  onclick='checks(\"".$name.$i."\");' >$rows[name]</span></li>";
			}
			$string.="</ul>";
			break;

	}


	return $string;
}else{
	return qs("select name from  `ibss`.ibss_variable_data  where pid=$target and value=$data ");
}



 }


*/





function datec($data){
$list= explode("-",$data);

return mktime(0,0,0,$list[1],$list[2],$list[0]);

}


function cut_str($msg,$cut_size,$tail="...") {

  if($cut_size<=0) return $msg;
  if(ereg("\[re\]",$msg)) $cut_size=$cut_size+4;

  $max_size = $cut_size;
  $i=0;
  while(1) {
   if (ord($msg[$i])>127)
    $i+=3;
   else
    $i++;
   if (strlen($msg) < $i)
    return $msg;
   if ($max_size == 0)
    return substr($msg,0,$i).$tail;
   else
    $max_size--;
  }
}









function json_encoder($array){



if(sizeof($array[0])){


	$json_str="[";
	for($i=0; $i < sizeof($array); $i++){

		$json_str.="{";
		for($j=0; $j < sizeof($array[$i]); $j++){
			$key_list=each($array[$i]);
			$key_list[0];
			$json_str.="\"".$key_list[0]."\":\"".$key_list[1]."\"";
			if((sizeof($array[$i])-1) > $j) $json_str.=",";
		}
		$json_str.="}";

		if((sizeof($array)-1) > $i) $json_str.=",";
	}

	return $json_str.="]";


}else if(sizeof($array)){


		$json_str.="{";
		for($j=0; $j < sizeof($array); $j++){
			$key_list=each($array);
			$key_list[0];
			$json_str.="\"".$key_list[0]."\":\"".$key_list[1]."\"";
			if((sizeof($array)-1) > $j) $json_str.=",";
		}
		$json_str.="}";


	return $json_str;

}else{
	return "[]";
}





}



function getTimes($var){

$var=(int)$var;

$hour=sprintf("%02d",($var/3600));
$minute=sprintf("%02d",(($var%3600)/60));


return $hour.":".$minute;

}


function post_request($url, $data='', $referer='')
 {
  // Convert the data array into URL Parameters like a=b&foo=bar etc.
  if(!empty($data))
  {
   $data = http_build_query($data);
  }

  // parse the given URL
  $url = parse_url($url);

  if ($url['scheme'] != 'http')
  {
   die('Error: Only HTTP request are supported !');
  }

  // extract host and path:
  $host = $url['host'];
  $path = $url['path'];

  // open a socket connection on port 80 - timeout: 30 sec
  if($fp = fsockopen($host, 80, $errno, $errstr, 30))
  {
   // send the request headers:
   fputs($fp, "POST $path HTTP/1.1\r\n");
   fputs($fp, "Host: $host\r\n");

   if ($referer != '')
   {
    fputs($fp, "Referer: $referer\r\n");
   }

   fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
   fputs($fp, "Content-length: ". strlen($data) ."\r\n");
   fputs($fp, "Connection: close\r\n\r\n");
   fputs($fp, $data);

   $result = '';

   while(!feof($fp))
   {
     // receive the results of the request
     $result .= fgets($fp, 128);
    }
   }
   else
   {
    return array(
      'status' => 'err',
      'error' => "$errstr ($errno)"
    );
   }
   fclose($fp);

  // split the result header from the content
  $result = explode("\r\n\r\n", $result, 2);

  $header = '';
  if(isset($result[0]))
  {
   $header = $result[0];
  }
  $content = '';
  if(isset($result[1]))
  {
   $content = $result[1];
  }

 // return as structured array:
  return array(
   'status' => 'ok',
   'header' => $header,
   'content' => $content
  );
 }
 // Submit those variables to the server



function FCM_Admin($tokens,$msg){


		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $msg
			);

		$headers = array(
			'Authorization:key = AAAAqu3M5Bo:APA91bGEcsgENXNvUQI5Qgtr68KweGOjaAxEIptLzb1Ak1IEd1xoXfFRB5pMNpM7O5cMyE0SGQXvFfCqOTEY83BjZkQojHmoJy_nPJ2Dvz3Ivj2hZuBKi2bBE9LVBhG73uAZYJc0y59n ',
			'Content-Type: application/json'
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		echo  $result = curl_exec($ch);
		if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);


}





function FCM_Shop($tokens,$msg){


		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $msg
			);

		$headers = array(
			'Authorization:key = AAAAppCpRPQ:APA91bGCSQtF2bJq7jiHBOngb550H9hXS3PF6NviUtfdgetK4FFuEg6ZhTKXwB_X-nMT-10skhZpvleRFZi7irl2RrZxMwEjx8ubptC0fvx1XIbTb1FRmRfeXrsEtwAW4HlbGJeL3bVs ',
			'Content-Type: application/json'
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		echo  $result = curl_exec($ch);
		if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);


}





function FCM($tokens,$msg,$key=""){


		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			 'registration_ids' => $tokens,
			 'data' => $msg
			);

		$headers = array(
			'Authorization:key = AAAAkfbNi-g:APA91bFw-QoLF7oMhxwNUuZbph5H5vSRGjI8F3Gwd6BZ5WWk-gVPyfkvGw4X-4UvnTGBmYSy7x8iQJqYQ0kuMO-NjZHqnzla-dQVNIuKH9fmZa88h64KU6KbtW-8AE2doESzxc8mnyBo',
			'Content-Type: application/json'
			);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		echo  $result = curl_exec($ch);
		if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);


}









function DocumentResultCheck(){
global $Mem;

//$r=$Mem->q("select * from ibss_preventive_document_execution_list where

$Q=$Mem->q("select *, b.IDX as XID from ibss_preventive_document_list a, ibss_preventive_document_execution_list b where b.DID=a.IDX  ");
while($r=$Q->fetch()){
	$total_count=$Mem->qs("SELECT count(*) FROM `ibss_preventive_document_element_list` WHERE DID=? ",$r["DID"]);
	$result_count=$Mem->qs("select count(*) from ibss_preventive_document_execution_list a, ibss_preventive_document_result_list b where a.DID= ? and b.XID=a.IDX and a.IDX= ? ", array($r["DID"],$r["XID"]));


	$result=(($total_count == $result_count)?"1":"8");

	$Mem->q("update ibss_preventive_document_execution_list set Status =? where DID=? and IDX=? " ,array( $result,  $r["DID"] ,$r["XID"]));

}


return $result;
}



function image_fix_orientationX(&$image, $filename) {
    $image = imagerotate($image, array_values([0, 0, 0, 180, 0, 0, -90, 0, 90])[@exif_read_data($filename)['Orientation'] ?: 0], 0);
}

function correctImageOrientation($filename) {
  if (function_exists('exif_read_data')) {
    $exif = exif_read_data($filename);
    if($exif && isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      if($orientation != 1){
        $img = imagecreatefromjpeg($filename);
        $deg = 0;
        switch ($orientation) {
          case 3:
            $deg = 180;
            break;
          case 6:
            $deg = 270;
            break;
          case 8:
            $deg = 90;
            break;
        }
        if ($deg) {
          $img = imagerotate($img, $deg, 0);
        }
        // then rewrite the rotated image back to the disk as $filename
        imagejpeg($img, $filename, 95);
      } // if there is some rotation necessary
    } // if have the exif orientation info
  } // if function exists
}


function generatorRandomCode($length=10){

//     $code="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$code="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$code_length=strlen($code);
$code_str="";
for($i=0; $i < $length; $i++){

$code_str .=$code[rand(0,$code_length-1)];

}
	return $code_str;


}



function getADCommit($UID, $time=0, $date_start=0, $date_end=0){
	global $Mem;

	$where="";
	if($date_start && !$date_end){	$where.=" and a.DT_RG >= $date_start ";			}
	if($date_start && $date_end){	$where.=" and a.DT_RG >= $date_start and a.DT_RG <= $date_end ";	}


	$AC_TIME=ceil($Mem->qs("select sum(commit_result) from ( SELECT CEIL( SUM(a.AC_TIME)/3600 )".($time?"*b.`AD_Commit`":"")."  as commit_result FROM  service_user_ad_location_filter_list a , `service_ad_pannel_list` b WHERE a.UID=? AND a.AC_TIME < 300 AND b.UID =a.UID AND a.`AID`=b.IDX  ".$where." GROUP BY a.AID  ) as tb  ", array($UID)));


return $AC_TIME;

}

?>
