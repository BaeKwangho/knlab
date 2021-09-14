<?
//error_reporting(E_ALL);	ini_set("display_errors", 1);

include "_h.php";

if($_GET["REMOVE_DOC"]){
    $Mem->q("update nt_document_list set STAT = 9 where idx = ?",$_GET["REMOVE_DOC"]);
}elseif($_GET["REMOVE_CRAWL"]){
    $params = [
        'index' => 'politica_service',
        'id'    => $_GET["REMOVE_CRAWL"]
    ];
    
    $params['refresh']=true;
    $response = $Mem->es->delete($params);
}

?>