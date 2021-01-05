<? 

//error_reporting(E_ALL);ini_set("display_errors", 1);
//error_reporting(E_ALL);ini_set("display_errors", 1);

include "/home/knlab/_Class/Member.php"; $Mem=new Member();
if(!$Mem->user["uid"]){			mvs("Member_Login.php");	exit;		}

 
// echo $Mem->class;

//print_R($_COOKIE);


?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <link rel="stylesheet" href="/_style.css">
  
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="/_script.js" ></script>

	<script type="text/javascript" src="/js/treeview.min.js"></script>

  <title>Document</title>
 </head>
 <body>




 <div id="overlay" class="web_dialog_overlay"></div> 
 <div id="overlay1" class="web_dialog_overlay1"></div> 
<div id="web_dialog" class="web_dialog_content"  ></div>
<div id="web_dialog1" class="web_dialog_content"  ></div>



<div class="main_wrap" >
<table cellpadding="0" cellspacing="0" border="0"  style="width:100%;"  >
	<tr>
		<td style="min-width:300px;"><img src="/images/logo.png" alt="" style="height:70px;" >	 </td>
		<td style="text-align:center;" > 
			
	<input type="text" style="border:solid 4px #2f5597; background-color:#FFF; font-size:16px;padding:5px;"> <input type="button"  class="button1" value="검색" style="height:38px; width:60px;font-size:14px;"> <input type="button"  class="button1" value="상세검색" style="height:38px; width:90px;font-size:14px;">

		</td>
		<td style="min-width:300px;text-align:right;">
			<b><?=$Mem->name?></b> 안녕하세요. <input type="button" class="button1" value="로그아웃" onclick="go('Member_Logout.php');">
		</td> 
	</tr>
</table>
 </div>



<? if($Mem->class < 8 ){ ?>
 
<div style="line-height:40px;background-color:#2f5597;text-align:center;padding-top:8px;">
 

		<div class="main_wrap" >
				<table cellpadding="0" cellspacing="0" border="0" style="min-width:1200px;margin:0 auto;"   class="menu_top" >
					<tr>
						<td style="width:16.6%;"  class="active" >최신자료</td>
						<td style="width:16.6%;" >리포트</td>
						<td style="width:16.6%;" >정책동향</td>
						<td style="width:16.6%;" >산업동향</td>
						<td style="width:16.6%;" >기술동향</td>
						<td style="width:16.6%;" >레퍼런스</td>
					</tr>
				</table>
		</div>

</div>
<? } ?>

<div style="min-width:1200px; ">
<table    cellpadding="0" cellspacing="0" border="0"  style="  width:100%;" >
	<tr>
		<td style="width:200px;" valign="top" >
<? if($Mem->class < 8 ){ ?>
 

 
	<div class="main_side_menu1" > 

	
<link rel="stylesheet" href="/js/treeview.min.css" />

<div id="tree"></div>

<button id="expandAll">Expand All</button>
<button id="collapseAll">Collapse All</button>
<script>
	
  var tree = [{
    name: 'Vegetables',
    children: []
  }, {
    name: 'Fruits',
    children: [{
      name: 'Apple',
      children: []
    }, {
      name: 'Orange',
      children: []
    }, {
      name: 'Lemon',
      children: []
    }]
  }, {
    name: 'Candy',
    children: [{
      name: 'Gummies',
      children: []
    }, {
      name: 'Chocolate',
      children: [{
        name: 'M & M\'s',
        children: []
      }, {
        name: 'Hershey Bar',
        children: []
      }]
    }, ]
  }, {
    name: 'Bread',
    children: []
  }];

  //
  // Grab expand/collapse buttons
  //
var expandAll = document.getElementById('expandAll');
var collapseAll = document.getElementById('collapseAll');

  //
  // Create tree
  //

var xhr= new XMLHttpRequest();
var paramVal= "paramVal";
var target= "URL";
var t="";
xhr.open("GET", "/_json_menu_list.php");
xhr.send();

xhr.onreadystatechange= function(){
if(xhr.readyState=== XMLHttpRequest.DONE){
if(xhr.status== 200){
let loadedJSON= JSON.parse(xhr.responseText);

//$("#content").html("resultCode: "+ loadedJSON.resultCode+ "<br>");
 var t = new TreeView(loadedJSON, 'tree');
  expandAll.onclick = function () { t.expandAll(); };
  collapseAll.onclick = function () { t.collapseAll(); };

  t.on('select', function () { alert(this.link); });
  t.on('expand', function () { alert('expand'); });
  t.on('collapse', function () { alert('collapse'); });
  t.on('expandAll', function () { alert('expand all'); });
  t.on('collapseAll', function () { alert('collapse all'); });

}else{
 //alert("fail to load");
}
}
}
 
//  var t = new TreeView(tree, 'tree');

  //
  // Attach events
  //


</script>

			<ul class="inline2"  >
				<? 
				$Q=$Mem->q("select * from nt_categorys where length(CODE)=2 and TYPE=1 and STAT=0 order by PR asc ");
				while($r=$Q->fetch()){
				?>
				<li>+<?=$r["CT_NM"]?></li>				
					<? } ?>
			</ul>
	</div> 
<? }else{ ?> 
 
	<div class="main_side_menu1" > 


			<ul class="menu_list" >
			 	<li><a href="/Content_User_List.php" style="color:white;" >+ 사용자관리</a></li> 
				<li><a href="/Content_Data_List.php" style="color:white;" >+ 데이터조회</a></li>
				<li><a href="/Content_Data_Register.php" style="color:white;" >+ 데이터 등록</a></li>
				<li><a href="/Content_Data_Register_Excel.php" style="color:white;" >+ 데이터 엑셀등록</a></li> 
				<li><a href="Content_Config_Category.php?CTYPE=1" style="color:white;" >+ 주제분류설정</a></li> 
				<li><a href="Content_Config_Category.php?CTYPE=2" style="color:white;" >+ 유형분류설정</a></li> 
				<li><a href="Content_Config_Category.php?CTYPE=3" style="color:white;" >+ 국가분류설정</a></li> 
				<li><a href="/Member_Logout.php" style="color:white;" >+ 로그아웃</a></li>
			</ul>
				

	</div> 

<? } ?>
</td>

		<td style=";">