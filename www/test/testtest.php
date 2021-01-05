<?
include "_head.php";
if($_POST["okt"]){
    print_r($_POST["okt"]);

}
if($_POST["DC_CONTENT"]){
    echo $_POST["DC_CONTENT"];
}
if($_POST["example"]){
    print_r($_POST["example"]);
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
  $DT =DT_trans("2019  "); 
  echo $DT;
  echo date("Y-m-d",$DT);


?>

<link href="/froala-editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/froala-editor/js/froala_editor.pkgd.min.js"></script>



<script type="text/javascript" src="./NaverEditor/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<form action="<?=SELF?>" method="post">

<textarea name="okt" id="okt" rows="10" cols="100"></textarea>
<textarea name="example" id="example"></textarea>
<textarea name="DC_CONTENT" id="DC_CONTENT_EDITOR"  class="input_cell" ></textarea>

<input type="button"onclick="submitContents(this);" value="보내기">
<?
    $list = [1,2,4,5];
    $number= 4;
?>
<script type="text/javascript">
var t = <? echo $number ?>;
  for(i=0;i<t ;i++)
         {
            document.write("<li><div><time>"+<?php json_encode($list[$count]) ?>+"</time>sdasdasda</div></li>");
            }
</script>


<script type="text/javascript">
var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
 oAppRef: oEditors,
 elPlaceHolder: "okt",
 sSkinURI: "./NaverEditor/SmartEditor2Skin.html",
 fCreator: "createSEditor2",
 htParams : {
     bUseToolbar : true,
     bUseVerticalResizer :true,
     bUseModeChanger : true
 }
});

// ‘저장’ 버튼을 누르는 등 저장을 위한 액션을 했을 때 submitContents가 호출된다고 가정한다.
function submitContents(elClickedObj) {
 // 에디터의 내용이 textarea에 적용된다.
 oEditors.getById["okt"].exec("UPDATE_CONTENTS_FIELD", []);

 // 에디터의 내용에 대한 값 검증은 이곳에서
 // document.getElementById("ir1").value를 이용해서 처리한다.

 try {
     elClickedObj.form.submit();
 } catch(e) {}
 }
</script>

<!--<link href="/css/froala_style.min.css" rel="stylesheet" type="text/css" />
--><script type="text/javascript" src="./Editor2/ckeditor.js"></script>

<script type="text/javascript">
//<![CDATA[
function LoadPage() {
    CKEDITOR.replace('DC_CONTENT_EDITOR');
}

function FormSubmit(f) {
    CKEDITOR.instances.contents.updateElement();
	if(f.contents.value == "") {
		alert("내용을 입력해 주세요.");
		return false;
	}
    alert(f.contents.value);
    
	// 전송은 하지 않습니다.
    return false;
}
//]]>
</script>

<script>
    LoadPage();
    //var editor = new FroalaEditor('#example');
</script>

<?
if(0){
    $q=$Mem->q("select * from nt_document_list where DC_MEMO2 like '3.gif'");
    while($r=$q->fetch()){
        print_r($r);
    }
}else{
    $q=$Mem->q("select * from nt_document_list where DC_MEMO2 like '3.gif'");
    while($r=$q->fetch()){
        print_r($r);
    }
}

?>