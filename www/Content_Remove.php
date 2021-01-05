<?

include "_h.php";

if($_GET["REMOVE_DOC"]){
    $Mem->q("update nt_document_list set STAT = 9 where idx = ?",$_GET["REMOVE_DOC"]);
}

?>