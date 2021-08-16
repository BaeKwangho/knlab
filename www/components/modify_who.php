<?
include "../_h.php";
//error_reporting(E_ALL);	ini_set("display_errors", 1);
//편집 시작부분
if($_GET["EDIT"]==='true'){
    if($_GET["PID"]){
        $worker=$Mem->qr("select * from nt_user_list where WORKING=?",$_GET["PID"]);
        if($worker){
            echo "작업자가 있습니다.";
            exit;
        }else{
            $Mem->q("update nt_user_list set WORKING = ? where IDX =? ",array($_GET["PID"],$Mem->user["uid"]));
            exit;
        }
    }
}else{
//편집 종료 시
    if($_GET["PID"]){
        $Mem->q("update nt_user_list set WORKING = 0 where IDX =? ",$Mem->user["uid"]);
    }
}
?>