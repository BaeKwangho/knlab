<?
//error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_h.php";

if($_GET["REMOVE_DOC"]){
    $Mem->q("update nt_document_list set STAT = 9 where idx = ?",$_GET["REMOVE_DOC"]);
}elseif($_GET["REMOVE_CRAWL"]){
    $query = 'id:'.$_GET['REMOVE_CRAWL'];

    $Mem->docs->delete($query);
}

?>